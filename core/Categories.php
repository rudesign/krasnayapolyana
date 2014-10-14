<?php
class Categories extends Db{

    public static function set(){
        self::$table = 'categories';

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

    public static function create($data = array(), $visible = true){
        try{
            if(empty($data)) throw new Error();

            $query = self::set();

            $query->values = array_merge(array(
                'visible' => $visible,
                'globalId' => getUnique(),
                'createdTime' => time(),
            ), $data);

            if(!$data['createdBy']) $query->values['createdBy'] = Users::$current['id'];

            if(!$id = $query->write()) throw new Error();

            return $id;
        }catch (Error $e){
            return false;
        }
    }
}
?>