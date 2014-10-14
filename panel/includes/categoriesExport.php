<?php

class categoriesExport{
    private $categories = array();
    private $i = 0;
    private $objPHPExcel;

    public function __construct(){
        try{

            define('APP_ROOT', APP_ROOT);
            define('PANEL_APP_ROOT', APP_ROOT.'/panel');

            // attach common methods file
            $fname = PANEL_APP_ROOT.'/includes/funcs.php';

            if(!file_exists($fname)) throw new Error('Unable to start');

            @require_once($fname);

            new Users();

            if(!Users::$isAllowed) throw new Error();

            if(!class_exists('PHPExcel', false)) require_once APP_ROOT.'/phpexcel/Classes/PHPExcel.php';

            $this->objPHPExcel = new PHPExcel();

            $this->getCategories();

            $this->branch(0);

            $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
            $fname = 'categories.xls';
            $objWriter->save(APP_ROOT.'/images/'.$fname);

            header('Content-type: application/xls');
            header("Location: /images/".$fname);

        }catch (Error $e){
            echo $e->getMessage();
        }
    }

    private function branch($parent = 0){
        foreach ($this->categories[$parent] as $branch){

            if(!$branch['parent']) $this->i++;

            $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$this->i, $branch['id'])
                ->setCellValue('B'.$this->i, $branch['name'])
                ->setCellValue('C'.$this->i, $branch['type'])
                ->setCellValue('D'.$this->i, $branch['promoted'])
                ;
            // colorise cells
            if(!$branch['parent']){
                $this->objPHPExcel->getActiveSheet()->getStyle('A'.$this->i.':D'.$this->i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $this->objPHPExcel->getActiveSheet()->getStyle('A'.$this->i.':D'.$this->i)->getFill()->getStartColor()->setARGB('FFa2ddff');
            }

            $this->i++;

            if($this->categories[$branch['id']]) $this->branch($branch['id']);
        }
    }

    private  function getCategories(){
        $db = Categories::configureDb();

        $db->order = 'name ASC';

        if(!$categories = Categories::get($db))  throw new Error('No categories');

        foreach($categories as $category){
            $this->categories[$category['parent']][] = $category;
        }
    }
}

new categoriesExport();
?>