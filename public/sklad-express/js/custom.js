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

    //"#address_form_apartment"
    $('form').on('keyup','input#address_form_city,' +
        '#address_form_regionOblast,' +
        '#address_form_street,' +
        '#address_form_userFirstName,' +
        '#address_form_userLastName,' +
        "#address_form_house"


        ,function () {
            var pattern = new RegExp('^([a-zA-Z0-9\\.\\,\\@,\\s\-\/]+)$'),
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
            var pattern = new RegExp('^([\\s,0-9+\\.\\,]+)$'),
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

    // $('form').on('keyup',"#order_form_sendDetailLength" +
    //     ",#order_form_sendDetailWidth" +
    //     ",#order_form_sendDetailHeight" +
    //     ",#order_form_products_0_price" +
    //     ",#order_form_products_0_count" +
    //     ",#order_form_sendDetailWeight"
    //     ,function () {
    //
    //     if(/[a-z!@#$%^&*()_+]+/.test($(this).val())){
    //         console.log('error');
    //         return false;
    //     }
    //
    //         var resReturn=0,
    //            volume=0,
    //             weight=($('#order_form_sendDetailWeight').val())?$('#order_form_sendDetailWeight').val():'1',
    //            s1=($('#order_form_sendDetailWidth').val())?$('#order_form_sendDetailWidth').val():'1',
    //            s2=($('#order_form_sendDetailHeight').val())?$('#order_form_sendDetailHeight').val():'1',
    //            s3=($('#order_form_sendDetailLength').val())?$('#order_form_sendDetailLength').val():'1';
    //             volume= (s1*s2*s3/5000).toFixed(3);
    //            var resW=(Number(weight)>Number(volume))?weight:volume;
    //
    //
    //         var WithCost = 0;
    // if(!$('#order_form_userVip').val()) {
    //     if (weight <= 100) {
    //         WithCost = 250;
    //     }
    //     if (weight <= 200 && weight >= 100) {
    //         WithCost = 300;
    //     }
    //     if (weight < 300 && weight >= 200) {
    //         WithCost = 350;
    //     }
    //     if (weight < 450 && weight >= 300) {
    //         WithCost = 400;
    //     }
    //     if (weight < 1000 && weight >= 450) {
    //         WithCost = 700;
    //     }
    //     if (weight < 1000 && weight >= 700) {
    //         WithCost = 700;
    //     }
    //     if (weight < 1500 && weight >= 1000) {
    //         WithCost = 1000;
    //     }
    //     if (weight < 4000 && weight >= 1500) {
    //         WithCost = 6000;
    //     }
    // }else {
    //     if (weight <= 100) {
    //         WithCost = 200;
    //     }
    //     if (weight <= 200 && weight >= 100) {
    //         WithCost = 250;
    //     }
    //     if (weight <= 300 && weight >= 200) {
    //         WithCost = 250;
    //     }
    //     if (weight <= 4000 && weight >= 100) {
    //         WithCost = 5000;
    //     }
    //     $.ajax({
    //         url: 'post/parcels/ajax/dhl/price',
    //         type: 'post',
    //         // data: {info:info},
    //         dataType: 'array',
    //         // beforeSend: function() {
    //         //     $('#sendajax').button('loading');
    //         // },
    //         // complete: function() {
    //         //     $('#sendajax').button('reset');
    //         // },
    //         success: function(res) {
    //            console.log(res);
    //         },
    //         error: function(xhr, ajaxOptions, thrownError) {
    //             alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    //         }
    //     });
    // }
    //
    //
    //         var message = "Для розрахунку вартості оберить тип доставки \"Економ\" ";
    //         if($('button[data-id=select_language]').attr('title')[0] == 'Р'){
    //             message ="Для расчета стоимости выберите тип доставки \"Эконом\"";
    //         }
    //        if($('#order_form_orderType option:selected').val() == 1){
    //            message = WithCost;
    //        };
    //         if($('#order_form_orderType option:selected').val() == 2){
    //             message = "Экспресс доставки автоматично розрахувати неможливо.";
    //             if($('button[data-id=select_language]').attr('title')[0] == 'Р'){
    //                 message ="Экспресс доставки автоматически рассчитать невозможно.";
    //             }
    //         };
    //
    //         $('#order_form_shippingCosts').val(message);
    //     $('#order_form_volumeWeigth').val(volume);
    //     $('#order_form_declareValue').val($('#order_form_products_0_price').val());
    //
    // });

    $(document).on('keypress',
        '#order_form_sendDetailWeight, ' +
        '#order_form_sendDetailLength, ' +
        '#order_form_sendDetailWidth, ' +
        '#order_form_sendDetailHeight, ' +
        '.order_form_product_count, ' +
        '.order_form_product_price',
        function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    $(".coupon-group").hide();
    $('#coupon_checkgox').on('click',function (e) {
        console.log('coupon');
            $(".coupon-group").fadeToggle();
    });

    $('#order_form_orderType , #order_form_addresses').change(function () {  coupon() });

    $('#order_form_Coupon').on('keyup',function () {  coupon();  });

    $('form').on('keyup',"#order_form_sendDetailWeight"  ,function () { coupon(); });


    function  coupon() {

       var weight = $('#order_form_sendDetailWeight').val(),
           code =  $('#order_form_Coupon').val(),
           type =  $('#order_form_orderType>option:selected').val();

        if(weight == '' || code=='' || type==''){console.log('empty'); return null;}

        var element =  $('#order_form_Coupon'),
            lengthCode = element.val().length,
        ru ="Код купона состоить из 30 символов у  вас "+lengthCode,
         ukr ="Код купона складається з 30 символів у вас " +lengthCode,
        code = element.val(),
        Weight = $('#order_form_sendDetailWeight').val(),
         DeliveryType = $('#order_form_orderType>option:selected').val(),
            DHLPrice = '';
        el =element.closest('.form-group');
        if(lengthCode!=30){ console.log('!!'); spanMessage(ukr,ru,el,'text-danger')};

        if(type == 2)DHLPrice = $('#order_form_shippingCosts').val();


    if(element.val().length== 30 && DeliveryType !=0){
    $.ajax({
                url: '/post/coupone/ajax',
                type: 'post',
                data: {code:code,Weight:Weight,DeliveryType:DeliveryType,DHLPrice:DHLPrice},
                dataType: 'json',
                // beforeSend: function() {
                //     $('#sendajax').button('loading');
                // },
                // complete: function() {
                //     $('#sendajax').button('reset');
                // },
                success: function(res) {
                    var data = res,
                     ru = "Цена доставки с использованиям купона составит "+data.priceCoupon +".  Возможное количество использований: "+data.quantity+" ",
                     ukr ="Ціна доставки з використанням купона складе "+data.priceCoupon +".  Можлива кількість використань: "+data.quantity+" ",
                        cl = 'text-green'
                    ;

                    if(data.priceCoupon){ $('#order_form_shippingCosts').val(data.priceCoupon);}

                    if(data.error){
                        ru = "Код купона не действителен";
                        ukr ="Код купона не дійсний";
                        cl = 'text-danger';
                    }
                    console.log(data);
                    spanMessage(ukr,ru,el,cl);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
    }

    };

    function spanMessage(ukr,ru,el,cl) {
       var message =ru;
        if($('button[data-id=select_language]').attr('title')[0] == 'У'){
            message =ukr;
        }
        if(!el.find('span.text-green')[0] && !el.find('span.text-danger')[0]){
            el.append('<span class="'+cl+'">'+message +'</span>');
        }else{
            el.find('span').text(message).attr({'class':cl});
        }

    }

});

