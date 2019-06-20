$(document).ready(function() {
    $('body').on('click','.payment-btn',function () {
        var parentTd=$(this).parent('td');
            console.log(parentTd.length);
            if (parentTd.length>0){
               var form=$(parentTd[0]).find('div.liq-pay-button>form');
                if (form.length>0) form.submit();
            }
    })

//    $(".payment-btn").click(function () {
//        $("input[name='btn_text']").click();
//    });
});

