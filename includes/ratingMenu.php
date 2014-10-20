<?php class_exists('Core', false) or die();

if($set = Hotels::getByRating()){
    echo '
        <div class="blue arial i mini-section">
        <p>Выберите</p>
        <p>категорию отеля:</p>
    </div>
    <ul class="white submenu rating-submenu">';

    $i = 0;
    foreach($set as $rating=>$rows){
        echo '
        <li onclick="toggleSubmenuDetails('.$i.');" class="title white-rating">';
            for($k=0;$k<$rating;$k++){ echo '<span></span>'; }
            echo '
            <ul'.(Chapters::$current['resort']['rating'] == $rating ? '' : ' class="h"').'>';
            foreach($rows as $row){
                $uri = '/hotels/'.$row['alias'].'.html';
                echo '<li'.(Chapters::$current['item']['id'] == $row['id'] ? ' class="active"' : '').'><a href="'.$uri.'">'.$row['name'].'</a></li>';
            }
            echo '
            </ul>
        </li>';

        $i++;
    }

    echo '</ul>';
}
?>