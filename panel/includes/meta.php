<?php class_exists('Core', false) or die();

Core::$params['title'] = Chapters::$current['title'] ? Chapters::$current['title'] : Chapters::$current['name'];

if(!empty(Chapters::$current['static'])){
    Core::$params['title'] = Chapters::$current['static']['title'] ? Chapters::$current['static']['title'] : Chapters::$current['static']['name'];
}
?>
