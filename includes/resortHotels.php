<?php class_exists('Core', false) or die();

$grid = new HotelsGrid();

$grid->table = 'hotels';

$grid->modifyQuery();

$grid->query->condition = 'hotels.resort = '.Chapters::$current['item']['id'];

if($rows = Hotels::get($grid->query)){

    echo '
    <h2>Отели курорта</h2>
    <div class="hotels-grid" style="border-top: 1px solid #ccc; padding-top: 15px;">';
        foreach($rows as $row){
            $grid->showGridItem($row);
        }
    echo '</div>';
}
?>