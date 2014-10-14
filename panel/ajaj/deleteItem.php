<?php

class Ajaj{

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            // result
            echo json_encode(array(''));

        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        $query = new Query($this->grid->table);

        if(!$query->key) throw new Error('No grid primary key defined');

        $query->id = $_POST['id'];
        $query->visibleOnly = false;

        if(!$item = $query->getById()) throw new Error('No item');

        $item = reset($item);

        $query->flush();

        $query->id = $query->id = $item[$this->grid->primary];
        if(!$query->delete()) throw new Error('An error occurred while delete');

        deleteAttachments($item);

        if(!empty($this->grid) && method_exists($this->grid, 'actionAfterDelete')){
            $this->grid->actionAfterDelete($_POST['id']);
        }

        if(isset($item['globalId'])) Redirects::deleteBindWithGid($item['globalId']);
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

        if(!$this->grid->primary)  throw new Error('No primary key defined');

        if(!$_POST['id'] = intval($_POST['id'])) throw new Error('No item id passed');
    }
}

$ajaj = new Ajaj();
?>