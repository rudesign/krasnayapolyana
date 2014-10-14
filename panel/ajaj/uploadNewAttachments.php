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
        $query = new Query($this->grid->table);

        if(!$query->key) throw new Error('No grid primary key defined');

        if($_POST['attachments']) $this->attachments = explode(' #', $_POST['attachments']);

        $i = 0;
        foreach($_FILES['_newAttachments']['tmp_name'] as $src){
            if(!file_exists($src)) throw new Error('Cannot locate '.$_FILES['_newAttachments']['name'][$i]);

            $outputDir = APP_ROOT;
            $dirToCheck = $outputDir.'/images';
            $outputDir .= Core::$item ? '/images' : '/tmp';

            $fname = $_FILES['_newAttachments']['name'][$i];
            $ext = end(explode('.', $fname));
            $fname = getUniqueFname($dirToCheck, $fname, $ext);
            $fpath = $outputDir.'/'.$fname;

            if(file_exists($fpath)) {
                $fname = getUnique().'.'.$ext;
                $fpath = $outputDir.'/'.$fname;
            }

            if(!copy($src, $fpath)) throw new Error('Cannot copy '.$_FILES['_newAttachments']['name'][$i]);

            $this->attachments[] = $fname;

            $i++;
        }

        if(Core::$item){
            $query = new Query($this->grid->table);
            $query->id = Core::$item[$this->grid->primary];
            $query->fields = array('attachments', 'modifiedTime');
            $query->values = array(
                implode(' #', $this->attachments),
                time(),
            );

            if(!$query->update()) throw new Error('Cannot update the item');
        }
    }

    private function check(){
        if(empty($_POST)) throw new Error('No data passed');

        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if($cname = getGridCName()){
            if(class_exists($cname)){
                $this->grid = new $cname();

                $this->grid->modifyConnection();

                $this->grid->check();
            } else throw new Error('No current grid');
        }

        if(!$this->grid->primary)  throw new Error('No primary key defined');
    }
}

$ajaj = new Ajaj();
?>