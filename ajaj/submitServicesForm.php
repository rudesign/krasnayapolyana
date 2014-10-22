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
            if($this->data['submit']){
                echo json_encode(array('submit'=>1));
            }else{
                echo json_encode(array('message'=>$e->getMessage()));
            }

        }
    }

    private function execute(){


//        $body = array();
//        $body[] = '<b>Тема:</b> '.$_POST['section'];
//        $body = implode('<br />', $body);
//        $data = array(
//            'name'=>$_POST['name'],
//            'body'=>$body,
//        );
//        if(!ServiceQueries::create($data)) throw new Error('Ошибка при записи заявки');
//        $theme = $_POST['name'].' отправил(а) заявку с сайта '.Core::$params['name'];
//        if(!sendAuthEmail(Core::$params['email'], $theme, $body)) throw new Error('Ошибка при отправке сообщения');

        $this->data['uri'] = '/';
    }

    private function check(){
        $_POST = trimArray($_POST);

        switch($_POST['section']){
            case 0:
                if(empty($_POST['extra1']) && empty($_POST['extra2'])){
                    $this->data['submit'] = 1;
                    throw new Error;
                }
                if(empty($_POST['value_2'])) throw new Error('Выберите авто');
            break;
            case 1:
                if(empty($_POST['value_2'])) throw new Error('Выберите авто');
                if(empty($_POST['email'])) throw new Error('Укажите e-mail');
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) throw new Error('Укажите реальный e-mail');
                if(empty($_POST['autoCheckIn'])) throw new Error('Укажите дату подачи');
                if(empty($_POST['autoCheckOut'])) throw new Error('Укажите дату возврата');

                if(!empty($_POST['extra1'])){
                    if(empty($_POST['resort'])) throw new Error('Выберите курорт');
                }
                if(!empty($_POST['extra2'])){
                    if(empty($_POST['aviaFrom'])) throw new Error('Укажите пункт вылета');
                    if(empty($_POST['aviaTo'])) throw new Error('Укажите пункт назначения');
                    if(empty($_POST['aviaCheckIn'])) throw new Error('Укажите дату вылета');
                    if(!empty($_POST['wayback']) && empty($_POST['aviaCheckOut'])) throw new Error('Укажите дату возврата');
                }
            break;
            case 2:
                if(empty($_POST['transferFrom'])) throw new Error('Укажите место отправления');
                if(empty($_POST['transferTo'])) throw new Error('Укажите место назначения');
                if(empty($_POST['email'])) throw new Error('Укажите e-mail');
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) throw new Error('Укажите реальный e-mail');

                if(!empty($_POST['extra1'])){
                    if(empty($_POST['resort'])) throw new Error('Выберите курорт');
                }
                if(!empty($_POST['extra2'])){
                    if(empty($_POST['aviaFrom'])) throw new Error('Укажите пункт вылета');
                    if(empty($_POST['aviaTo'])) throw new Error('Укажите пункт назначения');
                    if(empty($_POST['aviaCheckIn'])) throw new Error('Укажите дату вылета');
                    if(!empty($_POST['wayback']) && empty($_POST['aviaCheckOut'])) throw new Error('Укажите дату возврата');
                }
            break;
        }

        //if(!Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Повторите символы');
        //if(!Users::$current && !Captcha::check($_POST['code'], $_POST['entered'])) throw new Error('Неверный код безопасности');
    }
}

$ajaj = new Ajaj();
?>