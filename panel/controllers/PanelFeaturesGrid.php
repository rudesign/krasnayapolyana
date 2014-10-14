<?php class_exists('Core', false) or die();

class PanelFeaturesGrid extends PanelGrid{

    public function __construct(){
        parent::__construct();

        //$this->showQuery = true;

        $this->allowToChangeOrder = true;
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">

            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="hw" /></dt>
            <dl>Alias</dl>
            <dt><input name="alias" value="'.Core::$item['alias'].'" type="text" class="hw" /></dt>
            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    public function getAdditionalData(){
        return array_merge(array(
                'alias'=>($_POST['alias'] ? $_POST['alias'] : changeCase(transliterate($_POST['name']))),
            ),
            parent::getAdditionalData());
    }

    public function getAdditionalDataToCreate(){
        return array_merge(array(

        ), self::getAdditionalData(), parent::getAdditionalDataToCreate());
    }

    public function getAdditionalDataToUpdate(){
        return array_merge(array(

        ), self::getAdditionalData(), parent::getAdditionalDataToUpdate());
    }
}
?>