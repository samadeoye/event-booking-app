<?php
namespace KpakpandoEventsBooking\Event;

use KpakpandoEventsBooking\Crud\Crud;
use \Exception;
use KpakpandoEventsBooking\File\File;

class Event
{
    protected static $table = DEF_TBL_EVENTS;
    protected static $tableBookings = DEF_TBL_BOOKINGS;
    public static $arExtraData = [];
    public static $data = [];
    protected static $imgDirectory = 'assets/images/events/';

    protected static function validateEventData($id='')
    {
        $title = stringToTitle($_REQUEST['title']);
        $description = trim($_REQUEST['description']);
        $dateType = $_REQUEST['dateType'];
        $date = $_REQUEST['date'];
        $dateFrom = $_REQUEST['dateFrom'];
        $dateTo = $_REQUEST['dateTo'];
        $time = $_REQUEST['time'];
        $time = date('H:i:s', strtotime($time));
        $price = doTypeCastDouble($_REQUEST['price']);
        $venue = trim($_REQUEST['venue']);
        $venueMap = trim($_REQUEST['venueMap']);
        $slots = trim($_REQUEST['slots']);

        $currentDate = getCurrentDate('Y-m-d');
        $dateSet = false;
        switch ($dateType)
        {
            case 'dateRange':
                if ($dateFrom != '' && $dateTo != '')
                {
                    if (strlen($dateFrom) == 10 && strlen($dateTo) == 10)
                    {
                        $dateSet = true;
                        $date = null;
                    }
                }

                if ($dateSet)
                {
                    if ($dateFrom > $dateTo)
                    {
                        throw new Exception('End date cannot be before Start date!');
                    }
                    elseif ($dateTo < $currentDate)
                    {
                        throw new Exception('End date cannot be before current date!');
                    }
                }
            break;

            case 'singleDate':
                if ($date != '')
                {
                    if (strlen($date) == 10)
                    {
                        $dateSet = true;
                        $dateFrom = $dateTo = null;
                    }
                }
                
                if ($dateSet)
                {
                    if ($date < $currentDate)
                    {
                        throw new Exception('Event Date cannot be before current date!');
                    }
                }
            break;
        }

        if (!$dateSet)
        {
            throw new Exception('Please set a date for your event!');
        }
        
        $hasDuplicate = Crud::checkDuplicate(
            self::$table, 'title', $title, $id
        );
        if ($hasDuplicate)
        {
            throw new Exception('Title already exists!');
        }

        $data = [
            'title' => $title
            , 'description' => $description
            , 'datetype' => $dateType
            , 'date' => $date
            , 'datefrom' => $dateFrom
            , 'time' => $time
            , 'price' => $price
            , 'venue' => $venue
            , 'map' => $venueMap
        ];
        if ($dateTo != '')
        {
            if (strlen($dateTo) == 10)
            {
                $data['dateto'] = $dateTo;
            }
        }
        if ($slots != '')
        {
            $data['slots'] = doTypeCastInt($slots);
        }

        if ($id == '')
        {
            $id = getNewId();
            $data['id'] = $id;
        }
        $imgUploaded = 0;
        $fieldId = 'img';
        $imgFileSize = $_FILES[$fieldId]['size'];
        if ($imgFileSize > 0)
        {
            //upload image
            File::$directory = self::$imgDirectory;
            File::$fieldId = $fieldId;
            File::$arExtensions = ['jpeg', 'jpg', 'png'];
            $fileName = File::uploadFile($id);
            if ($fileName == '')
            {
                throw new Exception('An error occured. Please try again.');
            }
            else
            {
                $imgUploaded = 1;
            }
        }
        if ($imgUploaded)
        {
            $data['img'] = $fileName;
        }

        return $data;
    }

    public static function addEvent()
    {
        $data = self::validateEventData();
        $data['cdate'] = getCurrentDate();
        
        Crud::insert(
            self::$table
            , $data
        );
        self::$arExtraData['msg'] = 'Event added successfully!';
    }

    public static function updateEvent()
    {
        $id = $_REQUEST['id'];
        $data = self::validateEventData($id);
        $data['mdate'] = getCurrentDate();

        Crud::update(
            self::$table
            , $data
            , ['id' => $id]
        );
        self::$arExtraData['msg'] = 'Event updated successfully!';
    }

    public static function deleteEvent()
    {
        $id = $_REQUEST['id'];

        //check if event is linked with any booking
        $row = Crud::select(
            self::$tableBookings,
            [
                'columns' => 'id'
                , 'where' => [
                    'event_id' => $id
                    , 'deleted' => 0
                ]
                , 'limit' => 1
                , 'return_type' => 'row'
            ]
        );
        if ($row)
        {
            throw new Exception('You cannot delete this event as it is already linked with booking!');
        }
        else
        {
            Crud::update(
                self::$table
                , ['deleted' => 0]
                , ['id' => $id]
            );
        }
    }

    public static function getEventTitle($id)
    {
        $row = Crud::select(
            self::$table,
            [
                'columns' => 'title',
                'where' => ['id' => $id]
            ]
        );
        if ($row)
        {
            return $row['title'];
        }
        return '';
    }

    public static function getEvents($arFields=['*'])
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
    
    public static function getUpcomingEvents($arFields=['*'])
    {
        //these fields must be passed: datetype date, datefrom, dateto
        $rows = self::getEvents(
            $arFields
        );
        $ar = [];
        if (count($rows) > 0)
        {
            $currentDate = getCurrentDate('Y-m-d');
            $currentDate = strtotime($currentDate);
            foreach ($rows as $row)
            {
                switch ($row['datetype'])
                {
                    case 'singleDate':
                        $date = date('Y-m-d', strtotime($row['date']));
                        if (strtotime($date) >= $currentDate)
                        {
                            $ar[] = $row;
                        }
                    break;

                    case 'dateRange':
                        $dateFrom = date('Y-m-d', strtotime($row['datefrom']));
                        $dateTo = date('Y-m-d', strtotime($row['dateto']));
                        if (strtotime($dateTo) >= $currentDate)
                        {
                            $ar[] = $row;
                        }
                    break;
                }
            }
        }
        return $ar;
    }

    public static function getEventsList()
    {
        $rs = self::getEvents([
            'id, title, price, datetype, date, datefrom, dateto, time, img, cdate, mdate'
        ]);
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach ($rs as $r)
            {
                $id = $r['id'];
                $img = $r['img'];
                $imgPath = '';
                if ($img != '')
                {
                    $imgDirectory = self::$imgDirectory;
                    $imgPath = <<<EOQ
                    <img src="{$imgDirectory}{$r['img']}" class="adminTableImg">
EOQ;
                }

                $date = self::getEventFormattedEventDate([
                    'datetype' => $r['datetype']
                    , 'date' => $r['date']
                    , 'dateFrom' => $r['datefrom']
                    , 'dateTo' => $r['dateto']
                ]);

                $row = [
                    'sn' => $sn
                    , 'title' => $r['title']
                    , 'date' => $date
                    , 'time' => getFormattedDate(strtotime($r['time']), 'H:ia')
                    , 'price' => DEF_CURRENCY_SYMBOL . doNumberFormat($r['price'])
                    , 'img' => $imgPath
                    , 'cdate' => $r['cdate']
                    , 'mdate' => $r['mdate']
                ];
                $row['edit'] = <<<EOQ
                <button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="editEvent('{$id}')">
                    <i class="fa-solid fa-pen"></i>
                </button>
EOQ;
                $row['delete'] = <<<EOQ
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteEvent('{$id}')">
                    <i class="fa-solid fa-trash"></i>
                </button>
EOQ;
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

    public static function getEventFormattedEventDate($arParams)
    {
        $datetype = $arParams['datetype'];
        $date = array_key_exists('date', $arParams) ? $arParams['date'] : '';
        $dateFrom = array_key_exists('dateFrom', $arParams) ? $arParams['dateFrom'] : '';
        $dateTo = array_key_exists('dateTo', $arParams) ? $arParams['dateTo'] : '';
        $time = array_key_exists('time', $arParams) ? $arParams['time'] : '';

        if ($datetype == 'dateRange')
        {
            $date = $dateFrom;
            if ($dateTo != '')
            {
                if (strlen($dateTo) == 10)
                {
                    $date = "{$dateFrom} <b>-</b> {$dateTo}";
                }
            }
        }

        if ($date != '')
        {
            if ($time != '')
            {
                $date .= " {$time}";
            }
        }

        return $date;
    }

    public static function getEventDateTypeOptions($selectedValue='')
    {
        $arOptions = [
            'singleDate' => 'Single Date'
            , 'dateRange' => 'Date Range'
        ];

        $options = '';
        foreach ($arOptions as $value => $label)
        {
            $selected = '';
            if ($selectedValue == $value)
            {
                $selected = ' selected';
            }
            $options .= <<<EOQ
            <option value="{$value}" {$selected}>{$label}</option>
EOQ;
        }

        return $options;
    }

    public static function checkIfSlotsAvailable($arParams)
    {
        $returnType = array_key_exists('returnType', $arParams) ? $arParams['returnType'] : '';

        $slots = $slotsAvl = 0;
        if ($arParams['slots'] != null && $arParams['slots'] != 0)
        {
            $slots = doTypeCastInt($arParams['slots']);
        }
        if ($arParams['slotsUsed'] != null)
        {
            $slotsUsed = doTypeCastInt($arParams['slotsUsed']);
        }

        if ($slots > 0)
        {
            $slotsAvl = $slots - $slotsUsed;
            if ($slotsAvl < 0)
            {
                $slotsAvl = 0;
            }

            if ($slotsAvl == 0)
            {
                if ($returnType == 'slotsAvl')
                {
                    return $slotsAvl;
                }
                else
                {
                    throw new Exception('You can no longer book this event as there are no slots available!');
                }
            }
            else
            {
                if ($returnType == 'slotsAvl')
                {
                    return $slotsAvl;
                }
            }
        }
    }

    public static function updateEventSlots($id)
    {
        $row = Crud::getRecordInfo(
            self::$table, $id, [
                'slots'
                , 'slots_used'
            ]
        );
        if ($row)
        {
            if ($row['slots'] != null & $row['slots'] != 0)
            {
                //update slots used
                $newSlotsUsed = doTypeCastInt($row['slots_used']) + 1;
                $data = [
                    'slots_used' => $newSlotsUsed
                ];
                Crud::update(
                    self::$table
                    , $data
                    , ['id' => $id]
                );
            }
        }
    }
}