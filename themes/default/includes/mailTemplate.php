<?php class_exists('Core', false) or die();

$originalBody = $body;

$body = '
<style>
	td{ line-height: 1.3em; }
	a{ color:#00b1ff; }
	p{margin:0;padding:0;}
	.footer a{ color:gray; }
</style>

<table width="600" cellspacing="0" cellpadding="20">
    <tbody>
        <tr>
            <td>
                <font size="2" face="Arial" color="black">
                    <font size="4">Добрый день!</font>
                    <br /><br />
                    '.$originalBody.'
                    <br /><br />
                    С уважением, '.Core::$params['name'].'
                    <br />
                    <a href="http://'.$_SERVER['HTTP_HOST'].'">'.$_SERVER['HTTP_HOST'].'</a>
                </font>
            </td>
        </tr>
    </tbody>
</table>';

unset($originalBody);
?>
