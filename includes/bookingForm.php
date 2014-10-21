<?php class_exists('Core', false) or die();

$query = Hotels::set();
$query->order = 'name ASC';
$hotels = Hotels::get($query);

echo '
<div class="lm small booking-form">
    <form method="post">
        <div class="section">
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Отель:</div>
                    <div class="cell">
                        <select name="hotel" class="w100 customSelect">
                            <option value="0">Не имеет значения</option>';
                            foreach($hotels as $row){
                                echo '
                                <option value="'.$row['id'].'"'.($_GET['hotel'] == $row['id'] ? ' selected' : '').'>'.$row['name'].'</option>';
                            }
                            echo '
                        </select>
                    </div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Кол-во человек<span class="red b">*</span>:</div>
                    <div class="cell">
                        <div class="l">
                            <select name="peopleTotal" class="customSelect">
                                <option value="0"></option>';
                                for($i=1; $i<=7; $i++){
                                    echo '
                                    <option value="'.$i.'">'.$i.'</option>';
                                }
                                echo '
                            </select>
                        </div>
                        <div class="r tar">
                            <select name="rooms" class="r customSelect">
                                <option value="0"></option>';
                                for($i=1; $i<=4; $i++){
                                    echo '
                                    <option value="'.$i.'">'.$i.'</option>';
                                }
                                echo '
                            </select>
                        </div>
                        <div class="r" style="margin: 3px 5px 0 0;">Номеров:</div>
                    </div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Кол-во детей:</div>
                    <div class="cell"><input name="kids" type="text" class="w100" /></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Возраст детей:</div>
                    <div class="cell"><input name="kidsAge" type="text" class="w100" /></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Дата пребывания<span class="red b">*</span>  с:</div>
                    <div class="cell"><div class="datepicker"><input name="dateFrom" type="text" class="date w100" /></div></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">по:</div>
                    <div class="cell"><div class="datepicker"><input name="dateTo" type="text" class="date w100" /></div></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Категория номера:</div>
                    <div class="cell"><input name="roomsCategory" type="text" class="w100" /></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="vat cell">Дополнительные пожелания:</div>
                    <div class="cell"><textarea name="body" class="w100"></textarea></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Фамилия:<span class="red b">*</span></div>
                    <div class="cell"><input name="surname" type="text" class="w100" /></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Имя:<span class="red b">*</span></div>
                    <div class="cell"><input name="firstName" type="text" class="w100" /></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Отчество:</div>
                    <div class="cell"><input name="secondName" type="text" class="w100" /></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">E-mail:<span class="red b">*</span></div>
                    <div class="cell"><input name="email" type="text" class="w100" /></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Телефон:<span class="red b">*</span></div>
                    <div class="cell"><input name="phone" type="text" class="w100" /></div>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell"></div>
                    <div class="i cell">Для юридических лиц:</div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Название компании:</div>
                    <div class="cell"><input name="cName" type="text" class="w100" /></div>
                </div>
            </div>
            <div class="row-wrapper">
                <div class="row">
                    <div class="cell">Факс, на который будет выслан счет на оплату:</div>
                    <div class="cell"><input name="fax" type="text" class="w100" /></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="cell"></div>
            <div class="cell">
                <div class="l"><button onclick="return submitBookingForm();;" class="buttons" opt="Подождите...">Отправить</button></div>
                <!--<div class="r" style="padding-top: 7px;"><a onclick="$(\'.booking-form form\').clearForm();" href="javascript:void(0);">Очистить форму</a></div>-->
            </div>
        </div>
    </form>
</div>';
?>