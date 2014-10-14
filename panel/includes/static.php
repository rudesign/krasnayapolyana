<?php class_exists('Core', false) or die();

if($id) $static = Statics::getById($id);

if(empty($static)) $static = Core::$item ? Core::$item  : Chapters::$current['static'];

if(!empty($static)){
    if(!empty($static['teaser'])) echo '<div>'.$static['teaser'].'</div>';
    if(!empty($static['body'])) echo '<div>'.$static['body'].'</div>';
}
?>
