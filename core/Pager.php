<?php class_exists('Core', false) or die();

class Pager{
    protected $pagesInSet = 9;
    protected $showNav = true;
    protected $showAltNav = false;

    protected $pagerCSSClass = 'pager';
    protected $altNavCSSClass = 'alt-nav';

    protected $gridObj = array();
    private $set = 0;
    private $pages = 0;
    private $startPage  = 0;

    public function __construct(&$gridObj){
        $this->gridObj = $gridObj;

        $this->setup();
    }

    private function setup(){
        // calculate count of pages
        $this->pages = intval($this->gridObj->count / $this->gridObj->quantity);

        // add one more page if there are elements
        if($this->gridObj->count % $this->gridObj->quantity) $this->pages++;

        // calculate current set
        $this->set = intval($_SESSION['page']/$this->pagesInSet);

        // page to start
        $this->startPage = $this->set*$this->pagesInSet;
    }

    public function show(){

        if($this->pages > 1){
            if($this->showNav){

                if($_SESSION['page'] <= $this->pages){

                    echo '<ul class="'.$this->pagerCSSClass.'">';

                    if($this->set) $this->constructPrevSetItem(($this->startPage-1));

                    for($i = $this->startPage; $i < ($this->startPage + $this->pagesInSet); $i++){
                        if($i < $this->pages) $this->constructPageItem($i);
                    }

                    if($i < $this->pages) $this->constructNextSetItem($i);

                    echo '</ul>';
                }
            }

            /*
            if($this->showAltNav){
                if((($_SESSION['page']+1) <= $this->pages) || $this->set){

                    echo '<ul class="'.$this->altNavCSSClass.'">';

                    if($_SESSION['page']) $this->constructPrevSetItem();

                    if(($_SESSION['page']+1) < $this->pages) $this->constructNextSetItem();

                    echo '</ul>';
                }
            }
            */
        }
    }

    protected function constructPageItem($i){
        $uri = varExclude(null, 'page', $i);
        echo '<a href="'.$uri.'"'.($_SESSION['page'] == $i ? ' class="active"' : '').'>'.($i+1).'</a>';
    }

    protected function constructPrevSetItem($i = 0){
        $uri = varExclude(null, 'page', $i);
        echo '<a href="'.$uri.'" class="alt prev"><img alt="" src="/img/left_arr.png"></a>';
    }

    protected function constructNextSetItem($i = 0){
        $uri = varExclude(null, 'page', $i);
        echo '<a href="'.$uri.'" class="alt next"><img alt="" src="/img/right_arr.png"></a>';
    }
}
?>