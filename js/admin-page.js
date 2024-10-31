jQuery(function() {

    jQuery("#wpbody input[type='text']").on("keyup", function(){
        setShortcode();
    });
    
    jQuery("#calculator-size, #show-logo").on("change", function(){
        setShortcode();
    });
    
    setShortcode();
    
});

function setShortcode()
{
    var shortcode = "[nat-quote-calculator"
    var sc =                jQuery("#shortcode");
    var title =             jQuery("#title").val();
    var mainColor =         jQuery("#main-color").val();
    var secondaryColor =    jQuery("#secondary-color").val();
    var submitBg =          jQuery("#submit-bg").val();
    var submitColor =       jQuery("#submit-color").val();
    var submitHoverBg =     jQuery("#submit-hover-bg").val();
    var submitHoverColor =  jQuery("#submit-hover-color").val();
    var bgImage =           jQuery("#bg-image").val();
    var overlay =           jQuery("#background-overlay").val();
    var size =              jQuery("#calculator-size").val();
    var additionalClass =   jQuery("#additional-class").val();
    var defaultZip =        jQuery("#default-zip").val();
    var showLogo =          jQuery("#show-logo").val();
    
    
    //Change title on the fly
    if(title != "")
        {
            shortcode += " title='" + title + "'";
            jQuery(".qc-quote-calculator h2").html(title);
        }
    else
        jQuery(".qc-quote-calculator h2").html(jQuery(".qc-quote-calculator h2").data("default"));
    
    //Change main color on the fly
    if(mainColor != "")
        {
            shortcode += " main-color='" + mainColor + "'";
            jQuery(".qc-main-color").css({"color": mainColor});
        }
    else
        jQuery(".qc-main-color").css({"color": ""});
    
    //Change secondary color on the fly
    if(secondaryColor != "")
        {
            shortcode += " secondary-color='" + secondaryColor + "'";
            jQuery("head").append(jQuery('<style class="secondary-color-style">.qc-quote-calculator h2:after { background: '+secondaryColor+' !important; }</style>'));
            jQuery(".qc-quote-calculator .qc-quote-price span, .qc-quote-calculator .qc-estimated-delivery span").css({"color": secondaryColor});
        }
    else
        {   
            jQuery(".secondary-color-style").each(function(id, style){
                jQuery(this).remove();
            });
            
            jQuery(".qc-quote-calculator .qc-quote-price span, .qc-quote-calculator .qc-estimated-delivery span").css({"color": ""});
        }
    
    //Change submit bg-color on the fly
    if(submitBg != "")
        {
            shortcode += " submit-bg='" + submitBg + "'";
            jQuery(".qc-submit-bg-color").css({"background" : submitBg});
        }
    
    else
        jQuery(".qc-submit-bg-color").css({"background" : ""});
    
    //Change submit color on the fly
    if(submitColor != "")
        {
            shortcode += " submit-color='" + submitColor + "'";
            jQuery(".qc-submit-color").css({"color" : submitColor});
        }
    
    else
        jQuery(".qc-submit-color").css({"color" : ""});
    
    //Change submit hover-bg-color on the fly
    if(submitHoverBg != "")
        {
            shortcode += " submit-hover-bg='" + submitHoverBg + "'";
            
            jQuery(".qc-submit-bg-color").hover(function(){
                jQuery(this).css("background", submitHoverBg);
                }, function(){
                jQuery(this).css("background", submitBg);
            });
        }
    
    //Change submit hover-color on the fly
    if(submitHoverColor != "")
        {
            shortcode += " submit-hover-color='" + submitHoverColor + "'";
            
            jQuery(".qc-submit-color").hover(function(){
                jQuery(this).css("color", submitHoverColor);
                }, function(){
                jQuery(this).css("color", submitColor);
            });
        }
    
    //Change bg-image on the fly
    if(bgImage != "")
        {
            shortcode += " bg-image='" + bgImage + "'";
            jQuery(".qc-quote-calculator").css({"background-image" : "url("+ bgImage +")"});
        }
    
    else
        jQuery(".qc-quote-calculator").css({"background-image": ""});
    
    //Change bg-overlay on the fly
    if(overlay != "")
        {
            shortcode += " overlay='" + overlay + "'";
            jQuery(".qc-quote-calculator").css({"box-shadow" : "inset 0 0 0 1000px " + overlay});
        }
    
    else
        jQuery(".qc-quote-calculator").css({"box-shadow": ""});
    
    //Change calulator size on the fly
    jQuery(".qc-quote-calculator").removeClass("small medium").addClass(size);
    
    if(size != "")
        shortcode += " size='" + size + "'";
    
    //Additional CSS Classes
    if(additionalClass != "")
        shortcode += " class='" + additionalClass + "'";
    
    //Default pu-zip
    if(defaultZip != "")
        shortcode += " default-zip='" + defaultZip + "'";
    
    //Show NAT Logo
    if(showLogo != "")
        {
            shortcode += " show-logo='" + showLogo + "'";
            jQuery(".qc-header").css({"display" : "none"});
        }
    
    else
        jQuery(".qc-header").css({"display" : "block"});
        
    //close shortcode
    shortcode += "]";
    
    sc.html(shortcode);
        
}