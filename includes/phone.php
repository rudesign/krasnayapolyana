<?php class_exists('Core', false) or die();

$phone = Chapters::$current['city']['phone'];

if(!empty($phone)){
    if($short){
        echo str_replace(array(' ', '-', '(', ')'), array(), $phone);
    }else{
        $phone = explode(' ', $phone);
        $phone[(count($phone)-1)] = '<strong>'.end($phone).'</strong>';

        echo implode(' ', $phone);
    }

}
?>