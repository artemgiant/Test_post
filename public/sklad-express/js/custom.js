// import th from "../../bundles/sonatacore/vendor/moment/src/locale/th";

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

// $(document).on('keyup', 'form[name=address_form] input', function () {
 $('form[name=address_form]').find('input').keyup(function () {
     var message = $(this).attr('message'),
         pattern = new RegExp('^'+$(this).attr('pattern')+'$'),
         el =$(this).closest('.form-group');
    if(!pattern.test( $(this).val())){
    if(!el.find('span.text-danger')[0]){
        el.append('<span class="message_error text-danger">'+message+'</span>');
    }else{
        el.find('.text-danger').text(message);
    }
       $("button:submit").addClass('disabled').attr({"disabled":true}).css({"cursor":"not-allowed"});
   }else {
       el.find('span.text-danger').remove();
   }

 });
});

