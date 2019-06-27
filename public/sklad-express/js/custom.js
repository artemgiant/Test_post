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
        var date = new Date(new Date().getTime() + 30 * 24 * 60 * 60 * 1000);
        document.cookie = "local=" + local + "; path=/; domain=" + window.location.hostname + ";expires=" + date.toUTCString();
        $.ajax({
            url: '/post/ajax/set-locale',
            type: 'POST',
            data: {"local":local},
            success: function(mess) {
                    console.log('success_'.local);
           },
            error:function(mess) {
                console.log('error_'.local);
            }
        })
        window.location = url;
    });

//    $(".payment-btn").click(function () {
//        $("input[name='btn_text']").click();
//    });
});

