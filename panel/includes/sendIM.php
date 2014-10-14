<?php class_exists('Core', false) or die();

$message = '&nbsp;';

if(!empty($_POST['body'])){
    $message = 'Отправлено';

    $db = Users::configureDb();
    $db->fields = 'id';
    $db->condition = 'id != '.Users::$current['id'];

    if($users = Users::get($db)){
        $i = 0;
        foreach($users as $row){
            if(Messages::send($row['id'], $_POST['body'], Users::$current['id'])) $i++;
        }

        $message .= ' '.$i.' из '.count($users);

    }else $message = 'Нет пользователей для отправки сообщения';
}

echo $message.'<div class="d15"></div>';
?>