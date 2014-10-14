<?php class_exists('Core', false) or die();

echo '
<div id="login-form" class="forms">
    <a href="#" class="modal-close"></a>


    <form action="/" method="post">
        <ul class="inline">
            <li>
                <dl>E-mail</dl>
                <dt><input name="login" type="text" class="fw" /></dt>
            </li>
            <li>
                <dl>Пароль</dl>
                <dt><input name="password" type="password" class="fw" /></dt>
            </li>
        </ul>
        <button id="submit" class="buttons green-buttons" opt="Подождите...">Войти</button>
    </form>
</div>
';
?>