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
        $data = array(
            'name' => capitalise(changeCase($_POST['name']), false),
            'login' => $_POST['login'],
            'password' => md5($_POST['password']),
            'accessibleChapters' => Chapters::getOpenedToAuthorised(),
        );
        if(!$id = Users::create($data)) throw new Error('Ошибка при регистрации');

        if(Users::login($_POST['login'], $_POST['password'])){

        }

        $this->uri = '/my/';
    }

    private function check(){
        $_POST = trimArray($_POST);

        if(!$_POST['name']) throw new Error('Укажите свое имя');
        if(!checkLettersOnly($_POST['name'])) throw new Error('Имя должно содержать только буквы');
        if(mb_strlen(trim($_POST['name']), 'utf-8')<3) throw new Error('Имя слишком короткое');
        if(mb_strlen(trim($_POST['name']), 'utf-8')>25) throw new Error('Имя слишком длинное');
        if(!$_POST['login']) throw new Error('Укажите свой e-mail');
        if(!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) throw new Error('Укажите реально существующий email');
        if(Users::loginIsOccupied($_POST['login'])) throw new Error('Указанный e-mail уже зарегистрирован');
        if(!$_POST['password']) throw new Error('Укажите пароль');
        if(mb_strlen($_POST['password'], 'utf-8')<4) throw new Error('Длина пароля должна быть от 4 символов');
        if($_POST['password'] != $_POST['passwordReply']) throw new Error('Пароль и его подтверждение не совпадают');
        if(!Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Неверный код безопасности');
    }
}

$ajaj = new Ajaj();
?>