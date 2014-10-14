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
        if($_POST['gallery']) $gallery = explode(' #', $_POST['gallery']);

        // cleanup the array & rearrange the gallery
        foreach($_POST['order'] as $key=>$value) {
            $order[$key] = str_replace('i', '', $value);
            if(!is_numeric($order[$key])) unset($order[$key]);
        }

        foreach($order as $index) $this->gallery[] = $gallery[$index];

        if(Core::$item){
            $db = new Query(Chapters::$current['table']['name']);
            $db->idName = Chapters::$current['table']['primary'];
            $db->id = Router::$id;
            $db->fields = 'gallery';
            $db->values = implode(' #', $this->gallery);

            if(!$db->update()) throw new Error('Cannot update the item');
        }
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if(empty(Router::$id)) throw new Error('No item id');

        if($cname = getGridCName()){
            if(class_exists($cname)){
                $this->grid = new $cname();
            } else throw new Error('No current grid');
        }
    }
}

$ajaj = new Ajaj();
?>