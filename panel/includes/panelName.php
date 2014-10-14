<?php class_exists('Core', false) or die();

echo '
    <div>
        <dl style="font: bold 32px/1em Arial; font-weigth: 700;">'.$_SERVER['HTTP_HOST'].'</dl>
        <dl style="font: bold 18px/3em Arial; font-weigth: 700;">'.Chapters::$current['name'].'</dl>
    </div>';
?>
