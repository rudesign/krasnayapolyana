<?php class_exists('Core', false) or die();

class PanelInnerGrid extends PanelGrid{
    public function __construct(){

        $this->allowToChangeOrder = true;
        $this->allowToCreateGidRedirects = true;

        parent::__construct();

        $this->checkboxes = array(
            'sitemap',
            'footer',
        );
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>


            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="fw" /></dt>
            <dl>Вышележащий раздел</dl>';
            $query = Inner::set();
            $query->visibleOnly = false;
            echo '
            <dt>'.getSelector($this->table, $this->primary, Core::$item['parent'], 'parent', $query).'</dt>
            <dl>Текст</dl>
            <dt><textarea name="body" class="ck">'.Core::$item['body'].'</textarea></dt>
            <dl>Галерея</dl>
            <dt><input name="_newPictures[]" id="upload-new-pict" type="file" multiple /></dt>
            <input name="gallery" value="'.Core::$item['gallery'].'" type="hidden">
            <dl>Прикреплённые файлы</dl>
            <dt><input name="_newAttachments[]" id="upload-new-attachments" type="file" multiple /></dt>
            <input name="attachments" value="'.Core::$item['attachments'].'" type="hidden">
            <dl>Alias</dl>
            <dt><input name="alias" value="'.Core::$item['alias'].'" type="text" class="hw" /></dt>
            <dl>Title</dl>
            <dt><input name="title" value="'.Core::$item['title'].'" type="text" class="fw" /></dt>
            <dl>Meta Description</dl>
            <dt><input name="metaDescription" value="'.Core::$item['metaDescription'].'" type="text" class="fw" /></dt>
            <dl>Meta Keywords</dl>
            <dt><input name="metaKeywords" value="'.Core::$item['metaKeywords'].'" type="text" class="fw" /></dt>
            <dl>Шаблон страницы (оставьте пустым, если специального шаблона не требуется)</dl>
            <dt><input name="template" value="'.Core::$item['template'].'" type="text" class="hw" /></dt>
            <!--
            <dl>Веб-адрес для перенаправления (оставьте пустым, если не требуется)</dl>
            <dt><input name="uri" value="'.Core::$item['uri'].'" type="text" class="fw" /></dt>
            <dl>URI-эквивалент (относительный адрес, начиная с /)</dl>
            <dt><input name="equivalent" value="'.Core::$item['equivalent'].'" type="text" class="fw" /></dt>
            -->
            <dt>
                <input name="sitemap" value="1" type="checkbox"'.$this->checkboxes['sitemap']['checked'].' /><label>показывать в карте сайта</label>
            </dt>
            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    protected function modifyQuery(){

        $this->query->visibleOnly = false;

        if(!empty($_GET['q'])) $this->query->condition = "name LIKE '%".urldecode($_GET['q'])."%'";

        if(!empty($_GET['parent'])) {
            $this->allowToChangeOrder = false;
            $this->query->condition .= $this->query->condition ? ' AND ' : '';
            $this->query->condition .= "(".$this->primary." = '".$_GET['parent']."' OR parent = '".$_GET['parent']."')";
        }

        if(!empty($_GET['o']) && is_string($_GET['o'])){
            $this->query->order = 'name ASC';
        }else{ $this->query->order = 'ord ASC'; }
    }

    protected function showFilter(){
        $query = Inner::set();
        $query->condition = 'parent = 0';
        $query->visibleOnly = false;

        echo '
        <div class="filter forms">
            <form method="GET">
                <div class="l" style="margin-right: 15px;">
                    <dl>Раздел</dl>
                    '.getSelector($this->table, 'id', $_GET['parent'], 'parent', $query).'
                    </div>
                <div class="thw l">
                    <dl>Поиск по названию</dl>
                    <input name="q" value="'.urldecode($_GET['q']).'" class="fw l" type="text" /></div>
                <div class="l"><dl>&nbsp;</dl><input value="Найти" class="buttons green-buttons" type="submit" /></div>
                <div class="clear"></div>
            </form>
        </div>';
    }

    public function getAdditionalDataToCreate(){
        return array_merge(array(
                'alias'=>($_POST['alias'] ? $_POST['alias'] : changeCase(transliterate($_POST['name']))),
            ),
            parent::getAdditionalData(),
            parent::getAdditionalDataToCreate());
    }

    public function getAdditionalDataToUpdate(){
        return array_merge(array(
                'alias'=>($_POST['alias'] ? $_POST['alias'] : changeCase(transliterate($_POST['name']))),
            ),
            parent::getAdditionalData(),
            parent::getAdditionalDataToUpdate());
    }
}
?>