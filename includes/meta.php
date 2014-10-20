<?php class_exists('Core', false) or die();

replaceMeta(Core::$params);

replaceMeta(Chapters::$current);

if(!empty(Chapters::$text)){
    replaceMeta(Chapters::$text);
}

if(Core::$item){
   replaceMeta(Core::$item);

    switch(Chapters::$current['alias']){
        case 'content':
            if(!empty(Router::$request->parsed->origin)) Chapters::$current['rootContent'] = Inner::getById(reset(Router::$request->parsed->origin), 'alias');
        break;
    }

    if(Router::$originId && Core::$item['class']){

        if(class_exists(Core::$item['class'])){

            Chapters::$current['item'] = call_user_func(array(Core::$item['class'], 'getById'), Router::$originId, 'alias');

            if(Chapters::$current['item']) replaceMeta(Chapters::$current['item']);

                switch(reset(Router::$request->parsed->origin)){
                    case 'resorts':
                        if(!empty(Chapters::$current['item']) && !Chapters::$current['item']['title']){
                            $title = 'Курорт '.Core::$params['title'];
                            replaceMeta(array('title'=>$title));
                        }
                    break;
                    case 'hotels':
                        if(!empty(Chapters::$current['item']['resort'])){
                            Chapters::$current['resort'] = Resorts::getById(Chapters::$current['item']['resort']);

                            if(!Chapters::$current['item']['title']) {
                                $title = Core::$params['title'] . '. Курорт ' . Chapters::$current['resort']['name'];
                                replaceMeta(array('title' => $title));
                            }
                        }
                    break;

            }
        } else error('class '.Core::$item['class'].' does not exist');

    }else{
        if(!Core::$item['title']){
            switch(reset(Router::$request->parsed->origin)){
                case 'hotels':
                    if(!empty($_GET['rating'])){
                        $title = Core::$params['title'].' '.$_GET['rating'].' '.Dictionary::get('stars', $_GET['rating']);
                        replaceMeta(array('title'=>$title));
                    }
                    if(!empty($_GET['resort'])){
                        Chapters::$current['resort'] = Resorts::getById($_GET['resort']);

                        $title = Core::$params['title'].' курорта '.Chapters::$current['resort']['name'];
                        replaceMeta(array('title'=>$title));
                    }
                break;
            }
        }
    }

}

if($_GET['page']) Core::$params['title'] .= '. Стр. '.(intval($_GET['page'])+1);

if($_SERVER['REQUEST_URI'] != '/') Core::$params['title'] .= ' – '.Core::$params['name'];

function replaceMeta($item = array()){
    Core::$params['title'] = !empty($item['title']) ? $item['title'] : $item['name'];
    Core::$params['metaKeywords'] = !empty($item['metaKeywords']) ? $item['metaKeywords'] : Core::$params['metaKeywords'];
    Core::$params['metaDescription'] = !empty($item['metaDescription']) ? $item['metaDescription'] : Core::$params['metaDescription'];
}
?>
