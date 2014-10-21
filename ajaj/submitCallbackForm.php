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

        $data = array();

        $data[] = array('name'=>'Тема', 'value'=>'Просьба позвонить');
        if(!empty($_POST['peopleTotal'])) $data[] = array('name'=>'Имя', 'value'=>$_POST['name']);
        if(!empty($_POST['peopleTotal'])) $data[] = array('name'=>'Номер телефона', 'value'=>$_POST['phone']);

        // store
        if(Feedback::submitRemote($data)){
            $this->data['okMessage'] = 'Заявка отправлена';
        }else{
            throw new Error('Ошибка при отправке заявки');
        }

//        $body = array();
//        $body[] = '<b>Тема:</b> Позвоните мне';
//        $body[] = '<b>Имя:</b> '.$_POST['name'];
//        $body[] = '<b>Номер телефона:</b> '.$_POST['phone'];
//
//        $body = implode('<br />', $body);
//
//        $data = array(
//            'name'=>$_POST['name'],
//            'body'=>$body,
//        );
//
//        if(!Feedback::create($data)) throw new Error('Ошибка при записи сообщения');
//
//        $theme = $_POST['name'].' отправил(а) сообщение с сайта '.Core::$params['name'];
//
//        if(!sendAuthEmail(Core::$params['email'], $theme, $body)) throw new Error('Ошибка при отправке сообщения');
//
//        $this->data['okMessage'] = 'Заявка отправлена';
    }

    private function check(){
        $_POST = trimArray($_POST);

        if(!$_POST['name']) throw new Error('Укажите своё имя');
        if(!$_POST['phone']) throw new Error('Укажите номер телефона');
        if(!Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Повторите символы');
        if(!Users::$current && !Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Неверный код безопасности');
    }
}

$ajaj = new Ajaj();
?>