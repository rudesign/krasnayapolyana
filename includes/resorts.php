<?php class_exists('Core', false) or die();

$grid = new ResortsGrid();

$grid->table = 'resorts';

$grid->getGrid();

$grid->show();
?>