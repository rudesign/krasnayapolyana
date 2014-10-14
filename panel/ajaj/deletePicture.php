<?php
class Ajaj{

    private $gallery = array();
    private $grid = array();

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            // result
            echo json_encode(array('gallery'=>implode(' #', $this->gallery)));

        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        if($_POST['gallery']) $this->gallery = explode(' #', $_POST['gallery']);

        if(!isset($this->gallery[$_POST['index']])) throw new Error('No gallery item');

        $item = $this->gallery[$_POST['index']];
        $item = explode('@', $item);
        $fname = reset($item);

        foreach(Settings::$data->dirsToStoreImages as $dirParams){
            $dirParams = explode(':', $dirParams);

            $dir = APP_ROOT.(Core::$item ? '/images' : '/tmp').'/'.$dirParams[0];

            $fpath = $dir.'/'.$fname;

            if(file_exists($fpath)){
                if(!is_file($fpath)) throw new Error($dirParams[0].' is a directory');

                @unlink($fpath);
            }
        }

        unset($this->gallery[$_POST['index']]);

        if(Core::$item){
            $query = new Query($this->grid->table);
            $query->id = Core::$item[$this->grid->primary];
            $query->fields = array('gallery', 'modifiedTime');
            $query->values = array(
                implode(' #', $this->gallery),
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

        if(!isset($_POST['index'])) throw new Error('No index');
    }
}

$ajaj = new Ajaj();
?>