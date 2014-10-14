<?php
class Ajaj{
    private $data = array();

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            echo json_encode($this->data);
        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        $body = array();
        $body[] = '<b>Тема:</b> '.$_POST['theme'];
        $body[] = '<b>Имя:</b> '.$_POST['name'];
        $body[] = '<b>Инструмент:</b> '.$_POST['tool'];
        $body[] = '<b>Номер телефона:</b> '.$_POST['phone'];
        $body[] = '<b>E-mail:</b> '.$_POST['email'];

        $body = implode('<br />', $body);

        $data = array(
            'name'=>$_POST['name'],
            'body'=>$body,
        );

        if(!Feedback::create($data)) throw new Error('Ошибка при записи сообщения');

        $theme = $_POST['name'].' отправил(а) сообщение с сайта '.Core::$params['name'];

        if(!sendAuthEmail(Core::$params['email'], $theme, $body)) throw new Error('Ошибка при отправке сообщения');

        $this->data['okMessage'] = 'Заявка отправлена';
    }

    private function check(){
        $_POST = trimArray($_POST);

        if(!$_POST['name']) throw new Error('Как Вас зовут?');
        if(!$_POST['tool']) throw new Error('Укажите инструмент');
        if(!$_POST['phone']) throw new Error('А номер телефона?');
        if(!$_POST['email']) throw new Error('Ваш email, пожалуйста');
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) throw new Error('Email, пожалуйста');
        if(!Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Повторите символы');

        //if(!Users::$current && !Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Неверный код безопасности');
    }
}

$ajaj = new Ajaj();
?>