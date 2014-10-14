var mouseKeyDown = null;

// Yandex map vars
var myMap, myPlacemark, coords;

$(document).ready(function(){
    $.ajaxSetup({
        scriptCharset: "utf-8"
    });

    $('.modal').leanModal({ closeButton: ".modal-close" });

    $(document).mousedown(function (e){ mouseKeyDown = e.which; });
    $(document).mouseup(function (e){ mouseKeyDown = null; });

    initLoginForm();

    watchIdClicks();

    initCKEditor();

    showGallery();

    initNewPicturesUpload();

    showAttachments();

    initNewAttachmentsUpload();

    initGridSortable();

    $('.selectedIds').click(function(){
        checkIfAllIdsChecked();
    });

    initMasonry();

    if(typeof(ymaps) == 'object') ymaps.ready(initYmap);
});

function initMasonry(){
    var container = $('.masonry');

    if(container.length){
        container.masonry({
            itemSelector: '.items'
        });
    }
}

function initLoginForm(){
    var container = $('#login-form');
    if(container.length){
        container.find('button#submit').click(function(){
            var options = {
                success: function (response){
                    if(response.message){
                        alert(response.message);
                    }else if(response.uri){
                        document.location.assign(response.uri);
                    }else if(response.html){
                        container.html(response.html);
                    }
                },
                data: {},
                url: '/panel/ajaj/login.php',
                dataType:  'json'
            };

            container.find('form').ajaxSubmit(options);

            return false;
        });
    }
}

function watchIdClicks(){
    var clicks = 0, timer, element;
    $('.grid .items .ids').bind('click', function(){
        element = this;

        clearTimeout(timer);

        timer = setTimeout(function(){
            if(clicks > 1){
                showDeleteConfirmation(element);
            }else{
                toggleVisibility(element);
            }
            clicks = 0;
        }, 300);

        clicks++;
    }).bind('dblclick', function(e){ e.preventDefault(); });
}

function toggleVisibility(element){
    var notice = waitNotify();

    $('.grid .items .ids div').remove();

    var newVisibility = 0;

    var obj = $(element);
    var id = obj.text();

    $.post("/panel/ajaj/toggleVisibility.php", {
            'id': id
        },
        function(data){
            if(!data.message){
                okNotify(notice);

                if(obj.hasClass('hidden')){
                    obj.removeClass('hidden');
                }else{
                    obj.addClass('hidden');
                }
            }else{
                notice = notify(notice, data.message, 'error');
                clearNotify(notice);
            }
        }, 'json');
}

function showDeleteConfirmation(element){
    var obj = $(element);
    var id = obj.text();

    clearConfirmations();

    obj.append($('.deleteConfirmation').html());
    obj.addClass('toDelete');
    obj.unbind('click');
    obj.find('div button').bind('click', function() { return deleteItem(id); });
}

function clearConfirmations(){
    var obj = $('.grid .items .ids');
    obj.find('div').remove();
    obj.removeClass('toDelete');
    obj.unbind('click');
    obj.unbind('dblclick');
    watchIdClicks();
}


function deleteItem(id){
    var notice = waitNotify(null);

    $.post("/panel/ajaj/deleteItem.php", {
            id: id
        },
        function(data){
            if(!data.message){
                $('.grid .items#id'+id).remove();
                okNotify(notice);
            }else{
                notice = notify(notice, data.message, 'error');
                clearNotify(notice);
            }
        }, 'json');

    return false;
}

// str type: "notice", "info", "success", "wait" or "error"
function notify(notice, text, type){
    if(typeof(type) == 'undefined') type = 'notice';

    var icon;
    switch (type){
        default:
            icon = 'icon-info-sign';
        break;
        case 'success':
            icon = 'icon-ok-sign';
            break;
        case 'wait':
            icon = 'icon-time';
        break;
        case 'error':
            icon = 'icon-exclamation-sign';
        break;
    }

    var options = {
        icon: icon,
        title: false,
        text: text,
        type: type,
        delay: 3000,
        hide: false,
        history: false,
        width: "350px"
    };

    if(notice){
        notice.pnotify(options);
    }else{
        notice = $.pnotify(options);
    }

    return notice;
}

function waitNotify(notice){
    return notify(notice, 'Подождите...', 'wait');
}

function okNotify(notice){
    notice = notify(notice, 'Сохранено', 'success');
    clearNotify(notice);

    return notice;
}

function clearNotify(notice){
    var options = {
        hide: true
    };
    notice.pnotify(options);
}

function save(){
    var notice = waitNotify();

    var checkboxes = [];
    $('form[name=edit] input[type=checkbox]').each(function(){
        checkboxes.push($(this).attr('name'));
    });

    var options = {
        success: function (response){
            if(response.message){
                notice = notify(notice, response.message, 'error');
            }else if(response.uri){
                document.location.assign(response.uri);
            }else{
                okNotify(notice);
            }
        },
        data: {
            checkboxes: checkboxes
        },
        url: '/panel/ajaj/save.php',
        dataType:  'json'
    };

    $('form[name=edit]').ajaxSubmit(options);
}

function initCKEditor(){
    if($('textarea.ck').length > 0){
        $('textarea.ck').ckeditor({
            filebrowserBrowseUrl : '/panel/ckeditor/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : '/panel/ckeditor/ckfinder/ckfinder.html?type=Images',
            filebrowserFlashBrowseUrl : '/panel/ckeditor/ckfinder/ckfinder.html?type=Flash',
            filebrowserUploadUrl : '/panel//ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : '/panel/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : '/panel/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
            //extraAllowedContent: '*[*](*)',
            extraAllowedContent: 'div a [*] (*)',
            language: 'ru'
        });
    }
}

// gallery
function showGallery(){
    var galleryInput = $('form input[name=gallery]');

    if(galleryInput.length){
        //var notice = notify(null, 'Отображение галереи...', 'info');

        var options = {
            success: function (response){
                if(response.message){
                    notice = notify(null, response.message, 'error');
                }else{
                    //clearNotify(notice);

                    $('ul.gallery').remove();

                    if(response.gallery){
                        galleryInput.before(response.gallery);

                        $( ".gallery" ).sortable({
                            update: function( event, ui ) {
                                changeGalleryGridItemsOrder(ui);
                            }
                        });
                    }
                }
            },
            data: {},
            url: '/panel/ajaj/showGallery.php',
            dataType: 'json'
        };

        $('form[name=edit]').ajaxSubmit(options);

    }
}

function showPictureTitleField(index){
    var titleFields = $('.gallery .title-field');

    titleFields.hide();
    titleFields.eq(index).fadeIn('fast');
}

function hideTitleFields(){
    $('.title-field').fadeOut('fast');
}

function savePictureTitle(index){
    var notice = waitNotify(null);

    $.post("/panel/ajaj/savePictureTitle.php", {
        gallery: $('form input[name=gallery]').val(),
        index: index,
        newTitle: $('.gallery .title-field textarea').eq(index).val()
    }, function(data){
        if(!data.message){
            $('form input[name=gallery]').attr('value', data.gallery);
            hideTitleFields();
            showGallery();
            okNotify(notice);
        }else{
            notice = notify(notice, data.message, 'error');
            clearNotify(notice);
        }

    }, 'json');

    return false;
}

function initNewPicturesUpload(){
    var percentsLoaded = 0;
    var notice;

    $('#upload-new-pict').fileupload({
        url: '/panel/ajaj/uploadNewPictures.php',
        dataType: 'json',
        singleFileUploads: false,
        progressInterval: 120,

        drop: function (e, data) {
            notice = waitNotify(null);
        },
        change: function (e, data) {
            notice = waitNotify(null);
        },
        done: function (e, data) {

            if(!data.result.message){
                $('form input[name=gallery]').attr('value', data.result.gallery);
                showGallery();
                notice = notify(notice, 'Изображения загружены', 'success');
            }else{
                notice = notify(notice, data.result.message, 'error');
            }
            clearNotify(notice);
        }
    });

    $('#upload-new-pict').bind('fileuploadsubmit', function (e, data) {
        data.formData = {
            gallery: $('form input[name=gallery]').val()
        };
    });
    $('#upload-new-pict').bind('fileuploadprogress', function (e, data) {
        percentsLoaded = Math.round((data.loaded/data.total)*100);
        notice = notify(notice, 'Загружено '+percentsLoaded+'%', 'wait');
    });
}

function changeGalleryGridItemsOrder(ui){
    var notice = waitNotify(null);

    $.post("/panel/ajaj/changeGalleryItemsOrder.php", {
        gallery:  $('form input[name=gallery]').val(),
        order: $('.gallery').sortable('toArray')
    }, function(result){
        if(!result.message){
            $('form input[name=gallery]').attr('value', result.gallery);
            showGallery();
            okNotify(notice);
        }else{
            notice = notify(notice, data.message, 'error');
            clearNotify(notice);
        }
    }, 'json');
}

function deletePicture(index){
    var notice = waitNotify(null);

    $.post("/panel/ajaj/deletePicture.php", {
        gallery: $('form input[name=gallery]').val(),
        index: index
    },function(data){
        if(!data.message){
            $('form input[name=gallery]').attr('value', data.gallery);
            $('.gallery li').eq(index).remove();
            showGallery();
            notice = notify(notice, 'Изображение удалено', 'success');
        }else{
            notice = notify(notice, data.message, 'error');
        }
        clearNotify(notice);

    }, 'json');
}

// attachments

function showAttachmentTitleField(index){
    var titleFields = $('.attachments .title-field');

    titleFields.hide();
    titleFields.eq(index).fadeIn('fast');
}

function saveAttachmentTitle(index){
    var notice = waitNotify(null);

    $.post("/panel/ajaj/saveAttachmentTitle.php", {
        attachments: $('form input[name=attachments]').val(),
        index: index,
        newTitle: $('.attachments .title-field textarea').eq(index).val()
    }, function(data){
        if(!data.message){
            $('form input[name=attachments]').attr('value', data.attachments);
            hideTitleFields();
            showAttachments();
            okNotify(notice);
        }else{
            notice = notify(notice, data.message, 'error');
            clearNotify(notice);
        }

    }, 'json');

    return false;
}

function initNewAttachmentsUpload(){
    var percentsLoaded = 0;
    var notice;

    $('#upload-new-attachments').fileupload({
        url: '/panel/ajaj/uploadNewAttachments.php',
        dataType: 'json',
        singleFileUploads: false,
        progressInterval: 120,

        drop: function (e, data) {
            notice = waitNotify(null);
        },
        change: function (e, data) {
            notice = waitNotify(null);
        },
        done: function (e, data) {

            if(!data.result.message){
                $('form input[name=attachments]').attr('value', data.result.attachments);
                showAttachments();
                notice = notify(notice, 'Файлы загружены', 'success');
            }else{
                notice = notify(notice, data.result.message, 'error');
            }
            clearNotify(notice);
        }
    });

    $('#upload-new-attachments').bind('fileuploadsubmit', function (e, data) {
        data.formData = {
            attachments: $('form input[name=attachments]').val()
        };
    });
    $('#upload-new-attachments').bind('fileuploadprogress', function (e, data) {
        percentsLoaded = Math.round((data.loaded/data.total)*100);
        notice = notify(notice, 'Загружено '+percentsLoaded+'%', 'wait');
    });
}

function showAttachments(){
    var attachmentsInput = $('form input[name=attachments]');

    if(attachmentsInput.length){

        $.post("/panel/ajaj/showAttachments.php", {
                attachments: attachmentsInput.val()
            },
            function(data){
                if(!data.message){
                    $('.attachments').remove();
                    attachmentsInput.before(data.attachments);
                }else{
                    var notice = notify(null, data.message, 'error');
                }
            }, 'json');
    }
}

function deleteAttachment(index){
    var notice = waitNotify(null);

    $.post("/panel/ajaj/deleteAttachment.php", {
        attachments: $('form input[name=attachments]').val(),
        index: index
    },function(data){
        if(!data.message){
            $('form input[name=attachments]').attr('value', data.attachments);
            $('.attachments li').eq(index).remove();
            showAttachments();
            notice = notify(notice, 'Файл удален', 'success');
        }else{
            notice = notify(notice, data.message, 'error');
        }
        clearNotify(notice);

    }, 'json');
}

function initGridSortable(){
    $('.sortable').sortable({
        update: function( event, ui ) {
            changeGridItemsOrder(ui);
        }
    });
}

function changeGridItemsOrder(ui){
    var notice = waitNotify(null);

    var order = $('.sortable').sortable('toArray');
    var index = ui.item.index();
    var data = {
        index: index,
        order: order
    };
    $.post("/panel/ajaj/changeGridItemsOrder.php", data, function(result){
        if(!result.message){
            okNotify(notice);
        }else{
            notice = notify(notice, result.message, 'error');
            clearNotify(notice);
        }
    }, 'json');
}

function selectAllIds(element){
    var checkboxes = $('.grid .items input[type=checkbox]');

    if($(element).prop('checked')){
        checkboxes.prop('checked', true);
    }else{
        checkboxes.prop('checked', false);
    }

    checkIfAllIdsChecked();
}

function checkIfAllIdsChecked(){
    var checkboxes = $('.grid .items input.selectedIds');
    var checkedCheckboxes = $('.grid .items input.selectedIds:checked');

    if(checkboxes.length > checkedCheckboxes.length){
        $('input[name=_checkAllIds]').prop('checked', false);
    }else{
        $('input[name=_checkAllIds]').prop('checked', true);
    }

    $('.grid .items').removeClass('selected');
    checkedCheckboxes.each(function(){
        $('.grid .items#id'+$(this).val()).addClass('selected');
    });
}

function executeGroupAction(){
    var action = $('.group-action-controls select[name=action]').val();
    var selectedIds = [];

    $('input.selectedIds:checked').each(function(){
        selectedIds.push($(this).val());
    });

    if(selectedIds.length){
        var notice = waitNotify(null);

        $.post("/panel/ajaj/executeGroupAction.php", {
            action: action,
            ids: selectedIds
        },function(data){
            if(!data.message){
                document.location.reload();
            }else{
                notice = notify(notice, data.message, 'error');
                clearNotify(notice);
            }
        }, 'json');
    }
}

function submitToSubcribers(buttonElement){
    var button = $(buttonElement);
    var resultContainer = $('.result');
    var wait = $('.wait');

    var clear = $('input[name=_clear]').val();
    var theme = $('input[name=name]').val();
    var body = $('textarea[name=body]').val();

    button.prop('disabled', true);
    button.addClass('gray-buttons');

    if(clear) wait.show();

    $.post("/panel/ajaj/submitToSubcribers.php", {
        'clear':clear,
        'theme':theme,
        'body':body
    },function(data){

        if(data.result) resultContainer.html(data.result);

        if(!data.haveOneMore){
            button.prop('disabled', false);
            button.removeClass('gray-buttons');
            $('input[name=_clear]').val(1);
            wait.hide();
        }else{
            $('input[name=_clear]').val(0);
            submitToSubcribers(buttonElement);
        }

        if(data.message){
            var notice = notify(notice, data.message, 'error');
            clearNotify(notice);
            wait.hide();
        }
    }, 'json');

    return false;
}

function showItemCategories(){

    var form = $('form[name=edit]');
    var container = form.find('.item-categories-container');
    var categories = form.find('input[name=_categories]').val();

    $.post("/panel/ajaj/showItemCategories.php",
        {categories: categories},
        function(data){
            container.html(data.html);
        }, 'json');
}

function saveItemCategories(){

    var form = $('form[name=edit]');
    var container = form.find('.item-categories-container');
    var inputToStore = form.find('input[name=_categories]');

    var options = {
        success: function (response){
            if(response.message){
                alert(response.message);
            }else if(response.uri){
                document.location.assign(response.uri);
            }else{
                inputToStore.val(response.categories);
                showItemCategories();
            }
        },
        url: '/panel/ajaj/saveItemCategories.php',
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}

function showCourseTeachers(){

    var form = $('form[name=edit]');
    var container = form.find('.course-teachers-container');
    var teachers = form.find('input[name=_teachers]').val();

    $.post("/panel/ajaj/showCourseTeachers.php",
        {teachers: teachers},
        function(data){
            container.html(data.html);
        }, 'json');
}

function saveCourseTeachers(){

    var form = $('form[name=edit]');
    var container = form.find('.course-teachers-container');
    var inputToStore = form.find('input[name=_teachers]');

    var options = {
        success: function (response){
            if(response.message){
                alert(response.message);
            }else if(response.uri){
                document.location.assign(response.uri);
            }else{
                inputToStore.val(response.teachers);
                showCourseTeachers();
            }
        },
        url: '/panel/ajaj/saveCourseTeachers.php',
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}

// Yampex map
function initYmap () {
    var form = $('form[name=edit]');
    var input = form.find('input[name=latLng]');
    var mapContainer = form.find('#y-map');
    var oldCoords = input.val();

    if(oldCoords != '') {
        coords = oldCoords.split(',');
    // spb by default
    }else{
        coords = [59.939095,30.315868];
    }

    if(mapContainer.length){
        //Определяем начальные параметры карты
        myMap = new ymaps.Map('y-map', {
            center: coords,
            zoom: 15,
            behaviors: ['scrollZoom', 'default']
        });
    }

    //Определяем элемент управления поиск по карте
    var SearchControl = new ymaps.control.SearchControl({noPlacemark:true});

    //Добавляем элементы управления на карту
    myMap.controls
    .add(SearchControl)
    .add('zoomControl')
    .add('typeSelector')
    .add('mapTools');

    placeYMapMark();

    //Отслеживаем событие выбора результата поиска
    SearchControl.events.add("resultselect", function (e) {
        coords = SearchControl.getResultsArray()[0].geometry.getCoordinates();
        placeYMapMark();
    });

    //Отслеживаем событие щелчка по карте
    myMap.events.add('click', function (e) {
        coords = e.get('coordPosition');
        placeYMapMark();
    });
}

function placeYMapMark (){
    var form = $('form[name=edit]');
    var input = form.find('input[name=latLng]');

    if(typeof (myPlacemark) == 'object') myMap.geoObjects.remove(myPlacemark);
    myPlacemark = new ymaps.Placemark(coords,{}, {preset: "twirl#redIcon", draggable: true});
    myMap.geoObjects.add(myPlacemark);

    //Отслеживаем событие перемещения метки
    myPlacemark.events.add("dragend", function (e) {
        coords = this.geometry.getCoordinates();
        placeYMapMark();
    }, myPlacemark);

    input.val(coords);
}

function issueLicense(){
    var form = $('form[name=edit]');

    var options = {
        success: function (response){
            if(response.message){
                alert(response.message);
            }else if(response.okMessage){
                alert(response.okMessage);
                if(response.uri) document.location.assign(response.uri);
            }
        },
        url: '/panel/ajaj/issueLicense.php',
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}