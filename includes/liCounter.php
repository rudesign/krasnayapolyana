<?php class_exists('Core', false) or die();

if(Settings::$data->environment == 'production'){

$counter = <<<EOT
<!--LiveInternet counter--><script type="text/javascript">document.write("<a href='http://www.liveinternet.ru/click' target=_blank rel=nofollow><img src='//counter.yadro.ru/hit?t17.2;r" + escape(document.referrer) + ((typeof(screen)=="undefined")?"":";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + ";u" + escape(document.URL) + ";" + Math.random() + "' border=0 width=1 height=1 alt='' title='LiveInternet: number of pageviews for 24 hours, of visitors for 24 hours and for today is shown'><\/a>")</script><!--/LiveInternet-->
EOT;

echo $counter;
}
?>

