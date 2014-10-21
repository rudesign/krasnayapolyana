<?php class_exists('Core', false) or die();

$uri = '/booking/?hotel='.Chapters::$current['item']['id'];

echo '<div class="button-booking" onclick="document.location.assign(\''.$uri.'\');">Забронировать</div>';
?>