$(document).ready(function(){
    runCarousel();

    initFancybox();

    $( ".datepicker .date" ).datepicker({
        showOn: "button",
        buttonImage: "/img/datepicker.png",
        buttonImageOnly: true,
        dateFormat: "mm.dd.y"
    });

    //$('select.selectbox').selectbox();


    /*
    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal',
        increaseArea: '20%' // optional
    });
    */

    $('.customSelect').customSelect();

    switchSections(0);
    switchSFHiddenSections(0);

});


function runCarousel(){
    $('.jcarousel').jcarousel();

    $('.jcarousel-controls.prev')
        .on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        })
        .on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        })
        .jcarouselControl({
            target: '-=1'
        });

    $('.jcarousel-controls.next')
        .on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        })
        .on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        })
        .jcarouselControl({
            target: '+=1'
        });
}

function toggleCallbackForm(){
    var container = $('.callback-form');
    if(container.is(':visible')){
        container.fadeOut('fast');
    }else{
        container.fadeIn('fast');
    }
}

function submitCallbackForm(){

    var container = $('.callback-form');
    var form = container.find('form');

    var options = {
        success: function (response){
            if(response.message){
                alert(response.message);
            }else if(response.uri){
                document.location.assign(response.uri);
            }else if(response.okMessage){
                alert(response.okMessage);
                form.clearForm();
                toggleCallbackForm();
            }
        },
        data: {},
        url: '/ajaj/submitCallbackForm.php',
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}

function initFancybox(){
    $(".fancybox").fancybox({
        openEffect	: 'elastic',
        closeEffect	: 'elastic',

        helpers : {
            title : {
                type : 'inside'
            },
            media : {},
            overlay: {
                locked: false
            }
        }
    });
}

function toggleSubmenuDetails(index){
    var container = $('.submenu');
    var details = container.find('li.title').eq(index).find('ul');
    if(details.is(':visible')){
        details.slideUp('fast');
    }else{
        details.slideDown('fast');
    }
}

function switchSFHiddenSections(sectionIndex){
    var container = $('.services-form');

    if(container.length) {
        var sections = container.find('.form-sections');
        var section = sections.eq(sectionIndex);
        var checkers = section.find('.hidden-sections-switcher input');

        var mask = 0;
        checkers.each(function () {
            if ($(this).is(':checked')) mask += Math.round($(this).val());
        });

        sections.find('.hidden-sections').hide();

        if (mask > 2) {
            section.find('.hidden-sections').show();
        } else {
            switch (mask) {
                case 1:
                    section.find('.hidden-sections').eq(0).show();
                break;
                case 2:
                    section.find('.hidden-sections').eq(1).show();
                break;
            }
        }
    }
}

function toggleWayback(sectionIndex){
    var container = $('.services-form');
    var section = container.find('.form-sections').eq(sectionIndex);
    var wayback = section.find('.wayback');
    var oneway = section.find('input[name=oneway]:checked').val();

    if(oneway > 0) {
        wayback.hide();
    }else{
        wayback.show();
    }
}

function switchSections(sectionIndex)
{
    var container = $('.services-form');

    if(container.length) {

        var sections = container.find('.form-sections');
        var section = sections.eq(sectionIndex);
        var tabs = container.find('.form-title a');

        sections.hide();
        section.show();

        tabs.removeClass('active');
        tabs.eq(sectionIndex).addClass('active');

        switchSFHiddenSections(sectionIndex);
    }
}

function submitServicesForm(sectionIndex)
{
    var container = $('.services-form');

    if(container.length) {
        var section = container.find('.form-sections').eq(sectionIndex);
        var form = section.find('form');

        var options = {
            success: function (response) {
                if (response.message) {
                    alert(response.message);
                } else if (response.uri) {
                    document.location.assign(response.uri);
                } else {

                }
            },
            url: '/ajaj/submitServicesForm.php',
            dataType: 'json'
        };

        form.ajaxSubmit(options);
    }

    return false;
}

function submitBookingForm()
{
    var container = $('.booking-form');

    if(container.length) {
        var form = container.find('form');

        var options = {
            success: function (response) {
                if (response.message) {
                    alert(response.message);
                } else if (response.uri) {
                    document.location.assign(response.uri);
                } else {

                }
            },
            url: '/ajaj/submitBookingForm.php',
            dataType: 'json'
        };

        form.ajaxSubmit(options);
    }

    return false;
}