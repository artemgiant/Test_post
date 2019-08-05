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

   $('form').on('keyup','input:not(#exampleInputEmail1,#exampleInputPassword1),textarea:not(#support_Message,#order_form_comment,#order_form_products_0_descUa)',function () {
        var pattern = new RegExp('^([a-zA-Z0-9\\.\\,]+)$'),
            el =$(this).closest('.form-group');
        if(!pattern.test( $(this).val())){
            if(!el.find('span.text-danger')[0]){
                el.append('<span class="message_error text-danger"> В форме нельзя писать кириллицей, только латынь</span>');
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

});

