<?php class_exists('Core', false) or die();

$breadcrumbs = array();

$breadcrumbs[] = array('Главная', '/');

$path = array();

foreach(Router::$request->parsed->origin as $index=>$alias){
    if($row = Inner::getById($alias, 'alias')){
        if(Router::$originId || Router::$request->parsed->origin[($index + 1)]){
            $path[] = $row['alias'];
            $uri = '/'.implode('/', $path).'/';
        } else unset($uri);

        $breadcrumbs[] = array(($row['nameShort'] ? $row['nameShort'] : $row['name']), ($uri ? $uri : false));
    }
}

echo '<div class="black serif i">';
    foreach($breadcrumbs as $index=>$item){
        if($index) echo ' > ';
        echo ($item[1] ? '<a href="'.$item[1].'">' : '').$item[0].($item[1] ? '</a>' : '');
    }
echo '</div>';
?>