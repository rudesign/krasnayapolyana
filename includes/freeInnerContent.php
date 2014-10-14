<?php class_exists('Core', false) or die();

if(!empty($id) && !empty($field)){
    if($row = Inner::getById($id)){
        echo decodeHTMLEntities($row[$field]);
    }
}
?>