<?php class_exists('Core', false) or die();

if(!empty(Core::$params['email'])){
    echo Core::$params['email'];
}
?>