<?php
class Templates extends Users{

    public static $viewportType = 'desktop';
    public static $viewportWidth = 0;
    public static $templatesDirs = array();
    public static $includesDirs = array();
    public static $css = array();
    public static $js = array();
    public static $bottomJs = array();

    public function __construct(){
        parent::__construct();

        self::$css = array(

        );
        self::$js = array(

        );
        self::$bottomJs = array(

        );
    }

    public static function parse($source = '', $isHTML = false){

        if(empty(self::$templatesDirs)) self::setDefaultTemplatesLocation();
        if(empty(self::$includesDirs)) self::setDefaultIncludesLocation();

        try{
            $html = self::getTemplate($source, $isHTML);

            $html = explode('{{', $html);

            $parsed = array_shift($html);

            foreach($html as $part){
                $HTMLtoProcess = explode('}}', $part);

                if($incBlock = array_shift($HTMLtoProcess)){

                        $incBlock = explode(';', $incBlock);
                        $fileToProcess = array_shift($incBlock);

                        // assign vars
                        if(!empty($incBlock)){
                            foreach($incBlock as $var){
                                $var = explode(':', $var);
                                $$var[0] = $var[1];
                            }
                        }

                        $found = false;
                        foreach(self::$includesDirs as $dir){
                            $fname = $dir.'/'.$fileToProcess.'.php';

                            if(file_exists($fname)){
                                $found = true;

                                ob_start();
                                include $fname;
                                $parsed .= ob_get_clean();

                                break;
                            }
                        }

                        if(!$found){
                            throw new Error('No file to process '.$fileToProcess);
                        }else{
                            // clear vars
                            if(!empty($incBlock)){
                                foreach($incBlock as $var){
                                    $var = explode(':', $var);
                                    unset($$var[0]);
                                }
                            }
                        }
                // chapter-depended template
                }else{
                    // get cached
                    if(!$html = Cache::getCached()){

                        $html = self::parse(Chapters::$current['template']);

                        // store parsed html in the cache
                        Cache::store($html);
                    }

                    $parsed .= $html;
                }

                if(!empty($HTMLtoProcess)) $parsed .= implode('', $HTMLtoProcess);
            }

        }catch (Exception $e){
            error($e);
        }

        return $parsed;
    }

    protected function getTemplate($source = '', $isHTML = false){
        try{
            // if file name as a source
            if(!$isHTML){
                foreach(self::$templatesDirs as $dir){
                    $fname = $dir.'/'.$source.'.html';

                    if(file_exists($fname)){
                        $html = file_get_contents($fname);
                        break;
                    }
                }
            }else $html = $source;

            if(empty($html)) throw new Error('Cannot get template'.(!$isHTML ? ' '.$source : ''));
        }catch (Exception $e){
            error($e);
        }

        return $html;
    }

    private static function setDefaultTemplatesLocation(){
        self::$templatesDirs = array(
            APP_ROOT.'/themes/'.Core::$theme.'/templates',
            APP_ROOT.'/templates',
        );
    }

    public static function setDefaultIncludesLocation(){
        self::$includesDirs = array(
            APP_ROOT.'/themes/'.Core::$theme.'/includes',
            APP_ROOT.'/includes',
        );
    }
}