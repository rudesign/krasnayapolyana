<?php class_exists('Core', false) or die();

$query = Menu::set();
$query->condition = 'footer > 0';
$query->order = 'ord ASC';

if($rows = Menu::get($query)){
    $count = count($rows);
    $rowsN = ($count+($count%2))/2;

    $k = 0;
    for($i=0;$i<2;$i++){
        echo '<ul>';
        for($j=0;$j<$rowsN;$j++){
            $k = $i*$rowsN + $j;

            if($row = $rows[$k]){
                $uri = $row['uri'] ? $row['uri'] : ($row['alias'] ? '/'.$row['alias'].'/' : '/');
                
                echo '<li><a href="'.$uri.'">'.$row['name'].'</a></li>';
            }

        }
        echo '</ul>';
    }
}
?>