<?php class_exists('Core', false) or die();

if($row = Contacts::getById($id)){
    echo '
    <li class="blue">'.$row['name'].':</li>
    '.($row['phone'] ? '<li>Тел.: '.$row['phone'].'</li>' : '').'
    '.($row['fax'] ? '<li>Факс: '.$row['fax'].'</li>' : '').'
    <li class="inline-icons">'.($row['icq'] ? '<i class="icq"></i><span>'.$row['icq'].'</span>' : '')
        .($row['skype'] ? '<a href="skype:'.$row['skype'].'?call" onclick="Skype.tryAnalyzeSkypeUri(\'call\', 0);"><i class="skype" id="SkypeButton'.$row['id'].'"></i></a><span class="skype-addr">'.$row['skype'].'</span>' : '').'</li>';
}
?>