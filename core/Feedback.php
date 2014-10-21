<?php

class Feedback extends Db{

    public static function set(){
        self::$table = 'feedback';

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

    public static function create($data = array()){
        try{
            $query = self::set();

            $query->values = array_merge(array(
                'createdTime' => time(),
                'createdBy' => (Users::$current ? Users::$current['id'] : 0),
            ), $data);

            if(!$id = $query->write()) throw new Error();

            return $id;
        }catch (Error $e){
            return false;
        }
    }

    public static function submitRemote( $data, $opinion = 0 )
    {
        try {
            if(empty($data)) throw new Error;
            if(!Settings::$data->rsAddr) throw new Error;
            if(!Settings::$data->rsId) throw new Error;
            if(!Settings::$data->rsSecret) throw new Error;

            $parsedUri = parse_url(Settings::$data->rsAddr);

            // fuck that shit
            if(!empty($data)){
                $line = 0;
                foreach($data as $i=>$item){
                    if(is_array($item)){
                        foreach($item as $k=>$value){
                            $data[$i][$k] = iconv('utf8', 'cp1251', decodeHTMLEntities($value));

                        }
                        $data[$i]['line'] = $line;
                        $line++;
                    }
                }
            }

            $data = serialize($data);

            $data1 = $opinion ? "opinion=1&" : '';
            $data = $data1 . "site_id=".Settings::$data->rsId."&secret=".Settings::$data->rsSecret."&data=" . urlencode($data);

            if(!$fp = fsockopen($parsedUri["host"], 80, $errno, $errstr, 10)) throw new Error;

            $out = "POST " . $parsedUri["path"] . " HTTP/1.0\n";
            $out .= "Host: " . $parsedUri["host"] . "\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\n";
            $out .= "Content-Length: " . strlen($data) . "\n\n";
            $out .= $data . "\n\n";

            fputs($fp, $out);

            $s_id = 0;

            while (!feof($fp)) {
                $str = fgets($fp, 1000);
                //echo $str.'<br />';
                if (preg_match("/siteorder_id=([[:digit:]]+)/i", $str, $F)) $s_id = $F[1];
            }

            fclose($fp);

            return $s_id;
        }catch (Error $e){
            //echo 'Error at line '.$e->getLine();
            return false;
        }
    }
}
?>