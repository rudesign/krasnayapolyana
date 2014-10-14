<?php class_exists('Core', false) or die();

$grid = new HotelsGrid();

$grid->table = 'hotels';

$grid->getGrid();

$grid->show();
?>