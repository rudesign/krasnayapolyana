<?php class_exists('Core', false) or die();

class PanelHotelsGrid extends PanelGrid{
    public function __construct(){
        parent::__construct();

        //$this->showQuery = true;
        //$this->allowToChangeOrder = true;

        $this->selects = array(
            'rating',
        );
    }

    protected function showForm(){
        echo '
        <form name="edit" class="forms" method="POST" enctype="multipart/form-data">
            <dt class="save-buttons top"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>

            <dl>Название</dl>
            <dt><input name="name" value="'.Core::$item['name'].'" type="text" class="fw" /></dt>
            <dl>Курорт</dl>
            <dt>'.getSelector('resorts', 'id', Core::$item['resort'], 'resort', false).'</dt>
            <dl>Стоимость проживания, от ... USD в сутки</dl>
            <dt><input name="price" value="'.Core::$item['price'].'" type="text" class="dw" /></dt>
            <!--
            <dl>Вступление</dl>
            <dt><textarea name="teaser" class="ck">'.Core::$item['teaser'].'</textarea></dt>
            -->
            <dl>Текст</dl>
            <dt><textarea name="body" class="ck">'.Core::$item['body'].'</textarea></dt>
            <dt>';
                $this->showFeaturesSelector();
            echo '
            </dt>
            <dl>Рейтинг</dl>
            <dt>
            <select name="rating">
                <option value="0"></option>';
                for($i=1;$i<=5;$i++){
                    echo '<option value="'.$i.'"'.$this->selects['rating'][$i].'>'.$i.'</option>';
                }
                echo '
                </select>
            </dt>
            <dl>Галерея</dl>
            <dt><input name="_newPictures[]" id="upload-new-pict" type="file" multiple /></dt>
            <input name="gallery" value="'.Core::$item['gallery'].'" type="hidden">
            <!--
            <dl>Прикреплённые файлы</dl>
            <dt><input name="_newAttachments[]" id="upload-new-attachments" type="file" multiple /></dt>
            <input name="attachments" value="'.Core::$item['attachments'].'" type="hidden">
            -->
            <dl>Title</dl>
            <dt><input name="title" value="'.Core::$item['title'].'" type="text" class="fw" /></dt>
            <dl>Meta Description</dl>
            <dt><input name="metaDescription" value="'.Core::$item['metaDescription'].'" type="text" class="fw" /></dt>
            <dl>Meta Keywords</dl>
            <dt><input name="metaKeywords" value="'.Core::$item['metaKeywords'].'" type="text" class="fw" /></dt>
            <dl>Alias (оставьте пустым если несущественно)</dl>
            <dt><input name="alias" value="'.Core::$item['alias'].'" type="text" class="hw" /></dt>
            <dl>Дата публикации</dl>
            <dt>'.showDateSelector(Core::$item['pubTime'], '_').'</dt>

            <dt class="save-buttons"><button onClick="save(); return false;" class="buttons green-buttons">Сохранить</button></dt>
        </form>
        ';
    }

    private function showFeaturesSelector(){
        $features = explode(',', Core::$item['features']);

        if($rows = Features::get()){
           echo '<ul class="inline">';
            foreach($rows as $row){
                echo '<li class="hw" style="margin-right:0;"><input name="_features[]" value="'.$row['id'].'" type="checkbox"'.(array_search($row['id'], $features) !== false ? ' checked' : '').' /><label>'.$row['name'].'</label></li>';
            }

           echo '</ul>';
        }
    }

    protected function showGridName(&$query, $item){

        if(empty($query)) $query = new Query($this->table);

        $parentName = '';
        if($item['resort']){
            if($resort = Resorts::getById($item['resort'])){
                $parentName = ' (<a href="/panel/resorts/'.$resort['id'].'.html">'.getLimited($resort['name'], 80, true).'</a>)';
            }
        }

        echo '<td class="names black"><a href="/'.implode('/', Router::$request->parsed->path).'/'.$item[$this->primary].'.html">'.getLimited(($item[$this->nameField] ? $item[$this->nameField] : '<em>Без названия</em>'), 80, true).'</a>'.$parentName.'</td>';
    }

    public function getAdditionalData(){
        return array_merge(array(
            'alias'=>($_POST['alias'] ? $_POST['alias'] : makeAlias($_POST['name'])),
            'searchIndex'=>changeCase(strip_tags($_POST['name'].' '.$_POST['body'])),
            'features'=>implode(',', $_POST['_features']),
        ), parent::getAdditionalData());
    }

    public function getAdditionalDataToCreate(){
        return array_merge(array(

        ), self::getAdditionalData(), parent::getAdditionalDataToCreate());
    }

    public function getAdditionalDataToUpdate(){
        return array_merge(array(

        ), self::getAdditionalData(), parent::getAdditionalDataToUpdate());
    }
}
?>