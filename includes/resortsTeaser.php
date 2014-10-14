<?php class_exists('Core', false) or die();


if($set = Resorts::getWithHotels()){

    $i = 3;

    echo '<div class="tr">';

    foreach($set as $rows){
        $uri = '/resorts/'.$rows[0]['resortId'].'.html';

        echo '
        <div class="items item'.$i.'">
            <div class="title">
                <div class="l">'.$rows[0]['resortName'].'</div>
                <div class="r"><a href="'.$uri.'">Подробнее о курорте</a></div>
                <div class="clear"></div>
            </div>
            <ul>';
            foreach($rows as $index=>$row){
                $uri = '/hotels/'.$row['id'].'.html';
                echo '<li><a href="'.$uri.'">'.$row['name'].'</a></li>';
                if($index > 1) break;
            }
            echo '
            </ul>
            <div class="more">
                <a href="/hotels/?resort='.$rows[0]['resortId'].'">Все отели курорта</a>
            </div>
        </div>';

        $i++;
    }

    echo '</div>';
}
?>