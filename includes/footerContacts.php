<?php class_exists('Core', false) or die();

if($row = Contacts::getById($id)){
    $phones = explode('|', $row['phone']);

    echo '<li class="extrablack sochi title">'.$row['name'].':</li>';
    if($phones){
        foreach($phones as $index=>$phone){
            if(!$index){
                echo '<li>Тел.: '.$phone.'</li>';
            }else{
                echo '<li style="padding-left:28px;">'.$phone.'</li>';
            }
        }
    }
    echo ($row['fax'] ? '<li>Факс: '.$row['fax'].'</li>' : '').'
    <li class="inline-icons">'.($row['icq'] ? '<i class="icq" style="background-image: url(http://wwp.icq.com/scripts/online.dll?icq='.str_replace('-', '', $row['icq']).'&amp;img=5);"></i><span>'.$row['icq'].'</span>' : '')
        .($row['skype'] ? '<a href="skype:'.$row['skype'].'?call" onclick="Skype.tryAnalyzeSkypeUri(\'call\', 0);"><i class="skype" id="BSkypeButton'.$row['id'].'"></i></a><span class="skype-addr">'.$row['skype'].'</span>' : '').'</li>';
}
?>