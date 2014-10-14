<?php
class Redirects extends Db{

    public static function set(){
        self::$table = 'redirects';

        return new Query(self::$table);
    }

    public static function get(&$query = null, $options = array()){
        try{
            if(empty($query)) if(!$query = self::set()) throw new Error();

            return parent::get($query, $options);
        }catch (Error $e){
            return false;
        }
    }

    public static function getById($id = 0, $key = ''){
        try{
            if(!self::set()) throw new Error();

            return parent::getById($id, $key);
        }catch (Error $e){
            return false;
        }
    }

    public static function bindWithGid($gid, $uri){
        try{
            if(empty($gid) || empty($uri)) throw new Error();

            $query = self::set();
            $query->values = array(
                'globalId'=>getUnique(),
                'visible'=>1,
                'globalIdToBind'=>$gid,
                'uriToRedirect'=>$uri,
                'pubTime'=>time(),
                'createdTime'=>time(),
                'createdBy'=>Users::$currentUser['id'],
                'modifiedTime'=>time(),
                'modifiedBy'=>Users::$currentUser['id']
            );

            if(!$query->write()) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function updateBindWithGid($gid, $uri = ''){
        try{
            if(empty($gid)) throw new Error();

            $item = self::getById($gid, 'globalIdToBind', false);

            if(empty($item)) {
                // create if not exists
                if(!empty($uri)){
                    if(!self::bindWithGid($gid, $uri)) throw new Error();
                }else throw new Error();
            // update existed
            }else{
                $query = self::set();

                if(!$query->id = $item[$query->idName]) throw new Error();

                if(!empty($uri)){
                    $query->fields = 'uriToRedirect';
                    $query->values = $uri;

                    if(!$query->update()) throw new Error();
                // delete if empty uri
                }else if(!$query->delete()) throw new Error();
            }

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function deleteBindWithGid($gid){
        try{
            if(empty($gid)) throw new Error();

            $query = self::set();

            $query->idName = 'globalIdToBind';
            $query->visibleOnly = false;
            $query->id = $gid;

            if(!$query->delete()) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }

    }
}
?>