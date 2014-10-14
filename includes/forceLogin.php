<?php class_exists('Core', false) or die();

if(
    ($_SERVER['HTTP_REFERER'] != 'http://'.$_SERVER['HTTP_HOST'].'/bye/')
    && ($_SERVER['HTTP_REFERER'] != 'http://'.$_SERVER['HTTP_HOST'].'/force-login/')
){
    $uri = $_SERVER['HTTP_REFERER'];
}else{
    $uri = '/';
}

if(!Users::login($_POST['login'], $_POST['password'])){
    echo 'Неверные данные. <a href="/login/">Попробуйте войти</a> еще раз';
}else Router::redirect('/');
?>
