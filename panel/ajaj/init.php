<?php
if(!$_SERVER['HTTP_REFERER']) die();

    header("Content-type: text/html; charset=utf-8");

    define('APP_ROOT', $_SERVER['DOCUMENT_ROOT']);
    define('APP_PATH', '/panel');
    define('DIALOG_APP_ROOT', APP_ROOT.APP_PATH);

    // attach common methods file
    $fPath = DIALOG_APP_ROOT.'/includes/funcs.php';
    if(file_exists($fPath)) require_once($fPath); else die('Unable to start: no common methods file');

    new Users();

    Core::$visibleOnlyItems = false;

    Templates::$templatesDirs = array(
        DIALOG_APP_ROOT.'/templates'
    );
    Templates::$includesDirs = array(
        DIALOG_APP_ROOT.'/includes'
    );
?>