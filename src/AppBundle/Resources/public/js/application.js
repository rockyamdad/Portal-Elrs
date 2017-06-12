var form = $('#application');
var error = $('.alert-danger', form);
var success = $('.alert-success', form);

var telInput = $("#mobile"),
    errorMsg = $("#error-msg"),
    validMsg = $("#valid-msg");

// initialise plugin
var reset = function() {
    telInput.removeClass("error");
    telInput.closest('.form-group').removeClass('has-success');
    telInput.closest('.form-group').removeClass('has-error');
    errorMsg.addClass("hide");
    validMsg.addClass("hide");
};

form.on('click', function () {
    var intlNumber = telInput.intlTelInput("getNumber");
    if (intlNumber) {
        telInput.val(intlNumber);
    }
});
telInput.blur(function() {
    reset();
    if ($.trim(telInput.val())) {

        if (telInput.intlTelInput("isValidNumber")) {
            telInput.closest('.form-group').removeClass('has-error').addClass('has-success');
            validMsg.removeClass("hide error").addClass("has-success");
        } else {
            telInput.addClass("error");
            telInput.closest('.form-group').removeClass('has-success').addClass('has-error');
            errorMsg.removeClass("hide");
        }
    }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);


form.validate({
    errorElement: 'span',
    errorClass: 'help-inline',
    focusInvalid: false,
    ignore: ".ignore",
    rules: {
        'nid': {
            pattern: /^(\d{13}|\d{17})$/
        },
        'email': {
            email: true
        },
        'name':{
            required:true
        },
        'mobile':{
            required:true
        },
        'survey':{
            required:true
        },
        'division':{
            required:true
        },
        'district':{
            required:true
        },
        'upozila':{
            required:true
        },
        'mouza':{
            required:true
        },

        'khatianNo':{
            required:true,
            digits: true
        },
        'dagNo':{
            required:true
        },
        'sheetNo':{
            required:true
        },
        'plaintiffDefendant':{
            required:true
        },
         'lawyerName':{
            required:true
        },
         'caseNo':{
            required:true
        },
         'courtName':{
            required:true
        },
        'details':{
            required:true
        },
        'correction_reason':{
            required:true
        },
        'correction_info':{
            required:true
        }
    },
    messages : {
        'nid': "নম্বর টি সঠিক নয় ",
        'email': "আপনার ইমেইল টি বৈধ নয় ",
        'name': "তথ্য টি  আবশ্যক ",
        'survey': "তথ্য টি  আবশ্যক ",
        'mobile': "তথ্য টি  আবশ্যক ",
        'division': "তথ্য টি  আবশ্যক ",
        'district': "তথ্য টি  আবশ্যক ",
        'upozila': "তথ্য টি  আবশ্যক ",
        'mouza': "তথ্য টি  আবশ্যক ",
        'khatianNo':{
            'required':'তথ্য টি  আবশ্যক',
            'digits':'শুধু নম্বর দিতে হবে '
        },
        'dagNo': "তথ্য টি  আবশ্যক ",
        'sheetNo': "তথ্য টি  আবশ্যক ",
        'plaintiffDefendant': "তথ্য টি  আবশ্যক ",
        'lawyerName': "তথ্য টি  আবশ্যক ",
        'caseNo': "তথ্য টি  আবশ্যক ",
        'courtName': "তথ্য টি  আবশ্যক ",
        'details': "তথ্য টি  আবশ্যক ",
        'correction_reason': "তথ্য টি  আবশ্যক ",
        'correction_info': "তথ্য টি  আবশ্যক "
    },

    invalidHandler: function (event, validator) { //display error alert on form submit
        success.hide();
        error.show();
        Metronic.scrollTo(error, -200);
    },

    highlight: function (element) { // hightlight error inputs
        $(element)
            .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
    },

    unhighlight: function (element) { // revert the change done by hightlight
        $(element)
            .closest('.form-group').removeClass('has-error'); // set error class to the control group
    },

    success: function (label) {
        label
            .addClass('valid') // mark the current input as valid and display OK icon
            .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
    },
    errorPlacement: function(error, element) {
        if ($(element).attr('name') == 'mobile') {
            element.parents('.input-group').after(error);
        } else {
            error.insertAfter(element);
        }
    }

});
//apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
$('.select2me', form).change(function () {
    form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
});
