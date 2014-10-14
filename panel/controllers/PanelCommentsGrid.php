<?php class_exists('Core', false) or die();

class PanelCommentsGrid extends PanelGrid{
    public function __construct(){
        //$this->showQuery = true;

        parent::__construct();
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Дата создания</dl>
            <dt>'.showDateSelector(Core::$item['createdTime'], '_').'</dt>
            <dl>Текст</dl>
            <dt><textarea name="name">'.Core::$item['name'].'</textarea></dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
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