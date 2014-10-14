<?php
class Ajaj{

    private $gallery = array();
    private $grid = array();
    private $uploadedImageParams = array();

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
        $query = new Query($this->grid->table);

        if(!$query->key) throw new Error('No grid primary key defined');

        if($_POST['gallery']) $this->gallery = explode(' #', $_POST['gallery']);

        $i = 0;
        foreach($_FILES['_newPictures']['tmp_name'] as $src){

            list($this->uploadedImageParams['width'], $this->uploadedImageParams['height']) = getimagesize($src);

            if(!$name = getUnique().'.jpg') throw new Error('No image name');

            foreach(Settings::$data->dirsToStoreImages as $dirParams){

                $dirParams = explode(':', $dirParams);

                $dir = reset($dirParams);

                $dirToStore = Core::$item ? '/images' : '/tmp';

                $thumb = new Thumb();

                $thumb->config['crop'] = ($dirParams[3] ? true : false);

                $limit = 160;
                if(!$thumb->config['crop'] && ($this->uploadedImageParams['width'] < $limit) && ($this->uploadedImageParams['height'] < $limit)){
                    $dirParams[1] = $this->uploadedImageParams['width'];
                    $dirParams[2] = $this->uploadedImageParams['height'];
                }

                if($dirParams[1] > $this->uploadedImageParams['width']) $dirParams[1] = $this->uploadedImageParams['width'];
                if($dirParams[2] > $this->uploadedImageParams['height']) $dirParams[2] = $this->uploadedImageParams['height'];

                if(!$thumb->create($src, $dirToStore.'/'.$dir.'/'.$name, $dirParams[1], $dirParams[2], ($dirParams[4] ? true : false))) throw new Error('Ошибка при загрузке');
            }

            $this->gallery[] = $name;

            $i++;
        }

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

                $this->grid->check();
            } else throw new Error('No current grid');
        }

        if(!$this->grid->primary)  throw new Error('No primary key defined');
    }
}

$ajaj = new Ajaj();
?>