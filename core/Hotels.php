<?php

class Hotels extends Db{

    public static function set(){
        self::$table = 'hotels';

        return new Query(self::$table);
    }

    public static function get(&$query = null, $options = array()){
        try{
            if(empty($query)) if(!$query = self::set()) throw new Error();

            return parent::get($query, $options);
        }catch (Error $e){
            return array();
        }
    }

    public static function getById($id = 0, $key = ''){
        try{
            if(!self::set()) throw new Error();

            return parent::getById($id, $key);
        }catch (Error $e){
            return array();
        }
    }

    public static function getByRating($limit = 0){
        try{
            $query = self::set();
            $query->fields = 'id, name, alias, rating';
            if($limit) $query->limit = $limit;

            if(!$rows = self::get($query, array('key'=>'rating'))) throw new Error;

            ksort($rows);

            return $rows;
        }catch (Error $e){
            return false;
        }
    }
}
?>