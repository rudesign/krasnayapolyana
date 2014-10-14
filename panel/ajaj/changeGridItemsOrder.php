<?php

class Ajaj{
    private $result = array();
    private $grid;

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            // result
            echo json_encode($this->result);

        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        $query = new Query($this->grid->table);

        // repair order values if needed
        $query->fields = 'COUNT(*) AS count';
        $query->groupBy = 'ord';
        $query->having = 'count>1';
        $query->visibleOnly = false;

        if($query->get()){
            $query->flush();

            $query->fields = $this->grid->primary;
            $query->order = 'ord ASC';
            $query->visibleOnly = false;

            if(!$query->get()) throw new Error('Cannot repair items order');
        }

        // cleanup ordered ids array
        foreach($_POST['order'] as $key=>$value) {
            $_POST['order'][$key] = str_replace('id', '', $value);
            if(!is_numeric($_POST['order'][$key])) unset($_POST['order'][$key]);
        }

        if(empty($_POST['order'])) throw new Error('No order data passed');

        // min and max order values in the set
        $query->flush();
        $query->fields = 'MIN(ord) as min';
        $query->condition = $query->key.' IN ('.implode(',', $_POST['order']).')';
        $query->visibleOnly = false;
        $query->flat = true;

        if(!$query->get()) throw new Error('Cannot get items to order');

        $i = $query->result['min'];

        foreach($_POST['order'] as $id){
            $query->flush();
            $query->fields = 'ord';
            $query->values = $i;
            $query->id = $id;

            if(!$query->update()) throw new Error('Cannot update '.$id);

            $i++;
        }
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if($cname = getGridCName()){
            if(class_exists($cname)){
                $this->grid = new $cname();
            } else throw new Error('No current grid');
        }
    }
}

$ajaj = new Ajaj();
?>