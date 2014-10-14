<?php class_exists('Core', false) or die();

if(!empty(Chapters::$current['rootContent']['body'])) echo Templates::parse(Chapters::$current['rootContent']['body'], true);

?>