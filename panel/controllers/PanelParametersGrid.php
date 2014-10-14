<?php class_exists('Core', false) or die();

class PanelParametersGrid extends PanelGrid{
    public function __construct(){
        //$this->showQuery = true;
        //$this->allowToChangeOrder = true;

        parent::__construct();
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="hw" /></dt>
            <dl>E-mail</dl>
            <dt><input name="email" value="'.Core::$item['email'].'" type="text" class="thw" /></dt>
            <dl>Facebook</dl>
            <dt><input name="fb" value="'.Core::$item['fb'].'" type="text" class="fw" /></dt>
            <dl>ВКонтакте</dl>
            <dt><input name="vk" value="'.Core::$item['vk'].'" type="text" class="fw" /></dt>
            <!--
            <dl>Номер телефона</dl>
            <dt><input name="phone" value="'.Core::$item['phone'].'" type="text" class="thw" /></dt>
            <dl>Twitter</dl>
            <dt><input name="tw" value="'.Core::$item['tw'].'" type="text" class="fw" /></dt>
            <dl>Instagram</dl>
            <dt><input name="ig" value="'.Core::$item['ig'].'" type="text" class="fw" /></dt>
            -->

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    public function getAdditionalData(){
        return array_merge(array(
            'eventTime'=>mktime(0, 0, 0, $_POST['_event_month'], $_POST['_event_day'], $_POST['_event_year']),
        ), parent::getAdditionalData());

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