<?php class_exists('Core', false) or die();

if($row = Contacts::getById($id)){
    $phones = explode('|', $row['phone']);

    echo '<li class="extrablack sochi title">'.$row['name'].':</li>';
    if($phones){
        foreach($phones as $index=>$phone){
            if(!$index){
                echo '<li>Тел.: &nbsp;'.$phone.'</li>';
            }else{
                echo '<li style="padding-left:32px;">'.$phone.'</li>';
            }
        }
    }
    echo ($row['fax'] ? '<li>Факс: '.$row['fax'].'</li>' : '').'
    <li class="inline-icons">'.($row['icq'] ? '<span>ICQ: '.$row['icq'].'</span>' : '')
        .($row['skype'] ? '<span>Skype: '.$row['skype'].'</span>' : '').'</li>';
}
?>