<?php class_exists('Core', false) or die();

class PanelContactsGrid extends PanelGrid{
    public function __construct(){
        parent::__construct();

        //$this->showQuery = true;
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="hw" /></dt>
            <dl>Телефон(ы)</dl>
            <dt><input name="phone" value="'.Core::$item['phone'].'" type="text" class="hw" /></dt>
            <dl>Факс</dl>
            <dt><input name="fax" value="'.Core::$item['fax'].'" type="text" class="hw" /></dt>
            <dl>ICQ</dl>
            <dt><input name="icq" value="'.Core::$item['icq'].'" type="text" class="hw" /></dt>
            <dl>Skype</dl>
            <dt><input name="skype" value="'.Core::$item['skype'].'" type="text" class="hw" /></dt>
            <!--
            <dl>Галерея</dl>
            <dt><input name="_newPictures[]" id="upload-new-pict" type="file" multiple /></dt>
            <input name="gallery" value="'.Core::$item['gallery'].'" type="hidden">
            <dl>Прикреплённые файлы</dl>
            <dt><input name="_newAttachments[]" id="upload-new-attachments" type="file" multiple /></dt>
            <input name="attachments" value="'.Core::$item['attachments'].'" type="hidden">
            -->
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
        return array_merge(array(

        ), parent::getAdditionalData(), parent::getAdditionalDataToUpdate());
    }
}
?>