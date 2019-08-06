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
    // console.log($('input#order_form_trackingNumber,#order_form_products_0_price')[0]);
   $('form').on('keyup','input#address_form_city,' +
       '#address_form_regionOblast,' +
       '#address_form_street,' +
       '#address_form_userFirstName,' +
       '#address_form_userLastName,' +
     'textarea:not(#support_Message,' +
       '#order_form_comment,' +
       '#order_form_products_0_descUa)'
       ,function () {
        var pattern = new RegExp('^([a-zA-Z0-9\\.\\,\\@,\\s]+)$'),
            el =$(this).closest('.form-group');
        if(!pattern.test( $(this).val())){
            if(!el.find('span.text-danger')[0]){
                el.append('<span class="message_error text-danger">В форме нельзя писать кириллицей, только латынь</span>');
            }else{
                el.find('.text-danger').text(' В форме нельзя писать кириллицей, только латынь');
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
        ",#address_form_house" +
        ",#address_form_apartment" +
        ",#address_form_zip" +
        ",#address_form_phone" +
        ",#address_form_apartment" +
        ",#order_form_sendDetailWeight"
        ,function () {
        var pattern = new RegExp('^([\\s,0-9\\.\\,]+)$'),
            el =$(this).closest('.form-group'),
            massege_1 ="Введите только цифры.";
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
        ($(this).attr('id') == "order_form_sendDetailWeight")?test_max_weight():'';

        });
function test_max_weight(){
        var  el =$('#order_form_sendDetailWeight').closest('.form-group'),
            max_weight = '',
            isVip = $('#order_form_userVip').val(),
            now_weight = $('#order_form_sendDetailWeight').val(),
            max_weight_VipEconom = $('#order_form_maxWeightEconomVip').val(),
            max_weight_Econom = $('#order_form_maxWeightEconom').val(),
            max_weight = (isVip==1)? max_weight_VipEconom : max_weight_Econom,
            massage_2 =' Выберете тип доставки';
        if($('#order_form_orderType option:selected').val()==1){
            var massage_2 = "Максимально допустиме значения "+max_weight;
        }
        if(Number(now_weight)>=Number(max_weight)+1){
            if(!el.find('span.text-danger')[0]){
                el.append('<span class="message_error text-danger">'+massage_2 +'</span>');
            }else{
                el.find('.text-danger').text(massage_2);
            }
            $("button:submit").addClass('disabled').attr({"disabled":true}).css({"cursor":"not-allowed"});
        }else {
            if(!$('body').find('span.text-danger')[0]){
                $("button:submit").removeClass('disabled').attr({"disabled":false}).css({"cursor":"pointer"});
            }

        }



    };

    // console.log($('#order_form_maxWeightEconomVip').val());
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

