<?php class_exists('Core', false) or die();

class PanelUsersGrid extends PanelGrid{
    public function __construct(){
        //$this->showQuery = true;

        $this->allowToCreateGidRedirects = true;
        //$this->allowToChangeOrder = true;

        parent::__construct();

        $this->checkboxes = array(
            'emailVerified',
        );
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>


            <dl>Имя</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="hw" /></dt>
            <dl>E-mail или login</dl>
            <dt><input name="login" value="'.Core::$item['login'].'" type="text" class="thw" /></dt>
            <dt>
                <input name="emailVerified" value="1" type="checkbox"'.(Core::$item ? $this->checkboxes['emailVerified']['checked'] : ' checked').' /><label>login подтверждён</label>
            </dt>
            <dl>Пароль</dl>
            <dt><input name="_password" value="" type="password" class="thw" autocomplete="off" /></dt>';

            $this->showAccessibleChaptersSelector();

            echo '
            <dl>Дата активации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    private function showAccessibleChaptersSelector(){
        echo '
        <dl>Доступные разделы</dl>';
        $query = Chapters::set();
        $query->condition = 'authRequired>0';
        $query->order = 'ord ASC';
        $query->visibleOnly = true;
        if($chapters = Chapters::get($query)){
            echo '
            <dt></dt>
            <ul class="chapters-links">';

            $accessibleChapters = Core::$item['accessibleChapters'] ? explode(',', Core::$item['accessibleChapters']) : array();

            foreach($chapters as $item){
                $parentName = '';
                if($item['parent']){
                    if($parent = Chapters::getById($item['parent'], 'id', false)) $parentName = ' (<a href="/panel/chapters/'.$item['parent'].'.html">'.$parent['name'].'</a>)';
                }
                $checked = (array_search($item['id'], $accessibleChapters) === false) ? '' : ' checked';
                echo '<li><input name="_accessibleChapters[]" value="'.$item['id'].'" type="checkbox"'.$checked.'><label><a href="/panel/chapters/'.$item['id'].'.html">'.$item['name'].'</a>'.$parentName.'</label></li>';
            }
            echo '</ul>';
        }

    }

    protected function showItemInfo(){
        if(Core::$item){
            echo '<ul class="item-info small">';
            if(!empty(Core::$item['createdTime'])) {
                echo '<li><b>Зарегистрирован(а)</b> '.date('d.m.Y в G.i', Core::$item['createdTime']);
                if(!empty(Core::$item['createdBy'])){
                    if($user = Users::getById(Core::$item['createdBy'], 'id', false)) echo ' пользователем <a href="/panel/users/'.Core::$item['createdBy'].'.html">'.$user['name'].'</a>';
                }
                echo '</li>';
            }
            if(!empty(Core::$item['lastLoginTime'])) {
                echo '<li><b>Авторизован(а)</b> '.date('d.m.Y в G.i', Core::$item['lastLoginTime']).'</li>';
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

    public function check(){
        $query = Users::set();
        $query->condition = $query->idName."!='".Router::$id."' AND login='".$_POST['login']."'";
        $query->visibleOnly = false;
        if(Users::get($query)) throw new Error('E-mail уже зарегистрирован');
    }

    public function getAdditionalDataToCreate(){


        return array_merge(
            array(
                'emailVerified' => 1,
                'accessibleChapters' => (!empty($_POST['_accessibleChapters']) ? $this->getAccessibleChapters() : Chapters::getOpenedToAuthorised()),
            ),
            $this->getPassword(),
            parent::getAdditionalData(),
            parent::getAdditionalDataToCreate()
        );
    }

    public function getAdditionalDataToUpdate(){
        $password = !empty($_POST['password']) ? array('password'=>md5($_POST['password'])) : array();

        return array_merge(
            array(
                'accessibleChapters' => $this->getAccessibleChapters(),
            ),
            $this->getPassword(),
            parent::getAdditionalData(),
            parent::getAdditionalDataToUpdate()
        );
    }

    private function getPassword(){
        if(!empty($_POST['_password'])) {
            return array('password'=>md5($_POST['_password']));
        }else return array();
    }

    private function getAccessibleChapters(){
        return implode(',', $_POST['_accessibleChapters']);
    }
}
?>