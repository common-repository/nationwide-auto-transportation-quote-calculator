<?php 

    global $a;
    nat_qc_registerCalculatorAssets();
?>
<style>
    .qc-quote-calculator
    {
        background-image: url(<?=nat_qc_sanitizeURL($a['bg-image']); ?>);
        color: <?=esc_attr($a['main-color']); ?>;
        box-shadow: inset 0 0 0 1000px <?=esc_attr($a['overlay']); ?>;
    }
    
    .qc-quote-calculator .qc-loader:after
    {
        border-color: <?=esc_attr($a['secondary-color']); ?> !important;
    }
    
    .qc-quote-calculator .submit-container input, .qc-quote-calculator .qc-calculate-price-container input
    {
        background-color: <?=esc_attr($a['submit-bg']); ?>;
        color: <?=esc_attr($a['submit-color']); ?>;
    }
    
    .qc-quote-calculator .submit-container input:hover, .qc-quote-calculator .qc-calculate-price-container input:hover
    {
        background-color: <?=esc_attr($a['submit-hover-bg']); ?>;
        color: <?=esc_attr($a['submit-hover-color']); ?>;
    }
    
    .qc-quote-calculator .qc-quote-price span, .qc-quote-calculator .qc-estimated-price span
    {
        color: <?=esc_attr($a['secondary-color']); ?>;
    }
    
    .qc-quote-calculator h2
    {
        color: <?=esc_attr($a['main-color']); ?>;
    }
    
    .qc-quote-calculator h2:after
    {
        background: <?=esc_attr($a['secondary-color']); ?>;
    }
</style>

<div class="qc-quote-calculator <?=esc_attr($a['size']." ".$a['class']); ?>">
    <div class="qc-loader"></div>
    
    <?php
    if($a["show-logo"] != "false")
    {?>
        <div class="qc-header">
            <a href="https://nationwideautotransportation.com/">
                <img src="<?=plugin_dir_url( __FILE__ ) . 'img/nat-logo.png'?>" />
            </a>
        </div>
    <?php } ?>
    
    <h2><?=esc_html($a["title"]); ?></h2>
    <form action="" method="POST" id="qc-calculator-form">
        <div class="row">
            <div class="col-xs-5">
                <label class="bold" for="pu_city">Pickup City:</label>
                <input name="pu_city" id="pu_city" placeholder="Pickup City" autocomplete="off" type="text">
            </div>

            <div class="col-xs-3">
                <label class="bold" for="pu_state">State:</label>
                <select name="pu_state" id="pu_state">
                    <option value="">Select</option>
                    <option value="AK">AK</option>
                    <option value="AL">AL</option>
                    <option value="AZ">AZ</option>
                    <option value="AR">AR</option>
                    <option value="CA">CA</option>
                    <option value="CO">CO</option>
                    <option value="CT">CT</option>
                    <option value="DE">DE</option>
                    <option value="DC">DC</option>
                    <option value="FL">FL</option>
                    <option value="GA">GA</option>
                    <option value="ID">ID</option>
                    <option value="IL">IL</option>
                    <option value="IN">IN</option>
                    <option value="IA">IA</option>
                    <option value="KS">KS</option>
                    <option value="KY">KY</option>
                    <option value="LA">LA</option>
                    <option value="ME">ME</option>
                    <option value="MD">MD</option>
                    <option value="MA">MA</option>
                    <option value="MI">MI</option>
                    <option value="MN">MN</option>
                    <option value="MS">MS</option>
                    <option value="MO">MO</option>
                    <option value="MT">MT</option>
                    <option value="NE">NE</option>
                    <option value="NV">NV</option>
                    <option value="NH">NH</option>
                    <option value="NJ">NJ</option>
                    <option value="NM">NM</option>
                    <option value="NY">NY</option>
                    <option value="NC">NC</option>
                    <option value="ND">ND</option>
                    <option value="OH">OH</option>
                    <option value="OK">OK</option>
                    <option value="OR">OR</option>
                    <option value="PA">PA</option>
                    <option value="RI">RI</option>
                    <option value="SC">SC</option>
                    <option value="SD">SD</option>
                    <option value="TN">TN</option>
                    <option value="TX">TX</option>
                    <option value="UT">UT</option>
                    <option value="VT">VT</option>
                    <option value="VA">VA</option>
                    <option value="WA">WA</option>
                    <option value="WV">WV</option>
                    <option value="WI">WI</option>
                    <option value="WY">WY</option>
                </select>
            </div>

            <div class="col-xs-4">
                <label class="bold" for="pu_zipcode">Zip:</label>
                <input name="pu_zipcode" id="pu_zipcode" placeholder="Zipcode" maxlength="5" autocomplete="false" type="text" data-default="<?=esc_attr($a['default-zip']); ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-5">
                <label class="bold" for="do_city">Delivery City:</label>
                <input name="do_city" id="do_city" placeholder="Delivery City" autocomplete="off" type="text">
            </div>

            <div class="col-xs-3">
                <label class="bold" for="do_state">State:</label>
                <select name="do_state" id="do_state">
                    <option value="">Select</option>
                    <option value="AK">AK</option>
                    <option value="AL">AL</option>
                    <option value="AZ">AZ</option>
                    <option value="AR">AR</option>
                    <option value="CA">CA</option>
                    <option value="CO">CO</option>
                    <option value="CT">CT</option>
                    <option value="DE">DE</option>
                    <option value="DC">DC</option>
                    <option value="FL">FL</option>
                    <option value="GA">GA</option>
                    <option value="ID">ID</option>
                    <option value="IL">IL</option>
                    <option value="IN">IN</option>
                    <option value="IA">IA</option>
                    <option value="KS">KS</option>
                    <option value="KY">KY</option>
                    <option value="LA">LA</option>
                    <option value="ME">ME</option>
                    <option value="MD">MD</option>
                    <option value="MA">MA</option>
                    <option value="MI">MI</option>
                    <option value="MN">MN</option>
                    <option value="MS">MS</option>
                    <option value="MO">MO</option>
                    <option value="MT">MT</option>
                    <option value="NE">NE</option>
                    <option value="NV">NV</option>
                    <option value="NH">NH</option>
                    <option value="NJ">NJ</option>
                    <option value="NM">NM</option>
                    <option value="NY">NY</option>
                    <option value="NC">NC</option>
                    <option value="ND">ND</option>
                    <option value="OH">OH</option>
                    <option value="OK">OK</option>
                    <option value="OR">OR</option>
                    <option value="PA">PA</option>
                    <option value="RI">RI</option>
                    <option value="SC">SC</option>
                    <option value="SD">SD</option>
                    <option value="TN">TN</option>
                    <option value="TX">TX</option>
                    <option value="UT">UT</option>
                    <option value="VT">VT</option>
                    <option value="VA">VA</option>
                    <option value="WA">WA</option>
                    <option value="WV">WV</option>
                    <option value="WI">WI</option>
                    <option value="WY">WY</option>
                </select>
            </div>

            <div class="col-xs-4">
                <label class="bold" for="do_zipcode">Zip:</label>
                <input name="do_zipcode" id="do_zipcode" placeholder="Zipcode" maxlength="5" type="text">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3">
                <label class="bold" for="v_year">Vehicle Year:</label>
                <select name="v_year" id="v_year"><option value="">Year</option>';
                    <?php 
                        $year = intval(date("Y"));
                        $options = "";
                        for($i=$year; $i>=$year-100; $i--)
                        {
                            $options .= "<option value='".$i."'>".$i."</option>";
                        }
                        echo $options;
                    ?>
                </select>
            </div>

            <div class="col-xs-4">
                <label class="bold" for="v_make">Make:</label>
                <select id="v_make" name="v_make">
                    <option value="">Make</option>
                </select>
            </div>

            <div class="col-xs-5">
                <label class="bold" for="v_model">Model:</label>
                <select id="v_model" name="v_model" disabled="">
                    <option value="">Model</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <label class="bold" for="v_condition">Vehicle Condition:</label>
                <span class="info" data-info="The vehicle condition is important for us to give you more accurate quote. Please select if your vehicle is in running condition or not.">i</span>
                <select name="v_condition" id="v_condition">
                    <option value="operable">Running</option>
                    <option value="inop">Not Running</option>
                </select>
            </div>

            <div class="col-xs-4">
                <label class="bold" for="carrier_type">Shipping By:</label>
                <span class="info" data-info="Please choose between Open or Enclosed carrier, Please note that Enclosed price is higher.">i</span>
                <select name="carrier_type" id="carrier_type">
                    <option value="open">Open Carrier</option>
                    <option value="enclosed">Enclosed Carrier</option>
                </select>
            </div>
            
            <div class="col-xs-4">
                <label class="bold" for="pu_date">Pickup Date:</label>
                <span class="info" data-info="The pickup date is an estimated date, After you\'ve submitted the form you will recieve a call with more accurate pickup date.">i</span>
                <input name="pu_date" id="pu_date" type="text" />
            </div>
        </div>

        <div class="row qc-calculate-price-container">
            <input value="Calculate Price" class="submit" type="button" id="qc-calculate-price-btn" />
        </div>
        
        <div class="qc-quote-price hidden">The Estimated Price to Ship the Vehicle is <span></span><br /></div>
        
        <div class="qc-estimated-delivery hidden">The Estimated Delivery Time is <span></span><br /></div>
        
        <div class="row qc-email-container hidden">
            <div class="col-xs-8 col-xs-offset-2">
                Insert Your Email and Click the Button Below to Get a Quote and Start Your Booking:<br/><br />
                <input name="email" placeholder="Email" class="hidden" id="email" type="text" />
            </div>
        </div>
        
        <div class="row qc-submit-container hidden">
            <input value="Get Quote" class="submit" type="submit" />
        </div>
        
    </form>
    
</div>