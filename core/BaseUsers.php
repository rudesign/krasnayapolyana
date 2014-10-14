<?php

class BaseUsers extends Core{

    public static $current = array();
    public static $accessGranted = false;

    public function __construct(){

        parent::__construct();

        self::getCurrent();

        if(Users::$current && Users::$current['blocked']){
            self::logout();

            Users::route(APP_PATH.'/banned/');
        }

        if(Chapters::$current['alias'] == 'bye') self::logout();

        // email verification by request
        if(isset($_GET['verification']) && isset($_GET['u'])) {
            try{
                if(!$userId = intval(trim($_GET['u']))) throw new Error();

                if(!$globalId = trim($_GET['verification'])) throw new Error();

                if(!$userId = self::verifyEmail($userId, $globalId)) throw new Error();

                Router::redirect('/verification/success/');
            }catch (Error $e){
                Router::redirect('/verification/failed/');
            }
        }

        self::updateLastLoginTime();
    }

    public static function set(){
        self::$table = 'users';

        return new Query(self::$table);
    }

    public static function get(&$query = null){
        try{
            if(empty($query)) if(!$query = self::set()) throw new Error();

            return parent::get($query);
        }catch (Error $e){
            return false;
        }
    }

    public static function getById($id = 0, $key = '', $visibleOnly = true){
        try{
            if(!self::set()) throw new Error();

            return parent::getById($id, $key, $visibleOnly);
        }catch (Error $e){
            return false;
        }
    }

    public static function create($data = array(), $visible = true){
        try{
            if(empty($data)) throw new Error();

            $query = self::set();

            $query->values = array_merge(array(
                'visible' => ($visible ? 1 : 0),
                'globalId' => getUnique(),
                'pubTime' => ($visible ? time() : 0),
                'createdTime' => time(),
            ), $data);

            if(!$id = $query->write()) throw new Error();

            $query = self::set();

            $query->fields = array(
                'createdBy',
            );
            $query->values = array(
                (self::$current ? self::$current['id'] : $id)
            );
            $query->id = $id;

            $query->update();

            return $id;
        }catch (Error $e){
            return false;
        }
    }

    public static function terminate($id = 0){
        try{
            if(empty($id) && empty($id)) throw new Error();
            if(!$user = Users::getById($id)) throw new Error();

            // delete pubs
            $query = Lessons::set();
            $query->visibleOnly = false;
            $query->condition = 'createdByOrigin = '.$id;

            if($rows = Lessons::get($query)){
                foreach($rows as $row){
                    if(!Lessons::delete($row['id'])) throw new Error();
                }
            }

            // delete gallery
            if($user['gallery']){
                deleteGallery($user['gallery']);
            }

            // delete attachments
            if($user['attachments']){
                $fPath = APP_ROOT.'/'.$user['attachments'];
                if(file_exists($fPath)){
                    @unlink($fPath);
                }
            }

            // delete comments
            Comments::deleteByUser($id);

            $query = self::set();
            $query->id = $id;

            if(!$query->delete()) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function update($query = null, $userId = 0, $fields = array(), $values = array()){
        try{
            if(empty($userId)) throw new Error();

            if(empty($query)) $query = self::set();

            $query->id = $userId;

            $query->fields = array_merge(array(
                'modifiedTime',
            ), $fields);

            $query->values = array_merge(array(
                time(),
            ), $values);

            if(!$query->update()) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    protected function verifyEmail($userId = 0, $globalId = ''){
        try{
            if(!$userId) throw new Error();
            if(!$globalId) throw new Error();

            $query = self::set();
            $query->condition = "visible>0 AND id='{$userId}' AND globalId='{$globalId}'";
            $query->flat = true;

            if(!$user = self::get($query)) throw new Error();

            $query = self::set();

            $query->fields = 'emailVerified';
            $query->values = 1;
            $query->key = 'globalId';
            $query->id = $globalId;

            if(!$query->update()) throw new Error();

            // once
            if(!$user['emailVerified']){
                if(!self::login(null, null, $userId)) throw new Error($userId);
            }

            // log action
            // ...

            return $user['id'];
        }catch (Error $e){
            return false;
        }
    }

    public static function getCurrent(){
        try{

            if(empty($_SESSION['currentId'])) throw new Error();

            if(!self::$current = self::getById($_SESSION['currentId'])) throw new Error();

            if(self::$current['globalId'] != $_SESSION['currentGId']) throw new Error();

            self::$current['unreadMessages'] = Messages::getUnreadCount(self::$current['id']);

        }catch (Error $e){
            self::logout();
        }

        if(!self::checkAccessRights()){
            Users::redirectUnauthorised();

            if(Users::$current && !Users::$current['emailVerified']){
                Users::route(APP_PATH.'/verify/');
            }else{
                Users::route(APP_PATH.'/login/');
            }

            self::$accessGranted = false;
        }

        return self::$current ? true : false;
    }

    public static function checkAccessRights(){
        try{
            if(!Chapters::$current) throw new Error();

            if(Chapters::$current['authRequired']){

                if(!self::$current) throw new Error();

                if(!self::$current['accessibleChapters']) throw new Error();

                $accessibleChapters = explode(',', self::$current['accessibleChapters']);

                $accessibleChapters = array_flip($accessibleChapters);

                if(!isset($accessibleChapters[Chapters::$current[Chapters::$primary]])) throw new Error();

                if(Users::$current && !Users::$current['emailVerified']) throw new Error();
            }

            self::$accessGranted = true;

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function changePassword($login = ''){
        try{
            if(!$login) throw new Error();

            if(!$user = self::getById($login, 'login')) throw new Error('Пользователь с таким e-mail не зарегистрирован');

            $newPassword = substr(md5(microtime(1)), 0, 4);

            $query = self::set();
            $query->fields = 'password';
            $query->values = md5($newPassword);
            $query->id = $user['id'];

            if(!$query->update()) throw new Error('Ошибка при смене пароля');

            $body = 'Ваш новый пароль для входа на сайт: '.$newPassword;

            if(!sendAuthEmail($user['login'], 'Ваш новый пароль', $body)) throw new Error('Ошибка при отправке e-mail');

            return true;
        }catch (Error $e){
            return $e->getMessage();
        }
    }

    public static function login($email = '', $password = '', $userId = 0){
        try{
            if(empty($email) && empty($password) && empty($userId)) throw new Error();

            $query = self::set();

            if(!empty($userId)){
                $query->id = $userId;
            }else{
                $query->condition = "login = '{$email}' AND password = MD5('{$password}')";
            }

            $query->flat = true;

            if(!self::$current = self::get($query)) throw new Error();

            if(!self::setCurrent(self::$current['id'], self::$current['globalId'])) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function setCurrent($id = 0, $gid = ''){
        try{
            if(empty($id)) throw new Error();
            if(empty($gid)) throw new Error();

            $_SESSION['currentId'] = $id;
            $_SESSION['currentGId'] = $gid;

            if(empty($_SESSION['currentId']) || empty($_SESSION['currentGId'])) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function checkAliasIsUnique($alias = ''){
        try{
            if(empty($alias)) throw new Error();

            if(self::getById($alias, 'alias', false)) throw new Error();
            if(Chapters::getById($alias, 'alias', false)) throw new Error();
            if(preg_match('/(shop\d+[a-zA-Z])+/i', $alias)) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    protected function updateLastLoginTime(){
        if(self::$current){
            if(self::$current['lastLoginTime'] <= (time() - Settings::$data->usersOnlineTimeout)){

                $query = self::set();

                $query->fields = 'lastActivityTime';
                $query->values = time();
                $query->id = self::$current[$query->key];

                $query->update();
            }
        }
    }

    public static function route($uri = ''){
        if(empty($uri)) $uri = $_SERVER['HTTP_REFERER'];

        Chapters::$current = array();

        Router::route($uri);

        Chapters::getCurrent();

        Core::getItem();

        self::getCurrent();

    }

    public static function logout(){
        Session::destroy();
        self::$current = array();
    }

    public static function loginIsOccupied($login = ''){
        try{
            if(empty($login)) throw new Error();

            $query = self::set();

            $query->key = 'login';
            $query->id = $login;
            $query->visibleOnly = false;

            if($query->get()) throw new Error();

            return false;
        }catch (Error $e){
            return true;
        }
    }

    public static function isOnline($user = array(), $userId = 0){
        try{

            if(empty($user) && empty($userId)) throw new Error();

            if(empty($user)){
                if(!$user = self::getById($userId, 'id', false)) throw new Error();
            }

            if($user['lastActivityTime'] < (time() - Settings::$data->usersOnlineTimeout)) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function itIsMe($userId = 0){
        return Users::$current['id'] == $userId ? true : false;
    }
}
?>