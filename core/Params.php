<?php

class Params extends Db{

    public static function set(){
        self::$table = 'mainParams';

        return new Query(self::$table);
    }

    public function get_(&$query = null){
        try{
            if(empty($query)) if(!$query = self::set()) throw new Error();

            return $query->get();
        }catch (Error $e){
            return false;
        }
    }

    public static function getCurrent(){
        try{
            if(!$query = self::set()) throw new Error();

            $query->order = 'id DESC';
            $query->limit = 1;
            $query->flat = true;

            if(!$query->get()) throw new Error();

            return $query->result;
        }catch (Error $e){
            return false;
        }
    }
}
?>