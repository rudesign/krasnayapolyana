<?php

class Db extends Settings{

    public static $table = '';
    public static $primary = 'id';
    public static $conn = false;

    public function __construct(){
        parent::__construct();

        if(!self::$conn) self::setConnection();
    }

    public static function get(&$query = null, $options = array()){
        try{
            if(empty($query)) throw new Error();

            if(!$query->get()) throw new Error();

            try{
                $result = array();

                if($query->flat) throw new Error();
                if(!$options['key']) throw new Error();

                foreach($query->result  as $item){
                    $result[$item[$options['key']]][] = $item;
                }

            }catch (Error $e){
                $result = $query->result;
            }

            return $result;
        }catch (Error $e){
            return array();
        }
    }

    public static function getById($id = 0, $key = '', $visibleOnly = true){
        try{
            if(empty(self::$table)) throw new Error();

            if(empty($key)) $key = self::$primary;

            $query = new Query(self::$table);

            $query->key = $key;
            $query->id = $id;

            $query->flat = true;
            $query->visibleOnly = $visibleOnly;

            if(!$result = $query->getById()) throw new Error();

            return $result;
        }catch (Error $e){
            return array();
        }
    }

    public static function setConnection($newLink = false){

        // check if connection data defined
        if(!Settings::$data->dbHost) throw new Error('No DB host defined');
        if(!Settings::$data->dbName) throw new Error('No DB name defined');
        if(!Settings::$data->dbUser) throw new Error('No DB user defined');
        if(!Settings::$data->dbPassword) throw new Error('No DB password defined');
        if(!Settings::$data->dbEncoding) throw new Error('No DB encoding defined');

        // set a connection
        if(!self::$conn = @mysql_connect(Settings::$data->dbHost, Settings::$data->dbUser, Settings::$data->dbPassword, $newLink)){
            throw new Error('Cannot set a DB connection');
        }else if(!@mysql_select_db(Settings::$data->dbName)){
            throw new Error('Cannot select any DB');
        }else{
            @ini_set("default_charset", Settings::$data->dbEncoding);
            mysql_query("SET NAMES ".Settings::$data->dbEncoding);
            mysql_query("SET collation_connection = '".Settings::$data->dbEncoding."_general_ci'");
        }
    }
}
?>