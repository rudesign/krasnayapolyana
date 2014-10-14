<?php
class Ajaj{
    private $uri = '';

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            echo json_encode(array(
                'uri'=>$this->uri,
            ));
        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        $id = empty($_POST['id']) ?  Users::$current['id'] : $_POST['id'];

        if(!$user = Users::getById($id)) throw new Error('Пользователь удалён');

        if(empty($id)) throw new Error('Не указан пользователь');

        if(!Users::terminate($id)) throw new Error('Ошибка при удалении пользователя');

        if(Users::isEditor(Users::$current)){
            $this->uri = '/ue/';
        }else{
            if(Settings::$data->environment == 'production'){
                $theme = $user['name'].' удалил(а) свой аккаунт';
                $body = $user['name'].' удалил(а) свой аккаунт.<br />';
                $body .= $_POST['body'] ? 'Причина: '.strip_tags($_POST['body']) : 'Причина не указана.';
                sendAuthEmail(Core::$params['email'], $theme, $body);
            }

            $this->uri = '/bye/';
        }
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();
    }
}

$ajaj = new Ajaj();
?>