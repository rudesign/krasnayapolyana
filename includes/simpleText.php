<?php class_exists('Core', false) or die();

if(!empty($id)){
    if($row = Text::getById($id)){
        $text = $row['teaser'] ? : $row['body'];
        if(!empty($text)){
            echo $text;
        }
    }
}
?>