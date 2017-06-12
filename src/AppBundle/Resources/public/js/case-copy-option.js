var CaseCopyOption = function (){

    var multipleMouza = true;

    function init(param)
    {
        if (param !== undefined) {
            if ( param.hasOwnProperty('multipleMouza') ) {
                multipleMouza = param.multipleMouza;
            }
        }

        var select2DistrictData = [];
        var select2DistrictDataCitizen = [];
        var select2District = $('.po-district').select2(
            {
                id: function(e) {return e.id; },
                data:{results: select2DistrictData, text:'text'},
                placeholder: 'নির্বাচন করুণ',
                formatSelection: formatResult,
                formatResult: formatResult,
                allowClear: true
            });

        var select2DistrictCitizen = $('.citizen-district').select2(
            {
                id: function(e) {return e.id; },
                data:{results: select2DistrictDataCitizen, text:'text'},
                placeholder: 'নির্বাচন করুণ',
                formatSelection: formatResult,
                formatResult: formatResult,
                allowClear: true
            });



        $(".citizen-division").change(function() {

            if(!$(".citizen-district").length) {
                return;
            }

            $(".citizen-district").select2("val", "");
            var divisionId = $(this).val();
            if(divisionId){
                $("#citizenDivision").after("<div class=\"imageshow\">. </div>");
            }
            clearSelect2Fields([select2DistrictDataCitizen]);

            if(divisionId != "") {
                $.get(Routing.generate('combo_districts', {'divisionId': divisionId}), function (response) {
                    for (var k in response) {
                        console.log(k, response[k]);
                        select2DistrictDataCitizen.push(response[k]);
                        $('.imageshow').hide();
                    }
                });
            }

            //$(".po-district").change();
        });

        var select2UpozilaDataCitizen = [];
        var select2UpozilaCitizen = $('.citizen-upozila').select2(
            {
                id: function(e) {return e.id; },
                data:{results: select2UpozilaDataCitizen, text:'text'},
                placeholder: 'নির্বাচন করুণ',
                formatSelection: formatResult,
                formatResult: formatResult,
                allowClear: true
            });
        $(".citizen-district").change(function() {

            if(!$(".citizen-upozila").length) {
                return;
            }

            $(".citizen-upozila").select2("val", "");
            var districtId = $(this).val();
            if(districtId){
                $("#citizenDistrict").after("<div class=\"imageshow\">. </div>");
            }
            clearSelect2Fields([select2UpozilaCitizen]);

            if(districtId != "") {
                $.get(Routing.generate('combo_upozilas', {'districtId': districtId}), function (response) {
                    for (var k in response) {
                        console.log(k, response[k]);
                        select2UpozilaDataCitizen.push(response[k]);
                        $('.imageshow').hide();
                    }
                });
            }
        });

        $(".office-district").change(function() {
            var districtId = $(this).val();
            if(districtId != "") {
                $.get(Routing.generate('case_copy_court_fee_list', {'districtId': districtId}), function (response) {

                        $('.courtPrice').text(response.normalCourtFee);
                        $('.deliveryPrice').text(0);
                        $('.court-fee').val(response.normalCourtFee);
                        $('.delivery-fee').val(0);
                        calculateFee();

                });
            }
        });
        $(".deliveryUrgency").change(function() {
            var districtId = $('.office-district').select2("val");
            var deliveryUrgency = $('input[name="deliveryUrgency"]:checked').val();
            $.get(Routing.generate('case_copy_court_fee_list', {'districtId': districtId}), function (response) {

                if(deliveryUrgency == 'NORMAL'){
                    $('.courtPrice').text(response.normalCourtFee);
                    $('.court-fee').val(response.normalCourtFee);

                }else{
                    $('.courtPrice').text(response.urgentCourtFee);
                    $('.court-fee').val(response.urgentCourtFee);
                }
                    calculateFee();


            });

        });
        $(".deliveryWay").change(function() {
            var districtId = $('.office-district').select2("val");
            var deliveryWay = $('input[name="deliveryWay"]:checked').val();
            var district = $('.office-district').select2('data').text;
            var CitizenDistrict = $('.citizen-district').select2('data').text;
            $.get(Routing.generate('delivery_fee_list', {'districtId': districtId}), function (response) {
                        if(deliveryWay == 'DIRECT'){
                            $('.deliveryPrice').text(0);
                            $('.delivery-fee').val(0);
                        }else{
                            if(district == CitizenDistrict){
                                $('.deliveryPrice').text(response.deliveryFee);
                                $('.delivery-fee').val(response.deliveryFee);
                            }else{
                                $('.deliveryPrice').text(response.otherDistrictDeliveryFee);
                                $('.delivery-fee').val(response.otherDistrictDeliveryFee);
                            }
                        }
                        calculateFee();
            });

        });
        function calculateFee(){
            var courtPrice =  $('.courtPrice').text();
            var deliveryPrice =  $('.deliveryPrice').text();
            var total = parseInt(courtPrice) + parseInt(deliveryPrice);
            $('.total').text(total);
        }


        function clearSelect2Fields(fieldData) {
            for(var k in fieldData) {
                var last = fieldData[k].length;
                fieldData[k].splice(0, last);
            }
        }

        function formatResult(state) {
            if (!state.id) return state.text;
            return state.text;
        }
    }

    return {
        'init': init
    };
}();