<?php class_exists('Core', false) or die();

class mmenu{
    private $structure = array();
    public $parent = 0;

    public function __construct(){
        $this->getStructure();
    }

    private function getStructure(){
        $query = new Query('mmenu');
        $query->order = 'ord ASC';

        if($query->get()){
            foreach($query->result as $row){
                $this->structure[$row['parent']][$row['id']] = $row;
            }
        }
    }

    /*
     *
     *              <ul class="menu_header clearfix">
                        <li class="active_mnh"><a href="#">Об отеле</a></li>
                        <li><a href="#">Цены и туры</a></li>
                        <li><a href="#">Номера</a></li>
                        <li><a href="#">Бизнес-туризм</a></li>
                        <li><a class="dp_width" href="#">Бронирование</a></li>
                    </ul><!--menu_header-->
     */

    public function show(){
        if(array_key_exists($this->parent, $this->structure)){
            echo '<div class="black menu section">';
            foreach($this->structure[$this->parent] as $id => $item){
                if(!($item['authorisedOnly'] || $item['unauthorisedOnly']) || (Users::$current && $item['authorisedOnly']) || (!Users::$current && $item['unauthorisedOnly'])){
                    $active = (
                        ($item['alias'] && ($item['alias'] == changeCase(reset(Router::$request->parsed->origin)))) ||
                        (empty(Router::$request->parsed->origin) && (!$item['uri'] && !$item['path'] && !$item['alias']))
                    ) ? ' class="active"' : '';

                    $uri = $item['uri'] ? $item['uri'] : ($item['alias'] ? '/'.$item['alias'].'/' : '/');

                        /*
                        if(count(Router::$request->parsed->path) > 0){
                            $fname = DOCUMENT_ROOT.'/includes/mmenuDropdown'.$id.'.php';
                            if(file_exists($fname)) require_once $fname;
                        }
                        */

                        echo '<a href="'.$uri.'"'.($item['nofollow'] ? ' rel="nofollow"' : '').$active.($item['uri'] ? ' target="_blank"' : '').'><span>'.$item['name'].'</span></a>';

                    /*
                    if(array_key_exists($id, $this->structure) && count($this->structure[$id])){

                        $this->parent = $id;

                        $this->show();
                    }
                    */
                }
            }
            echo '</div>';
        }
    }
}

$mmenu = new mmenu();

$mmenu->show();
?>