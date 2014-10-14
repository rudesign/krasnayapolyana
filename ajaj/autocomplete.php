<?php
class Ajaj{

    private $query = null;
    private $suggestions = array();

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->getSuggestions();

            echo json_encode(array(
                'query' => $_POST['query'] ,
                'suggestions' => $this->suggestions,
            ));
        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }


    private function getSuggestions(){
        try{
            switch(Router::$request->parsed->path[0]){
                default:
                    $grid = new CombinedGrid();
                break;
                case 'lessons':
                    $grid = new LessonsSliceGrid();
                break;
                case 'master-classes':
                    $grid = new MasterclassesSliceGrid();
                break;
                case 'blog':
                    $grid = new BlogSliceGrid();
                break;
                case 'marketplace':
                    $grid = new MarketplaceGrid();
                break;
            }

            if(!$grid->getAutocomplete()) throw new Error();

            // categories
            $categories = array_slice($this->sort($grid->query->result, 'categoryName'), 0, 3);
            if(!empty($categories)) $this->suggestions = array_merge($this->suggestions, $categories);

            // authors
            $users = array_slice($this->sort($grid->query->result, 'userName'), 0, 1);
            if(!empty($users)) $this->suggestions = array_merge($this->suggestions, $users);

            // items
            foreach($grid->query->result as $row){
                $this->suggestions[] = decodeHTMLEntities($row['name']);
            }

            if(empty($this->suggestions)) throw new Error();

            return true;
        }catch (Error $e){
            return false;
        }
    }

    private function sort($array = array(), $key = ''){

        $sorted = array();

        if(!empty($array) && !empty($key)){

            foreach($array as $index=>$row){

                $foundKey = array_search($row[$key], $sorted);
                if($foundKey === false) {
                    $sorted[0] = $row[$key];
                }else{
                    unset($sorted[$foundKey]);
                    array_unshift($sorted, $row[$key]);
                }
            }

            unset($array);
        }

        return $sorted;
    }

    private function check(){
        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        $_POST = trimArray($_POST);

        if(empty($_REQUEST['query'])) throw new Error('No query passed');
    }
}

$ajaj = new Ajaj();
?>