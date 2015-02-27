<?php class_exists('Core', false) or die();

class Search{
    private $query = '';
    private $i = 0;
    private $found = false;

    public function __construct(){
        if($this->query = encodeHTMLEntities($_GET['q'])){
            if(mb_strlen($this->query, 'utf-8') > 2){
                $this->display();
            }
        }

        if(!$this->found) $this->notFound();
    }

    private function get($table = ''){
        try{
            if(empty($table)) throw new Error;

            $query = new Query($table);
            $query->condition = "searchIndex LIKE '%".$this->query."%'";
            if(!$rows = Db::get($query)) throw new Error;

            $this->found = true;

            return $rows;
        }catch (Error $e){
            return array();
        }
    }

    private function display(){
        if($rows = $this->get('resorts')) $this->displayResults($rows, '/resorts/', 'Курорт');
        if($rows = $this->get('hotels')) $this->displayResults($rows, '/hotels/', 'Отель');
    }

    private function getSnippet($string = ''){
        try{
            if(empty($string)) throw new Error;

            $pos = strpos($string, $this->query);
            if($pos >= 60){ $pos = 60; }else{ $pos = 0; }
            $snippet = mb_substr($string, $pos, 200, 'utf-8');
            $snippet = str_replace($this->query, '<b>'.$this->query.'</b>', $snippet);

            return $snippet;
        }catch (Error $e){
            return '';
        }
    }

    private function displayResults($rows = array(), $baseUri = '', $description = ''){
        if(!empty($rows)){
            foreach($rows as $row){
                $uri = $baseUri.$row['alias'].'.html';


                if(!$this->i) echo '<div class="small section">По запросу <b class="arial i">'.$this->query.'</b> найдено:</div>';
                $this->i++;

                echo '
                <div class="items">
                    <i class="pp">'.$this->i.'.</i>
                    <div class="mini-section"><strong>'.($description ? $description.' ' : '').'<a href="'.$uri.'">'.$row['name'].'</a></strong></div>
                    '.$this->getSnippet($row['searchIndex']).'
                </div>';
            }
        }
    }

    private function notFound(){
        $message = empty($this->query) ? 'Пустой запрос' : 'По запросу <b class="arial i">'.$this->query.'</b> ничего не найдено';
        if(mb_strlen($this->query, 'utf-8') <= 2) $message = 'Длина запроса должна быть более 2 символов';

        echo '<div class="small">'.$message.'</div>';
    }
}

$search = new Search();
?>