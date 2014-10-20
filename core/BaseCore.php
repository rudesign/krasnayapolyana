<?php

class BaseCore extends Db{

    // application parameters in DB
    public static $params = array();
    // str current theme name = dir name in /themes
    public static $theme = 'default';
    // array Db item if id extracted
    public static $item = array();

    public static $visibleOnlyItems = true;

    public function __construct(){

        parent::__construct();

        $this->checkRedirect();

        $this->setTimezone();

        $this->getParams();

        Session::set();

        $this->setCurrentTheme();

        Chapters::getCurrent();

        Core::getItem();
    }

    private function setTimezone(){
        $timezone = 'Europe/Moscow';

        ini_set('date.timezone', $timezone);
        date_default_timezone_set($timezone);

        if(!empty($_REQUEST['userTimeOffset'])) self::setUserTimeOffset($_REQUEST['userTimeOffset']);
    }

    private function getParams(){
        if(!self::$params = Params::getCurrent()) throw new Error('No application parameters', true);
    }

    private function setCurrentTheme(){
        try{
            if(!empty(Router::$request->subdomain)){
                if(!empty(Settings::$data->themes)){
                    if(array_search(Router::$request->subdomain, Settings::$data->themes) !== false) self::$theme = Router::$request->subdomain;
                }
            }

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function getItem(){
        try{
            if(empty(Router::$id)) throw new Error();
            if(empty(Chapters::$current['table']['primary']) || empty(Chapters::$current['table']['name'])) throw new Error();

            try{
                if($cName = getGridCName()){
                    $obj = new $cName;
                    if(class_exists($cName, false)){
                        $obj->modifyConnection();
                    }
                }
            }catch (Error $e){}

            $query = new Query(Chapters::$current['table']['name']);
            $query->key = Chapters::$current['table']['primary'];
            $query->id = Router::$id;
            $query->flat = true;
            $query->visibleOnly = Core::$visibleOnlyItems;

            if((!self::$item = $query->getById()) && (Router::$id != 'new')) error();


            if(!empty($obj->connectionModified)){
                $settings = new Settings;
                $settings->loadSettings();
                Db::setConnection();

                $obj->connectionModified = false;

            }

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function setUserTimeOffset($offset = 0){
        if($offset) $_SESSION['tOffset'] = $offset*60;
    }

    private function checkRedirect(){

        $query = Redirects::set();

        $query->condition = "uri='".$_SERVER['REQUEST_URI']."' AND uriToRedirect != '' AND uri != uriToRedirect";
        $query->flat = true;

        if($row = Redirects::get($query)){
            Router::redirect($row['uriToRedirect']);
        }
    }

    private function checkURIEquivalent(){

        $query = Equivalents::set();

        $query->condition = "host='".$_SERVER['HTTP_HOST']."' AND uri= '".$_SERVER['REQUEST_URI']."'";
        $query->flat = true;

        if($query->get()){
            if($query->result['equivalent']) Router::route($query->result['equivalent']);
        }
    }
}
?>