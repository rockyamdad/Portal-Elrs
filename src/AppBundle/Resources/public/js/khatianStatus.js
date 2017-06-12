$("#kahtianStatusSubmitBtn").on("click", function () {
    var applicationId = $('#applicationId').val();
    var regex =/^\d*(?:\.\d{1,2})?$/;

    if(applicationId == ""){
        $('.alert span').html("তথ্য টি অবশ্যক");
        $('.alert').show().removeClass("success").addClass("alert-danger");
        return false;
    }
    if(!regex.test(applicationId)){
        $('.alert span').html("আপনার ইনপুট সংখ্যা হতে হবে");
        $('.alert').show().removeClass("success").addClass("alert-danger");
        return false;
    }
    $.get(Routing.generate('khatian_status_check', {'applicationId': applicationId}), function (response) {

            console.log(response);
        if(response == 'কোন রেকর্ড নেই'){
            $('.alert span').html('কোন রেকর্ড নেই');
            $('.alert').show().addClass("alert-danger").removeClass("success");
        }else{
            if(response == 'PENDING'){
                $('.alert span').html('আপনার আবেদনটি  অনুমোদনের অপেক্ষায়  আছে ');
            }
            if(response == 'PROCESSING'){
                $('.alert span').html('আপনার আবেদনটি  প্রক্রিয়াকরণ হচ্ছে ');
            }
            if(response == 'READY_FOR_DELIVERY'){
                $('.alert span').html('আপনার আবেদনটি  বিতরণের  এর জন্য প্রস্তুত ');
            }
            if(response == 'DELIVERED'){
                $('.alert span').html('আপনার আবেদনটি  ইতিমধ্যে বিতরণ করা হয়েছে ');
            }
            $('.alert').show().addClass("success").removeClass("alert-danger");
        }

    });
});
$("#khatianSats").on("click", function () {
    $('.alert').hide();
    $(".kahtianStatusForm").show();
});
