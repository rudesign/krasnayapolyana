<?php
class Cache{
    public static $source = 'undefined';

    private static $cacheable = false;

    public static function getCached(){
        try{
            if(!self::isCacheable()) throw new Error();

            $fname = self::getUriPointer();

            $fpath = APP_ROOT.'/cache/'.$fname;

            if(!file_exists($fpath)) throw new Error();

            if(!$html = file_get_contents($fpath)) throw new Error();

            self::$source = 'cached';

            return $html;
        }catch (Error $e){
            self::$source = 'fresh';

            return false;
        }
    }

    public static function store($html = ''){
        try{
            if(!self::$cacheable) throw new Error();
            if(!$html) throw new Error();

            $fname = self::getUriPointer();

            $fpath = APP_ROOT.'/cache/'.$fname;

            if(!file_put_contents($fpath, $html)) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    public static function clean($dirToCheck = ''){
        try{
            if(empty($dirToCheck)) throw new Error();
            if(!class_exists('Settings', false)) new Settings();
            if(!class_exists('Settings', false)) throw new Error();

            $pastTime = time() - Settings::$data->cacheStoreTime;

            if($files = @scandir($dirToCheck)){
                $files = array_slice($files, 2);
                if(!empty($files)){
                    foreach($files as $file){
                        if($file != 'clean.php'){
                            $fpath = $dirToCheck.'/'.$file;
                            if(file_exists($fpath)){
                                if(!is_dir($fpath)){
                                    if(filemtime($fpath) < $pastTime){
                                        @unlink($fpath);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return true;
        }catch (Error $e){
            return false;
        }
    }

    private static function isCacheable(){
        try{
            if((Settings::$data->environment == 'production') && Settings::$data->cacheEnabled){
                // if start page
                if(empty(Router::$request->parsed->path)) throw new Error();

                switch(Router::$request->parsed->path[0]){
                    case 'marketplace':
                    case 'master-classes':
                    case 'materials':
                    case 'online':
                        $currentAlias = end(Router::$request->parsed->path);

                        switch($currentAlias){
                            case 'marketplace':
                            case 'master-classes':
                            case 'materials':
                            case 'online':
                            case 'slice':
                                if(!Router::$id) throw new Error();
                            break;
                        }
                    break;

                    case 'top':
                        throw new Error();
                    break;
                }
            }

            self::$cacheable = false;

            return false;
        }catch (Error $e){
            self::$cacheable = true;

            return true;
        }
    }

    private static function getUriPointer($uri = ''){
        try{
            if(empty($uri)) $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'];
            if(empty($uri)) throw new Error();

            return md5($uri);
        }catch (Error $e){
            return null;
        }
    }
}
?>