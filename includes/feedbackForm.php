<?php class_exists('Core', false) or die();

if(!class_exists('Captcha', false)) new Captcha();

echo '
<form method="POST">
    <input name="code" value="'.Captcha::$code.'" type="hidden" />
    <input type="hidden" name="theme" value="">
    <input type="hidden" name="email-out" value="some@mail.ru">

    <label for="modal-form-name">Ваше имя</label>
    <input name="name" type="text" id="modal-form-name">
    <label for="modal-form-tool">Музыкальный инструмент</label>
    <input name="tool" type="text" id="modal-form-tool">
    <label for="modal-form-phone">Ваш телефон</label>
    <input name="phone" type="text" id="modal-form-phone">
    <label for="modal-form-email">E-mail</label>
    <input name="email" type="text" id="modal-form-email">
    <label>Повторите символы</label>
    <img src="'.Captcha::$fpath.'?rand='.rand(1, 10000000).'" class="l" /><input name="entered" type="text" style="width:5em; text-align:center;" maxlength="4" autocomplete="off" />
    <div class="clearfix"></div>
    <button>ОТПРАВИТЬ</button>
</form>';
?>