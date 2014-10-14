<?php class_exists('Core', false) or die();

if(Chapters::$current['item']['features']){
    $rowLimit = 7;
    $query = Features::set();
    $query->order = 'ord ASC';

    $query->condition = 'id IN('.Chapters::$current['item']['features'].')';

    if($rows = Features::get($query)){
        $rowsCount = intval(count($rows)/$rowLimit);
        if(count($rows)%$rowLimit) $rowsCount++;

        $k = $j = 0;
        for($i=0;$i<$rowsCount;$i++){
            echo '<ul class="blue hotel-features extra-section">';
            $k = 0;
            while($k<$rowLimit && ($row = $rows[$j])){
                echo '
                <li class="'.$row['alias'].'">
                    <i></i>
                    <span>'.$row['name'].'</span>
                </li>';

                $k++;
                $j++;
            }
            echo '</ul>';
        }
    }
}
?>