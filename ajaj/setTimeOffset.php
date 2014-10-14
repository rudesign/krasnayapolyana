<?php
class Ajaj{
    private $uri = '';

    public function __construct(){

        try{

            @include 'init.php';

            echo json_encode(array(
                'uri'=>$this->uri,
                'html'=>$this->html,
            ));

            Core::setUserTimeOffset($_REQUEST['uDiff']);

        } catch(Error $e){
            error($e);
        }
    }
}

$ajaj = new Ajaj();
?>