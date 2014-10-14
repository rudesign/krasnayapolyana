<?php class_exists('Core', false) or die();

class PanelCategoriesGrid extends PanelGrid{
    private $nextId = 0;

    public function __construct(){
        parent::__construct();

        //$this->showQuery = true;
        $this->allowToChangeOrder = true;
        $this->downloadURI = '/panel/includes/categoriesExport.php';

        $this->checkboxes = array(
            'promoted',
        );
        $this->selects = array(
            'type',
        );

        $this->import();
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <!--<dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>-->

            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>
            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="hw" /></dt>
            <dl>Вышележащая категория</dl>
            <dt>';
            $query = Categories::set();
            $query->condition = 'parent = 0';
            echo getSelector(null, $this->primary, Core::$item['parent'], 'parent', $query);
            echo '</dt>
            <!--
            <dl>Тип</dl>
            <dt>
                <select name="type">
                    <option value="0"></option>
                    <option value="1"'.$this->selects['type'][1].'>товарная категория</option>
                    <option value="2"'.$this->selects['type'][2].'>вид деятельности</option>
                    <option value="3"'.$this->selects['type'][3].'>рубрика Журнала</option>
                </select>
            </dt>
            <dt><input name="promoted" value="1" type="checkbox"'.$this->checkboxes['promoted']['checked'].' /><label>промотируемый</label></dt>
            -->
            <dl>Title</dl>
            <dt><input name="title" value="'.Core::$item['title'].'" type="text" class="fw" /></dt>
            <dl>Meta Description</dl>
            <dt><input name="metaDescription" value="'.Core::$item['metaDescription'].'" type="text" class="fw" /></dt>
            <dl>Meta Keywords</dl>
            <dt><input name="metaKeywords" value="'.Core::$item['metaKeywords'].'" type="text" class="fw" /></dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    function showFilter(){
        echo '
		<div class="filter inline">

                <!--
                <form action="/'.implode('/', Router::$request->parsed->path).'/" method="POST" enctype="multipart/form-data">
                    <ul>
                        <li><input name="xls" value="" type="file" /></li>
                        <li><button type="submit" class="buttons green-buttons">Загрузить</li>
                    </ul>
                </form>
                -->

                <div class="d15"></div>

                <form action="/'.implode('/', Router::$request->parsed->path).'/" method="GET">
                    <ul>
                        <li><input name="q" value="'.$_GET['q'].'" type="text" style="width:15em;" /></li>
                        <li>';
                        $query = Categories::set();
                        $query->condition = 'parent = 0';
                        $query->order = 'name ASC';
                        if($categories = Categories::get($query)){
                            echo '
                            <select name="parent">
                                <option></option>';
                                foreach($categories as $row){
                                    echo '<option value="'.$row['id'].'"'.($_GET['parent'] == $row['id'] ? ' selected' : '').'>'.$row['name'].'</option>';
                                }
                            echo '</select>';
                        }
                        echo '</li>
                        <li><button type="submit" class="buttons green-buttons">Найти</li>
                    </ul>
                    <div class="clear"></div>
                </form>


            <div class="clear"></div>
		</div>
		';
    }

    private function import(){
        try{
            if($fname = $_FILES['xls']['tmp_name']){

                if(!class_exists('PHPExcel', false)) {
                    require_once APP_ROOT.'/phpexcel/Classes/PHPExcel.php';

                    $objPHPExcel = new PHPExcel();
                }

                if(strpos($_FILES['xls']['type'], 'excel') === false) throw new Error('Wrong type of file');

                if(file_exists($fname)){
                    $objPHPExcel = PHPExcel_IOFactory::load($fname);
                }else throw new Error('No excel file to process');

                $worksheet = $objPHPExcel->getActiveSheet()->toArray();

                $query = Categories::set();
                $query->truncate();

                // get max id
                $ids = array();
                foreach($worksheet as $row){
                    $ids[] = $row[0];
                }
                sort($ids);
                $this->nextId =  end($ids)+1;

                $i=1;
                $parent = 0;
                foreach($worksheet as $row){
                    if($row[1]){

                        $data = array(
                            'id'=>($row[0] ? $row[0] : 0),
                            'name'=>$row[1],
                            'parent'=>$parent,
                            'type'=>($row[2] ? $row[2] : 1),
                            'promoted'=>$row[3],
                            'ord'=>$i,
                        );

                        if($id = Categories::create($data)) {
                            if(!$parent) $parent = $id;
                            if(!$row[0]) $this->nextId++;
                        }

                        $i++;
                    }else{
                        $parent = 0;
                    }
                }
            }
        }catch (Error $e){
            echo $e->getMessage();
        }
    }

    public function getAdditionalDataToCreate(){
        return array_merge(array(

        ), parent::getAdditionalData(), parent::getAdditionalDataToCreate());
    }

    public function getAdditionalDataToUpdate(){
        return array_merge(array(

        ), parent::getAdditionalData(), parent::getAdditionalDataToUpdate());
    }
}
?>