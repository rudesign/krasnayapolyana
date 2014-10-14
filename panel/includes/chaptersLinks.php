<?php class_exists('Core', false) or die();

try{
    if(!Users::$current['accessibleChapters']) throw new Error;

    $query = new Query('chapters');
    $query->idName = 'id';
    $query->condition = 'id IN('.Users::$current['accessibleChapters'].') AND parent='.Chapters::$current['id'].' AND authRequired > 0 AND accessibleToAll = 0';
    $query->order = 'ord ASC';

    if(!$chapters = Chapters::get($query, array('key'=>'adminCategory'))) throw new Error;

    $query = AdminCategories::set();
    $query->condition = 'id IN ('.implode(',', array_keys($chapters)).')';
    $query->order = 'ord ASC';

    if(!$categories = AdminCategories::get($query)) throw new Error;

    echo '<div class="black masonry chapters-links">';

    foreach($categories as $category){

        if(!empty($chapters[$category['id']])){
            echo '
            <ul class="items">
                <li><h2>'.$category['name'].'</h2>
                    <ul>';
                        foreach($chapters[$category['id']] as $row){
                            echo '<li><a href="/'.Chapters::$current['alias'].'/'.$row['alias'].'/">'.$row['name'].'</a></li>';
                        }
                    echo '
                    </ul>
                </li>
            </ul>';
        }
    }

        echo '
        <div class="clear"></div>
    </div>';

}catch (Error $e){

}
?>