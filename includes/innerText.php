<?php class_exists('Core', false) or die();

if(!empty(Core::$item['teaser'])) echo '<div class="mini-section">'.Templates::parse(decodeHTMLEntities(Core::$item['teaser']), true).'</div>';
if(!empty(Core::$item['body'])) echo '<div class="text mini-section">'.Templates::parse(decodeHTMLEntities(Core::$item['body']), true).'</div>';


?>