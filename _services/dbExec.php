<?php
    //if(!$_SERVER['HTTP_REFERER']) die();

    header("Content-type: text/html; charset=utf-8");

    define('APP_ROOT', $_SERVER['DOCUMENT_ROOT']);
    define('APP_PATH', '');

    // attach common methods file
    $fPath = APP_ROOT.'/includes/funcs.php';
    if(file_exists($fPath)) require_once($fPath); else die('Unable to start: no common methods file');

    try {
        new Users();

        if(!Users::$accessGranted) throw new Error;

        $query = new Query();

        $query->string = "
        CREATE TABLE IF NOT EXISTS `autos` (
        `id` int(11) unsigned NOT NULL,
          `visible` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `name` tinytext NOT NULL,
          `remoteId` smallint(4) unsigned NOT NULL DEFAULT '0',
          `alias` tinytext NOT NULL,
          `ord` int(10) unsigned NOT NULL DEFAULT '0',
          `teaser` text NOT NULL,
          `body` text NOT NULL,
          `gallery` text NOT NULL,
          `attachments` text NOT NULL,
          `title` tinytext NOT NULL,
          `metaDescription` tinytext NOT NULL,
          `metaKeywords` tinytext NOT NULL,
          `pubTime` int(11) unsigned NOT NULL DEFAULT '0',
          `createdTime` int(11) unsigned NOT NULL DEFAULT '0',
          `createdBy` int(11) unsigned NOT NULL DEFAULT '0',
          `modifiedTime` int(10) unsigned NOT NULL DEFAULT '0',
          `modifiedBy` int(10) unsigned NOT NULL DEFAULT '0',
          `searchIndex` text NOT NULL
        ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;";

        if(!$query->execute(false)) throw new Error;

        $query->string = "
        INSERT INTO `autos` (`id`, `visible`, `name`, `remoteId`, `alias`, `ord`, `teaser`, `body`, `gallery`, `attachments`, `title`, `metaDescription`, `metaKeywords`, `pubTime`, `createdTime`, `createdBy`, `modifiedTime`, `modifiedBy`, `searchIndex`) VALUES
        (1, 1, 'Daewoo Matiz', 52, '', 1, '', '', '', '', '', '', '', 1413974640, 1413974697, 1, 0, 0, ''),
        (2, 1, 'Chevrolet Spark', 18, '', 2, '', '', '', '', '', '', '', 1413974700, 1413974712, 1, 0, 0, ''),
        (3, 1, 'Chevrolet Aveo Hatchback', 53, '', 3, '', '', '', '', '', '', '', 1413974700, 1413974725, 1, 0, 0, ''),
        (4, 1, 'Chevrolet Aveo Sedan', 56, '', 4, '', '', '', '', '', '', '', 1413974700, 1413974737, 1, 0, 0, ''),
        (5, 1, 'Chevrolet Cruze', 54, '', 5, '', '', '', '', '', '', '', 1413974700, 1413974749, 1, 0, 0, ''),
        (6, 1, 'Chevrolet Captiva', 55, '', 6, '', '', '', '', '', '', '', 1413974700, 1413974761, 1, 0, 0, '');";

        if(!$query->execute(false)) throw new Error;

    }catch(Error $e) {
        echo $e->getLine();
    }
?>