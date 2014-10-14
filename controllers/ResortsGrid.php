<?php class_exists('Core', false) or die();

class resortsGrid extends Grid{

    public function __construct(){
        //$this->showQuery = true;

        parent::__construct();

        $this->quantity = 12;
    }

    protected function modifyQuery(){
        if(!$this->query->order) $this->query->order = $this->table.'.ord ASC';
    }

    public function show(){

        if(!Router::$originId){
            if(!empty($this->grid)){

                echo '<div class="lm resorts-grid">';

                foreach($this->grid as $index=>$row){
                    $this->showGridItem($row);
                }

                echo '</div>';

                $pager = new Pager($this);

                $pager->show();

            }else{
                httpResponse(404);
            }
        }else if(Core::$item){
            $this->showItem();
        }
    }

    public function showItem(){
        echo '
        <div class="lm">
            <div class="mini-section">
                '.(Chapters::$current['item']['teaser'] ? '<div class="body section">'.Templates::parse(decodeHTMLEntities(Chapters::$current['item']['teaser']), true).'</div>' : '').'
                '.(Chapters::$current['item']['body'] ? '<div class="body section">'.Templates::parse(decodeHTMLEntities(Chapters::$current['item']['body']), true).'</div>' : '').'
                <div class="clear"></div>
            </div>';

        echo Templates::parse('{{itemGallery}}', true);

        echo Templates::parse('{{resortHotels}}', true);

        echo '</div>';
    }

    private function showGridItem($row = array()){

        $uri = '/'.implode('/', Router::$request->parsed->origin).'/'.$row['id'].'.html';

        $thumbnail = getThumbnail($row['gallery'], $row['pubTime']);

        echo '
        <div class="items">
            <div class="image"><img src="/images/small/'.$thumbnail[0].'" alt="'.($thumbnail[1] ? $thumbnail[1] : $row['name']).'" /></div>
            <div class="teaser">
                <div class="name medium mini-section"><a href="'.$uri.'">'.$row['name'].'</a></div>
                '.$row['teaser'].'
            </div>
        </div>';
    }
}
?>