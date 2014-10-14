<?php class_exists('Core', false) or die();

if(Users::$current){
    echo '
    <ul>
        <li>'.Users::$current['name'].'</li>
        <li><a class="buttons green-buttons" href="/panel/bye/" style="position:relative; top:-6px;">Выйти</a></li>
    </ul>
    ';
}else{
    echo '
    <ul>
        <li><a class="buttons green-buttons modal" href="#login-form" style="position:relative; top:-6px;">Войти</a></li>
    </ul>';
}
?>