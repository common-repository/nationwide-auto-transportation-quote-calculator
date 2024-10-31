jQuery(function() {
    
    jQuery("#pu_date").datepicker({
        format: "mm/dd/yy",
        startDate: "today",
        todayBtn: "linked",
        autoclose: true
    });
    
    //auto complete for pickup and delivery
    jQuery("#pu_city").autocomplete({
        source: 'https://nationwideautotransportation.com/wp-content/themes/canvas/ajax/search.php',
        minLength: 2,
        select: function(event, ui) {
            jQuery('#pu_state').val(ui.item.state);
            jQuery('#pu_zipcode').val(ui.item.zipcode);
        }
    });
    jQuery("#do_city").autocomplete({
        source: 'https://nationwideautotransportation.com/wp-content/themes/canvas/ajax/search.php',
        minLength: 2,
        select: function(event, ui) {
            jQuery('#do_state').val(ui.item.state);
            jQuery('#do_zipcode').val(ui.item.zipcode);
        }
    });
    //get the city and state from zip
    jQuery('#pu_zipcode').on('change', function() {
        jQuery('#pu_zipcode').addClass("ui-autocomplete-loading"); 
        var zip = jQuery(this).val();
        jQuery.post('https://nationwideautotransportation.com/wp-content/themes/canvas/ajax/getZip.php', {
            zipcode: zip
        }, function(data) {
            var values = JSON.parse(data);
            if(values){
                var address = values[0];
                jQuery("#pu_city").val(address.city);
                jQuery('#pu_state').val(address.state);
                jQuery(this).val(address.zipcode);
            }
            jQuery('#pu_zipcode').removeClass("ui-autocomplete-loading"); 
        });
    });
    //get the city and state from zip
    jQuery('#do_zipcode').on('change', function() {
        jQuery('#do_zipcode').addClass("ui-autocomplete-loading"); 
        var zip = jQuery(this).val();
        jQuery.post('https://nationwideautotransportation.com/wp-content/themes/canvas/ajax/getZip.php', {
            zipcode: zip
        }, function(data) {
            var values = JSON.parse(data);
            var address = values[0];
            jQuery("#do_city").val(address.city);
            jQuery('#do_state').val(address.state);
            jQuery(this).val(address.zipcode);
            jQuery('#do_zipcode').removeClass("ui-autocomplete-loading"); 
        });
    });
    
    //change zipcode to default on load
    jQuery('#pu_zipcode').val(jQuery('#pu_zipcode').data("default"));
    jQuery('#pu_zipcode').trigger("change");
    
    jQuery.post('https://nationwideautotransportation.com/wp-content/themes/canvas/ajax/getMakes.php', {
        mobile: 1
    }, function(data) {
        var makes = JSON.parse(data);
        jQuery('#v_make').html('<option value="">Make</option>');
        jQuery.each(makes, function(i, value) {
            jQuery('#v_make').append('<option value="' + ucfirst(value.model_make_id) + '">' + ucfirst(value.model_make_id) + '</option>');
        });
        jQuery('#v_make').removeAttr('disabled');
    });
    //get all models corrisponde to the make selected
    jQuery('body').on('change', '#v_make', function() {
        jQuery('.qc-loader').css({
               'opacity' : '1',
               'display' : 'block'
            });
        if (jQuery(this).val() != '') {
            var year = jQuery('#v_year').val();
            var make = jQuery(this).val();
            jQuery.post('https://nationwideautotransportation.com/wp-content/themes/canvas/ajax/getModels.php', {
                make: make,
                year: year
            }, function(data) {
                //hide loader
                jQuery('.qc-loader').css({
                   'opacity' : '0',
                   'display' : 'none'
                });
                var models = JSON.parse(data);
                jQuery('#v_model').html('<option value="">Model</option>')
                jQuery.each(models, function(i, value) {
                    jQuery('#v_model').append('<option value="' + value.model_name + '">' + value.model_name + '</option>');
                });
                jQuery('#v_model').removeAttr('disabled');
            });
        } else {
            return;
        }
    });
    
    jQuery("#qc-calculate-price-btn").on("click", function(){
        
        if(!nat_qc_validateForm(false)) { return false; }
        
        nat_qc_getQuotePrice();
    });
    
    jQuery("#qc-calculator-form").submit(function(e) {
        if(!nat_qc_validateForm(true)) { return false; }
    });   
});

function nat_qc_getQuotePrice()
{
    //show loader
    jQuery('.qc-loader').css({
       'opacity' : '1',
       'display' : 'block'
    });
    
    jQuery.post( "https://nationwideautotransportation.com/app/get-quote-price", 
        {
            'company_id':   75005,
            'pu_city':      jQuery("#pu_city").val(),
            'pu_state':     jQuery("#pu_state").val(),
            'pu_zipcode':   jQuery("#pu_zipcode").val(),
            'do_city':      jQuery("#do_city").val(),
            'do_state':     jQuery("#do_state").val(),
            'do_zipcode':   jQuery("#do_zipcode").val(),
            'pu_date':      jQuery("#pu_date").val(),
            'condition':    jQuery("#v_condition").val(),
            'carrier':      jQuery("#carrier_type").val(),
            'year':         jQuery("#v_year").val(),
            'make':         jQuery("#v_make").val(),
            'model':        jQuery("#v_model").val()
        },
           
        function( data ) {
            //console.log(data.data.price);
            jQuery( ".qc-quote-price span" ).html( "$" + data.data.price + ".00" );
            jQuery( ".qc-estimated-delivery span" ).html( data.data.estimatedDelivery + " Days" );
            jQuery( ".qc-quote-price, .qc-email-container, .qc-email-container input, .qc-submit-container, .qc-estimated-delivery" ).removeClass("hidden");
        
            //hide loader
            jQuery('.qc-loader').css({
               'opacity' : '0',
               'display' : 'none'
            });
    });
}

function ucfirst(str) {
    str += '';
    var f = str.charAt(0).toUpperCase();
    return f + str.substr(1);
}

function nat_qc_validateForm(validateEmail)
{
    if(validateEmail)
        var inputs = jQuery("#qc-calculator-form input[type='text']:not(.hidden)");
    
    else
        var inputs = jQuery("#qc-calculator-form input[type='text']:not(#email)");
    
    var selects = jQuery("#qc-calculator-form select");
    var flag = false;

    inputs.each(function( index ){
        if(jQuery(this).val() == "")
            {
                flag = true;
                jQuery(this).addClass("invalid-input");
            }
        else
            {
                jQuery(this).removeClass("invalid-input");
            }

    });
    
    var pu_zip = jQuery("#pu_zipcode").val();
    var do_zip = jQuery("#do_zipcode").val();
    
    if(!nat_qc_isValidZip(pu_zip))
        {
            jQuery("#pu_zipcode").addClass("invalid-input");
            flag = true;
        }
    
    if(!nat_qc_isValidZip(do_zip))
        {
            jQuery("#do_zipcode").addClass("invalid-input");
            flag = true;
        }


    selects.each(function( index ){
        if(jQuery(this).val() == "" || jQuery(this).val() == null)
            {
                flag = true;
                jQuery(this).addClass("invalid-input");
            }
        else
            {
                jQuery(this).removeClass("invalid-input");
            }

    });

    if (flag) {
        Command: toastr["error"]("Please make sure you fill all required fields", "The following errors has occured:");
        return false;
    }
    
    return true;
}

function nat_qc_isValidZip(zip)
{
    return (!isNaN(zip) && zip.toString().length == 5);
}