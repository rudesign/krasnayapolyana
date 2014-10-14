<?php class_exists('Core', false) or die();

if(Chapters::$innerSegments[0]['id']){
    $grid = new GalleriesGrid();

    $grid->table = 'galleries';

    $grid->getGrid();

    $grid->show();
}
?>