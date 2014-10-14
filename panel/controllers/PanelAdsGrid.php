<?php class_exists('Core', false) or die();

class PanelAdsGrid extends PanelGrid{
    public function __construct(){
        //$this->showQuery = true;
        //$this->allowToChangeOrder = true;
        $this->allowToCreateGidRedirects = true;

        parent::__construct();
    }

    protected function showForm(){

        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="fw" /></dt>
            <dl>Адрес ссылки</dl>
            <dt><input name="uri" value="'.Core::$item['uri'].'" type="text" class="fw" /></dt>';
            $sel[Core::$item['target']] = ' checked';
            echo '
            <dt><input name="target" value="0" type="radio"'.$sel[0].' /><label>открывать в этом же окне</label> <input name="target" value="1" type="radio"'.$sel[1].' /><label>открывать в новом окне</label></dt>
            <dl>Прикреплённые файлы</dl>
            <dt><input name="_newAttachments[]" id="upload-new-attachments" type="file" multiple /></dt>
            <input name="attachments" value="'.Core::$item['attachments'].'" type="hidden">
            <dl>или произвольный HTML</dl>
            <input name="_natural_body" value="1" type="hidden" />
            <dt><textarea name="body" class="fw textarea-medium">'.stripslashes(Core::$item['body']).'</textarea></dt>
            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    public function getAdditionalDataToCreate(){
        return array_merge(array(

        ), parent::getAdditionalData(), parent::getAdditionalDataToCreate());
    }

    public function getAdditionalDataToUpdate(){
        if(!get_magic_quotes_gpc()) $_POST['body'] = addslashes($_POST['body']);

        return array_merge(array(
            'body'=>addslashes($_POST['body'])
        ), parent::getAdditionalData(), parent::getAdditionalDataToUpdate());
    }
}
?>