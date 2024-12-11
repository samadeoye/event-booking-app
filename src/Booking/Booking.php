<?php
namespace KpakpandoEventsBooking\Booking;

use Exception;
use KpakpandoEventsBooking\Crud\Crud;
use KpakpandoEventsBooking\Event\Event;
use KpakpandoEventsBooking\Mailer\Mailer;
use KpakpandoEventsBooking\Sequence\Sequence;

class Booking
{
    protected static $table = DEF_TBL_BOOKINGS;
    protected static $tableEvents = DEF_TBL_EVENTS;
    protected static $tableSequence = DEF_TBL_SEQUENCE;
    protected static $referencePrefix = 'TICKET-';
    protected static $lineBreak = "<br>";//"\r\n";
    public static $arExtraData = [];
    public static $data = [];

    public static function checkDuplicateTransaction($arWhere)
    {
        $row = Crud::select(
            self::$table,
            [
                'columns' => 'cdate'
                , 'where' => $arWhere
                , 'order' => 'cdate DESC'
                , 'limit' => 1
            ]
        );
        if ($row)
        {
            $duplicateTrxMin = DEF_DUPLICATE_TRX_MIN;
            $timenow = time();
            $trxtime = $row['cdate'];
            $revtime = strtotime("+{$duplicateTrxMin} minutes", strtotime($trxtime));
            if ($timenow < $revtime)
            {
                //transaction was done in less than the "specified minutes" ago
                throw new Exception("Duplicate transaction detected. Please wait for {$duplicateTrxMin} minutes and try again.");
            }
        }
    }

    public static function bookTicket()
    {
        $eventId = $_REQUEST['eventId'];
        $firstName = stringToTitle($_REQUEST['firstName']);
        $lastName = stringToTitle($_REQUEST['lastName']);
        $email = trim(strtolower($_REQUEST['email']));
        $phone = trim($_REQUEST['phone']);
        $gender = $_REQUEST['gender'];
        $age = doTypeCastInt($_REQUEST['age']);
        $ticketQty = doTypeCastInt($_REQUEST['ticketQty']);
        $ticketAmt = doTypeCastDouble($_REQUEST['ticketAmt']);

        //validate amount with actual event price
        $rowEvent = Crud::getRecordInfo(
            self::$tableEvents, $eventId, [
                'price'
                , 'slots'
                , 'slots_used'
            ]
        );
        $eventPrice = doTypeCastDouble($rowEvent['price']);
        //total booking amount
        $totalEventPrice = doTypeCastDouble($ticketQty * $eventPrice);
        if ($totalEventPrice != $ticketAmt)
        {
            throw new Exception('Booking amount does not tally with event price!');
        }

        //check if event has slots management and validate
        Event::checkIfSlotsAvailable([
            'slots' => $rowEvent['slots']
            , 'slotsUsed' => $rowEvent['slots_used']
        ]);

        $id = getNewId();
        $data = [
            'event_id' => $eventId
            , 'first_name' => $firstName
            , 'last_name' => $lastName
            , 'fullname' => "{$firstName} {$lastName}"
            , 'email' => $email
            , 'phone' => $phone
            , 'gender' => $gender
            , 'age' => $age
            , 'qty' => $ticketQty
            , 'total_amount' => $ticketAmt
            , 'status' => 'pending' //pending until payment is made
        ];

        self::checkDuplicateTransaction(
            $data
        );

        $data['id'] = $id;
        $data['cdate'] = getCurrentDate();
        Crud::insert(
            self::$table
            , $data
        );

        //process payment and return success message if payment verification is successful
        $arResponse = BookingPayment::makePayment([
            'bookingId' => $id
            , 'eventId' => $eventId
            , 'amount' => $ticketAmt
            , 'email' => $email
        ]);
        if ($arResponse['status'] == true)
        {
            $arExtraData = [
                'msg' => 'Redirecting to payment page...'
                , 'link' => $arResponse['link']
            ];
            self::$arExtraData = array_merge($arExtraData, self::$arExtraData);
        }
        else
        {
            throw new Exception('An error occured while processing your payment. Please try again.');
        }
    }

    protected static function getNewTicketReference()
    {
        $row = Crud::select(
            DEF_TBL_SEQUENCE, [
                'columns' => 'counter',
                'where' => [
                    'type' => 'booking'
                ]
            ]
        );
        $counter = 0;
        if ($row)
        {
            $counter = doTypeCastInt($row['counter']);
        }

        $newCounter = $counter + 1;
        $counterLen = strlen(strval($newCounter));
        switch ($counterLen)
        {
            case 1:
                $newCounter = "00$newCounter";
            break;
            case 2:
                $newCounter = "0$newCounter";
            break;
        }
        $referencePrefix = self::$referencePrefix;

        return [
            'newCounter' => $counter + 1
            , 'reference' => "{$referencePrefix}{$newCounter}"
        ];
    }

    public static function finaliseBooking($bookingId, $eventId, $paymentId)
    {
        //to update only if booking is not yet approved (still pending)
        $row = Crud::getRecordInfo(
            self::$table, $bookingId, ['status']
        );
        if ($row)
        {
            if ($row['status'] == 'pending')
            {
                //update booking reference and payment
                $reference = Sequence::getNewReferenceByRecord(
                    'booking', $eventId, self::$referencePrefix
                );
                $data = [
                    'reference' => $reference
                    , 'payment_id' => $paymentId
                    , 'status' => 'approved'
                    , 'mdate' => getCurrentDate()
                ];
                Crud::update(
                    self::$table
                    , $data
                    , ['id' => $bookingId]
                );

                //reset reference sequence
                Sequence::updateReferenceCounterByRecord(
                    'booking', $eventId
                );

                //update event slots available if event has slot management
                Event::updateEventSlots(
                    $eventId
                );
                
                //send ticket to participant
                $row = Crud::getRecordInfo(
                    self::$table, $bookingId, [
                        'event_id'
                        , 'reference'
                        , 'fullname'
                        , 'email'
                        , 'total_amount'
                        , 'qty'
                    ]
                );
                if ($row)
                {
                    $lineBreak = self::$lineBreak;
                    $siteName = SITE_NAME;

                    $fullName = $row['fullname'];

                    //get ticket print to send as an attachment
                    $ticketPrintFile = self::getTicketPrint([
                        'bookingId' => $bookingId
                        , 'eventId' => $row['event_id']
                        , 'reference' => $row['reference']
                        , 'fullName' => $row['fullname']
                        , 'totalAmount' => doTypeCastDouble($row['total_amount'])
                        , 'qty' => doTypeCastInt($row['qty'])
                        , 'printType' => 'string'
                    ]);

                    $body = <<<EOQ
Dear {$fullName},{$lineBreak}{$lineBreak}
This is to notify you that we have received your booking on {$siteName}{$lineBreak}
Your ticket has been attached to this email.{$lineBreak}{$lineBreak}
Thank you for your booking!
EOQ;

                    $subject = "{$siteName} - Event Booking Confirmation";
                    Mailer::sendMail([
                        'mailTo' => $row['email']
                        , 'toName' => $fullName
                        , 'mailFrom' => SITE_EMAIL
                        , 'fromName' => $siteName
                        , 'subject' => $subject
                        , 'body' => $body
                        , 'arAttachments' => [
                            [
                                'fileEncoded' => base64_encode($ticketPrintFile)
                                , 'fileExtension' => 'pdf'
                                , 'attachmentName' => "{$subject}.pdf"
                            ]
                        ]
                    ]);
                }
            }
        }
    }

    protected static function getTicketPrint($arParams)
    {
        $printType = array_key_exists('printType', $arParams) ? $arParams['printType'] : 'default';

        //get event info
        $rowEvent = Crud::getRecordInfo(
            self::$tableEvents, $arParams['eventId'], [
                'datetype'
                , 'date'
                , 'datefrom'
                , 'dateto'
                , 'title'
                , 'time'
                , 'venue'
            ]
        );

        //print ticket
        $date = Event::getEventFormattedEventDate([
            'datetype' => $rowEvent['datetype']
            , 'date' => $rowEvent['date']
            , 'dateFrom' => $rowEvent['datefrom']
            , 'dateTo' => $rowEvent['dateto']
        ]);
        
        return BookingPrint::getTicketPrint([
            'bookingId' => $arParams['bookingId']
            , 'title' => $rowEvent['title']
            , 'reference' => $arParams['reference']
            , 'fullName' => $arParams['fullName']
            , 'date' => $date
            , 'time' => $rowEvent['time']
            , 'price' => getCurrencyAmount($arParams['totalAmount'])
            , 'qty' => doTypeCastInt($arParams['qty'])
            , 'venue' => $rowEvent['venue']
            , 'printType' => $printType
        ]);
    }

    public static function printTicket()
    {
        $id = $_REQUEST['id'];

        //get booking info
        $row = Crud::getRecordInfo(
            self::$table, $id, [
                'event_id'
                , 'reference'
                , 'fullname'
                , 'total_amount'
                , 'qty'
            ]
        );
        self::getTicketPrint([
            'bookingId' => $id
            , 'eventId' => $row['event_id']
            , 'reference' => $row['reference']
            , 'fullName' => $row['fullname']
            , 'totalAmount' => doTypeCastDouble($row['total_amount'])
            , 'qty' => doTypeCastInt($row['qty'])
        ]);
    }

    public static function validateTicket()
    {
        $id = trim($_GET['id']);

        if ($id != '')
        {
            if (strlen($id) == 36)
            {
                $row = Crud::getRecordInfo(
                    self::$table, $id, ['id']
                );
                if ($row)
                {
                    if (strlen($row['id']) == 36)
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public static function getBookings($arFields=['*'], $typeId='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => ['deleted' => 0],
                'return_type' => 'all',
                'order' => 'cdate DESC'
            ]
        );
    }

    public static function getBookingsList()
    {
        $rs = self::getBookings([
            'id, reference, event_id, fullname, email, phone, gender, age, total_amount, cdate, status, payment_id, check_in'
        ]);
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];
                //get event title
                $eventTitle = Event::getEventTitle(
                    $r['event_id']
                );
                $paymentId = $r['payment_id'];

                $row = [
                    'sn' => $sn
                    , 'reference' => $r['reference']
                    , 'event' => $eventTitle
                    , 'fullname' => $r['fullname']
                    , 'email' => $r['email']
                    , 'phone' => $r['phone']
                    , 'gender' => ucwords($r['gender'])
                    , 'age' => doTypeCastInt($r['age'])
                    , 'amount' => DEF_CURRENCY_SYMBOL . doNumberFormat($r['total_amount'])
                    , 'cdate' => $r['cdate']
                ];

                $paymentTheme = 'bg-danger';
                $paymentLabel = 'NO';
                $checkinBtn = '';
                if ($r['status'] == 'approved')
                {
                    if ($paymentId != '')
                    {
                        if (strlen($paymentId) == 36)
                        {
                            $paymentTheme = 'bg-success';
                            $paymentLabel = 'YES';

                            if ($r['check_in'] == 0)
                            {
                                $checkinBtn = <<<EOQ
<button type="button" class="btn btn-success btn-rounded" onclick="checkIn('{$id}')">
    <i class="fa-solid fa-check"></i> Check-in
</button>
EOQ;
                            }
                        }
                    }
                }
                $row['paid'] = <<<EOQ
<span class="badge {$paymentTheme}">{$paymentLabel}</span>
EOQ;

                $row['checkin'] = $checkinBtn;

                $rows[] = $row;
                $sn++;
            }
            $data = [
                'status' => true,
                'msg' => 'Records fetched successfully!',
                'data' => $rows
            ];
        }
        else
        {
            $data = [
                'status' => false,
                'msg' => 'No record found!',
                'data' => []
            ];
        }
        self::$data = $data;
    }

    public static function validateBookingCheckin()
    {
        $id = $_REQUEST['id'];

        //check if current date is the event date
        $row = Crud::getRecordInfo(
            self::$table, $id, ['event_id']
        );
        if ($row)
        {
            $row = Crud::getRecordInfo(
                self::$tableEvents, $row['event_id'], [
                    'datetype'
                    , 'date'
                    , 'datefrom'
                    , 'dateto'
                ]
            );
            if ($row)
            {
                $currentDate = getCurrentDate('Y-m-d');
                $currentDate = strtotime($currentDate);
                switch ($row['datetype'])
                {
                    case 'singleDate':
                        if ($row['date'] != '')
                        {
                            if (strlen($row['date']) == 10)
                            {
                                if (strtotime($row['date']) == $currentDate)
                                {
                                    return true;
                                }
                            }
                        }
                    break;

                    case 'dateRange':
                        if ($row['datefrom'] != '' 
                            && $row['dateto'] != '')
                        {
                            if (strlen($row['datefrom']) == 10 
                                && strlen($row['dateto'] == 10))
                            {
                                if (strtotime($row['datefrom']) <= $currentDate 
                                    || strtotime($row['dateto']) >= $currentDate)
                                {
                                    return true;
                                }
                            }
                        }
                    break;
                }
            }
        }

        self::$arExtraData['datemismatched'] = true;
    }

    public static function checkinBooking()
    {
        $id = $_REQUEST['id'];

        $data = [
            'check_in' => 1
            , 'mdate' => getCurrentDate()
        ];
        Crud::update(
            self::$table
            , $data
            , ['id' => $id]
        );
    }
}