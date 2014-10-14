<?php
class Ajaj{
    private $data = array();

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            // result
            echo json_encode($this->data);

        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        $_POST['_selectedTeachers'] = is_array($_POST['_selectedTeachers']) ? array_filter($_POST['_selectedTeachers']) : array();

        if(!empty(Core::$item)){
            CourseTeacherBinds::saveBinds(Core::$item['id'], $_POST['_selectedTeachers']);
        }

        $this->data['teachers'] = implode(' #', $_POST['_selectedTeachers']);
    }


    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if(empty(Router::$id)) throw new Error('No item id');

        if($cname = getGridCName()){
            if(class_exists($cname)){
                $this->grid = new $cname();

                $this->grid->modifyConnection();

                $this->grid->check();
            } else throw new Error('No current grid');
        }
    }
}

$ajaj = new Ajaj();
?>