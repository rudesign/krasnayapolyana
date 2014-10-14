<?php class_exists('Core', false) or die();

class PanelGrid extends Grid{

    // bool absolute or relative path to ck uploaded images
    protected $imagesAbsPath= false;

    protected $nameField = 'name';
    // form checkbox names
    protected $checkboxes = array();
    // custom form select names
    protected $selects = array();
    // whether to allow to add new rec
    public $allowToCreate = true;
    // whether to allow create http redirects
    public $allowToCreateGidRedirects = false;
    // whether to allow create uri equivalents
    public $allowToCreateEquivalents = false;
    // grid chapter's uri
    public $gridUriBase = '';
    // whether to allow change grid order
    public $allowToChangeOrder = false;
    // uri of download list
    public $downloadURI = '';
    public $connectionModified = false;

    public function __construct(){
        parent::__construct();

        $_SESSION['imagesAbsPath'] = $this->imagesAbsPath ? 1 : 0;

        $this->quantity = (!empty($_GET['gs']) && is_numeric($_GET['gs'])) ? intval($_GET['gs']) : 25;
    }

    public function modifyConnection(){}

    protected function modifyQuery(){

        $this->query->visibleOnly = false;

        if(!empty($_GET['q'])) $this->query->condition = "name LIKE '%".urldecode($_GET['q'])."%'";

        if(!empty($_GET['parent'])) {
            $this->query->condition .= $this->query->condition ? ' AND ' : '';
            $this->query->condition .= "parent = '".$_GET['parent']."'";
        }

        if($this->allowToChangeOrder) {
            $this->query->order = 'ord ASC';
        }else if(!empty($_GET['o']) && is_string($_GET['o'])){
            $this->query->order = 'name ASC';
        }
    }

    public function show(){
        // if grid
        if(!Router::$id){

            $this->showGridH1();

            $this->showFilter();

            if(!empty($this->grid)){
                echo '
                <div class="grid'.($this->allowToChangeOrder ? ' sortable' : '').'">
                    <div class="deleteConfirmation">
                        <div class="shadow">
                            <dt class="small">Подтвердите удаление</dt>
                            <dt class="d10"></dt>
                            <dt><button class="buttons green-buttons">Удалить</button></dt>
                            <dt class="d10"></dt>
                            <dt class="small"><a href="javascript:clearConfirmations();">Отменить</a></dt>
                        </div>
                    </div>';

                    $query = new Query($this->table);
                    foreach($this->grid as $item){
                        $dependent = $item['parent'] ? 'dependent ' : '';
                        echo '
                        <div class="'.$dependent.'items" id="id'.$item[$this->primary].'">
                            <table>
                                <tr>
                                    '.($this->count > 1 ? '<td><input class="selectedIds" value="'.$item[$this->primary].'" type="checkbox" /></td>' : '').'
                                    <td class="dark icons">';
                                        $this->showIcons($item);
                                        if($item['pubTime'] > time()) echo '<i class="icon2" title="Не опубликовано"></i>';
                                    echo
                                    '</td>
                                    <td class="noSelection dark ids'.(!$item['visible'] ? ' hidden' : '').'">'.$item[$this->primary].'</td>';

                                    $this->showGridName($query, $item);

                                    echo '</tr>
                            </table>
                        </div>';
                    }
                        echo '
                        <div class="clear"></div>
                    </div>';
                    if($this->count > 1){
                        echo '
                        <div class="forms group-action-controls">
                            <div class="select-all"><input name="_checkAllIds" onClick="selectAllIds(this);" type="checkbox" /><label>выделить все</label></div>
                            <ul class="inline selector">
                                <li>
                                    <select name="action" class="l">
                                        <option value="turnOn">включить</option>
                                        <option value="turnOff">выключить</option>
                                        <!--<option value="activate">активировать</option>-->
                                        <option value="delete">удалить</option>
                                        <!--'.($this->allowToCreateGidRedirects ? '<option value="clearRedirects">очистить перенаправления</option>' : '').'-->
                                    </select>
                                </li>
                                <li><button onclick="executeGroupAction(); return false;" class="buttons green-buttons">Применить</button></li>
                            </ul>
                        </div>';
                    }

                    $pager = new Pager($this);

                    $pager->show();
            }
            // if item
        }else if(Core::$item){

            self::checkOptions();

            $this->showItemH1();

            $this->showForm();

            $this->showItemInfo();

            // if no item: show form
        }else{
            $this->showNewItemH1();

            $this->showForm();

        }
    }

    protected function checkOptions(){
        if(is_array($this->checkboxes) && !empty($this->checkboxes)){
            foreach($this->checkboxes as $item){
                if(Core::$item[$item]){
                    $this->checkboxes[$item]['checked'] = ' checked';
                }else{
                    unset($this->checkboxes[$item]['checked']);
                }
            }
        }

        if(is_array($this->selects) && !empty($this->selects)){
            foreach($this->selects as $item){
                if(Core::$item[$item]){
                    $this->selects[$item][Core::$item[$item]] = ' selected';
                }else{
                    unset($this->selects[$item]);
                }
            }
        }
    }

    protected function showGridH1(){
        $uri = '/'.implode('/', Router::$request->parsed->path).'/new.html';
        $selectedOrder[$_GET['o']] = ' class="active"';
        $gs = $_GET['gs'] ? $_GET['gs'] : 25;
        $selectedGridSize[$gs] = ' class="active"';
        echo '<h1 class="l">'.Chapters::$current['name'].($this->allowToCreate ? '. <a href="'.$uri.'">Создать</a>' : '').'</h1>';
        if($this->count > 1){
            echo '
            <ul class="r grid-size-selector small black">
                <li>По</li>
                <li'.$selectedOrder[''].'><a href="/'.implode('/', Router::$request->parsed->path).'/">'.(!$this->allowToChangeOrder ? 'дате' : 'порядку').'</a></li>
                '.(!$this->allowToChangeOrder ? '<li'.$selectedOrder['n'].'><a href="'.varExclude('', 'o', 'n').'">алфавиту</a></li>' : '').'
                <li'.$selectedGridSize[25].'><a href="'.varExclude('', 'gs', 25).'">25</a></li>
                <li'.$selectedGridSize[50].'><a href="'.varExclude('', 'gs', 50).'">50</a></li>
                <li'.$selectedGridSize[75].'><a href="'.varExclude('', 'gs', 75).'">75</a></li>
                <li'.$selectedGridSize[100].'><a href="'.varExclude('', 'gs', 100).'">100</a></li>
                '.($this->downloadURI ? '<li><a href="'.$this->downloadURI.'" target="_blank">Скачать</a></li>' : '').'
            </ul>';
        }
        echo '<div class="clear"></div>';
    }

    protected function showNewItemH1(){
        $uri = '/'.implode('/', Router::$request->parsed->path).'/';
        echo '<h1><a href="'.$uri.'">'.Chapters::$current['name'].'</a> &larr; Новая запись</h1>';
    }

    protected function showItemH1(){
        $uri = '/'.implode('/', Router::$request->parsed->path).'/';
        $uri = varExclude($uri, 'page', $_SESSION['page']);
        if(Core::$item){
            echo '<h1><a href="'.$uri.'">'.Chapters::$current['name'].'</a> &larr; '. getLimited(Core::$item[$this->nameField], 80, true).'</h1>';
        }else{
            error('This page is no longer available');
        }
    }

    protected function showFilter(){
        echo '
        <ul class="inline filter">
            <form method="GET">
                <li class="thw"><input name="q" value="'.urldecode($_GET['q']).'" class="fw" type="text" /></li>
                <li><input value="Найти" class="buttons green-buttons" type="submit" /></li>
            </form>
        </ul>';
    }

    protected function showIcons(){
        //echo '<i class="icon1"></i>';
    }

    protected function showGridName(&$query, $item){

        if(empty($query)) $query = new Query($this->table);

        $parentName = '';
        if($item['parent']){

            $query->flush();
            $query->id = $item['parent'];
            $query->flat = true;

            if($query->get()) $parentName = ' (<a href="/'.implode('/', Router::$request->parsed->path).'/'.$query->result[$this->primary].'.html">'.getLimited($query->result[$this->nameField], 80, true).'</a>)';

        }

        echo '<td class="names black"><a href="/'.implode('/', Router::$request->parsed->path).'/'.$item[$this->primary].'.html">'.getLimited(($item[$this->nameField] ? $item[$this->nameField] : '<em>Без названия</em>'), 80, true).'</a>'.$parentName.'</td>';
    }

    protected function showItemInfo(){
        if(Core::$item){
            echo '<ul class="item-info small">';
            if(!empty(Core::$item['createdTime'])) {
                echo '<li><b>Запись создана</b> '.date('d.m.Y в G.i', Core::$item['createdTime']);
                if(!empty(Core::$item['createdBy'])){
                    if($user = Users::getById(Core::$item['createdBy'], 'id', false)) echo ' пользователем <a href="/panel/users/'.Core::$item['createdBy'].'.html">'.$user['name'].'</a>';
                }
                echo '</li>';
            }
            if(!empty(Core::$item['modifiedTime']) && (Core::$item['modifiedTime'] != Core::$item['createdTime'])) {
                echo '<li><b>Изменения внесены</b> '.date('d.m.Y в G.i', Core::$item['modifiedTime']);
                if(!empty(Core::$item['modifiedBy'])){
                    if($user = Users::getById(Core::$item['modifiedBy'], 'id', false)) echo ' пользователем <a href="/panel/users/'.Core::$item['modifiedBy'].'.html">'.$user['name'].'</a>';
                }
                echo '</li>';
            }
            echo '</ul>';
        }
    }

    public function check(){}

    public function getAdditionalData(){
        return array(
            'pubTime' => mktime($_POST['_hours'], $_POST['_mins'], 0, $_POST['_month'], $_POST['_day'], $_POST['_year']),
        );
    }

    public function getAdditionalDataToCreate(){
        $data = array(
            'createdTime' => time(),
            'createdBy' => Users::$current['id'],
        );

        if($this->allowToCreateGidRedirects) $data['globalId'] = getUnique();

        return $data;
    }

    public function getAdditionalDataToUpdate(){
        return array(
            'modifiedTime' => time(),
            'modifiedBy' => Users::$current['id'],
        );
    }

    public function actionAfterSave($id = 0){}
    public function actionAfterDelete($id = 0){}

    protected function showRedirectField(){
        if($this->allowToCreateGidRedirects){
            $redirect = Redirects::getById(Core::$item['globalId'], 'globalIdToBind', false);
            echo '
            <dl>Страница для перенаправления</dl>
            <dt><input name="_uriToRedirect" value="'.($redirect ? $redirect['uriToRedirect'] : '').'" type="text" class="fw" /></dt>';
        }
    }
}
?>