<?php class_exists('Core', false) or die();

Breadcrumbs::$set[] = array('Курорты', '/resorts/');

if(Chapters::$current['resort']) Breadcrumbs::$set[] = array(Chapters::$current['resort']['name'], '/resorts/'.Chapters::$current['resort']['alias'].'.html');

Breadcrumbs::$set[] = array('Отели', '/hotels/'.(Chapters::$current['resort'] ? '?resort='.Chapters::$current['resort']['alias'] : ''));

if($_GET['rating']) Breadcrumbs::$set[] = array($_GET['rating'].' '.Dictionary::get('stars', $_GET['rating']), '/hotels/?rating='.$_GET['rating']);

if(Chapters::$current['item']) Breadcrumbs::$set[] = array(Chapters::$current['item']['name'], '/hotels/'.Chapters::$current['item']['alias'].'.html');

Breadcrumbs::show();
?>

