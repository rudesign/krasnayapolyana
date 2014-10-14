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

        foreach($_POST['ids'] as $id){
            if($id = intval($id)){

                $query->flush();
                $query->id = $id;

                switch($_POST['action']){
                    case 'turnOn':
                        $query->fields = 'visible';
                        $query->values = 1;
                        if(!$query->update()) throw new Error('An error occurred while update '.$id);
                    break;
                    case 'turnOff':
                        $query->fields = 'visible';
                        $query->values = 0;
                        if(!$query->update()) throw new Error('An error occurred while update '.$id);
                    break;
                    case 'activate':
                        $query->fields = 'pubTime';
                        $query->values = time();
                        if(!$query->update()) throw new Error('An error occurred while update '.$id);
                    break;
                    case 'delete':
                        $query->visibleOnly = false;

                        if(!$item = $query->getById()) throw new Error('No item to delete');

                        $query->flush();
                        $query->id = $id;

                        if(!$query->delete()) throw new Error('An error occurred while deleting '.$id);

                        deleteAttachments($item);

                        if(isset($item['globalId'])) Redirects::deleteBindWithGid($item['globalId']);
                    break;
                    case 'clearRedirects':
                        if(!$item = Misc::getById($query, $id, Chapters::$current['table']['primary'], false)) throw new Error('No item '.$id);

                        if(isset($item['globalId'])) Redirects::deleteBindWithGid($item['globalId']);
                    break;
                }
            }
        }


    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if(empty($_POST['action'])) throw new Error('No action passed');

        if(empty($_POST['ids'])) throw new Error('No ids passed');

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