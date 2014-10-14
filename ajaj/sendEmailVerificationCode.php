<?php
class Ajaj{

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            $html = '
            <h2 class="no-margin">Инструкции высланы</h2>
            <div class="d20">Инструкции высланы</div>
            Проверьте Ваш почтовый ящик';

            echo json_encode(array('html'=>$html));

        } catch(Error $e){
            $message = $e->getMessage() ? $e->getMessage() : 'Невозможно подтвердить e-mail';
            echo json_encode(array('message'=>$message));
        }
    }

    private function execute(){

        $query = Users::set();
        
        $query->condition = "login='".$_POST['email']."' AND emailVerified > 0";

        if(Users::get($query)) throw new Error('Указанный e-mail уже был зарегистрирован и подтверждён ранее');
        
        if(!Users::update(null, Users::$current['id'], array('login'), array($_POST['email']))) throw new Error('Ошибка при смене e-mail');

        if(!$static = Text::getById(6)) throw new Error('Текст письма недоступен');

        $body = $static['body'];
        $uri = 'http://'.$_SERVER['HTTP_HOST'].'/?u='.Users::$current['id'].'&verification='.Users::$current['globalId'];
        $body.= ' <a href="'.$uri.'">'.$uri.'</a>';

        if(!sendAuthEmail($_POST['email'], $static['name'], $body)) throw new Error('Ошибка при отправке письма подтверждения');
    }

    private function check(){

        Users::route($_SERVER['HTTP_REFERER']);

        $_POST = trimArray($_POST);

        if(!Users::$current['globalId']) throw new Error('Авторизуйтесь снова');

        if(empty($_POST['email'])) throw new Error('Укажите свой e-mail');

        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) throw new Error('Укажите реально существующий e-mail');

        if(Users::$current['emailVerified']) throw new Error('Ваш e-mail уже подтверждён');
    }
}

$ajaj = new Ajaj();
?>