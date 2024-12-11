<?php
require_once 'utils.php';

use KpakpandoEventsBooking\Param\Param;
use KpakpandoEventsBooking\Event\Event;
use KpakpandoEventsBooking\Booking\Booking;
use KpakpandoEventsBooking\Admin\Admin;

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
if ($action == '')
{
    getJsonRow(false, 'Invalid request!');
}

$params = Param::getRequestParams($action);
doValidateRequestParams($params, true);

try
{
    $data = $arExtraData = [];
    $db->beginTransaction();

    switch ($action)
    {
        case 'register':
            KpakpandoEventsBooking\Auth\Register::registerAdmin();
        break;

        case 'login':
            KpakpandoEventsBooking\Auth\Login::loginAdmin();
        break;

        case 'updateprofile':
            Admin::updateAdmin();
            $rs = Admin::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'changepassword':
            Admin::changePassword();
        break;

        case 'forgotpassverifyemail':
            Admin::verifyEmailForPasswordReset();
        break;

        case 'resetpassword':
            Admin::resetPassword();
        break;

        case 'addevent':
            Event::addEvent();
            $arExtraData = Event::$arExtraData;
        break;

        case 'updateevent':
            Event::updateEvent();
            $arExtraData = Event::$arExtraData;
        break;

        case 'deleteevent':
            Event::deleteEvent();
            $arExtraData = Event::$arExtraData;
        break;

        case 'bookticket':
            Booking::bookTicket();
            $arExtraData = Booking::$arExtraData;
        break;

        case 'printticket':
            Booking::printTicket();
        break;

        case 'getevents':
            Event::getEventsList();
            $rs = Event::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;
        
        case 'getbookings':
            Booking::getBookingsList();
            $rs = Booking::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'validatecheckin':
            Booking::validateBookingCheckin();
            $arExtraData = Booking::$arExtraData;
        break;

        case 'checkinbooking':
            Booking::checkinBooking();
        break;
    }

    $db->commit();
    if (count($data) > 0)
    {
        getJsonList($data);
    }
    getJsonRow(
        true
        , 'Operation successful!'
        , $arExtraData
    );
}
catch(Exception $ex)
{
	$db->rollBack();
	// $ex->getMessage();exit;
    getJsonRow(false, $ex->getMessage());
}