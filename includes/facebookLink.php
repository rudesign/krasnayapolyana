<?php class_exists('Core', false) or die();

if(!empty(Core::$params['fb'])) echo '<div class="l"><a href="'.Core::$params['fb'].'" target="_blank"></a></div>';

?>