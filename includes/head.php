<?php class_exists('Core', false) or die();

class Head{

    public function __construct(){
        $this->show();
    }

    private function show(){
        echo '
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <title>'.decodeHTMLEntities(Core::$params['title']).'</title>
            <meta name="description" content="'.Core::$params['metaDescription'].'">
            <meta name="keywords" content="'.Core::$params['metaKeywords'].'">
            <meta content="width=1100,maximum-scale=1.0" name="viewport">';
            self::placeCSS();
            self::placeJS();
            echo '
            <link rel="icon" type="image/ico" href="/favicon.ico" />
        </head>';
    }

    public static function placeCSS($cssSet = array()){

        if(empty($cssSet)) $cssSet = Templates::$css;

        if(!empty($cssSet) && is_array($cssSet)){
            foreach($cssSet as $css){
            echo '
            <link href="'.$css.'" rel="stylesheet" />';
            }
        }
    }

    public static function placeJS($jsSet = array(), $atTheBottom = false){


        if(empty($jsSet)){
            $jsSet = $atTheBottom ? Templates::$bottomJs : Templates::$js;
        }

        if(!empty($jsSet) && is_array($jsSet)){
            foreach($jsSet as $js){
            echo '
            <script src="'.$js.'" language="javascript" type="text/javascript"></script>';
            }
        }
    }
}

new Head();
?>