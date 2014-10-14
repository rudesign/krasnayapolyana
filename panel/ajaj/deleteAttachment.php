<?php
class Ajaj{

    private $attachments = array();
    private $grid = array();

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
        $fname = reset($item);

        $dir = APP_ROOT.(Core::$item ? '/images' : '/tmp');

        $fpath = $dir.'/'.$fname;

        //if(!file_exists($fpath)) throw new Error('No file to delete '.$fname);

        //if(!is_file($fpath)) throw new Error($fname.' is a directory');

        //if(!@unlink($fpath)) throw new Error('Cannot delete '.$fname);
        @unlink($fpath);

        unset($this->attachments[$_POST['index']]);

        if(Core::$item){
            $db = new Query($this->grid->table);
            $db->id = Core::$item[$this->grid->primary];
            $db->fields = array('attachments', 'modifiedTime');
            $db->values = array(
                implode(' #', $this->attachments),
                time(),
            );

            if(!$db->update()) throw new Error('Cannot update the item');
        }
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if($cname = getGridCName()){
            if(class_exists($cname)){
                $this->grid = new $cname();

                $this->grid->check();
            } else throw new Error('No current grid');
        }

        if(!$this->grid->primary)  throw new Error('No primary key defined');

        if(!isset($_POST['index'])) throw new Error('No index');
    }
}

$ajaj = new Ajaj();
?>