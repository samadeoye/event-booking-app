<?php
namespace KpakpandoEventsBooking\Dashboard;

use KpakpandoEventsBooking\Crud\Crud;

class Dashboard
{
    public static function getDashboardData()
    {
        $numEvents = self::getDashboardModuleCount(
            DEF_TBL_EVENTS
        );
        $numBookings = self::getDashboardModuleCount(
            DEF_TBL_BOOKINGS
        );

        return [
            'numEvents' => $numEvents
            , 'numBookings' => $numBookings
        ];
    }

    public static function getDashboardModuleCount($table)
    {
        $row = Crud::select(
            $table
            , [
                'columns' => 'COUNT(id) AS total'
                , 'where' => [
                    'deleted' => 0
                ]
            ]
        );
        return $row['total'];
    }
}