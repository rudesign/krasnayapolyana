<?php

class Users extends BaseUsers{

    public function __construct(){
        parent::__construct();
    }

    public static function redirectUnauthorised(){
        if(!self::$current){

        }
    }
}
?>