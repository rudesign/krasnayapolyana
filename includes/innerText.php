<?php class_exists('Core', false) or die();

if(!empty(Core::$item['teaser'])) echo '<div class="section">'.Templates::parse(decodeHTMLEntities(Core::$item['teaser']), true).'</div>';
if(!empty(Core::$item['body'])) echo '<div class="text section">'.Templates::parse(decodeHTMLEntities(Core::$item['body']), true).'</div>';


?>