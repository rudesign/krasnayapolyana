<?php

class Ajaj{
    private $haveOneMore = 0;
    private $result = '';

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            // result
            echo json_encode(array(
                'result' => $this->result,
                'haveOneMore' => $this->haveOneMore,
            ));

        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        $this->result = '';

        if($_POST['clear']){
            $query = Users::set();
            $query->fields = 'sent';
            $query->values = 0;
            if(!$query->update()) throw new Error('Ошибка при подготовке рассылки');
        }

        if($row = Users::getById(0, 'sent')){
            try{
                if(filter_var($row['login'], FILTER_VALIDATE_EMAIL)) sendAuthEmail($row['login'], decodeHTMLEntities($_POST['theme']), $_POST['body']);
            }catch (Error $e){
                throw new Error('Ошибка при отправке');
            }

            $query = Users::set();
            $query->fields = 'sent';
            $query->values = 1;
            $query->id = $row['id'];

            if(!$query->update()) throw new Error('Ошибка при обновлении статуса отправки');
        }

        $query = Users::set();
        $query->fields = 'SUM(sent) AS sent, COUNT(*) AS count';
        $query->flat = true;
        $query->visibleOnly = false;
        if($query->get()){
            $this->result = 'Отправлено <b>'.$query->result['sent'].'</b> из <b>'.$query->result['count'].'</b>';
            if($query->result['sent'] != $query->result['count']) $this->haveOneMore = true;
        }
    }

    private function check(){

    }
}

$ajaj = new Ajaj();
?>