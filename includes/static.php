<?php class_exists('Core', false) or die();

if(!empty($id)){
    if($text = Text::getById($id)){
        if(!empty($text['teaser'])) echo '<div class="section">'.Templates::parse(decodeHTMLEntities($text['teaser']), true).'</div>';
        if(!empty($text['body'])) echo '<div class="section">'.Templates::parse(decodeHTMLEntities($text['body']), true).'</div>';
        if(!empty($text['gallery'])) echo Templates::parse('{{gallery}}', true);
    }
}
?>
