<?php
namespace KpakpandoEventsBooking\Booking;

use KpakpandoEventsBooking\Crud\Crud;
use KpakpandoEventsBooking\Api\Api;

class BookingPayment
{
    static $table = DEF_TBL_PAYMENTS;

    public static function makePayment($arParams)
    {
        $paymentLink = '';

        //initiate payment
        $id = getNewId();
        $email = $arParams['email'];
        $amount = doTypeCastDouble($arParams['amount']);

        $data = [
            'id' => $id
            , 'booking_id' => $arParams['bookingId']
            , 'event_id' => $arParams['eventId']
            , 'email' => $email
            , 'amount' => $amount
            , 'status' => 'pending'
            , 'cdate' => getCurrentDate()
        ];
        Crud::insert(
            self::$table,
            $data
        );

        //invoke API
        $arParams = [
            'reference' => $id
            , 'amount' => $amount * 100
            , 'email' => $email
            , 'callback_url' => DEF_COMMON_REDIRECT_URL.'?action=verifypayment'
        ];
        $arData = [
            'url' => DEF_PSK_PAYMENT_URL
            , 'method' => 'POST'
            , 'body' => $arParams
        ];
        $rsx = Api::invokeApiCall($arData);
        $res = json_decode($rsx, true);

        if (array_key_exists('status', $res))
        {
            if ($res['status'] == true)
            {
                if (array_key_exists('data', $res))
                {
                    if (array_key_exists('authorization_url', $res['data']))
                    {
                        if ($res['data']['authorization_url'] != '')
                        {
                            if (array_key_exists('reference', $res['data']))
                            {
                                //update table with payment gateway reference
                                Crud::update(
                                    self::$table,
                                    ['payment_ref' => $res['data']['reference']],
                                    ['id' => $id]
                                );
                            }

                            $paymentLink = $res['data']['authorization_url'];

                            return [
                                'status' => true
                                , 'link' => $res['data']['authorization_url']
                            ];
                        }
                    }
                }
            }
        }

        $status = false;
        if ($paymentLink != '')
        {
            $status = true;
        }

        return [
            'status' => $status
            , 'link' => $paymentLink
        ];
    }

    public static function getPayment($id, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(', ', $arFields) : $arFields;
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => [
                    'id' => $id
                ]
            ]
        );
    }

    public static function verifyPayment()
    {
        $txRef = trim($_GET['trxref']);
        $reference = trim($_GET['reference']);

        $bookingId = $eventId = '';
        if ($txRef != '' && $reference != '')
        {
            //verify payment
            $rs = self::getPayment(
                $txRef
                , ['id', 'amount', 'booking_id', 'event_id']
            );
            if ($rs)
            {
                $paymentId = $rs['id'];
                $paymentAmount = doTypeCastDouble($rs['amount']) * 100;
                $eventId = $rs['event_id'];
                //https://api.paystack.co/transaction/verify/{reference}
                //invoke API
                $arData = [
                    'url' => DEF_PSK_VERIFY_PAYMENT_URL.'/'.$reference,
                    'method' => 'GET'
                ];
                $rsx = Api::invokeApiCall($arData);
                $res = json_decode($rsx, true);
                //print_r($res);exit;
                
                if (array_key_exists('status', $res))
                {
                    if ($res['status'] == true)
                    {
                        if ($res['data']['status'] === 'success'
                            && doTypeCastDouble($res['data']['amount']) === $paymentAmount)
                        {
                            //update payments table
                            $data = [
                                'payment_ref' => $reference
                                , 'status' => 'paid'
                                , 'mdate' => getCurrentDate()
                            ];
                            Crud::update(
                                self::$table,
                                $data,
                                ['id' => $paymentId]
                            );

                            $bookingId = $rs['booking_id'];

                            //finalise booking
                            Booking::finaliseBooking(
                                $bookingId, $eventId, $paymentId
                            );
                        }
                    }
                }
            }
        }

        $status = false;
        if ($bookingId != '')
        {
            $status = true;
        }
        return [
            'status' => $status
            , 'bookingId' => $bookingId
            , 'eventId' => $eventId
        ];
    }
}