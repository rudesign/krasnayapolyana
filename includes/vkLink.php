<?php class_exists('Core', false) or die();

if(!empty(Core::$params['vk'])) echo '<div class="r"><a href="'.Core::$params['vk'].'" target="_blank"></a></div>';

?>