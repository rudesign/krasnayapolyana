<?php class_exists('Core', false) or die();

class PanelRedirectsGrid extends PanelGrid{
    public function __construct(){
        //$this->showQuery = true;
        //$this->allowToChangeOrder = true;

        $this->nameField = 'uri';

        parent::__construct();
    }

    protected function modifyQuery(){

        $this->query->visibleOnly = false;

        if(!empty($_GET['q'])) $this->query->condition = "uri LIKE '%".urldecode($_GET['q'])."%' OR uriToRedirect LIKE '%".urldecode($_GET['q'])."%'";


        if($this->allowToChangeOrder) {
            $this->query->order = 'ord ASC';
        }else if(!empty($_GET['o']) && is_string($_GET['o'])){
            $this->query->order = 'name ASC';
        }
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <input name="globalIdToBind" value="'.(Core::$item['globalIdToBind'] ? Core::$item['globalIdToBind'] : $_GET['gid']).'" type="hidden" />
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>';
            if(!($_GET['gid'] || Core::$item['globalIdToBind'])){
                echo '
                <dl>Адрес страницы (относительный, начиная с /)</dl>
                <dt><input name="uri" value="'.Core::$item['uri'].'" type="text" class="hw" /></dt>';
            }
            echo '
            <dl>Адрес перенаправления (относительный или абсолютный)</dl>
            <dt><input name="uriToRedirect" value="'.Core::$item['uriToRedirect'].'" type="text" class="hw" /></dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    protected function showGridName(&$db, $item){
        $name = $item['globalIdToBind'] ? $item['globalIdToBind'] : $item[$this->nameField];

        echo '<td class="names black"><a href="/'.implode('/', Router::$request->parsed->path).'/'.$item[$this->primary].'.html">'.($name ? $name : '<em>Без названия</em>').'</a>'.($item['uriToRedirect'] ? ' &rarr; <a href="'.$item['uriToRedirect'].'">'.$item['uriToRedirect'].'</a>' : '').'</td>';
    }

    protected function showItemH1(){
        $uri = '/'.implode('/', Router::$request->parsed->path).'/';
        $uri = varExclude($uri, 'page', $_SESSION['page']);
        if(Core::$item){
            $name = Core::$item['globalIdToBind'] ? Core::$item['globalIdToBind'] : Core::$item[$this->nameField];
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