<?php
class BaseGrid{

    public $query = null;
    public $showQuery = false;
    public $grid = array();
    public $table = '';
    public $primary = '';
    public $quantity = 25;
    public $count = 0;
    public $connectionModified = false;

    protected function __construct(){
        $this->getTable();

        if(!$this->query = new Query($this->table)) throw new Error();
    }

    protected function getTable(){
        if(!(Chapters::$current['table']['name'] && Chapters::$current['table']['primary'])) throw new Error();

        $this->table = Chapters::$current['table']['name'];
        $this->primary = Chapters::$current['table']['primary'];
    }

    public function get(){
        try{
            if(empty($this->table)) throw new Error();
            if(empty($this->primary)) throw new Error();

            if(empty($this->query)) throw new Error();
            if(!$this->grid = $this->query->get()) throw new Error();

            if($this->showQuery) echo $this->query->string;

            return true;
        }catch (Error $e){
            if($this->showQuery) echo $this->query->string;

            return false;
        }
    }

    public function getGrid(){
        try{

            if(empty($this->table)) throw new Error();
            if(empty($this->primary)) throw new Error();
            if(!$this->query = new Query($this->table)) throw new Error();

            $this->modifyQuery();

            // getting all items count
            $this->query->fields = 'COUNT(*) AS count';
            $this->query->flat = true;

            if($result = $this->query->get()){
                $this->count = $result['count'];

                // getting the list
                $this->getStartPage();

                $this->query->flush();

                $this->modifyQuery();

                $this->query->flat = false;

                if($_SESSION['page']){
                    $this->query->limit = ($_SESSION['page']*$this->quantity).','.$this->quantity;
                }else $this->query->limit = '0,'.$this->quantity;

                $this->grid = $this->query->get();
            }

            if($this->showQuery) echo $this->query->string;

            unset($this->query);

            return true;
        }catch (Error $e){
            if($this->showQuery) echo $this->query->string;

            return false;
        }
    }

    protected function getStartPage(){
        if(!Router::$originId) $_SESSION['page'] = $_GET['page'] ? $_GET['page'] : 0;
    }

    public function show(){
        // if grid
        if(!Router::$id){
            if(!empty($this->grid)){
                echo '<div class="grid">';
                foreach($this->grid as $item){
                    echo '
                    <div class="item">'.$item['name'].'</div>';
                }
                echo '
                    <div class="clear"></div>
                </div>';

                $pager = new Pager($this);

                $pager->show();
            }
            // if item
        }else if(Core::$item){

            // if no item
        }else{
            error('This page is no longer available');
        }
    }

    public function modifyConnection(){}

    protected function modifyQuery(){
        $this->query->tables[0] = $this->table;
        if(!$this->query->order) $this->query->order = $this->table.'.'.$this->primary.' DESC';
    }


}
?>