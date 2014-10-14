<?php

class News extends Db{

    public static function set(){
        self::$table = 'news';

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
}
?>