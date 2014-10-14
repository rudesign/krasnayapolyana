<?php
class Ajaj{

    private $attachments = array();

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            // result
            echo json_encode(array('attachments'=>implode(' #', $this->attachments)));

        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        if($_POST['attachments']) $this->attachments = explode(' #', $_POST['attachments']);

        if(!isset($this->attachments[$_POST['index']])) throw new Error('No attachments item');

        $item = $this->attachments[$_POST['index']];
        $item = explode('@', $item);
        $item[1] = $_POST['newTitle'];

        $this->attachments[$_POST['index']] = implode('@', $item);

        if(Core::$item){
            $query = new Query(Chapters::$current['table']['name']);
            $query->key = Chapters::$current['table']['primary'];
            $query->id = Router::$id;
            $query->fields = array('attachments', 'modifiedTime');
            $query->values = array(
                implode(' #', $this->attachments),
                time(),
            );

            if(!$query->update()) throw new Error('Cannot update the item');
        }
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if(!isset($_POST['index'])) throw new Error('No index');
    }
}

$ajaj = new Ajaj();
?>