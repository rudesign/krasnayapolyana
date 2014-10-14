<?php class_exists('Core', false) or die();

if(!class_exists('Captcha', false)) new Captcha();

echo '
<form method="POST">
    <input name="code" value="'.Captcha::$code.'" type="hidden" />
    <div class="table">
        <div class="tr">
            <div class="td"></div>
            <div class="td">
                <dl>Оставьте свой номер</dl>
                <dl>и мы перезвоним</dl>
            </div>
        </div>
        <div class="tr">
            <div class="td">Имя:</div>
            <div class="td"><input name="name" type="text" /></div>
        </div>
        <div class="tr">
            <div class="td">Номер:</div>
            <div class="td"><input name="phone" type="text" /></div>
        </div>
    </div>
    <div class="table captcha">
        <div class="tr">
            <div class="td">Код:</div>
            <div class="td"><img src="'.Captcha::$fpath.'?rand='.rand(1, 10000000).'" /></div>
            <div class="td"><input name="entered" type="text" maxlength="4" autocomplete="off" /></div>
            <div class="td"><button onClick="return submitCallbackForm();" class="buttons">ОК</button></div>
        </div>
    </div>
    <div class="centered">
        <a href="javascript:toggleCallbackForm();">Закрыть</a> х
    </div>
</form>
';
?>