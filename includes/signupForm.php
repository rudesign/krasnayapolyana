<?php class_exists('Core', false) or die();

if(!Users::$current){

    if(!class_exists('Captcha', false)) new Captcha();

    echo '
    <div class="signup-form forms">
        <form action="/" method="POST">
        <input name="code" value="'.Captcha::$code.'" type="hidden" />
            <div class="section">
                <a href="/login/">Войдите на сайт</a>, если уже регистрировались
            </div>

            <div class="mini-section">
                <div class="l" style="width: 48%; margin-right: 2%;">
                    <dl class="label">Ваше Имя</dl>
                    <input name="name" class="w100" type="text" maxlength="25" />
                </div>
                <div class="l" style="width: 48%; margin-right: 2%;">
                    <dl class="label">Ваш e-mail</dl>
                    <input name="login" class="w100" type="text" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="mini-section">
                <div class="l" style="width: 48%; margin-right: 2%;">
                    <dl class="label">Пароль (минимум 4 символа)</dl>
                    <input name="password" class="w100" type="password" autocomplete="off" />
                </div>
                <div class="l" style="width: 48%; margin-right: 2%;">
                    <dl class="label">Повторите введённый пароль</dl>
                    <input name="passwordReply" class="w100" type="password" autocomplete="off" />
                </div>
                <div class="clear"></div>
            </div>

            <div class="mini-section">
                <dl class="label">
                    Введите проверочный код
                </dl>
                <img src="'.Captcha::$fpath.'?rand='.rand(1, 10000000).'" class="l" /><input name="entered" type="text" style="width:5em; text-align:center;" maxlength="4" autocomplete="off" />
            </div>

            <button onclick="signup(); return false;" class="buttons black-buttons" opt="Подождите...">Зарегистрироваться</button>
        </form>
    </div>
    <div class="clear"></div>
    ';

}else Router::redirect('/my/');
?>
