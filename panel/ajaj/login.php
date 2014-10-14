<?php
class Ajaj{
    private $uri = '';

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            echo json_encode(array('uri'=>$this->uri));
        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        if($_POST['login'] && $_POST['password']){
            if(!Users::login($_POST['login'], $_POST['password'])) throw new Error('Неверные данные');

            if(Chapters::$current['alias'] != 'bye'){
                $this->uri = $_SERVER['HTTP_REFERER'];
            }else{
                $this->uri = $_POST['subdomain'] ? 'http://'.$_POST['subdomain'].'.'.$_SERVER['HTTP_HOST'] : '';
                $this->uri .= APP_PATH.'/';
            }
        }else if($_POST['loginToRemind']){

            $result = Users::changePassword($_POST['loginToRemind']);

            if($result !== true) throw new Error($result);
        }
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        $_POST = trimArray($_POST);

        if(!($_POST['login'] || $_POST['loginToRemind'])) throw new Error('Укажите свой e-mail');

        if(!$_POST['password'] && !$_POST['loginToRemind']){
            throw new Error('Укажите пароль');
        }

        if($_POST['loginToRemind']){
            if(!Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Неверный код безопасности');
        }
    }
}

$ajaj = new Ajaj();
?>