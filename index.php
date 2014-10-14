<?php

header("Content-type: text/html; charset=utf-8");

// app's server root dir
define('APP_ROOT', $_SERVER['DOCUMENT_ROOT']);
// relative path to the app's dir
define('APP_PATH', '');

try {
    if(!defined('APP_ROOT')) die('Unable to start: no application root defined');
    if(!defined('APP_PATH')) die('Unable to start: no application path defined');

    // attach common methods file
    $fPath = APP_ROOT.'/includes/funcs.php';
    if(file_exists($fPath)) require_once($fPath); else die('Unable to start: no common methods file');

    new Templates();

    Templates::$css = array_merge(Templates::$css, array(
        APP_PATH.'/js/jcarousel/css/jcarousel.basic.css',
        APP_PATH.'/js/jquery-ui/jquery-ui-1.10.3.custom.min.css',
        APP_PATH.'/js/customSelect/style.css',
        APP_PATH.'/js/fancybox/source/jquery.fancybox.css',
        APP_PATH.'/css/common.css?v='.Settings::$data->version,
        APP_PATH.'/css/forms.css?v='.Settings::$data->version,
    ));

    Templates::$js = array_merge(Templates::$js, array(

    ));

    Templates::$bottomJs = array_merge(array(
        APP_PATH.'/js/jquery-1.10.2.min.js',
        APP_PATH.'/js/jquery.form.min.js',
        APP_PATH.'/js/jcarousel/js/jquery.jcarousel.min.js',
        APP_PATH.'/js/jquery-ui/jquery-ui-1.10.3.custom.min.js',
        APP_PATH.'/js/jquery-ui/ui.datepicker-ru.js',
        APP_PATH.'/js/customSelect/jquery.customSelect.min.js',
        APP_PATH.'/js/fancybox/source/jquery.fancybox.pack.js',
        APP_PATH.'/js/common.js?v='.Settings::$data->version,
        'http://www.skypeassets.com/i/scom/js/skype-uri.js'
    ));

    echo Templates::parse('baseWrapper');

} catch (Error $e) {
    error($e);
}
?>