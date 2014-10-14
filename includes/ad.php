<?php class_exists('Core', false) or die();

if(!empty($id)){
    if($row = Ads::getById($id)){
        if(!empty($row['body'])){
            echo stripslashes($row['body']);
        }else if(!empty($row['attachments'])){
            $attachments = getExplodedGroup($row['attachments']);
            $ad = reset($attachments);

            if($row['uri']) echo '<a href="'.$row['uri'].'" target="'.(!$row['target'] ? '_self' : '_blank').'">';
            echo '<img src="/images/'.$ad[0].'" alt="'.$ad[1].'" />';
            if($row['uri']) echo '</a>';
        }

    }
}
?>