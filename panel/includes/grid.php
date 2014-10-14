<?php class_exists('Core', false) or die();

if(!empty(Router::$request->parsed->path) && is_array(Router::$request->parsed->path)){

    $aliases = Router::$request->parsed->path;
    foreach($aliases as $i=>$value){
        $aliases[$i] = capitalise(str_replace('-', '', changeCase($value)));
    }

    $aliases[] = 'Grid';
    $cname = implode($aliases);

    try{
        if(class_exists($cname)){

            $class = new $cname();

            $class->getGrid();

            $class->show();
        }
    } catch (Error $e){
        error($e);
    }
}
?>
