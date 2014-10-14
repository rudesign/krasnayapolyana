<?php
    //if(!$_SERVER['HTTP_REFERER']) die();

    header("Content-type: text/html; charset=utf-8");

    define('APP_ROOT', $_SERVER['DOCUMENT_ROOT']);
    define('APP_PATH', '');

    // attach common methods file
    $fPath = APP_ROOT.'/includes/funcs.php';
    if(file_exists($fPath)) require_once($fPath); else die('Unable to start: no common methods file');

    new Core();
?>