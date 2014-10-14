<?php class_exists('Core', false) or die();

if(is_array(Router::$request->parsed->path)){
    $cname = getGridCName();

    //if(empty(Router::$request->parsed->path)) $cname = 'Main'.$cname;

    try{
        if(class_exists($cname)){
            $class = new $cname();

            if(!Router::$originId) $class->getGrid();

            $class->show();
        }
    } catch (Error $e){
        error($e);
    }
}

function getGridCName(){
    if(is_array(Router::$request->parsed->path)){

        $aliases = Router::$request->parsed->path;

        foreach($aliases as $i=>$value){
            $value = str_replace('-', '', $value);
            $aliases[$i] = capitalise(changeCase($value));
        }
        $aliases[] = 'Grid';
        $cname = implode($aliases);
    }

    return isset($cname) ? $cname : '';
}
?>
