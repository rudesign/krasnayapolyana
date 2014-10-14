<?php class_exists('Core', false) or die();

if($row = Inner::getById(41)){
    echo decodeHTMLEntities($row['body']);
}
?>