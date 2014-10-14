<?php class_exists('Core', false) or die();

if(!Users::$current){
    if(!class_exists('Captcha', false)) $captcha =  new Captcha();

    echo '
    <div id="login-form" class="forms">
        <form action="/force-login/" method="post">
            <input name="code" value="'.Captcha::$code.'" type="hidden" />
            <div class="normal-login-form">
                <div class="section">
                    <div class="l" style="width: 48%; margin-right: 2%;">
                        <dl class="label">Ваш e-mail</dl>
                        <dt><input name="login" class="w100" type="text" /></dt>
                    </div>
                    <div class="l" style="width: 48%; margin-right: 2%;">
                        <dl class="label">Пароль. <a onclick="showChangePasswordSection();" href="javascript:void(0);">Запросите новый</a>, если забыли</dl>
                        <dt><input name="password" class="w100" type="password" autocomplete="off" /></dt>
                    </div>
                    <div class="clear"></div>

                    <button onclick="return hello();" class="buttons buttons black-buttons" opt="Подождите...">Войти</button>
                    <button onclick="document.location.assign(\'/signup/\'); return false;" class="buttons buttons black-buttons">Зарегистрироваться</button>
                </div>
            </div>

            <div class="forget-pwd-form h">
                <div class="section">Ваш пароль будет изменён и выслан e-mail, указанный при регистрации.</div>

                <div class="section">
                    <div class="l" style="width: 48%; margin-right: 2%;">
                        <dl class="label">Укажите Ваш e-mail</dl>
                        <dt><input name="loginToRemind" class="w100" type="text" /></dt>
                    </div>
                    <div class="l" style="width: 48%; margin-right: 2%;">
                        <dl class="label">Повторите написанное</dl>
                        <dt>
                            <img src="'.Captcha::$fpath.'?rand='.rand(1, 10000000).'" class="l" />
                            <input name="entered" class="l" type="text" style="width:6em; margin-right: 5px; text-align:center;" maxlength="4" autocomplete="off" />
                        </dt>
                    </div>
                    <div class="clear"></div>

                    <button onclick="return hello();" class="buttons black-buttons" opt="Подождите...">Отправить</button>
                </div>

                <dt><a onclick="showLoginSection();" href="javascript:void(0);">Вернуться</a> к форме авторизации</dt>
            </div>
        </form>
    </div>
    ';
}else Router::redirect('/');
?>
