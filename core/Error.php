<?php

class Error extends Exception{

    public function __construct($message = "", $log = false, $code = 0){

        parent::__construct($message, $code);

        if(!empty(Settings::$data->debug)) echo 'Line '.$this->getLine().' at '.$this->getFile().'<br />';

        if($log) self::log($message, $this->getFile(), $this->getLine());
    }

    public static function log($message = '', $file = '', $line = 0){
        if($message = self::getLogString($message, $file, $line)){

            $fPath = APP_ROOT.'/log/'.date('dmy\.\l\o\g');

            if($handle = @fopen($fPath, 'a')){

                @fwrite($handle, $message);

                @fclose($handle);
            }
        }

    }

    private static function getLogString($message = '', $file = '', $line = 0){
        $logString = array(date('d.m.y \a\t H:i:s'));

        if(!empty($message) || !empty($file) || !empty($line)){
            if(!empty($file)) $logString[] = ' file: '.$file;
            if(!empty($line))$logString[] = ' line: '.$line;
            if(!empty($message))$logString[] = ' message: '.$message;
        }

        return implode(', ', $logString)."\n";
    }
}
?>