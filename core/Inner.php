<?php

class Inner extends Db{
    public static $contentSegments = array();

    public static function set(){
        self::$table = 'innerContent';

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

    public static function getPathSegments($id = 0){
        try{
            if(empty(self::$contentSegments)){
                $query = self::set();
                $query->fields = 'id, parent, alias, name';
                if(!self::$contentSegments = self::get($query, array('key'=>'id'))) throw new Error();
            }

            $segments = array();
            do{
                if($set = self::$contentSegments[$id]) $row = reset($set);

                $id = $row['parent'];

                $segments[] = $row['alias'];
            } while($row['parent']);


            if(empty($segments)) throw new Error();

            $segments = array_reverse($segments);

            return $segments;

        }catch (Error $e){
            return array();
        }
    }
}
?>