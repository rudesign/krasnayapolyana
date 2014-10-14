<?php class_exists('Core', false) or die();

class PanelEquivalentsGrid extends PanelGrid{
    public function __construct(){
        //$this->showQuery = true;
        //$this->allowToChangeOrder = true;

        $this->nameField = 'uri';

        parent::__construct();
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <input name="globalIdToBind" value="'.(Core::$item['globalIdToBind'] ? Core::$item['globalIdToBind'] : $_GET['gid']).'" type="hidden" />
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>
            <dl>Адрес страницы (относительный, начиная с /)</dl>
            <dt><input name="uri" value="'.Core::$item['uri'].'" type="text" class="hw" /></dt>
            <dl>Адрес перенаправления (относительный, начиная с /)</dl>
            <dt><input name="equivalent" value="'.Core::$item['equivalent'].'" type="text" class="hw" /></dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    protected function showGridName(&$db, $item){
        $host = $item['host'] ? $item['host'] : $_SERVER['HTTP_HOST'];
        echo '
        <td class="names black">
            <a href="/'.implode('/', Router::$request->parsed->path).'/'.$item[$this->primary].'.html">http://'.$host.$item['uri'].'</a> &rarr; <a href="http://'.$host.$item['equivalent'].'" target="_blank">http://'.$host.$item['equivalent'].'</a>
        </td>';
    }

    protected function showItemH1(){
        $uri = '/'.implode('/', Router::$request->parsed->path).'/';
        $uri = varExclude($uri, 'page', $_SESSION['page']);
        if(Core::$item){
            $name = 'http://'.(Core::$item['host'] ? Core::$item['host'] : $_SERVER['HTTP_HOST']);
            $name .= Core::$item['uri'];
            echo '<h1><a href="'.$uri.'">'.Chapters::$current['name'].'</a> &larr; '.$name.'</h1>';
        }else{
            error('This page is no longer available');
        }
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