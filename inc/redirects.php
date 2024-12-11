<?php
require_once 'utils.php';

$action = $_REQUEST['action'] ?? '';

if ($action != '')
{
    try
    {
        $db->beginTransaction();

        switch ($action)
        {
            case 'verifypayment':
                $redirectToHome = true;
                if (isset($_GET['trxref']) && isset($_GET['reference']))
                {
                    $arResponse = KpakpandoEventsBooking\Booking\BookingPayment::verifyPayment();
                    if ($arResponse['status'] == true)
                    {
                        $redirectToHome = false;
                        header("location: " . DEF_FULL_ROOT_PATH . "/booking-confirmed?id={$arResponse['bookingId']}");
                    }
                    else
                    {
                        if ($arResponse['eventId'] != '')
                        {
                            if (strlen($arResponse['eventId']) == 36)
                            {
                                $redirectToHome = false;
                                $_SESSION['redirectError'] = 'Payment failed! Please try again.';
                                header("location: " . DEF_FULL_ROOT_PATH . "/checkout?id={$arResponse['eventId']}");
                            }
                        }
                    }
                }
                if ($redirectToHome)
                {
                    $_SESSION['redirectError'] = 'Payment failed! Please try again.';
                    header('location: ' . DEF_FULL_ROOT_PATH);
                }
            break;

            case 'validateticket':
                $redirectToHome = true;
                if (isset($_GET['id']))
                {
                    if (strlen(trim($_GET['id'])) == 36)
                    {
                        if (KpakpandoEventsBooking\Booking\Booking::validateTicket())
                        {
                            $bookingId = trim($_GET['id']);
                            $redirectToHome = false;
                            header("location: " . DEF_FULL_ROOT_PATH . "/ticket?id={$bookingId}");
                        }
                    }
                }
                if ($redirectToHome)
                {
                    $_SESSION['redirectError'] = 'Validation failed! Booking not found.';
                    header('location: ' . DEF_FULL_ROOT_PATH);
                }
            break;
        }

        $db->commit();
    }
    catch(Exception $ex)
    {
        $db->rollBack();
        $_SESSION['redirectError'] = $ex->getMessage(); //'A redirect error has occured!';
        header('location: ' . DEF_FULL_ROOT_PATH);
    }
}