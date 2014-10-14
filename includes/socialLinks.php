<?php class_exists('Core', false) or die();

if(!empty(Core::$params['fb'])) echo '<a href="'.Core::$params['fb'].'" class="facebook" target="_blank">Facebook</a>';
if(!empty(Core::$params['tw'])) echo '<a href="'.Core::$params['tw'].'" class="tweeter" target="_blank">Twetter</a>';
if(!empty(Core::$params['vk'])) echo '<a href="'.Core::$params['vk'].'" class="vk" target="_blank">Vk</a>';
if(!empty(Core::$params['ig'])) echo '<a href="'.Core::$params['ig'].'" class="instagram" target="_blank">Instagram</a>';
?>