<?php class_exists('Core', false) or die();

class PanelResortsGrid extends PanelGrid{
    private $nextId = 0;

    public function __construct(){
        parent::__construct();

        //$this->showQuery = true;
        $this->allowToChangeOrder = true;

    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>
            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="hw" /></dt>
            <dl>Вступление</dl>
            <dt><textarea name="teaser" class="ck">'.Core::$item['teaser'].'</textarea></dt>
            <dl>Текст</dl>
            <dt><textarea name="body" class="ck">'.Core::$item['body'].'</textarea></dt>
            <dl>Галерея</dl>
            <dt><input name="_newPictures[]" id="upload-new-pict" type="file" multiple /></dt>
            <input name="gallery" value="'.Core::$item['gallery'].'" type="hidden">
            <dl>Title</dl>
            <dt><input name="title" value="'.Core::$item['title'].'" type="text" class="fw" /></dt>
            <dl>Meta Description</dl>
            <dt><input name="metaDescription" value="'.Core::$item['metaDescription'].'" type="text" class="fw" /></dt>
            <dl>Meta Keywords</dl>
            <dt><input name="metaKeywords" value="'.Core::$item['metaKeywords'].'" type="text" class="fw" /></dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }


    public function getAdditionalData(){
        return array_merge(array(
                'alias'=>($_POST['alias'] ? $_POST['alias'] : changeCase(transliterate($_POST['name']))),
                'searchIndex'=>changeCase(strip_tags($_POST['name'].' '.$_POST['teaser'].' '.$_POST['body'])),
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