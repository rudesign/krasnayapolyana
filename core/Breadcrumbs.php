<?php

class Breadcrumbs{
    public static $set = array(array('Главная', '/'));

    public function __construct(){

    }

    public static function show(){
        $lastIndex = count(self::$set)-1;

        $links = array();
        foreach(self::$set as $index=>$item){
            $showHref = ($item[1] && (($index != $lastIndex) || !$index)) ? true : false;
            $links[] = ($showHref ? '<a href="'.$item[1].'">' : '').$item[0].($showHref ? '</a>' : '');
        }

        echo '<div class="extrasmall breadcrumbs">';
        echo implode(' / ', $links);
        echo '</div>';
    }
}
?>