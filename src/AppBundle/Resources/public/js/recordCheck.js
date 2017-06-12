$("#recordCheckSubmitBtn").on("click", function () {
    var applicationId = $('#recordCheckApplicationId').val();
    var regex =/^\d*(?:\.\d{1,2})?$/;

    if(applicationId == ""){
        $('.alert span').html("তথ্য টি অবশ্যক");
        $('.alert').show().addClass("alert-danger").removeClass("success");
        return false;
    }
    if(!regex.test(applicationId)){
        $('.alert span').html("আপনার ইনপুট সংখ্যা হতে হবে");
        $('.alert').show().addClass("alert-danger").removeClass("success");
        return false;
    }
    $.get(Routing.generate('record_check', {'applicationId': applicationId}), function (response) {

            console.log(response);
        if(response=='No Result Found'){
            $('.alert span').html('কোন রেকর্ড নেই');
            $('.alert').show().addClass("alert-danger").removeClass("success");
        }else{
            $('.alert span').html('<div id="check">আপনার খতিয়ানটি পাওয়া গেছে , খতিয়ানটি দেখুন! <i class="fa fa-file"></i>  <i class="fa fa-hand-o-left"></i></div>');
            $('.alert').show().addClass("success").removeClass("alert-danger");
            $('#check').hover(function(){
                $(this).css("color", "#8BC34A");
            }, function(){
                $(this).css("color", "white");
            });
            $("#check").on("click", function () {
                window.open('/khatian/'+response,'_blank');
            });
        }
    });

});
$(".recordCheck").on("click", function () {
    $('.alert').hide();
    $(".recordCheckForm").show();
});

