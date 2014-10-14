<?php class_exists('Core', false) or die();

class Head{

    public function __construct(){
        $this->show();
    }

    private function show(){
        echo '
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta content="width=1050,maximum-scale=1.0" name="viewport">
            <meta name="yandex-verification" content="4586fa1fb697cd5e" />
            <title>'.decodeHTMLEntities(Core::$params['title']).'</title>
            <meta name="description" content="'.Core::$params['metaDescription'].'">
            <meta name="keywords" content="'.Core::$params['metaKeywords'].'">';
            $this->placeOGMarkup();

            $this->placeCSS();

            $this->placeJS();
            echo '
            <link rel="icon" type="image/ico" href="/favicon.ico" />
        </head>';
    }

    private function placeCSS(){
        if(!empty(Templates::$css) && is_array(Templates::$css)){
            foreach(Templates::$css as $css){
                echo '
                <link href="'.$css.'" rel="stylesheet" />';
            }
        }
    }

    private function placeJS(){
        if(!empty(Templates::$js) && is_array(Templates::$js)){
            foreach(Templates::$js as $js){
                echo '
                <script src="'.$js.'" language="javascript" type="text/javascript"></script>';
            }
        }
    }

    private function placeOGMarkup(){
        $ogData['title'] = Core::$params['title'];
        $ogData['image'] = array();
        if(Core::$params['metaDescription']) $ogData['description'] = Core::$params['metaDescription'];

        if(Router::$request->parsed->path[0]){
            if(Core::$item){
                switch(Router::$request->parsed->path[0]){
                    case 'marketplace':
                    case 'materials':
                    case 'blog':
                    case 'master-classes':

                        // title
                        if(Lessons::isSweet(Core::$item)){
                            $ogData['title'] = 'Разыгрывается приз! '.Core::$item['name'];
                        }else{
                            $ogData['title'] = Core::$item['name'].(Core::$item['productionType'] == 1 ? '. Ручная работа' : '');
                        }

                        // thumbnail
                        if(Core::$item['thumbnail']){
                            $gallery = getExplodedGroup(Core::$item['thumbnail']);

                            foreach($gallery as $item){
                                $ogData['image'][] = '/images/small/'.$item[0];
                            }
                        }

                        // description
                        if(Core::$item['teaser'] || Core::$item['description']){
                            $ogData['description'] = (Core::$item['metaDescription'] ? getLimited(Core::$item['metaDescription'], 180, true) : getLimited(strip_tags(Core::$item['teaser']), 180, true));
                        }
                    break;
                    case 'people':
                        // gallery
                        if(Core::$item['gallery']){
                            $gallery = getExplodedGroup(Core::$item['gallery']);

                            foreach($gallery as $item){
                                $ogData['image'][] = '/images/small/'.$item[0];
                            }
                        }

                        // description
                        if(Core::$item['teaser']){
                            $ogData['description'] = getLimited(Core::$item['teaser'], 180, true);
                        }
                    break;

                    case 'online':
                        // thumbnails
                        if(Core::$item['thumbnail']){
                            $gallery = getExplodedGroup(Core::$item['thumbnail']);

                            foreach($gallery as $item){
                                $ogData['image'][] = '/images/small/'.$item[0];
                            }
                        }

                        // gallery
                        if(Core::$item['gallery']){
                            $galleries = explode(' $', Core::$item['gallery']);

                            foreach($galleries as $gallery){
                                $gallery = getExplodedGroup($gallery);

                                foreach($gallery as $item){
                                    $ogData['image'][] = '/images/small/'.$item[0];
                                }
                            }
                        }

                        // description
                        if(Core::$item['teaser']){
                            $ogData['description'] = getLimited(Core::$item['teaser'], 180, true);
                        }
                    break;
                }
            // if !item
            }else{
                switch(Router::$request->parsed->path[0]){
                    case 'combined':
                        if(Chapters::$current['alias'] == 'slice'){
                            // shop
                            if(Chapters::$current['author']){
                                $ogData['title'] = 'Магазин мастера '.Chapters::$current['author']['name'];

                                // thumbnail
                                if(Chapters::$current['author']['gallery']){
                                    $gallery = getExplodedGroup(Chapters::$current['author']['gallery']);

                                    foreach($gallery as $item){
                                        $ogData['image'][] = '/images/small/'.$item[0];
                                    }

                                    if($items = Lessons::getByAuthor(Chapters::$current['author']['id'], array(4))){
                                        foreach($items as $row){
                                            if($thumbnail = getThumbnail($row['thumbnail'], $row['pubTime'])){
                                                $ogData['image'][] = '/images/small/'.$thumbnail[0];
                                            }
                                        }
                                    }
                                }

                                // description
                                if(Chapters::$current['author']['shopDescription'] || Chapters::$current['author']['teaser']){
                                    $ogData['description'] = (Chapters::$current['author']['shopDescription'] ? getLimited(Chapters::$current['author']['shopDescription'], 180) : getLimited(Chapters::$current['author']['teaser'], 180));
                                }
                            }
                        }
                    break;
                }
            }
        }

        echo '
        <meta property="og:site_name" content="'.Core::$params['name'].'" />';

        if(!empty($ogData['title'])) echo '
        <meta property="og:title" content="'.$ogData['title'].'" />';

        if(empty($ogData['image']) && !Router::$request->parsed->path[0]) $ogData['image'][] = '/img/logo128.png';
        foreach($ogData['image'] as $item){
            echo '
            <meta property="og:image" content="http://'.$_SERVER['HTTP_HOST'].$item.'" />';
        }

        if(!empty($ogData['description'])) echo '
        <meta property="og:description" content="'.$ogData['description'].'" />';
    }
}

new Head();
?>