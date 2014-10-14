<?php

class Ajaj{
    private $result = array();

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
        $result = array();

        $attachments = Core::$item ? Core::$item['attachments'] : $_POST['attachments'];
        $dir = Core::$item ? 'images' : 'tmp';

        if($attachments){

            if($attachments = getExplodedGroup($attachments)){

                $this->result['attachments'] = '<div class="attachments">';

                $i=0;
                foreach($attachments as $item){
                    $uri = '/'.$dir.'/'.$item[0];

                    $this->result['attachments'] .= '
                    <div>
                        <div class="title-field'.$alignRight.'">
                            <dl><a onClick="hideTitleFields();" href="javascript:void(0);">Отменить</a></dl>
                            <dl><textarea class="fw">'.$item[1].'</textarea></dl>
                            <dl><button onClick="return saveAttachmentTitle('.$i.');" class="buttons green-buttons">Сохранить</button></dl>
                        </div>
                        <div class="links">
                            <dl><input value="http://'.$_SERVER['HTTP_HOST'].$uri.'" type="text" style="width:30em;" onclick="this.select();" /></dl>
                            <dl class="black"><a onClick="deleteAttachment('.$i.');" href="javascript:void(0);">Удалить</a></dl>
                            <dl class="black"><a onClick="showAttachmentTitleField('.$i.');" href="javascript:void(0);">Title</a></dl>
                            <dl><a href="'.$uri.'" target="_blank">'.$item[0].'</a></dl>
                        </div>
                    </div>
                    ';

                    $i++;
                }

                $this->result['attachments'] .= '</div>';
            }
        }

        return $result;
    }

    private function check(){
        if(empty($_POST)) throw new Error('No data passed');

        Users::route($_SERVER['HTTP_REFERER']);

        if($cname = getGridCName()){
            if(class_exists($cname)){
                $this->grid = new $cname();

                $this->grid->modifyConnection();

                $this->grid->check();
            } else throw new Error('No current grid');
        }

        if(!Users::$accessGranted) die();
    }
}

$ajaj = new Ajaj();
?>