<?php
$uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

echo '
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>404</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta content="width=1100,maximum-scale=1.0" name="viewport">
        <link href="/css/reset.css" rel="stylesheet" />
        <link href="/js/selectbox/css/selectox.css" rel="stylesheet" />
        <link href="/css/typo.css?v=1" rel="stylesheet" />
        <link href="/css/common.css?v=1" rel="stylesheet" />
        <link rel="icon" type="image/ico" href="/favicon.ico" />
    </head>
<body>

    <div class="content-wrapper">

        <div class="small orange callback-contols">
            <div>
                <a href="javascript:toggleCallbackForm();">Заказать бесплатный звонок</a>
            </div>
        </div>

        <div class="header">

            <ul class="small l mini-section">

        <li class="blue">Офис продаж в Сочи::</li>
        <li>Тел.: +7 (862) 235-84-00 | +7 (862) 254-80-54</li>
        <li>Факс: +7 (862) 267-03-47</li>
        <li class="inline-icons"><i class="icq"></i><span>389-128-979</span><i class="skype"></i><span>tour-shop</span></li>
            </ul>

            <ul class="small r">

        <li class="blue">Офис продаж в Москве:</li>
        <li>Тел.: +7 (495) 988-43-77 | +7 (495) 797-05-84</li>
        <li>Факс: +7 (495) 628-12-90</li>
        <li class="inline-icons"><i class="icq"></i><span>664-691-565</span><i class="skype"></i><span>moscow.tour</span></li>
            </ul>

            <div class="clear"></div>
        </div>

        <div class="through-teaser">
            <div>
                <div class="search-form">
                    <div class="micro-nav">
                        <a href="/"></a><i></i><a href="/sitemap/"></a><i></i><a href="mailto:220619@gmail.com"></a>
                    </div>
                    <div class="social">
                        <div class="l"><a href="http://facebook.com" target="_blank"></a></div>
                        <div class="r"><a href="http://vk.com" target="_blank"></a></div>
                        <div class="clear"></div>
                    </div>

                    <form action="/search/" method="GET">
                        <div class="small white micro-section">Что-нибудь ищете?</div>
                        <input value="" name="q" type="text" />
                        <button class="buttons">Поехали!</button>
                    </form>

                </div>

                <div class="overlay"></div>
            </div>

            <div>
                <div class="white small callback-form">

                <form method="POST">
                    <input name="code" value="3ed7be" type="hidden" />
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
                            <div class="td"><img src="/img/captcha.jpg?rand=2910654" /></div>
                            <div class="td"><input name="entered" type="text" maxlength="4" autocomplete="off" /></div>
                            <div class="td"><button onClick="return submitCallbackForm();" class="buttons">ОК</button></div>
                        </div>
                    </div>
                    <div class="centered">
                        <a href="javascript:toggleCallbackForm();">Закрыть</a> х
                    </div>
                </form>

                </div>
            </div>
        </div>

        <div class="content">

    <div class="extra-section">
        <div class="l lm c23">
            <div class="text mini-section">
                <br />
                Страница по этому адресу не актуальна.
                <br />
                Добро пожаловать на <a href="/">стартовую страницу сайта</a>.
            </div>
        </div>
        <div class="r c14">

        </div>
        <div class="clear"></div>
    </div>
        </div>

        <div class="blue-bg white footer">
            <div id="fs1" class="logo">
                <a href="index.html"></a>
            </div>
            <div id="fs2" class="small">
                <ul>
                    <li class="extrablack sochi title">Офис продаж в Сочи::</li><li>Тел.: +7 (862) 235-84-00 </li><li style="padding-left:28px;"> +7 (862) 254-80-54</li><li>Факс: +7 (862) 267-03-47</li>
        <li class="inline-icons"><i class="icq"></i><span>389-128-979</span><i class="skype"></i><span>tour-shop</span></li>
                </ul>
            </div>
            <div id="fs3" class="small">
                <ul>
                    <li class="extrablack sochi title">Офис продаж в Москве::</li><li>Тел.: +7 (495) 988-43-77 </li><li style="padding-left:28px;"> +7 (495) 797-05-84</li><li>Факс: +7 (495) 628-12-90</li>
        <li class="inline-icons"><i class="icq"></i><span>664-691-565</span><i class="skype"></i><span>moscow.tour</span></li>
                </ul>
            </div>
            <div id="fs4" class="small">
                <div class="extrablack title super-micro-section">Группа компаний "Курортный Отдых:</div>
                <ul><li><a href="/services/">Услуги</a></li><li><a href="/agencies/">Агентствам</a></li><li><a href="/about-us/">О компании</a></li></ul><ul><li><a href="/car-rent/">Car rent</a></li><li><a href="/transfer/">Transfer</a></li><li><a href="/guide/">Guide</a></li></ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>

    <script src="/js/jquery-1.10.2.min.js" language="javascript" type="text/javascript"></script>
    <script src="/js/jquery.form.min.js" language="javascript" type="text/javascript"></script>
    <script src="/js/jquery-ui/jquery-ui-1.10.3.custom.min.js" language="javascript" type="text/javascript"></script>
    <script src="/js/selectbox/js/jquery.selectbox.min.js" language="javascript" type="text/javascript"></script>
    <script src="/js/common.js?v=1" language="javascript" type="text/javascript"></script>
</body>
</html>
';

