<?php class_exists('Core', false) or die();

class Sitemap{

    private $uriSet = array();

    public function __construct(){

        $this->showBranch('Стартовая страница сайта');
        //$this->showBranch('Новости', '/news/');

        $this->showBranch('', '', 'inner');
    }

    private function showBranch($name, $rootUri = '/', $segment = ''){

        $uri = $rootUri;

        echo '
        <ul>
            <li><a href="'.$uri.'">'.$name.'</a></li>
        </ul>';

        switch($segment){
            default:
            break;

            case 'inner':
                $query = Inner::set();
                $query->condition = 'sitemap > 0';
                $query->order = 'ord ASC';

                if($set = Inner::get($query, array('key'=>'parent'))){
                    $this->showContentTree($set, 0);
                }
            break;
        }
    }

    private function showContentTree($set, $parent){


        $rows = $set[$parent];

        if(!empty($rows)){
            echo '<ul>';
            foreach($rows as $row){
                if(!$row['parent']) $this->uriSet = array();

                $this->uriSet[] = $row['alias'];

                echo '<li>';

                    echo '<a href="/'.implode('/', $this->uriSet).'/">'.$row['name'].'</a>';

                    if(!empty($set[$row['id']])){ $this->showContentTree($set, $row['id']); }else{ array_pop($this->uriSet); }

                    switch($row['alias']){
                        case 'resorts':
                            $this->showResorts();
                        break;
                    }

                echo '</li>';
            }
            echo '</ul>';
        }
    }


    private function showResorts(){
        try{
            if(!$rows = Resorts::getWithHotels()) throw new Error;

            foreach($rows as $resort){
                echo '
                <ul>
                    <li>
                        <a href="/resorts/'.$resort[0]['resortId'].'.html">'.$resort[0]['resortName'].'</a>
                        <ul>';
                        foreach($resort as $hotel){
                            $uri = '/hotels/'.$hotel['id'].'.html';
                            echo '<li><a href="'.$uri.'">'.$hotel['name'].'</a></li>';
                        }
                        echo '</ul>
                    </li>
                </ul>';
            }
        }catch (Error $e){}
    }
}

echo '<div class="black sitemap">';

new Sitemap();

echo '</div>';
?>