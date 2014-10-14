<?php class_exists('Core', false) or die();

$h1 = (Chapters::$text ? Chapters::$text['name'] : Chapters::$current['name']);

if(Core::$item) $h1 = Core::$item['name'];

if(Chapters::$current['item']) $h1 = Chapters::$current['item']['name'];

echo decodeHTMLEntities($h1);
?>
