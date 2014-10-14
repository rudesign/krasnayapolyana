<?php class_exists('Core', false) or die();

echo '
<form action="/search/" method="GET">
    <div class="small white micro-section">Что-нибудь ищете?</div>
    <input value="'.encodeHTMLEntities($_GET['q']).'" name="q" type="text" />
    <button class="buttons">Поехали!</button>
</form>
';
?>