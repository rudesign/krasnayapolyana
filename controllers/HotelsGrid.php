<?php class_exists('Core', false) or die();

class HotelsGrid extends Grid{

    public function __construct(){
        //$this->showQuery = true;

        parent::__construct();

        $this->quantity = 12;
    }

    public function modifyQuery(){

        $this->query->tables[0] = $this->table;
        $this->query->join = 'resorts';
        $this->query->compare = 'resorts.id = '.$this->table.'.resort';
        $this->query->fields = $this->table.'.*, resorts.name as resortName';

        $condition = array();
        if($_GET['rating']) $condition[] = "hotels.rating = '".$_GET['rating']."'";
        if($_GET['resort']) $condition[] = "resorts.id = '".$_GET['resort']."'";
        if(!empty($condition)){
            $this->query->condition = implode(' AND ', $condition);
        }

        if(!$this->query->order) $this->query->order = $this->table.'.pubTime DESC';
    }

    public function show(){

        if(!Router::$originId){
            if(!empty($this->grid)){

                echo '<div class="lm hotels-grid">';

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

    public function showItem($shiftFirst = true){
        echo '
        <div class="lm">
            <div class="mini-section">
                '.(Chapters::$current['item']['teaser'] ? '<div class="body section">'.Templates::parse(decodeHTMLEntities(Chapters::$current['item']['teaser']), true).'</div>' : '').'
                '.(Chapters::$current['item']['body'] ? '<div class="body section">'.Templates::parse(decodeHTMLEntities(Chapters::$current['item']['body']), true).'</div>' : '').'
                <div class="clear"></div>
            </div>';

        echo Templates::parse('{{hotelFeatures}}', true);

        echo Templates::parse('{{itemGallery}}', true);

        echo '</div>';
    }

    public function showGridItem($row = array()){

        $uri = '/hotels/'.$row['id'].'.html';

        $thumbnail = getThumbnail($row['gallery'], $row['pubTime']);

        echo '
        <div class="items">
            <div class="white blue-bg price">от <span>'.$row['price'].'</span> руб. в сутки</div>
            <div class="small teaser">
                <a href="'.$uri.'" alt=""><img src="/images/small/'.$thumbnail[0].'" alt="'.$thumbnail[1].'" /></a>
                <dl class="b"><a href="'.$uri.'">'.$row['name'].'</a></dl>
                <dl class="complex-name"><i class="cableway"></i> '.$row['resortName'].'</dl>
            </div>
            <ul class="rating">';
                for($k=0;$k<$row['rating'];$k++){ echo '<li></li>'; }
                echo '
            </ul>
        </div>';
    }
}
?>