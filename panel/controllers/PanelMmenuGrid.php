<?php class_exists('Core', false) or die();

class PanelMmenuGrid extends PanelGrid{
    public function __construct(){
        //$this->showQuery = true;
        $this->allowToChangeOrder = true;
        $this->allowToCreateGidRedirects = true;

        parent::__construct();

        $this->checkboxes = array(
            'nofollow',
            'authorisedOnly',
            'unauthorisedOnly',
            'footer',
        );
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="hw" /></dt>
            <dt>
                <input name="footer" value="1" type="checkbox"'.$this->checkboxes['footer']['checked'].' /><label>показывать ссылку в футере</label>
                <input name="nofollow" value="1" type="checkbox"'.($this->checkboxes['nofollow']['checked'] ? ' checked' : '').' /><label>атрибут nofollow</label>
                <input name="authorisedOnly" value="1" type="checkbox"'.$this->checkboxes['authorisedOnly']['checked'].' /><label>только для авторизованных</label>
                <input name="unauthorisedOnly" value="1" type="checkbox"'.$this->checkboxes['unauthorisedOnly']['checked'].' /><label>только для неавторизованных пользователей</label>
            </dt>
            <dl>Alias раздела сайта</dl>
            <dt><input name="alias" value="'.Core::$item['alias'].'" type="text" class="hw" /></dt>
            <dl>... или любая ссылка на сторонний сайт</dl>
            <dt><input name="uri" value="'.Core::$item['uri'].'" type="text" class="fw" /></dt>
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