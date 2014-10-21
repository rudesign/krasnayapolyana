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

    private function execute()
    {
        $data = array();

        if($_POST['hotel']){
            if($hotel = Hotels::getById((int) $_POST['hotel'])){
                $data[] = array('name'=>'Отель', 'value'=>$hotel['name']);
                if($hotel['resort']){
                    $resort = Resorts::getById($hotel['resort']);
                    $data[] = array('name'=>'Курорт', 'value'=>$resort['name']);
                }
            }
        }

        if(!empty($_POST['peopleTotal'])) $data[] = array('name'=>'Человек', 'value'=>$_POST['peopleTotal']);
        if(!empty($_POST['rooms'])) $data[] = array('name'=>'Номеров', 'value'=>$_POST['rooms']);
        if(!empty($_POST['kids'])) $data[] = array('name'=>'Детей', 'value'=>$_POST['kids']);
        if(!empty($_POST['kidsAge'])) $data[] = array('name'=>'Возраст детей', 'value'=>$_POST['kidsAge']);
        if(!empty($_POST['dateFrom'])) $data[] = array('name'=>'Дата заезда', 'value'=>$_POST['dateFrom']);
        if(!empty($_POST['dateTo'])) $data[] = array('name'=>'Дата отъезда', 'value'=>$_POST['dateTo']);
        if(!empty($_POST['roomsCategory'])) $data[] = array('name'=>'Категория номера', 'value'=>$_POST['roomsCategory']);
        if(!empty($_POST['body'])) $data[] = array('name'=>'Пожелания', 'value'=>$_POST['body']);
        if(!empty($_POST['surname'])) $data[] = array('name'=>'Фамилия', 'value'=>$_POST['surname']);
        if(!empty($_POST['firstName'])) $data[] = array('name'=>'Имя', 'value'=>$_POST['firstName']);
        if(!empty($_POST['secondName'])) $data[] = array('name'=>'Отчество', 'value'=>$_POST['secondName']);
        if(!empty($_POST['email'])) $data[] = array('name'=>'E-mail', 'value'=>$_POST['email']);
        if(!empty($_POST['phone'])) $data[] = array('name'=>'Телефон', 'value'=>$_POST['phone']);
        if(!empty($_POST['cName'])) $data[] = array('name'=>'Название компании', 'value'=>$_POST['cName']);
        if(!empty($_POST['fax'])) $data[] = array('name'=>'Факс', 'value'=>$_POST['fax']);

        // store
        if(Feedback::submitRemote($data)){
            $this->data['uri'] = '/';
        }else{
            $this->data['uri'] = '/';
        }

        /*
        $data = array(
            'name'=>$_POST['name'],
            'body'=>$body,
        );

        if(!ServiceQueries::create($data)) throw new Error('Ошибка при записи заявки');

        $theme = $_POST['name'].' отправил(а) заявку с сайта '.Core::$params['name'];

        if(!sendAuthEmail(Core::$params['email'], $theme, $body)) throw new Error('Ошибка при отправке сообщения');
        */
    }

    private function check(){
        $_POST = trimArray($_POST);

        if(empty($_POST['peopleTotal'])) throw new Error('Укажите количество человек');
        if(empty($_POST['dateFrom'])) throw new Error('Укажите дату начала пребывания');
        if(empty($_POST['surname'])) throw new Error('Укажите фамилию');
        if(empty($_POST['firstName'])) throw new Error('Укажите имя');
        if(empty($_POST['email'])) throw new Error('Укажите e-mail');
        if(empty($_POST['phone'])) throw new Error('Укажите номер телефона');

        //if(!Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Повторите символы');
        //if(!Users::$current && !Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Неверный код безопасности');
    }
}

$ajaj = new Ajaj();
?>