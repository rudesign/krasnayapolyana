<?php

class Settings extends Router{

    // settings file location
    public static $settingsLocation = '';
    // obj application settings
    public static $data = null;

    public function __construct(){
        parent::__construct();

        $this->loadSettings();
    }

    public function loadSettings(){
        self::$settingsLocation = APP_ROOT.'/settings/settings.ini';
        $this->getSettings();
        $this->checkHost();
    }

    public function getSettings(){

        if(!file_exists(self::$settingsLocation)) throw new Error('No settings file');

        self::$data = parse_ini_file(self::$settingsLocation, false);

        if(empty(self::$data)) throw new Error('No settings data');

        self::$data = toObject(self::$data);
    }

    protected function checkHost(){
        if(!empty($_SERVER['HTTP_HOST'])){
            if(array_search($_SERVER['HTTP_HOST'], self::$data->hosts) === false)  throw new Error('Wrong host');
        }
    }
}
?>