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
        $body[] = '<b>Тема:</b> '.$_POST['section'];

        $body = implode('<br />', $body);

        $data = array(
            'name'=>$_POST['name'],
            'body'=>$body,
        );

        //if(!ServiceQueries::create($data)) throw new Error('Ошибка при записи заявки');

        $theme = $_POST['name'].' отправил(а) заявку с сайта '.Core::$params['name'];

        //if(!sendAuthEmail(Core::$params['email'], $theme, $body)) throw new Error('Ошибка при отправке сообщения');

        $this->data['uri'] = '/';
    }

    private function check(){
        $_POST = trimArray($_POST);

        switch($_POST['section']){
            case 1:
                if(empty($_POST['autoType'])) throw new Error('Select car type');
                if(empty($_POST['email'])) throw new Error('Type in your email');
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) throw new Error('Type a real email please');
                if(empty($_POST['autoCheckIn'])) throw new Error('Select check in date');
                if(empty($_POST['autoCheckOut'])) throw new Error('Select check out date');

                if(!empty($_POST['extra1'])){
                    if(empty($_POST['resort'])) throw new Error('Select a resort');
                }
                if(!empty($_POST['extra2'])){
                    if(empty($_POST['aviaFrom'])) throw new Error('Type in departure city');
                    if(empty($_POST['aviaTo'])) throw new Error('Type in destination city');
                    if(empty($_POST['aviaCheckIn'])) throw new Error('Select departure date');
                    if(!empty($_POST['wayback']) && empty($_POST['aviaCheckOut'])) throw new Error('Select return flight date');
                }
            break;
            case 2:
                if(empty($_POST['transferFrom'])) throw new Error('Select a resort');
                if(empty($_POST['transferTo'])) throw new Error('Type in a destination');
                if(empty($_POST['email'])) throw new Error('Type in your email');
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) throw new Error('Type a real email please');

                if(!empty($_POST['extra1'])){
                    if(empty($_POST['resort'])) throw new Error('Select a resort');
                }
                if(!empty($_POST['extra2'])){
                    if(empty($_POST['aviaFrom'])) throw new Error('Type in departure city');
                    if(empty($_POST['aviaTo'])) throw new Error('Type in destination city');
                    if(empty($_POST['aviaCheckIn'])) throw new Error('Select departure date');
                    if(!empty($_POST['wayback']) && empty($_POST['aviaCheckOut'])) throw new Error('Select return flight date');
                }
            break;
        }

        //if(!Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Повторите символы');
        //if(!Users::$current && !Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Неверный код безопасности');
    }
}

$ajaj = new Ajaj();
?>