<?php

class Thumb{
    public $config = array();
    
    function __construct(){
        try{
            require_once APP_ROOT.'/getThumb/getthumb.class.php';

            $this->config['keep_ratio'] = true;
            $this->config['crop'] = true;
            
            return true;
        }catch (Error $e){
            return false;
        }
    }
    
    public function create($absSource, $relDestination, $w, $h, $watermark = false, $sx = null, $sy = null, $sw = null, $sh = null){
        try{
            $gt = new Getthumb();

            $this->config['width'] = $w;
            $this->config['height'] = $h;
            $this->config['src_w'] = (!is_null($sw) ? $sw : false);
            $this->config['src_h'] = (!is_null($sh) ? $sh : false);
            $this->config['src_x'] = (!is_null($sx) ? $sx : false);
            $this->config['src_y'] = (!is_null($sy) ? $sy : false);

            if($watermark) {
                $this->config['overlay'] = APP_ROOT.'/images/watermark.png';
                $this->config['overlay_align'] = 'right';
            }

            if(!$image = $gt->get($absSource, $this->config)) throw new Error();

            if(!$fres = fopen(APP_ROOT.$relDestination, 'w')) throw new Error();

            if(!fwrite($fres, $image)) throw new Error();

            fclose($fres);

            return true;
        }catch (Error $e){
            //echo $e->getMessage();
            return false;
        }
    }
}
?>