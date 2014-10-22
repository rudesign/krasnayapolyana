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
        $data = array();

        if(!empty($_POST['value_2'])){
            $auto = Autos::getById((int) $_POST['value_2'], 'remoteId');
        }

        if(!empty($_POST['transferFrom'])){
            $transfer = Resorts::getById((int) $_POST['transferFrom']);
            $data[] = array('name'=>'Трансфер из', 'value'=>$transfer['name']);
        }

        if(!empty($_POST['transferFromBack'])){
            $transfer = Resorts::getById((int) $_POST['transferFromBack']);
            $data[] = array('name'=>'Обратный трансфер из', 'value'=>$transfer['name']);
        }

        if(!empty($_POST['resort'])){
            $resort = Resorts::getById((int) $_POST['resort']);
        }

        if(!empty($_POST['value_2']) && !empty($auto)) $data[] = array('name'=>'Автомобиль', 'value'=>$auto['name']);

        if(!empty($_POST['value3'])) {
            $date = $_POST['value3'].' в '.$_POST['hour3'].':'.$_POST['minute3'];
            $data[] = array('name'=>'Дата подачи', 'value'=>$date);
        }
        if(!empty($_POST['value4'])) {
            $date = $_POST['value4'].' в '.$_POST['hour4'].':'.$_POST['minute4'];
            $data[] = array('name'=>'Дата возврата', 'value'=>$date);
        }

        if(!empty($_POST['transferTo'])) $data[] = array('name'=>'Трансфер в', 'value'=>$_POST['transferTo']);
        if(!empty($_POST['transferToBack'])) $data[] = array('name'=>'Обратный трансфер в', 'value'=>$_POST['transferToBack']);
        if(!empty($_POST['extra1'])){
            $data[] = array('name'=>'Выбрана опция', 'value'=>'+ отель');
            if(!empty($_POST['resort']) && !empty($resort)) $data[] = array('name'=>'Курорт', 'value'=>$resort['name']);
            if(!empty($_POST['hotel'])) $data[] = array('name'=>'Отель', 'value'=>$_POST['hotel']);
        }
        if(!empty($_POST['extra2'])){
            $data[] = array('name'=>'Выбрана опция', 'value'=>'+ авиа');
            if(!empty($_POST['aviaFrom'])) $data[] = array('name'=>'Перелёт из', 'value'=>$_POST['aviaFrom']);
            if(!empty($_POST['aviaCheckIn'])) $data[] = array('name'=>'Дата перелёта', 'value'=>$_POST['aviaCheckIn']);
            if(!empty($_POST['aviaTo'])) $data[] = array('name'=>'Перелёт в', '$_POST'=>$_POST['aviaTo']);
            if(!empty($_POST['aviaCheckOut'])) $data[] = array('name'=>'Дата перелёта обратно', 'value'=>$_POST['aviaCheckOut']);
        }

        if(!empty($_POST['email'])) $data[] = array('name'=>'E-mail', 'value'=>$_POST['email']);

        // store
        if(Feedback::submitRemote($data)){
            $this->data['uri'] = '/';
        }else{
            $this->data['uri'] = '/';
        }


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
                if(empty($_POST['value3'])) throw new Error('Укажите дату подачи');
                if(empty($_POST['value4'])) throw new Error('Укажите дату возврата');

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