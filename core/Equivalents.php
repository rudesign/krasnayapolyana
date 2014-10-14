<?php

class Equivalents extends Db{

    public static function set(){
        self::$table = 'equivalents';

        return new Query(self::$table);
    }

    public static function get(&$query = null){
        try{
            if(empty($query)) if(!$query = self::set()) throw new Error();

            return parent::get($query);
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

    public static function create($uri, $equivalent){
        try{
            if(empty($uri) || empty($equivalent)) throw new Error();

            $query = self::set();

            $query->values = array(
                'visible'=>1,
                'globalId' => getUnique(),
                'host'=>$_SERVER['HTTP_HOST'],
                'uri'=>$equivalent,
                'equivalent'=>$uri,
                'pubTime' => time(),
                'createdTime' => time(),
                'createdBy' => Users::$current['id'],
            );

            if(!$query->write()) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function updateEquivalent($uri, $equivalent = ''){
        try{
            if(empty($uri)) throw new Error();

            // getting an existing record
            if(!$item = self::getById($uri, 'equivalent', false)){
                // create if no record
                if(!empty($equivalent)) self::create($uri, $equivalent);
            }

            $query = self::set();
            $query->id = $item[$query->idName];

            // delete existed if no equivalent
            if(empty($equivalent)){
                if(!$query->delete()) throw new Error($query->query);
                // update existed
            }else{
                $query->fields = array(
                    'uri',
                    'modifiedTime',
                    'modifiedBy',
                );
                $query->values = array(
                    $equivalent,
                    time(),
                    Users::$current['id'],
                );
                if(!$query->update()) throw new Error();
            }

            return true;
        }catch (Error $e){
            return false;
        }
    }
}
?>