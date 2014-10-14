<?php class_exists('Core', false) or die();

class PanelChaptersGrid extends PanelGrid{
    public function __construct(){
        //$this->showQuery = true;
        $this->allowToChangeOrder = true;
        $this->allowToCreateGidRedirects = true;

        parent::__construct();

        $this->checkboxes = array(
            'accessibleToAll',
            'authRequired',
        );
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="hw" /></dt>
            <dt>
                <input name="accessibleToAll" value="1" type="checkbox"'.$this->checkboxes['accessibleToAll']['checked'].' /><label>доступен всем</label>
                <input name="authRequired" value="1" type="checkbox"'.$this->checkboxes['authRequired']['checked'].' /><label>требуется авторизация</label>
            </dt>
            <dl>Alias</dl>
            <dt><input name="alias" value="'.Core::$item['alias'].'" type="text" class="hw" /></dt>
            <dl>Вышележащий раздел</dl>
            <dt>'.getSelector($this->table, $this->primary, Core::$item['parent'], 'parent').'</dt>
            <dl>'.(Core::$item['staticId'] ? '<a href="/panel/static/'.Core::$item['staticId'].'.html">' : '').'Текст'.(Core::$item['staticId'] ? '</a>' : '').'</dl>
            <dt>'.getSelector('static', 'id', Core::$item['staticId'], 'staticId', false).'</dt>
            <dl>HTML-шаблон</dl>
            <dt><input name="template" value="'.Core::$item['template'].'" type="text" class="hw" /></dt>
            <dl>SQL table info (primary field name@table name)</dl>
            <dt><input name="tableDetails" value="'.Core::$item['tableDetails'].'" type="text" class="hw" /></dt>
            <dl>Title</dl>
            <dt><input name="title" value="'.Core::$item['title'].'" type="text" class="fw" /></dt>
            <dl>Meta Description</dl>
            <dt><input name="metaDescription" value="'.Core::$item['metaDescription'].'" type="text" class="fw" /></dt>
            <dl>Meta Keywords</dl>
            <dt><input name="metaKeywords" value="'.Core::$item['metaKeywords'].'" type="text" class="fw" /></dt>
            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>
            <dl>Категория разделов панели управления</dl>
            <dt>'.getSelector('adminCategories', 'id', Core::$item['adminCategory'], 'adminCategory', false).'</dt>';

            //$this->showRedirectField();

            echo '
            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    protected function showIcons($item){
        if($item['accessibleToAll']) echo '<i class="icon3" title="Доступен всем"></i>';
        if($item['authRequired']) echo '<i class="icon1" title="Требуется авторизация"></i>';
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

        ), parent::getAdditionalData(), parent::getAdditionalDataToUpdate());
    }
}
?>