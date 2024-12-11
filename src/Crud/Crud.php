<?php
namespace KpakpandoEventsBooking\Crud;

class Crud
{
    public static function insert($table, $data)
    {
        global $db;

        $cols = array_keys($data);
        $values = array_values($data);
        //generate the escaping ? based on the number of data keys
        $valString = str_repeat("?,", count($cols));
        //remove the last comma
        $valString = substr($valString, 0, -1);
        $cols = implode(',', $cols);
        $insert = "INSERT INTO ".$table." (".$cols.") VALUES(".$valString.")";
        $insert = $db->prepare($insert);
        $insert->execute($values);
        if($insert)
        {
            return true;
        }
        return false;
    }

    public static function select($table, $data=[])
    {
        global $db;

        $join = $joinOn = $joinCols = "";
        if(array_key_exists('join', $data))
        {
            $joinTable = $data['join']['table'];
            $joinColumns = $data['join']['columns'];
            if(count($joinColumns) > 0)
            {
                foreach($joinColumns as $col)
                {
                    $joinCols .= ', '.$joinTable.'.'.trim($col);
                }
            }
            
            $joinType = $data['join']['type'];
            if(array_key_exists('on', $data['join']))
            {
                $joinOn = ' ON ' . $data['join']['on'];
            }
            
            $join = ' ' . $joinType . ' ' . $joinTable . $joinOn;
        }

        $columns = array_key_exists('columns', $data) && !empty($data['columns']) ? $data['columns'] : "*";
        if($joinCols != '')
        {
            $arColumns = explode(',', $columns);
            if(count($arColumns) > 0)
            {
                $i = 0;
                $columns = '';
                foreach($arColumns as $col)
                {
                    $comma = ($i > 0) ? ', ' : '';
                    $columns .= $comma.$table.'.'.trim($col);

                    $i++;
                }
            }
            $columns .= $joinCols;
        }
        $return_type = array_key_exists("return_type", $data) && !empty($data['return_type']) ? $data['return_type'] : "row";
        $where = "";
        $values = [];

        $allowWhere = false;
        if(array_key_exists('where', $data) && count($data['where']) > 0)
        {
            $allowWhere = true;
        }
        if($allowWhere)
        {
            $where = '';
            if(array_key_exists('where', $data) && count($data['where']) > 0)
            {
                $where .= " WHERE ";
                $i = 0;
                foreach($data['where'] as $key => $value)
                {
                    $con = ($i > 0) ? " AND " : "";
                    /*
                    if(is_array($value) && in_array('expression', $value))
                    {
                        $where .= $con . $value[1];
                        unset($data['where'][$key]);
                    }
                    */
                    if($key == 'expression')
                    {
                        $where .= $con . $value;
                        unset($data['where'][$key]);
                    }
                    else
                    {
                        $where .= $con . $key . " = ?";
                        $values[] = $value;
                    }
                    $i++;
                }
                //$values = array_values($data['where']);
            }
        }
        $allowOrWhere = false;
        if(array_key_exists('orWhere', $data) && count($data['orWhere']) > 0)
        {
            $allowOrWhere = true;
        }
        if($allowWhere && $allowOrWhere)
        {
            $i = 0; 
            foreach($data['orWhere'] as $key => $value)
            {
                $con = " OR ";
                if(is_array($value) && in_array('expression', $value))
                {
                    $where .= $con . $value[1];
                    unset($data['where'][$key]);
                }
                else
                {
                    $where .= $con . $key . " = ?";
                }
                $i++;
            }
            $valuesx = array_values($data['orWhere']);
            $values = array_merge($values, $valuesx);
        }

        if (array_key_exists('search', $data))
        {
            $i = 0;
            $where .= empty($where) ? " WHERE" : " AND";
            foreach($data['search'] as $key => $value)
            {
                $con = ($i > 0) ? " OR " : " ";
                $where .= $con . $key . " LIKE ?";
            }
            $valuesx = array_values($data['search']);
            $values = array_merge($values, $valuesx);
        }

        if (array_key_exists('group', $data))
        {
            $where .= " GROUP BY ". $data['group'];
        }

        if (array_key_exists('order', $data))
        {
            $where .= " ORDER BY ". $data['order'];
        }
        if (array_key_exists("limit", $data))
        {
            $where .= " LIMIT ". $data['limit'];
        }
        
        $select = "SELECT ".$columns." FROM ".$table . $join . $where;//echo $select;exit;
        $select = $db->prepare($select);
        $select->execute($values);
        if($select->rowCount() > 0)
        {
            if($return_type == 'row')
            {
                return $select->fetch(\PDO::FETCH_ASSOC);
            }
            return $select->fetchAll(\PDO::FETCH_ASSOC);
        }
        return [];
    }

    public static function update($table, $data, $where)
    {
        global $db;

        $datax = "";
        $values = [];
        if(count($data) > 0)
        {
            $i = 0; 
            foreach($data as $key => $value)
            {
                $comma = ($i > 0) ? ", " : "";
                $datax .= $comma . $key . " = ?";
                $i++;
            }
            $values = array_values($data);
        }

        $whre = "";
        if (count($where) > 0)
        { 
            $whre .= " WHERE ";
            $i = 0; 
            foreach($where as $key => $value)
            {
                $and = ($i > 0) ? " AND " : "";
                $whre .= $and . $key . " = ?";
                $i++;
            }
            $valuesx = array_values($where);
            $values = array_merge($values, $valuesx);
        }
        
        $update = "UPDATE ".$table." SET ".$datax . $whre;
        $update = $db->prepare($update);
        $update->execute($values);
        if ($update)
        {
            return true;
        }
        return false;
    }

    public static function delete($table, $where=[])
    {
        global $db;

        $values = [];
        $whre = "";
        if(count($where) > 0)
        { 
            $whre .= " WHERE ";
            $i = 0; 
            foreach($where as $key => $value)
            {
                $and = ($i > 0) ? " AND " : "";
                $whre .= $and . $key . " = ?";
                $i++;
            }
            $values = array_values($where);
        }
        $delete = "DELETE FROM ".$table . $whre;
        $delete = $db->prepare($delete);
        $delete->execute($values);
        if($delete)
        {
            return true;
        }
        return false;
    }

    public static function checkDuplicate($table, $field, $value, $id='', $checkDeleted=false)
    {
        $arWhere = [
            $field => $value
        ];
        if ($checkDeleted)
        {
            $arWhere['deleted'] = 0;
        }
        $rs = self::select(
            $table,
            [
                'columns' => 'id',
                'where' => $arWhere
            ]
        );
        if ($rs)
        {
            if ($id != '')
            {
                if (strlen($rs['id']) == 36 && $rs['id'] == $id)
                {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public static function checkDuplicateByArray($table, $arWhere)
    {
        $rs = self::select(
            $table,
            [
                'columns' => 'id',
                'where' => $arWhere
            ]
        );
        if ($rs)
        {
            return true;
        }
        return false;
    }

    public static function getRecordInfo($table, $record, $arFields=['*'])
    {
        $fields = implode(',', $arFields);
        return self::select(
            $table,
            [
                'columns' => $fields,
                'where' => [
                    'id' => $record
                ]
            ]
        );
    }
}