<?php class_exists('Core', false) or die();

$grid = new NewsGrid();

$grid->table = 'news';

$grid->getGrid();

$grid->show();
?>