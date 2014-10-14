<?php class_exists('Core', false) or die();

class PanelFeedbackGrid extends PanelGrid{
    public function __construct(){
        parent::__construct();

        //$this->showQuery = true;

        $this->allowToCreate = false;

    }

    protected function showForm(){
        echo Core::$item['body'];
    }

    public function getAdditionalDataToCreate(){
        return array_merge(array(

        ), parent::getAdditionalData(), parent::getAdditionalDataToCreate());
    }

    public function getAdditionalDataToUpdate(){
        return array_merge(array(

        ), parent::getAdditionalData(), parent::getAdditionalDataToUpdate());
    }
}
?>