<?php

class Ajaj{
    private $result = array();
    private $grid;

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->correctCheckboxes();

            $this->execute();

            // result
            echo json_encode($this->result);

        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        // if a new record
        if(Router::$id== 'new'){

            $_POST = array_merge($_POST, $this->grid->getAdditionalDataToCreate());

            if(!$id = self::write()) throw new Error('An error occurred while saving');

            $this->actionAferSave($id);

            // update items order if enabled
            if($this->grid->allowToChangeOrder){

                $query = new Query(Chapters::$current['table']['name']);

                $query->fields = 'ord';
                $query->values = 'ord+1';
                $query->naturalValues = true;

                if(!$query->update()) throw new Error('Cannot update items order');
            }

            if($this->grid->allowToCreateGidRedirects && !empty($_POST['_uriToRedirect'])) Redirects::bindWithGid($_POST['globalId'], $_POST['_uriToRedirect']);
            if($this->grid->allowToCreateEquivalents && !empty($_POST['uriEquivalent']) && $this->grid->gridUriBase) Equivalents::create($this->grid->gridUriBase.$id.'.html', $_POST['equivalent']);

            moveTemporaryAttachments($_POST['gallery'], $_POST['attachments']);

            //$this->result['uri'] = '/'.implode('/', Router::$request->parsed->path).'/'.$id.'.html';
            $this->result['uri'] = '/'.implode('/', Router::$request->parsed->path).'/';

        // update existing
        }else{
            $_POST = array_merge($_POST, $this->grid->getAdditionalDataToUpdate());

            $query = new Query(Chapters::$current['table']['name']);

            for(Reset($_POST);list($key, $value)=each($_POST);){

                $query->flush();

                $query->idName = Chapters::$current['table']['primary'];
                $query->id = Router::$id;
                $query->fields = $key;
                $query->values = $value;

                $query->update();
            }

            if($this->grid->allowToCreateGidRedirects) Redirects::updateBindWithGid(Core::$item['globalId'], $_POST['_uriToRedirect']);
            if($this->grid->allowToCreateEquivalents) Equivalents::updateEquivalent($this->grid->gridUriBase.Router::$id.'.html', $_POST['equivalent']);
        }


    }

    private function actionAferSave($id = 0){
        if(!empty($this->grid) && method_exists($this->grid, 'actionAfterSave')){
            $this->grid->actionAfterSave($id);
        }
    }

    private function write(){
        $filteredPost = $_POST;

        foreach(array_keys($filteredPost) as $key){
            if(substr($key, 0, 1) == '_'){
                unset($filteredPost[$key]);
            }
        }

        $query = new Query(Chapters::$current['table']['name']);
        $query->values = $filteredPost;
        if(!$id = $query->write()) throw new Error('Cannot complete write request');//.$query->string);

        return $id;
    }

    private function correctCheckboxes(){
        if(!empty($_POST['checkboxes'])){

            foreach($_POST['checkboxes'] as $key=>$value){
                if(substr($value, 0, 1) == '_'){
                    unset($_POST['checkboxes'][$key]);
                }
            }

            foreach($_POST['checkboxes'] as $item){
                if(!$_POST[$item]) $_POST[$item] = 0;
            }

            unset($_POST['checkboxes']);
        }
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

                if(!$this->grid->allowToCreate) throw new Error('Создание новых записей не разрешено в этом разделе');
            } else throw new Error('No current grid');
        }
    }
}

$ajaj = new Ajaj();
?>