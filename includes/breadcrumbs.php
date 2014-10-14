<?php class_exists('Core', false) or die();

if(!empty(Core::$item['alias'])) Breadcrumbs::$set[] = array(Core::$item['name'], '/'.Core::$item['alias'].'/');

Breadcrumbs::show();
?>

