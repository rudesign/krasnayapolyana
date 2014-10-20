<?php class_exists('Core', false) or die();

if($set = Hotels::getByRating()){

    $i = 0;

    echo '<div class="tr">';

    foreach($set as $rating=>$rows){
        $uri = '/resorts/'.$rows[0]['resortAlias'].'.html';

        echo '
        <div class="items item'.$i.'">
            <div class="white-rating title">
                <div class="l" style="margin-right:5px;">Отели</div>';
                for($k=0;$k<$rating;$k++){ echo '<span></span>'; }
            echo '</div>
            <ul>';
            foreach($rows as $index=>$row){
                $uri = '/hotels/'.$row['alias'].'.html';
                echo '<li><a href="'.$uri.'">'.$row['name'].'</a></li>';
                if($index > 1) break;
            }
            echo '
            </ul>
            <div class="more">
                <a href="/hotels/?rating='.$rating.'">Все отели '.$rating.' '.Dictionary::get('stars', $rating).'</a>
            </div>
        </div>';

        $i++;
    }

    echo '</div>';
}
?>