<?php
class Ajaj{

    private $gallery = array();

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
        $item[1] = $_POST['newTitle'];

        $this->gallery[$_POST['index']] = implode('@', $item);

        if(Core::$item){
            $query = new Query(Chapters::$current['table']['name']);
            $query->key = Chapters::$current['table']['primary'];
            $query->id = Router::$id;
            $query->fields = array('gallery', 'modifiedTime');
            $query->values = array(
                implode(' #', $this->gallery),
                time(),
            );

            if(!$query->update()) throw new Error('Cannot update the item');
        }
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if($cname = getGridCName()){
            if(class_exists($cname)){
                $this->grid = new $cname();

                $this->grid->modifyConnection();

                $this->grid->check();
            } else throw new Error('No current grid');
        }

        if(!isset($_POST['index'])) throw new Error('No index');
    }
}

$ajaj = new Ajaj();
?>