<?php

/* MySQL DB queries composer & manager */

class Query{

    public $fields = null;
    public $values = null;
    public $tables = null;
    public $join = null;
    public $joinType = 'INNER JOIN';
    public $compare = null;
    public $key = 'id';
    public $id = 0;
    public $condition = null;
    public $having = null;
    public $group = '';
    public $limit = '';
    public $order = '';

    private $querySegments = array();
    public $string = '';
    // multi-dimentional result
    public $flat = false;
    // discard wrapping values with quotes
    public $naturalValues = false;
    // visible rows only
    public $visibleOnly = true;

    public $result = array();

    public function __construct($tables = null){
        try{
            if(!class_exists('Db', false)) throw new Error('DB class does not exist', true);

            if(!$this->setTables($tables)) throw new Error('Cannot set tables', true);

            return true;
        }catch (Error $e){
            return null;
        }
    }

    public function setTables($tables = null){
        try{
            if(empty($tables)) throw new Error();

            $this->tables = $this->getArrFromUnknown($tables);

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public function get(){
        try{
            if(empty($this->tables)) throw new Error();

            $this->constructFields();
            $this->querySegments[] = "SELECT";
            $this->querySegments[] = $this->fields;
            $this->querySegments[] = "FROM";
            if(!$this->constructTables()) throw new Error();
            if($this->join) if(!$this->constructJoin()) throw new Error();

            if($this->id && $this->key){
                $this->querySegments[] = "WHERE ".$this->key." = ".(!$this->naturalValues ? "'" : '').$this->id.(!$this->naturalValues ? "'" : '');
                if($this->visibleOnly) $this->querySegments[] = "AND ".$this->tables[0].".visible>0 AND ".$this->tables[0].".pubTime <= ".time();
            }else{
                if($this->condition) {
                    $this->querySegments[] = "WHERE ".$this->condition;
                    if($this->visibleOnly) $this->querySegments[] = "AND ".$this->tables[0].".visible>0 AND ".$this->tables[0].".pubTime <= ".time();
                }else{
                    if($this->visibleOnly) $this->querySegments[] = "WHERE ".$this->tables[0].".visible>0 AND ".$this->tables[0].".pubTime <= ".time();
                }
            }

            if($this->group) $this->querySegments[] = "GROUP BY ".$this->group;
            if($this->order) { $this->querySegments[] = "ORDER BY ".$this->order; }else{ $this->querySegments[] = "ORDER BY ".$this->tables[0].".".$this->key." DESC"; }
            if($this->limit) $this->querySegments[] = "LIMIT ".$this->limit;

            if(!$this->constructQueryString()) throw new Error();

            if(!$result = $this->execute()) throw new Error();
            return $result;
        }catch (Error $e){
            return false;
        }
    }

    public function getById(){
        try{
            if(empty($this->tables)) throw new Error();
            if(empty($this->key)) throw new Error();

            $this->querySegments[] = "SELECT";
            if(!$this->constructFields()) throw new Error();
            $this->querySegments[] = $this->fields;
            $this->querySegments[] = "FROM";
            if(!$this->constructTables()) throw new Error();
            if($this->join) if(!$this->constructJoin()) throw new Error();
            $this->querySegments[] = "WHERE ".$this->key." = '".$this->id."'";

            if($this->visibleOnly) $this->querySegments[] = "AND ".$this->tables[0].".visible>0 AND ".$this->tables[0].".pubTime <= ".time();

            if(!$this->constructQueryString()) throw new Error();

            if(!$result = $this->execute()) throw new Error();

            return $result;
        }catch (Error $e){
            return false;
        }
    }

    public function update(){
        try{
            if(empty($this->tables)) throw new Error();

            $this->querySegments[] = "UPDATE";
            if(!$this->constructTables()) throw new Error();
            if(!$set = $this->constructUpdateSet()) throw new Error();
            $this->querySegments[] = $set;

            if($this->id && $this->key){
                $this->querySegments[] = "WHERE ".$this->key." = ".(!$this->naturalValues ? "'" : '').$this->id.(!$this->naturalValues ? "'" : '');
            }else{
                if($this->condition) $this->querySegments[] = "WHERE ".$this->condition;
            }

            if($this->limit) $this->querySegments[] = "LIMIT ".$this->limit;

            if(!$this->constructQueryString()) throw new Error();

            if(!$this->execute(false)) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    private function constructUpdateSet(){
        try{
            if(empty($this->fields)) throw new Error();

            // fields as array
            if(is_array($this->fields)){
                $values = $this->getArrFromUnknown($this->values);

                $result = array();
                $i=0;

                foreach($this->fields as $field){
                    $result[$i] = $field.'='.($this->naturalValues ? '' : "'").$values[$i].($this->naturalValues ? '' : "'");
                    $i++;
                }

                if(empty($result)) throw new Error();

                $set = ' SET '.implode(',', $result);

                unset($fields, $values, $result);
                // fields as string
            }else{
                $set = ' SET '.$this->fields.'='.($this->naturalValues ? '' : "'").$this->values.($this->naturalValues ? '' : "'");
            }

            return $set;
        }catch (Error $e){
            return false;
        }
    }

    public function write(){
        try{
            if(empty($this->tables)) throw new Error();
            if(empty($this->values)) throw new Error();

            foreach($this->tables as $table){

                $this->string = "INSERT INTO ".$table;
                $this->string .= ' '.self::constructWriteValues($this->values);

                if(!$this->execute(false)) throw new Error();
            }

            return mysql_insert_id();
        }catch (Error $e){
            return false;
        }
    }
    protected function constructWriteValues($values = null){
        $valuesToWrite = '';

        if(!empty($values)){
            if(is_array($values)){
                $valuesToWriteArr = array();
                $tableFieldsArr = array();

                foreach($values as $key=>$value){
                    $tableFieldsArr[] = '`'.$key.'`';
                    $valuesToWriteArr[] = isset($value) ? "'".$value."'" : "''";
                }

                $valuesToWrite = "(".implode(', ', $tableFieldsArr).") VALUES (".implode(', ', $valuesToWriteArr).")";
            }else{
                $valuesToWrite = "VALUES '".$values."'";
            }
        }

        return $valuesToWrite;
    }

    public function delete(){
        try{
            if(empty($this->tables)) throw new Error();

            $this->key = $this->key ? $this->key : 'id';

            foreach($this->tables as $table){

                $this->querySegments[] = "DELETE FROM ".$table;
                if($this->key && $this->id){
                    $this->querySegments[] = " WHERE ".$this->key."=".($this->naturalValues ? '' : "'").$this->id.($this->naturalValues ? '' : "'");
                } else if($this->condition){
                    $this->querySegments[] = " WHERE ".$this->condition;
                }else if($this->having){
                    $this->querySegments[] = " HAVING ".$this->having;
                }else throw new Error();

                if(!$this->constructQueryString()) throw new Error();

                if(!$this->execute(false)) throw new Error();
            }

            return true;

        }catch (Error $e){
            return false;
        }
    }


    private function constructFields(){
        try{
            if(empty($this->fields)) {
                $this->fields = '*';
            }else{
                $this->fields = is_array($this->fields) ? implode(', ', $this->fields) : $this->fields;
            }

            return true;
        }catch (Error $e){
            return false;
        }
    }

    private function constructTables(){
        try{
            $tables = is_array($this->tables) ? implode(', ', $this->tables) : $this->tables;
            $this->querySegments[] = $tables;

            return true;
        }catch (Error $e){
            return false;
        }
    }

    private function constructJoin(){
        try{
            if(empty($this->compare)) throw new Error();

            if(is_array($this->join)){
                if(!is_array($this->compare)) throw new Error();
                if(count($this->compare) != count($this->join)) throw new Error();

                foreach($this->join as $index=>$table){
                    $this->querySegments[] = $this->joinType." ".$table;
                    $this->querySegments[] = "ON ".$this->compare[$index];
                }
            }else{
                $this->querySegments[] = $this->joinType." ".$this->join;

                $compare = is_array($this->compare) ? reset($this->compare) : $this->compare;

                $this->querySegments[] = "ON ".$compare;
            }

            return true;
        }catch (Error $e){
            return false;
        }
    }

    private function constructQueryString(){
        try{
            if(empty($this->querySegments)) throw new Error();

            $this->string = implode(' ', $this->querySegments);
            unset($this->querySegments);

            return $this->string;
        }catch (Error $e){
            return false;
        }
    }

    public function execute($get = true){
        try{
            if(empty($this->string)) throw new Error();

            if(!$result = @mysql_query($this->string)) throw new Error();

            if($get){
                if($this->flat){
                    $this->result = mysql_fetch_array($result, MYSQL_ASSOC);
                }else{
                    while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
                        $this->result[] = $row;
                    }
                }

                unset($result, $row);
            }

            return $get ? $this->result : true;
        }catch (Error $e){
            return false;
        }
    }

    private function getArrFromUnknown($input = null){
        try{
            if(!empty($input)){
                $input = is_array($input) ? $input : explode(',', $input);
                return array_filter($input, create_function('$i', 'return trim($i);'));
            }else throw new Error();
        }catch (Error $e){
            return array('');
        }
    }


    public function flush(){
        $this->fields = null;
        $this->values = null;
        $this->key = 'id';
        $this->id = 0;
        $this->condition = null;
        $this->having = null;
        $this->group = '';
        $this->limit = '';
        $this->order = '';
        $this->string = '';
        $this->flat = false;
        $this->naturalValues = false;
        $this->visibleOnly = true;
        $this->result = array();
    }
}
?>