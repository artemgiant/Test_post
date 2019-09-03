$(document).ready(function() {
    $('body').on('click','.payment-btn',function () {
        var parentTd=$(this).parent('td');
            console.log(parentTd.length);
            if (parentTd.length>0){
               var form=$(parentTd[0]).find('div.liq-pay-button>form');
                if (form.length>0) form.submit();
            }
    })

    $("body").on('change', '#select_language', function (e) {
        tmp = window.location.pathname.replace('/ru/', '/');
        url = $(this).val() + tmp + window.location.search + window.location.hash;

        switch ($(this).val()) {
            case '/ru':
                local = 'ru';
                break;
            default:
                local = 'ua';
        }

        var datastr="locale="+local;
        var date = new Date(new Date().getTime() + 30 * 24 * 60 * 60 * 1000);

        $.ajax({
            url: '/post/ajax/set-locale',
            type: 'POST',
            data: datastr,
            success: function(mess) {
                    console.log('success_'+local);
                console.log(mess);
           },
            error:function(mess) {
                console.log('error_'+local);
            }
        })
        document.cookie = "local=" + local + "; path=/; domain=" + window.location.hostname + ";expires=" + date.toUTCString();
        window.location = url;

    });

//    $(".payment-btn").click(function () {
//        $("input[name='btn_text']").click();
//    });

$('.selectpicker').selectpicker();
    // console.log($('button[data-id=select_language]').attr('title')[0]);

   $('form').on('keyup','input#address_form_city,' +
       '#address_form_regionOblast,' +
       '#address_form_street,' +
       '#address_form_userFirstName,' +
       '#address_form_userLastName' +
        "#address_form_house," +
       "#address_form_apartment"



       ,function () {
        var pattern = new RegExp('^([a-zA-Z0-9\\.\\,\\@,\\s]+)$'),
            el =$(this).closest('.form-group');

        var message ="Форма поддерживает только латинские символы";
           if($('button[data-id=select_language]').attr('title')[0] == 'У'){
               message ="Форма пiдтримує‎ тiльки латинськi символи";
           }
        if(!pattern.test( $(this).val())){
            if(!el.find('span.text-danger')[0]){
                el.append('<span class="message_error text-danger">'+message+'</span>');
            }else{
                el.find('.text-danger').text(message);
            }
            $("button:submit").addClass('disabled').attr({"disabled":true}).css({"cursor":"not-allowed"});
        }else {
            el.find('span.text-danger').remove();
            if(!$('body').find('span.text-danger')[0]){
                $("button:submit").removeClass('disabled').attr({"disabled":false}).css({"cursor":"pointer"});
            }

     }
 });
    $('form').on('keyup',"#order_form_sendDetailLength" +
        ",#order_form_sendDetailWidth" +
        ",#order_form_sendDetailHeight" +
        ",#order_form_products_0_price" +
        ",#order_form_products_0_count" +
        ",#address_form_zip" +
        ",#address_form_phone" +
        ",#order_form_sendDetailWeight"
        ,function () {
        var pattern = new RegExp('^([\\s,0-9\\.\\,]+)$'),
            el =$(this).closest('.form-group'),
            massege_1 ="Введите только цифры.";
            if($('button[data-id=select_language]').attr('title')[0] == 'У'){
                massege_1 ="Введіть лише цифри.";
            }
        if(!pattern.test( $(this).val())){
            if(!el.find('span.text-danger')[0]){

                el.append('<span class="message_error text-danger">'+massege_1 +'</span>');
            }else{
                el.find('.text-danger').text(massege_1);
            }
            $("button:submit").addClass('disabled').attr({"disabled":true}).css({"cursor":"not-allowed"});
        }else {
            el.find('span.text-danger').remove();
            if(!$('body').find('span.text-danger')[0]){
                $("button:submit").removeClass('disabled').attr({"disabled":false}).css({"cursor":"pointer"});
            }

        }

        });


// Модальное окно

// открыть по кнопке

    if($('#modal_window')[0]){
        $('.js-overlay-campaign').fadeIn();
        $('.js-overlay-campaign').addClass('disabled');
    }


// закрыть на крестик
    $('.js-close-campaign').click(function() {
        $('.js-overlay-campaign').fadeOut();

    });

// закрыть по клику вне окна
    $(document).mouseup(function (e) {
        var popup = $('.js-popup-campaign');
        if (e.target!=popup[0]&&popup.has(e.target).length === 0){
            $('.js-overlay-campaign').fadeOut();

        }
    });










});

