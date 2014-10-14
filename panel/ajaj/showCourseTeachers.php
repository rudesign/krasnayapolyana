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

        $this->data['html'] = '<div class="item-categories">';

            $this->data['html'] .= '<dl>Преподаватели</dl>';

            if(!empty($_POST['teachers'])){
                $teachers = explode(' #', $_POST['teachers']);

                foreach($teachers as $teacher){
                    $this->data['html'] .= '
                    <ul>
                        <li>'.getSelector('teachers', 'id', $teacher, '_selectedTeachers[]', false, '', 'saveCourseTeachers();').'</li>
                        <li><a onclick="$(this).parent().parent().remove(); saveCourseTeachers(); return false;" href="javascript:void(0);">x</a></li>
                    </ul>';
                }
            }

            $this->data['html'] .= '<ul><li>'.getSelector('teachers', 'id', 0, '_selectedTeachers[]', false, '', 'saveCourseTeachers();').'</li></ul>';

            $this->data['html'] .= '
            <div class="clear"></div>
        </div>';
    }


    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if(empty(Router::$id)) throw new Error('No item id');
    }
}

$ajaj = new Ajaj();
?>