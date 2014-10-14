<?php
class Captcha{
    public static $code = '';
    public static $fpath = '/img/captcha.jpg';
    private $dimensions = array(50, 26);
    
    public function __construct(){
        try{
            $this->make();
        }catch (Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

    private function make(){
        $mt = microtime(1);
        $code = substr(md5($mt), 0, 4);
        $captcha=@imagecreatetruecolor($this->dimensions[0], $this->dimensions[1]);
        $backgroundFill = @imageColorAllocate($captcha, 234, 234, 234);
        imagefill($captcha, 0, 0, $backgroundFill);
        $textColor = @imageColorAllocate($captcha, 0, 0, 0);
        imagestring($captcha, 5, intval(($this->dimensions[0]/6)), intval(($this->dimensions[1]/4)), $code, $textColor);
        if(!@imagejpeg($captcha, APP_ROOT.self::$fpath, 90)) throw new Error('Cannot write captcha file');

        $hash = md5($code);
        self::$code = substr($hash, 0, 3);
        self::$code .= substr($hash, 6, 3);
    }

    public static function check($code, $entered){
        try{
            if(empty($code)) throw new Error();
            if(empty($entered)) throw new Error();

            $checkedHash=md5($entered);
            for($i=0; $i<strlen($checkedHash); $i++){
                $computed = substr($checkedHash, 0, 3);
                $computed .= substr($checkedHash, 6, 3);
            }

            if($computed != $code) throw new Error();

            return true;
        }catch (Exception $e){
            return false;
        }
    }
}
?>