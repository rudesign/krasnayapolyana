<?php

class AJAJCommon{
    protected $data = array();
    protected $quantity = 3;
    protected $count = 0;
    protected $currentPage = 0;
    protected $pages = 0;


    protected function getGridCount($query = null){
        try{
            if(empty($query)) throw new Error;

            $query->fields = 'COUNT(*) AS count';
            $query->condition = $query->condition ? $query->condition.' AND ' : '';
            $query->condition .= $query->tables[0].'.visible > 0 AND '.$query->tables[0].'.pubTime < '.time();
            $query->visibleOnly = false;
            $query->flat = true;
            if(!$row = $query->get()) throw new Error;

            $count = $row['count'];

            if(empty($count)) throw new Error;

            $this->count = $count;

            return $this->count;
        }catch (Error $e){
            return 0;
        }
    }

    protected function getPagesCount(){
        $this->pages = intval($this->count/$this->quantity);
        $this->pages += $this->count%$this->quantity ? 1 : 0;
    }
}
?>