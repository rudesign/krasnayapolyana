<?php class_exists('Core', false) or die();

class NewsGrid extends Grid{

    public function __construct(){
        //$this->showQuery = true;

        parent::__construct();

        $this->quantity = 10;
    }

    protected function modifyQuery(){
        if(!$this->query->order) $this->query->order = $this->table.'.pubTime DESC';
    }

    public function show(){

        if(!Router::$originId){
            if(!empty($this->grid)){

                echo '<ul class="list_about clearfix">';

                foreach($this->grid as $index=>$row){
                    $this->showGridItem($row);
                }

                echo '</ul>';

                $pager = new Pager($this);

                $pager->show();

            }else{
                httpResponse(404);
            }
        }else if(Core::$item){
            $this->showItem();
        }
    }

    private function showGridItem($row = array(), $rootUri = ''){

        $uri = '/'.implode('/', Router::$request->parsed->origin).'/'.$row['id'].'.html';

        $thumbnail = getThumbnail($row['gallery'], $row['pubTime']);

        echo '
        <div class="items">
            <div class="left_img ld">
                <a href="'.$uri.'"><img alt="'.$thumbnail[1].'" src="/images/small/'.$thumbnail[0].'" /></a>
            </div>
            <div class="right_img ld">
                <span class="title_rim"><a href="'.$uri.'">'.$row['name'].'</a></span>
                <p>'.$row['teaser'].'</p>
                <a href="'.$uri.'">Подробнее</a>
            </div>
        </div>';
    }
}
?>