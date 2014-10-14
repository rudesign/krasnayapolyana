<?php class_exists('Core', false) or die();

if(Users::$current['moderated']){
    $settings = new Settings;
    $settings->loadBaseSettings();
    Db::setConnection();

    $query = Cities::set();
    $query->order = 'ord ASC';
    $cities = Cities::get($query);

    $settings->loadSettings();
    Db::setConnection();

    if($cities){
        echo '
        <div class="l city-selector">
            <select onChange="document.location.assign(this.value);">';
            foreach($cities as $row){
                $uri = 'http://'.($row['subdomain'] ? $row['subdomain'].'.' : '').reset(Settings::$data->hosts).'/panel/';
                echo '<option value="'.$uri.'"'.(self::$data->currentSubdomain == $row['subdomain'] ? ' selected' : '').'><a href="'.$uri.'">'.$row['name'].'</a></option>';
            }
            echo '
            </select>
        </div>';
    }
}
?>