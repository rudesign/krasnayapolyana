<?php
class Ajaj{
    private $uri = '';

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            echo json_encode(array(
                'uri'=>$this->uri,
            ));
        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        switch(Router::$request->parsed->path[0]){
            default:
                if(!Lessons::like($_POST['id'])) throw new Error('Ошибка при обновлении рейтинга');
                break;
            case 'online':
                if(!Online::like($_POST['id'])) throw new Error('Ошибка при обновлении рейтинга');
                break;
        }
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();
    }
}

$ajaj = new Ajaj();
?>