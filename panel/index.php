<?php
header("Content-type: text/html; charset=utf-8");

define('APP_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('APP_PATH', '/panel');
define('DIALOG_APP_ROOT', APP_ROOT.APP_PATH);


try {
    if(!defined('APP_ROOT')) die('Unable to start: no application root defined');
    if(!defined('APP_PATH')) die('Unable to start: no application path defined');

    // attach common methods file
    $fPath = DIALOG_APP_ROOT.'/includes/funcs.php';
    if(file_exists($fPath)) require_once($fPath); else die('Unable to start: no common methods file');

    Core::$visibleOnlyItems = false;

    new Templates();

    Templates::$templatesDirs = array(
        DIALOG_APP_ROOT.'/templates'
    );
    Templates::$includesDirs = array(
        DIALOG_APP_ROOT.'/includes'
    );

    Templates::$css = array(
        APP_PATH.'/css/reset.css',
        APP_PATH.'/css/common.css',
        APP_PATH.'/css/jquery.pnotify.default.css',
        APP_PATH.'/css/jquery.pnotify.default.icons.css',
        APP_PATH.'/css/panel.css',
    );

    Templates::$js = array_merge(Templates::$js, array(
        APP_PATH.'/js/jquery-1.10.2.min.js',
        APP_PATH.'/js/jquery.cookie.js',
        APP_PATH.'/js/jquery.form.min.js',
        APP_PATH.'/js/respond.min.js',
        APP_PATH.'/js/jquery.leanModal.min.js', // http://leanmodal.finelysliced.com.au/
        APP_PATH.'/js/jquery.pnotify.min.js', // http://pinesframework.org/pnotify/
        APP_PATH.'/ckeditor/ckeditor.js',
        APP_PATH.'/ckeditor/adapters/jquery.js',
        APP_PATH.'/ckeditor/ckfinder/ckfinder.js',
        APP_PATH.'/js/jquery.ui.widget.js',
        APP_PATH.'/js/jquery.iframe-transport.js',
        APP_PATH.'/js/jquery.fileupload.js',
        APP_PATH.'/js/jquery-ui-1.10.0.custom.min.js',
        APP_PATH.'/js/panel.js?v=1',
    ));

    echo Templates::parse('baseTemplate');

} catch (Error $e) {
    error($e);
}
?>