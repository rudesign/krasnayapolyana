$(document).ready(function(){
    runCarousel();

    initFancybox();

    $( ".datepicker .date" ).datepicker({
        showOn: "button",
        buttonImage: "/img/datepicker.png",
        buttonImageOnly: true,
        dateFormat: "mm.dd.y"
    });

    $('select').selectbox();
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
