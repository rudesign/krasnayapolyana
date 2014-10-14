<?php

class Feedback extends Db{

    public static function set(){
        self::$table = 'feedback';

        return new Query(self::$table);
    }

    public static function get(&$query = null, $options = array()){
        try{
            if(empty($query)) if(!$query = self::set()) throw new Error();

            return parent::get($query, $options);
        }catch (Error $e){
            return false;
        }
    }

    public static function getById($id = 0, $key = ''){
        try{
            if(!self::set()) throw new Error();

            return parent::getById($id, $key);
        }catch (Error $e){
            return false;
        }
    }

    public static function create($data = array()){
        try{
            $query = self::set();

            $query->values = array_merge(array(
                'createdTime' => time(),
                'createdBy' => (Users::$current ? Users::$current['id'] : 0),
            ), $data);

            if(!$id = $query->write()) throw new Error();

            return $id;
        }catch (Error $e){
            return false;
        }
    }
}
?>