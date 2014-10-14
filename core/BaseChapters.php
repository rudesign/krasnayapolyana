<?php

class BaseChapters extends Db{

    public static $current = array();
    public static $segments = array();
    public static $text = array();

    public static function set(){
        self::$table = 'chapters';

        return new Query(self::$table);
    }

    public static function getCurrent(){

        $path = Router::$request->parsed->path;

        if(end($path) != 'php'){
            // if main page
            if(empty($path)) array_push($path, Settings::$data->startChapterAlias);

            foreach($path as $alias){
                self::$current = self::getByAlias($alias, (self::$current ? self::$current[self::$primary] : 0));

                if(!self::$current) throw new Error('Cannot get segment '.$alias);

                // set chapter associated db table
                if(self::$current['tableDetails']){
                    $tableData = explode('@', self::$current['tableDetails']);
                    self::$current['table']['name'] = end($tableData);
                    if(count($tableData)>1) self::$current['table']['primary'] = reset($tableData);
                }

                self::$segments[] = self::$current;

            }

            self::getText();
        }
    }

    protected static function getText(){
        if(!empty(self::$current['staticId'])) self::$text = Text::getById(self::$current['staticId']);
    }


    public static function getByAlias($alias = '', $parent = 0){
        try{
            if(empty($alias)) throw new Error();
            if(!$query = self::set()) throw new Error();

            $query->condition = "alias = '".$alias."'";
            $query->condition .= " AND parent = '".($parent ? $parent : 0)."'";
            $query->flat = true;

            return self::get($query);
        }catch (Error $e){
            return false;
        }
    }

    public static function getOpenedToAuthorised(){
        $query = self::set();

        $query->fields = 'GROUP_CONCAT('.$query->key.') as ids';
        $query->condition = 'visible>0 AND accessibleToAll>0 AND authRequired>0';
        $query->visibleOnly = false;
        $query->flat = true;

        return $query->get() ? $query->result['ids'] : '';
    }
}
?>