<script language="javascript">


    <?php class_exists('Core', false) or die();

        switch(Core::$item['alias']){
            default:
                $servicesTormTab = 0;
            break;
            case 'transfer':
                $servicesTormTab = 2;
            break;
        }

        echo 'var servicesTormTab = '.$servicesTormTab.';';
    ?>

</script>

<div class="services-form section">
    <div class="form-title">
        <a onclick="switchSections(0);" href="javascript:void(0);">Прокат авто</a>
        <a onclick="switchSections(1);" href="javascript:void(0);">Аренда с водителем</a>
        <a onclick="switchSections(2);" href="javascript:void(0);">Трансфер</a>
    </div>

    <div class="inputs">

        <!-- section 0 -->
        <div class="form-sections">
            <form action="http://ru.rentacarsochi.com/order/" method="POST">
                <input type="hidden" value="short" name="mode">
                <input type="hidden" value="1" name="type">
                <!-- left column -->
                <div class="lc">
                    <div class="rows">
                        <div class="title">Автомобиль:</div>
                        <?php showAutoSelector(); ?>
                    </div>
                    <div class="rows">
                        <div class="title mini-section">
                            Дополнительно:
                        </div>
                        <ul class="hidden-sections-switcher inline vert-set">
                            <li><input type="checkbox" name="extra1" value="1" onClick="switchSFHiddenSections(0);" id="type1" /><label for="type1">+ отель</label> </li>
                            <li><input type="checkbox" name="extra2" value="2" onClick="switchSFHiddenSections(0);" id="type2"><label for="type2">+ авиа</label></li>
                        </ul>
                    </div>
                </div>
                <!-- right column -->
                <div class="rc">
                    <div class="rows">
                        <div class="title">Подача:</div>
                        <ul class="inline">
                            <li class="l datepicker" style="width: 42%;">
                                <input name="value3" type="text" class="date" />
                            </li>
                            <li class="r">
                                <?php
                                sfShowMinsSelector('minute3');
                                ?>
                            </li>
                            <li class="r mins-sep">:</li>
                            <li class="r">
                                <?php
                                sfShowHoursSelector('hour3');
                                ?>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <div class="rows">
                        <div class="title">Возврат:</div>
                        <ul class="inline">
                            <li class="l datepicker" style="width: 42%;">
                                <input name="value4" type="text" class="date" />
                            </li>
                            <li class="r">
                                <?php
                                sfShowMinsSelector('minute4');
                                ?>
                            </li>
                            <li class="r mins-sep">:</li>
                            <li class="r">
                                <?php
                                sfShowHoursSelector('hour4');
                                ?>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
                <!-- hidden section 0 -->
                <div class="hidden-sections">
                    <!-- hidden left column -->
                    <div class="lc">
                        <div class="rows">
                            <div class="title">Курорт</div>
                            <div class="clear"></div>
                            <?php showResortsSelector(); ?>
                        </div>
                    </div>
                    <!-- hidden right column -->
                    <div class="rc">
                        <div class="title">Отель (не обязательно)</div>
                        <input name="hotel" class="w100" type="text" />
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- hidden section 1 -->
                <div class="hidden-sections">
                    <!-- hidden left column -->
                    <div class="lc">
                        <div class="rows">
                            <div class="title">Перелет из:</div>
                            <div class="clear"></div>
                            <input name="aviaFrom" class="w100" type="text" />
                        </div>
                        <div class="rows">
                            <div class="title">Перелет в:</div>
                            <div class="clear"></div>
                            <input name="aviaTo" class="w100" type="text" />
                        </div>
                    </div>
                    <!-- hidden right column -->
                    <div class="rc">
                        <div class="rows">
                            <div class="l w50 free-checkers">
                                <input type="radio" value="1" name="oneway" onclick="toggleWayback(0);" /><label>туда</label>
                            </div>
                            <div class="r w50">
                                <div class="title">Туда:</div>
                                <div class="clear"></div>
                                <div class="inline">
                                    <div class="datepicker"><input name="aviaCheckIn" type="text" class="w100 date" /></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="rows">
                            <div class="l w50 free-checkers">
                                <input type="radio" value="0" name="oneway" onclick="toggleWayback(0);" checked /><label>туда и обратно</label>
                            </div>
                            <div class="r w50 wayback">
                                <div class="title">Обратно:</div>
                                <div class="clear"></div>
                                <div class="inline">
                                    <div class="datepicker"><input name="aviaCheckOut" type="text" class="w100 date" /></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="l"><input type="submit" class="buttons" value="Бронировать" onclick="return submitServicesForm(0);" /></div>
                <div class="r"><a href="http://ru.rentacarsochi.com/terms" target="_blank">Условия бронирования</a></div>
                <div class="clear"></div>
            </form>
        </div>

        <!-- section 1 -->
        <div class="form-sections">
            <form method="POST">
                <input name="section" value="1" type="hidden" />
                <!-- left column -->
                <div class="lc">
                    <div class="rows">
                        <div class="title">Автомобиль:</div>
                        <?php showAutoSelector(); ?>
                    </div>
                    <div class="rows">
                        <div class="title">E-mail:</div>
                        <input type="text" name="email" class="w100" />
                    </div>
                    <div class="rows">
                        <div class="title mini-section">
                            Дополнительно:
                        </div>
                        <ul class="hidden-sections-switcher inline vert-set">
                            <li><input type="checkbox" name="extra1" value="1" onClick="switchSFHiddenSections(1);" id="type11" /><label for="type11">+ отель</label> </li>
                            <li><input type="checkbox" name="extra2" value="2" onClick="switchSFHiddenSections(1);" id="type12"><label for="type12">+ авиа</label></li>
                        </ul>
                    </div>
                </div>
                <!-- right column -->
                <div class="rc">
                    <div class="rows">
                        <div class="title">Подача:</div>
                        <ul class="inline">
                            <li class="l datepicker" style="width: 42%;">
                                <input name="value3" type="text" class="date" />
                            </li>
                            <li class="r">
                                <?php
                                sfShowMinsSelector('minute3');
                                ?>
                            </li>
                            <li class="r mins-sep">:</li>
                            <li class="r">
                                <?php
                                sfShowHoursSelector('hour3');
                                ?>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <div class="rows">
                        <div class="title">Возврат:</div>
                        <ul class="inline">
                            <li class="l datepicker" style="width: 42%;">
                                <input name="value4" type="text" class="date" />
                            </li>
                            <li class="r">
                                <?php
                                sfShowMinsSelector('minute4');
                                ?>
                            </li>
                            <li class="r mins-sep">:</li>
                            <li class="r">
                                <?php
                                sfShowHoursSelector('hour4');
                                ?>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
                <!-- hidden section 0 -->
                <div class="hidden-sections">
                    <!-- hidden left column -->
                    <div class="lc">
                        <div class="rows">
                            <div class="title">Курорт</div>
                            <div class="clear"></div>
                            <?php showResortsSelector(); ?>
                        </div>
                    </div>
                    <!-- hidden right column -->
                    <div class="rc">
                        <div class="title">Отель (не обязательно)</div>
                        <input name="hotel" class="w100" type="text" />
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- hidden section 1 -->
                <div class="hidden-sections">
                    <!-- hidden left column -->
                    <div class="lc">
                        <div class="rows">
                            <div class="title">Перелет из:</div>
                            <div class="clear"></div>
                            <input name="aviaFrom" class="w100" type="text" />
                        </div>
                        <div class="rows">
                            <div class="title">Перелет в:</div>
                            <div class="clear"></div>
                            <input name="aviaTo" class="w100" type="text" />
                        </div>
                    </div>
                    <!-- hidden right column -->
                    <div class="rc">
                        <div class="rows">
                            <div class="l w50 free-checkers">
                                <input type="radio" value="1" name="oneway" onclick="toggleWayback(1);" /><label>туда</label>
                            </div>
                            <div class="r w50">
                                <div class="title">Туда:</div>
                                <div class="clear"></div>
                                <div class="inline">
                                    <div class="datepicker"><input name="aviaCheckIn" type="text" class="w100 date" /></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="rows">
                            <div class="l w50 free-checkers">
                                <input type="radio" value="0" name="oneway" onclick="toggleWayback(1);" checked /><label>туда и обратно</label>
                            </div>
                            <div class="r w50 wayback">
                                <div class="title">Обратно:</div>
                                <div class="clear"></div>
                                <div class="inline">
                                    <div class="datepicker"><input name="aviaCheckOut" type="text" class="w100 date" /></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="l"><input type="submit" class="buttons" value="Бронировать" onclick="return submitServicesForm(1);" /></div>
                <div class="r"><a href="http://ru.rentacarsochi.com/terms" target="_blank">Условия бронирования</a></div>
                <div class="clear"></div>
            </form>
        </div>

        <!-- section 2 -->
        <div class="form-sections">
            <form method="POST">
                <input name="section" value="2" type="hidden" />
                <div>
                    <div class="lc">
                        <div class="rows">
                            <div class="title">Трансфер из:</div>
                            <?php showResortsSelector('transferFrom'); ?>
                        </div>
                    </div>
                    <div class="rc">
                        <div class="rows">
                            <div class="title">Трансфер в:</div>
                            <input type="text" name="transferTo" class="w100" />
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div>
                    <div class="lc">
                        <div class="rows">
                            <input type="radio" value="1" name="returnTransfer" onclick="toggleReturn(2);" checked /><label>в одну сторону</label> <input type="radio" value="0" name="returnTransfer" onclick="toggleReturn(2);" /><label>+ возврат</label>
                        </div>
                    </div>
                    <div class="rc">
                        <div class="rows">
                            <div class="title">Отправление:</div>
                            <ul class="inline">
                                <li class="l w50 datepicker" style="width: 42%;">
                                    <input name="value3" type="text" class="date" />
                                </li>
                                <li class="r">
                                    <?php
                                    sfShowMinsSelector('minute3');
                                    ?>
                                </li>
                                <li class="r mins-sep">:</li>
                                <li class="r">
                                    <?php
                                    sfShowHoursSelector('hour3');
                                    ?>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="returnTransfer">
                    <div class="lc">
                        <div class="rows">
                            <div class="title">Трансфер из:</div>
                            <?php showResortsSelector('transferFromBack'); ?>
                        </div>
                    </div>
                    <div class="rc">
                        <div class="rows">
                            <div class="title">Трансфер в:</div>
                            <input type="text" name="transferToBack" class="w100" />
                        </div>
                        <div class="rows">
                            <div class="title">Отправление:</div>
                            <ul class="inline">
                                <li class="l datepicker" style="width: 42%;">
                                    <input name="value4" type="text" class="date" />
                                </li>
                                <li class="r">
                                    <?php
                                    sfShowMinsSelector('minute4');
                                    ?>
                                </li>
                                <li class="r mins-sep">:</li>
                                <li class="r">
                                    <?php
                                    sfShowHoursSelector('hour4');
                                    ?>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- left column -->
                <div class="lc">
                    <div class="rows">
                        <div class="title">E-mail:</div>
                        <input type="text" name="email" class="w100" />
                    </div>
                </div>
                <!-- right column -->
                <div class="rc">
                    <div class="rows">
                        <div class="title mini-section">
                            Дополнительно:
                        </div>
                        <ul class="hidden-sections-switcher inline vert-set">
                            <li><input type="checkbox" name="extra1" value="1" onClick="switchSFHiddenSections(2);" id="type21" /><label for="type21">+ отель</label> </li>
                            <li><input type="checkbox" name="extra2" value="2" onClick="switchSFHiddenSections(2);" id="type22"><label for="type22">+ авиабилет</label></li>
                        </ul>
                    </div>
                </div>
                <div class="clear"></div>
                <!-- hidden section 0 -->
                <div class="hidden-sections">
                    <!-- hidden left column -->
                    <div class="lc">
                        <div class="rows">
                            <div class="title">Курорт</div>
                            <div class="clear"></div>
                            <?php showResortsSelector(); ?>
                        </div>
                    </div>
                    <!-- hidden right column -->
                    <div class="rc">
                        <div class="title">Отель (не обязательно)</div>
                        <input name="hotel" class="w100" type="text" />
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- hidden section 1 -->
                <div class="hidden-sections">
                    <!-- hidden left column -->
                    <div class="lc">
                        <div class="rows">
                            <div class="title">Перелёт из:</div>
                            <div class="clear"></div>
                            <input name="aviaFrom" class="w100" type="text" />
                        </div>
                        <div class="rows">
                            <div class="title">Перелёт в:</div>
                            <div class="clear"></div>
                            <input name="aviaTo" class="w100" type="text" />
                        </div>
                    </div>
                    <!-- hidden right column -->
                    <div class="rc">
                        <div class="rows">
                            <div class="l w50 free-checkers">
                                <input type="radio" value="1" name="oneway" onclick="toggleWayback(2);" /><label>в одну сторону</label>
                            </div>
                            <div class="r w50">
                                <div class="title">Вылет:</div>
                                <div class="clear"></div>
                                <div class="inline">
                                    <div class="datepicker"><input name="aviaCheckIn" type="text" class="w100 date" /></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="rows">
                            <div class="l w50 free-checkers">
                                <input type="radio" value="0" name="oneway" onclick="toggleWayback(2);" checked /><label>+ обратно</label>
                            </div>
                            <div class="r w50 wayback">
                                <div class="title">Обратно:</div>
                                <div class="clear"></div>
                                <div class="inline">
                                    <div class="datepicker"><input name="aviaCheckOut" type="text" class="w100 date" /></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="l"><input type="submit" class="buttons" value="Бронировать" onclick="return submitServicesForm(2);" /></div>
                <div class="r conditions"><a href="http://ru.rentacarsochi.com/terms" target="_blank">Условия бронирования</a></div>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>

<?php
    function sfShowHoursSelector($name = '')
    {
        if(!empty($name)) {
            $selected = 10;

            echo '<select name="' . $name . '" class="customSelect">';
            for($i = 0; $i < 24; $i++) {
                $v = $i < 10 ? '0'.$i : $i;
                echo '<option value="'.$v.'"'.($i == $selected ? ' selected' : '').'>'.$v.'</option>';
            }
            echo '</select>';
        }
    }

    function sfShowMinsSelector($name = '')
    {
        if(!empty($name)) {
            echo '<select name="' . $name . '" class="customSelect">';
            for($i = 0; $i < 60; $i+=10) {
                $v = $i < 10 ? '0'.$i : $i;
                echo '<option value="'.$v.'">'.$v.'</option>';
            }
            echo '</select>';
        }
    }

    function showAutoSelector( $name = 'value_2' )
    {
        $query = Autos::set();
        if($rows = Autos::get($query)){
            echo '
            <select name="'.$name.'" class="customSelect">
                <option value="0">выберите...</option>';
                foreach($rows as $row){
                    echo '<option value="'.$row['remoteId'].'">'.$row['name'].'</option>';
                }
            echo '</select>';
        }
    }

    function showResortsSelector( $name = 'resort' )
    {
        $query = Resorts::set();

        $query->order = 'name ASC';

        if($rows = Resorts::get($query)) {
            echo '
            <select name="'.$name.'" class="customSelect">
                <option value="">выберите...</option>';
                foreach($rows as $row) {
                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                }
            echo '</select>';
        }
    }
?>