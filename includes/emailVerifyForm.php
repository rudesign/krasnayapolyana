<?php class_exists('Core', false) or die();

if(Users::$current){
    echo '
    <div class="email-verify-form">
        <form class="forms" method="POST">
            <div class="section">
                Для подтверждения регистрации мы просим Вас подтвердить свой e-mail. <br />На указанный ниже e-mail будет отправлено письмо с инструкциями.
            </div>
            <div class="mini-section">
                <div class="l" style="width: 48%; margin-right: 2%;">
                    <dl class="label">Ваш e-mail</dl>
                <dt><input name="email" value="'.Users::$current['login'].'" type="text" class="w100" /></dt>
                </div>
                <div class="l" style="width: 48%; margin-right: 2%;">

                </div>
                <div class="clear"></div>
            </div>
            <button onclick="sendEmailVerificationCode(); return false;" opt="Подождите..." class="buttons black-buttons">Подтвердить</button>
        </form>
    </div>
    ';
}
?>
