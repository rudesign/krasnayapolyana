<?php

class Chapters extends BaseChapters{
    public static $innerSegments = array();

    public static function getCurrent(){

        $path = Router::$request->parsed->path;

        if(empty($path)) array_push($path, Settings::$data->startChapterAlias);

        foreach($path as $alias){
            if(!$current = self::getByAlias($alias, (self::$current ? self::$current[self::$primary] : 0))){
                if(!self::getShop($alias)) throw new Error('Cannot get content segment '.$alias);
            }else {
                self::$current = $current;
                unset($current);
            }

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

    private static function getShop($alias){
        try{
            $query = Inner::set();
            $lastSegment =  !empty(self::$innerSegments) ? end(self::$innerSegments) : null;
            $query->condition = "alias = '{$alias}' AND parent = ";
            $query->condition .= $lastSegment ? $lastSegment['id'] : 0;
            $query->flat = true;

            if(!$item = Inner::get($query)) throw new Error();

            self::$innerSegments[] = $item;

            Users::route('/content/'.$item['id'].'.html');

            return true;
        }catch (Exception $e){
            return false;
        }
    }
}
?>