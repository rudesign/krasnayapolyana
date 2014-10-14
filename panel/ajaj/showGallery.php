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

        $gallery = Core::$item ? Core::$item['gallery'] : $_POST['gallery'];
        $dir = Core::$item ? 'images' : 'tmp';

        if($gallery){

            if($gallery = getExplodedGroup($gallery)){

                $this->result['gallery'] = '<ul class="gallery">';

                $i = 0;
                foreach($gallery as $item){

                    // how much in the row
                    $maxToRight = 5;
                    if(($i%$maxToRight)*$maxToRight > $maxToRight){
                        $alignRight = ' right';
                    }else if($alignRight){
                        unset($alignRight);
                    }

                    $this->result['gallery'] .= '
                    <li id="i'.$i.'">
                        <div class="manage small">
                            <div class="title-field shadow'.$alignRight.'">
                                <dl><a onClick="hideTitleFields();" href="javascript:void(0);">Отменить</a></dl>
                                <dl><textarea class="fw small">'.$item[1].'</textarea></dl>
                                <dl><button onClick="return savePictureTitle('.$i.');" class="buttons green-buttons">Сохранить</button></dl>
                            </div>
                            <div class="links black">
                                <dl><a onClick="deletePicture('.$i.');" href="javascript:void(0);">Удалить</a></dl>
                                <dl><a onClick="showPictureTitleField('.$i.');" href="javascript:void(0);">Title</a></dl>
                                <dl class="clear"></dl>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="image">';
                            $this->result['gallery'] .= '
                            <a href="/'.$dir.'/big/'.$item[0].'" target="_blank"><img title="'.$item[1].'" alt="'.$item[1].'" src = "/'.$dir.'/thumbs/'.$item[0].'" /></a>
                        </div>
                    </li>
                    ';
                    $i++;
                }

                $this->result['gallery'] .= '<div class="clear"></div></ul>';
            }
        }

        return $result;
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
    }
}

$ajaj = new Ajaj();
?>