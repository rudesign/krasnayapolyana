<?php class_exists('Core', false) or die();

Breadcrumbs::$set[] = array('Курорты', '/resorts/');
if(Chapters::$current['item']) Breadcrumbs::$set[] = array(Chapters::$current['item']['name'], '/resorts/'.Chapters::$current['item']['id'].'.html');

Breadcrumbs::show();
?>

