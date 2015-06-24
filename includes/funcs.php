<?php
if(!empty($_GET['go'])){
    Router::redirect($_GET['go']);
}

// clear GET
if(!empty($_GET)){
    foreach($_GET as $key=>$value){
        if(!is_array($value)) {
            $_GET[$key] = str_replace('"', '&quot;', $value);
            $_GET[$key] = str_replace("'", '&apos;', $_GET[$key]);
        }else{
            foreach($value as $keyDep=>$valueDep){
                if(!is_array($valueDep)) {
                    $_GET[$key][$keyDep] = str_replace('"', '&quot;', $valueDep);
                    $_GET[$key][$keyDep] = str_replace("'", '&apos;', $_GET[$key][$keyDep]);
                }

            }
        }
    }
}

// clear POST
if(!empty($_POST)){
    foreach($_POST as $key=>$value){
        if($key != 'html'){
            if(!is_array($value)) {
                $_POST[$key] = encodeHTMLEntities($value);
                $_POST[$key] = encodeHTMLEntities($_POST[$key]);
            }else{
                foreach($value as $keyDep=>$valueDep){
                    if(!is_array($valueDep)) {
                        $_POST[$key][$keyDep] = encodeHTMLEntities($valueDep);
                        $_POST[$key][$keyDep] = encodeHTMLEntities($_POST[$key][$keyDep]);
                    }

                }
            }
        }
    }
}

function __autoload($cName) {

    // dirs where to look for
    $dirStack = array(
        APP_ROOT.'/core',
        APP_ROOT.'/controllers',
    );

    // if theme name is defined - expand autoload to the theme
    if(class_exists('Core', false)) $dirStack[] =  APP_ROOT.'/themes/'.Core::$theme.'/controllers';

    $loaded = false;
    foreach($dirStack as $dir){
        $fPath = $dir.'/'.$cName.'.php';

        if(@file_exists($fPath)) {
            $loaded = require_once($fPath);
            break;
        }
    }

    if(!$loaded) throw new Error('Unable to load class or controller '.$cName);
}

function error($e = null, $code = 404){

    $html = '';

    httpResponse($code);

    $message = (is_object($e) ? $e->getMessage() : $e);

    switch($code){
        default:
            if(!$message) $message = 'This page is no longer available';

        break;
        case 403:
            if(!$message) $message = 'Access forbidden';
        break;
    }

    try{
        $errorFPath = APP_ROOT.'/includes/error.php';

        if(!class_exists('Core', false)) throw new Error();
        if(!isset(Settings::$data)) throw new Error();
        if(Settings::$data->environment != 'production') throw new Error();
        if(!file_exists($errorFPath)) throw new Error();

        $silent = true;
    }catch (Error $e){
        $silent = false;
    }

    if($silent) {
        @include_once $errorFPath;

        die();
    }else{
        $html = 'Error '.$code.': '.$message;
        die( $html );
    }

}

function httpResponse($code = 200){
    switch($code){
        default:
            header("HTTP/1.0 200 OK");
            break;
        case 404:
            header('HTTP/1.0 404 Not Found');
            break;
        case 403:
            header('HTTP/1.1 403 Forbidden');
            break;
    }
}

function toObject($val = null){
    return empty($val) ? null : json_decode(json_encode($val));
}

function encodeHTMLEntities($string = ''){
    if(!empty($string)){
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace("'", '&apos;', $string);
    }

    return $string;
}

function decodeHTMLEntities($string = ''){
    if(!empty($string)){
        $string = str_replace('&quot;', '"', $string);
        $string = str_replace('&apos;', "'", $string);
    }

    return $string;
}


function changeCase($source = '', $toLower = true){
    if(!empty($source) && is_string($source)){
        $source=trim($source);

        $source = strtolower($source);

        // cyrillic fix
        $upper=Array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ь','Ы','Ъ','Э','Ю','Я');
        $lower=Array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ь','ы','ъ','э','ю','я');

        $source = $toLower ? str_replace($upper, $lower, $source) : str_replace($lower, $upper, $source);
    }

    return !empty($source) ? (string) $source : null;
}


function capitalise($source = '', $firstLetterOnly = true){
    if(!empty($source)){
        $source=explode(' ', trim($source));

        $changed = array();
        $f = false;
        foreach($source as $word){
            $firstLetter = mb_substr($word, 0, 1, 'utf8');
            $word = mb_substr($word, 1, mb_strlen($word), 'utf8');

            if(!$firstLetterOnly || (!$f && $firstLetterOnly)){
                $firstLetter = changeCase($firstLetter, false);
                $firstLetter = strtoupper($firstLetter);
            }

            $word = $firstLetter.$word;
            $changed[] = $word;
            if(!$f) $f = true;
        }
    }

    return !empty($source) ? implode(' ', $changed) : null;
}

function varExclude($uri = '', $name = '', $value = null){
    if(empty($uri)) $uri = $_SERVER['REQUEST_URI'];

    $parsedUri = parse_url("http://".$_SERVER['SERVER_NAME'].$uri);

    if($parsedUri['query']) parse_str($parsedUri['query'], $parsedQuery);

    if($name){
        if(empty($value)){
            unset($parsedQuery[$name]);
        }else{
            $parsedQuery[$name] = $value;
        }
    }

    $uri = $parsedUri['path'];

    if(!empty($parsedQuery)) {
        $uriVals = array();

        foreach($parsedQuery as $key=>$value){
            if(is_array($value)){
                foreach($value as $item){
                    $uriVals[] = $key.'[]='.$item ;
                }
            }else{
                $uriVals[] = $key.'='.$value ;
            }

        }
        $uri .= '?'.implode('&', $uriVals);
    }

    return $uri;
}

function getExplodedGroup($various = null){
    try{
        if(empty($various)) throw new Error();
        if(!is_array($various)){
            if(is_string($various)){
                $exploded = explode(' #', $various);
            }else{
                throw new Error();
            }
        }else $exploded = $various;

        foreach($exploded as $item){
            $result[] = explode('@', $item);
        }

        return $result;

    }catch (Error $e){
        return array();
    }
}

function getThumbnail($gallery = '', $pubTime = 0){
    try{
        if(!$exploded = getExplodedGroup($gallery)) throw new Error();
        $thumbnail = reset($exploded);
        $thumbnail[0] .= '?v='.$pubTime;
        if(empty($thumbnail))  throw new Error();

        return $thumbnail;

    }catch (Error $e){
        return array('nopict.jpg');
    }
}

function moveTemporaryAttachments($gallery = '', $attachments = '', $dirSettings = array(), $dirFrom = '', $dirTo = ''){

    if(empty($dirFrom)) $dirFrom = APP_ROOT.'/tmp';
    if(empty($dirTo)) $dirTo = APP_ROOT.'/images';

    // gallery
    if(!empty($gallery)){
        $folders = array();

        $dirSettings = $dirSettings ? $dirSettings : Settings::$data->dirsToStoreImages;

        if(is_array($dirSettings)){
            foreach($dirSettings as $dirDesrciption){
                $dirDetails = explode(':', $dirDesrciption);
                $folders[] = $dirDetails[0];
            }

            $gallery = getExplodedGroup($gallery);

            foreach($gallery as $item){
                foreach($folders as $folder){
                    $fpath = $dirFrom.'/'.$folder.'/'.$item[0];
                    if(file_exists($fpath)){
                        if(@copy($fpath, $dirTo.'/'.$folder.'/'.$item[0])) @unlink($fpath);
                    }
                }
            }
        }
    }

    // attachments
    if(!empty($attachments)){

        $attachments = getExplodedGroup($attachments);
        foreach($attachments as $item){
            $fpath = $dirFrom.'/'.$item[0];
            if(file_exists($fpath)){
                if(@copy($fpath, $dirTo.'/'.$item[0])) @unlink($fpath);
            }
        }
    }
}

function deleteGallery($gallery = '', $dirSettings = array()){

    if(!empty($gallery)){
        $folderToStore = APP_ROOT.'/images';

        $folders = array();

        $dirSettings = $dirSettings ? $dirSettings : Settings::$data->dirsToStoreImages;

        if(is_array($dirSettings)){
            foreach($dirSettings as $dirDesrciption){
                $dirDetails = explode(':', $dirDesrciption);
                $folders[] = $dirDetails[0];
            }

            $gallery = getExplodedGroup($gallery);

            foreach($gallery as $item){
                foreach($folders as $folder){
                    $fpath = $folderToStore.'/'.$folder.'/'.$item[0];
                    if(file_exists($fpath)){
                        @unlink($fpath);
                    }
                }
            }
        }
    }

    return true;
}

function getUnique(){
    for($x=0; $x<3; $x++){ $t[$x]=rand(1000, 9000); }
    for($y=0; $y<3; $y++){ $s[$y]=chr(rand(65, 90)); }

    $a =  $s[0].$s[1].$s[2].$t[0].$t[1].$s[3];

    return md5(time().$a);
}

function trimArray($array, $stripTags = false){
    if(!empty($array)){
        foreach($array as $key=>$value){
            if(is_string($value)) {
                if($stripTags) $value = strip_tags($value);
                $array[$key] = trim($value);

            }
        }
    }

    return $array;
}

// letters + dot
function checkLettersOnly($string){
    $pattern="/([\d-_,!\'\"№;%:?*\(\)_+\/#@$^&*+|])/ui";
    return preg_match($pattern, $string) ? false : true;
}

// letters + digits
function checkLettersAndDigitsOnly($string){
    $pattern="/([-_,!\'\"№;%:?*\(\)(\s)_+\/#@$^&*+|])/ui";
    return preg_match($pattern, $string) ? false : true;
}

function cleanRN($string = '', $toReplace = ''){
    if(!empty($string)){
        $string = str_replace("\r", '', $string);
        $string = str_replace("\n", $toReplace, $string);
    }
    return $string;
}

function replaceRN($string = '', $replace = "\n", $toReplace = "<br />"){
    if(!empty($string)){
        $string = str_replace("\r", '', $string);
        $string = str_replace($replace, $toReplace, $string);
    }
    return $string;
}

function restoreRN($string = '', $replace = "<br />", $toReplace = "\n"){
    if(!empty($string)) {
        $string = str_replace($replace, $toReplace, $string);
    }

    return $string;
}

function getSelector($table, $key = 'id', $value = 0, $selectorName='', $query = null, $elementClass = '', $onChange = ''){
    $toReturn = '';

    if(empty($query)) $query = new Query($table);
    if(!$selectorName) $selectorName = $table;

    if(is_object($query)){
        $query->multi = true;
        if(!$query->order) $query->order = 'name ASC';

        if($query->get()){
            $result = $query->result;

            $toReturn = '
            <select name="'.$selectorName.'"'.($elementClass ? ' class="'.$elementClass.'"' : '').($onChange ? ' onchange="'.$onChange.'"' : '').'>
                <option value="0">выберите...</option>';
            $sel[$value] = ' selected';
            foreach($result as $item){
                $toReturn .= '<option value="'.$item[$key].'"'.$sel[$item[$key]].'>'.$item['name'].'</option>';
            }
            $toReturn .= '</select>';
        }
    }

    return $toReturn;
}

function getYouTubeVideoId($string = ''){
    try{

        if(empty($string)) throw new Error();

        @preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $string, $matches);

        if(empty($matches)) throw new Error();

        return $matches[1];
    }catch (Error $e){
        return false;
    }
}

function getLimited($string, $limit = 0, $uri = null){

    if($string = strip_tags($string)){

        $string = decodeHTMLEntities($string);

        if(mb_strlen($string, 'utf-8') >= $limit){
            $string = mb_substr($string, 0, $limit, 'UTF-8');
            $stringArr = explode(' ', $string);
            array_pop($stringArr);
            $string = implode(' ', $stringArr);

            if($uri && $uri !== true){
                $string.='&nbsp;<a href="'.$uri.'">...</a>';
            }else if($uri === true){
                $string .= '&nbsp;...';
            }
        }
    }

    return encodeHTMLEntities($string);
}

function prepareUri($uri = ''){
    $cleanUri = array();

    if(!empty($uri)){

        $uriSet = explode(',', $uri);

        foreach($uriSet as $uri){
            $uri = trim($uri);

            $httpsCount = $wwwCount = 0;

            $uri = str_replace('https://', '', $uri, $httpsCount);
            if(!$httpsCount) $uri = str_replace('http://', '', $uri);
            $uri = str_replace('www.', '', $uri, $wwwCount);
            $uri = str_replace('+', '%2B', $uri);

            $prefix = $httpsCount ?  'https://' : 'http://';
            if($wwwCount) $prefix .= 'www.';

            $uri = $prefix.$uri;

            $parts = parse_url($uri);

            if($parts['query']) $parts['query'] = urlencode($parts['query']);

            $cleanUri[] = unparse_url($parts);
        }
    }

    return implode(', ', $cleanUri);
}

function unparse_url($parsed_url) {

    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

    return "$scheme$user$pass$host$port$path$query$fragment";
}

function cleanOutgoingUri($string){

    $params='/?go=';

    $string = str_replace($params, '', $string);

    $pattern = "#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#iS";

    if(preg_match_all($pattern, $string, $matches)){

        foreach($matches[0] as $item){

            if(!detectCyrillic($item)){
                $string = str_replace($item, '<a href="'.$params.$item.'" target="_blank" rel="nofollow">'.$item.'</a>', $string);
            }
        }
    }

    return $string;
}

function getRealTime($time = 0){
    if(empty($time)) $time = time();

    return $time - $_SESSION['tOffset'];
}

function humanuzeTime($time = 0, $showHoursAndMinutes = false, $capitalize = 0){
    if(empty($time)) $time = time();

    $month = intval(date('n', $time));

    $date = date('j', $time).' '.humanizeMonth($month, false) . ' ' . date('Y', $time);

    if($showHoursAndMinutes){
        $date .= date(' в H:i', $time);
    }

    return $date;
}

function humanizeMonth($month = 0, $asArray = true, $capitalize = false){
    $month = intval($month);

    $months = array(
        array(''),
        array('январь', 'января'),
        array('февраль', 'февраля'),
        array('март', 'марта'),
        array('апрель', 'апреля'),
        array('май', 'мая'),
        array('июнь', 'июня'),
        array('июль', 'июля'),
        array('август', 'августа'),
        array('сентябрь', 'сентября'),
        array('октябрь', 'октября'),
        array('ноябрь', 'ноября'),
        array('декабрь', 'декабря'),
    );

    if(!empty($month)){
        $month = $asArray ? $months[$month] : end($months[$month]);

        if($capitalize){
            if($asArray){
                foreach($month as $index=>$name){
                    $months[$index] = capitalise($name);
                }
            }else{
                $month = capitalise($month);
            }
        }
    }

    return $month;
}

function xmlToArray($xmlstring = ''){
    $xml = simplexml_load_string($xmlstring);
    $json = json_encode($xml);
    return $array = json_decode($json,TRUE);
}

function transliterate($string){
    $cyr =Array('/', ' ', 'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ь','Ы','Ъ','Э','Ю','Я');
    $lat =Array('_', '-', 'A','B','V','G','D','E','YO','J','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','H','TS','CH','SH','SH','','Y','','E','JU','YA');
    $string = str_replace($cyr, $lat, $string);
    $cyr =Array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ь','ы','ъ','э','ю','я');
    $lat =Array('a','b','v','g','d','e','yo','j','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sh','sh','','y','','e','ju','ya');
    $string = str_replace($cyr, $lat, $string);

    return $string;
}

function sendAuthEmail($emails, $theme, $body, $attachmentPath = ''){

    try{
        if(empty($emails)) throw new Error('No recipients defined');
        if(empty($body)) throw new Error('No letter body');

        @include APP_ROOT.'/themes/'.Core::$theme.'/includes/mailTemplate.php';

        if(!class_exists('PHPMailer', false)) @include APP_ROOT.'/phpmailer/class.phpmailer.php';

        $mail = new PHPMailer(false);                               // true - for debugging
        $mail->SMTPDebug  = 0;                                  // enables SMTP debug information (for testing)  1 = errors and messages 2 = messages only. false to disable
        $mail->IsSMTP();
        $mail->CharSet = 'utf-8';
        $mail->SMTPAuth   = true;
        $mail->Port       = 25;
        $mail->Host       = Settings::$data->smtpServerHost;        // SMTP server
        $mail->Username   = Settings::$data->smtpServiceUser;       // SMTP account username
        $mail->Password   = Settings::$data->smtpServiceUserPwd;    // SMTP account password

        $emails = is_array($emails) ? implode(',', $emails) : $emails;

        if($emails){
            $emailsArr=explode(',', $emails);
            foreach($emailsArr as $email){
                if($email = trim($email)) $mail->AddAddress($email);
            }
        }

        $mail->SetFrom(Settings::$data->smtpServiceEmail, html_entity_decode(Core::$params['name']));

        $mail->Subject = $theme ? $theme : 'From '.Settings::$data->smtpServiceEmail;

        $mail->MsgHTML($body);

        if(!empty($attachmentPath)) $mail->AddAttachment($attachmentPath);

        $mail->Send();

        return true;
    }catch (Exception $e){
        return false;
    }


}

// $outputDir array(dirToStore, dirToCheckUnique)
function resizeImage($src, $outputDir = array(), $outputFname = '', $phpThumbParams = array()){
    if(empty($src) || !file_exists($src)) throw new Error('No source defined '.$src);

    require_once(APP_ROOT.'/phpthumb/phpthumb.class.php');

    if(!class_exists('phpThumb')) throw new Error('No phpThumb class');

    $phpThumb = new phpThumb();

    $phpThumb->resetObject();

    $phpThumb->setSourceFilename($src);

    // quality by default
    if(!isset($phpThumbParams['q'])) $phpThumbParams['q'] = 90;

    if(!empty($phpThumbParams)){
        foreach($phpThumbParams as $key=>$value){
            $phpThumb->setParameter($key, $value);
        }
    }

    if(!$phpThumb->GenerateThumbnail()) throw new Error('Cannot generate thumbnail');

    if(!$outputFname = getUniqueFname(($outputDir[1] ? $outputDir[1] : $outputDir[0]), $outputFname, 'jpg')) throw new Error('Cannot get unique file name');

    $output = $outputDir[0].'/'.$outputFname;

    if(!$phpThumb->RenderToFile($output)) throw new Error('Cannot render the file');

    $phpThumb->purgeTempFiles();

    return $outputFname;
}

function watermark($picturePath, $watermarkPath = '', $options = array()){

    $quality = $options['quality'] ? $options['quality'] : 90;
    $watermarkPath = $watermarkPath ? $watermarkPath : APP_ROOT.'/images/watermark.png';

    $picturesize = getimagesize($picturePath);
    $watermarksize = getimagesize($watermarkPath);

    $dest_y = $picturesize[1] - $watermarksize[1] - intval($picturesize[1]/4);
    $dest_x = $picturesize[0] - $watermarksize[0];

    $picture = imagecreatefromjpeg($picturePath);
    $watermark = imagecreatefrompng($watermarkPath);

    //imagecopy($picture, $watermark, 0, $dest_y, 0, 0, $watermarksize[0], $watermarksize[1]);
    imagecopy($picture, $watermark, $dest_x, 0, 0, 0, $watermarksize[0], $watermarksize[1]);

    imagejpeg($picture, $picturePath, $quality);

    imagedestroy($picture);
    imagedestroy($watermark);
}

function getUniqueFname($dirToCheck = '', $outputFname = '', $ext = 'jpg'){
    $limit = 2000;
    if(!$dirToCheck) $dirToCheck = DOCUMENT_ROOT;

    if(!$outputFname){
        $outputFname = getUnique();
        $outputFname .= '.'.$ext;

        $fpath = $dirToCheck.'/'.$outputFname;

        $i=0;
        while(file_exists($fpath)){
            if($i<$limit) {
                $outputFname = getUniqueFname($dirToCheck, '', $ext);
            }else break;
            $i++;
        }
    }

    return $outputFname;
}

// has cyrillic symbols
function detectCyrillic($string){
    $pattern='/([\x{0030}-\x{007f}\/\.\?&#%\+\-]+)/ui';

    preg_match($pattern, $string, $matches) ? false : true;

    return ($string != reset($matches)) ? true : false;
}


function getRealIPAddress(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function getGridCName(){
    if(is_array(Router::$request->parsed->path)){
        $aliases = Router::$request->parsed->path;
        foreach($aliases as $i=>$value){
            $value = str_replace('-', '', $value);
            $aliases[$i] = capitalise(changeCase($value));
        }
        $aliases[] = 'Grid';
        $cname = implode($aliases);
    }

    return isset($cname) ? $cname : '';
}

function getAge($birthTime = 0){
    try{
        if(empty($birthTime)) throw new Error;

        $curentYear = date('Y');
        $birthYear = date('Y', $birthTime);
        $age = $curentYear - $birthYear;
        if(date('z') >= date('z', $birthTime)) $age++;

        return $age;
    }catch (Error $e){
        return 0;
    }
}


function isSummer(){
    try{
        $month = date('n');

        $isSummer = ((($month >= 1) && ($month < 5)) || (($month <= 12) && ($month >= 11))) ? false : true;

        return $isSummer;
    }catch (\Exception $e){
        return false;
    }
}