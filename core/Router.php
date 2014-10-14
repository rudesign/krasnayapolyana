<?php

class Router extends Error{

    // str current relative URI of the document
    public static $uri = '';
    // str current absolute URI of the document
    public static $absUri = '';
    // obj parsed request
    public static $request = array();
    // null id picked from the uri
    public static $id = null;
    public static $originId = null;

    public function __construct(){
        $this->routerSettingsPath = APP_ROOT.'/settings/router.php';

        self::route();
    }

    public static function route($uri = ''){

        self::$request = array();

        $fullHost = !empty($_SERVER['HTTPS']) ? 'https' : 'http'.'://'.$_SERVER['HTTP_HOST'];

        // extract relative
        $uri = str_replace($fullHost, '', $uri);

        if(!empty($uri)) self::$uri = $uri;

        if(empty(self::$uri)) self::$uri = $_SERVER['REQUEST_URI'];

        // set absolute
        self::$absUri = $fullHost.self::$uri;

        if(empty(self::$absUri)) throw new Error('Could not locate the document', true);

        // classic parsed uri
        self::$request['parsed']['uri'] = @parse_url(self::$absUri);

        // classic parsed origin uri
        self::$request['parsed']['origin'] = @parse_url($fullHost.$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);

        // host segments divided by dot
        self::$request['parsed']['host'] = explode('.',  self::$request['parsed']['uri']['host']);

        // 3rd level domain name
        self::$request['subdomain'] = count(self::$request['parsed']['host']) > 2 ? reset(self::$request['parsed']['host']) : null;

        // get segments - parsed request path divided by /
        if(!empty(self::$request['parsed']['uri']['path'])) self::$request['parsed']['path'] = array_values(array_filter(explode('/',  self::$request['parsed']['uri']['path'])));

        // get segments - parsed request path divided by /
        if(!empty(self::$request['parsed']['origin']['path'])) {
            self::$request['parsed']['origin'] = array_values(array_filter(explode('/',  self::$request['parsed']['origin']['path'])));
            self::$originId = self::filterId(self::$request['parsed']['origin']);
        }

        // clean segments if .php
        if(!empty(self::$request['parsed']['path'])){
            foreach(self::$request['parsed']['path'] as $index=>$segment){
                if(preg_match('/\.php/i', $segment)) unset(self::$request['parsed']['path'][$index]);
            }
        }

        // set GET vars from the pattern
        self::getVarsFromPath();

        // set GET from the query
        if(!empty(self::$request['parsed']['uri']['query'])){
            parse_str(self::$request['parsed']['uri']['query'], self::$request['parsed']['query']);
            if(!empty(self::$request['parsed']['query'])){
                foreach(self::$request['parsed']['query'] as $key=>$value){
                    $_GET[$key] = $_REQUEST[$key] = $value;
                }
            }
        }

        // convert to object
        self::$request = toObject(self::$request);

        // extract id from given uri
        self::$id = self::filterId(self::$request->parsed->path);
    }

    private static function getVarsFromPath(){

        @require(APP_ROOT.'/settings/router.php');

        if(!empty($pointer)){

            $segments = array();
            $found = false;

            foreach(self::$request['parsed']['path'] as $item){
                if(!empty($pointer[$item])) {
                    $found = true;
                    $pointer = $pointer[$item];
                    if(!is_array($pointer)){
                        $segments[] = $pointer;
                    }else{
                        $segments[] = $item;
                    }
                }else{
                    if(is_array($pointer)){
                        $varName = array_shift($pointer);
                        if(!empty($varName) && !is_array($varName)) {
                            $found = true;
                            $_GET[$varName] = $item;
                        }else $found = false;
                    }
                }
            }

            if($found) self::$request['parsed']['path'] = $segments;
        }
    }

    public static function filterId(&$path){
        $ext ='.html';
        $id = 0;

        if(!empty($path)){
            $point = end($path);

            if(strpos($point, $ext) !== false){
                $id = str_replace($ext, '', $point);
                array_pop($path);
            }
        }

        return $id;
    }

    public static function redirect($uri = '/', $httpResponseCode = 301){
        if($uri != $_SERVER['REQUEST_URI']);

        if(empty($httpResponseCode)) $httpResponseCode = 301;

        switch($httpResponseCode){
            default:
                header ('HTTP/1.1 301 Moved Permanently');
            break;
            case 302:
                header ('HTTP/1.1 302 Found');
            break;
        }

        header('Location: '.$uri, null, $httpResponseCode);
        die();
    }
}
?>