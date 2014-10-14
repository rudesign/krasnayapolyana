<?php

class Resorts extends Db{

    public static function set(){
        self::$table = 'resorts';

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

    public static function getWithHotels($limit = 0){
        try{
            $query = Hotels::set();
            $query->join = 'resorts';
            $query->compare = 'hotels.resort = resorts.id';
            $query->fields = 'hotels.id, hotels.name, resorts.id as resortId, resorts.name as resortName';
            $query->order = 'resorts.ord';
            if($limit) $query->limit = $limit;

            if(!$set = Hotels::get($query, array('key'=>'resortId'))) throw new Error($query->string);

            return $set;
        }catch (Error $e){
            return false;
        }
    }
}
?>