<?php
namespace KpakpandoEventsBooking\Sequence;

use KpakpandoEventsBooking\Crud\Crud;

class Sequence
{
    protected static $table = DEF_TBL_SEQUENCE;

    public static function getNewReferenceByRecord($type, $recordId, $prefix)
    {
        $row = Crud::select(
            DEF_TBL_SEQUENCE, [
                'columns' => 'counter',
                'where' => [
                    'type' => $type
                    , 'record_id' => $recordId
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

        return "{$prefix}{$newCounter}";
    }

    public static function updateReferenceCounterByRecord($type, $recordId)
    {
        //get new counter
        $row = Crud::select(
            self::$table
            , [
                'columns' => 'counter',
                'where' => [
                    'record_id' => $recordId
                    , 'type' => $type
                ]
            ]
        );
        if ($row)
        {
            $counter = doTypeCastInt($row['counter']);
            Crud::update(
                self::$table
                , ['counter' => $counter + 1]
                , ['type' => $type]
            );
        }
        else
        {
            $data = [
                'type' => $type
                , 'record_id' => $recordId
                , 'counter' => 1
            ];
            Crud::insert(
                self::$table
                , $data
            );
        }
    }
}