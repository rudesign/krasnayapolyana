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
                $this->uri = '/';
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

        try{
            if(!empty($_POST['login'])) {
                if(!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) throw new Error;
            }else if(!empty($_POST['loginToRemind'])){
                if(!filter_var($_POST['loginToRemind'], FILTER_VALIDATE_EMAIL)) throw new Error;
            }
        }catch (Error $e){
            throw new Error('Укажите реально существующий e-mail');
        }

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