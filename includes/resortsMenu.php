<?php class_exists('Core', false) or die();

if($set = Resorts::getWithHotels()){
    echo '
    <div class="orange arial i mini-section">
        <p>Выберите</p>
        <p>горнолыжный курорт:</p>
    </div>
    <ul class="orange-bg white submenu">';

    $i = 0;
    foreach($set as $resortId=>$rows){
        echo '
        <li class="title">
            <a href="javascript:toggleSubmenuDetails('.$i.');">'.$rows[0]['resortName'].'</a>';
            $active = false;
            if(Chapters::$current['resort']['id'] == $resortId) $active = true;
            if((reset(Router::$request->parsed->origin) == 'resorts') && (Chapters::$current['item']['id'] == $resortId)) $active = true;
            echo '<ul'.($active ? '' : ' class="h"').'>';
                foreach($rows as $row){
                    $uri = '/hotels/'.$row['id'].'.html';
                    echo '<li'.(Chapters::$current['item']['id'] == $row['id'] ? ' class="active"' : '').'><a href="'.$uri.'">'.$row['name'].'</a></li>';
                }
            echo '</ul>
        </li>';

        $i++;
    }

    echo '</ul>';
}
?>