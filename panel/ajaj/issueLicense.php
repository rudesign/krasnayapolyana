<?php

class Ajaj{
    private $data = array();
    private $adminTemplate = array();
    private $conn;
    private $settingsPath = '/settings';
    private $dumpFile = '/dump.sql';
    private $subdomain = '';

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            // result
            echo json_encode($this->data);

        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        $this->getAdminTemplate();

        $this->dumpFile = APP_ROOT.$this->settingsPath.$this->dumpFile;

        if(!file_exists($this->dumpFile)) throw new Error('Отсутствует шаблон БД');

        $this->extractSubdomain();

        $this->saveIniFile();

        $this->connect();

        $this->cloneDBTemplate();

        $this->addAdmin();

        $this->createCity();

        $this->data['okMessage'] = 'Успешно';

        $this->data['uri'] = '/panel/';

        $this->conn->close();
    }

    private function getAdminTemplate(){
        if($_POST['login'] && $_POST['pwd'] && !$this->adminTemplate = Users::getById('template', 'login')) throw new Error('Отсутствует шаблон администратора');
    }

    private function connect(){
        $this->conn = @new mysqli("localhost", $_POST['dbUser'], $_POST['userPwd'], $_POST['dbName']);

        if (mysqli_connect_errno()) throw new Error('Не удалось подключиться к базе данных');
    }

    private function cloneDBTemplate(){
        if(!$query = file_get_contents($this->dumpFile)) throw new Error('Не удалось прочитать шаблон БД');

        if (!$this->conn->multi_query($query)) throw new Error('Не удалось клонировать шаблон БД');
    }

    private function addAdmin(){

        Settings::$data->dbName = $_POST['dbName'];
        Settings::$data->dbUser = $_POST['dbUser'];
        Settings::$data->dbPassword = $_POST['userPwd'];

        Db::setConnection();

        $data = array(
            'globalId' => md5(time()),
            'name' => ($_POST['adminName'] ? $_POST['adminName'] : $_POST['login']),
            'login' => $_POST['login'],
            'password' => md5($_POST['pwd']),
            'accessibleChapters' => $this->adminTemplate['accessibleChapters'],
            'emailVerified' => 1,
        );

        if(!$id = Users::create($data)) throw new Error('Не удалось создать администратора');
    }

    private function extractSubdomain(){
        $_POST['domain'] = str_replace(array('http://', 'www.'), array(), $_POST['domain']);
        if(!$this->subdomain = reset(explode('.', $_POST['domain']))) throw new Error('Не удалось получить имя домена');

        if(preg_match('/'.$this->subdomain.'/i', $_SERVER['HTTP_HOST'])) throw new Error('Неверный домен');
    }

    private function saveIniFile(){
        if(!$this->subdomain) throw new Error('Не получено имя домена');

        $contents = array(
            'dbHost = localhost',
            'dbName = '.$_POST['dbName'],
            'dbUser = '.$_POST['dbUser'],
            'dbPassword = '.$_POST['userPwd'],
            'dbEncoding = utf8',
        );
        $contents = implode("\n", $contents);

        $fPath = APP_ROOT.$this->settingsPath.'/'.$this->subdomain.'.ini';

        if(file_exists($fPath)) throw new Error('Информация по указанному домену была сохранена ранее');

        if(!@file_put_contents($fPath, $contents)) throw new Error('Не удалось сохранить файл настроек');
    }

    private function createCity(){

        if(!$this->subdomain) throw new Error('Не получено имя домена');

        // switch to base DB
        $settings = new Settings;
        $settings->loadBaseSettings();
        Db::setConnection();

        $data = array(
            'visible'=>1,
            'name'=>$_POST['name'],
            'subdomain'=>$this->subdomain,
        );

        if(!Cities::create($data)) throw new Error('Не удалось сохранить информацио о городе');
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if(empty($_POST['name'])) throw new Error('Укажите город');
        if(empty($_POST['domain'])) throw new Error('Укажите домен');
        if(empty($_POST['dbName'])) throw new Error('Укажите название базы данных');
        if(empty($_POST['dbUser'])) throw new Error('Укажите пользователя базы данных');
        if(empty($_POST['userPwd'])) throw new Error('Укажите пароль пользователя базы данных');

        if(Users::getById($_POST['login'], 'login', false)) throw new Error('Пользователь с указанным login-ом уже существует');
    }
}

$ajaj = new Ajaj();
?>