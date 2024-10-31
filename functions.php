<?php

/* 
    Quote Calculator Shortcode 
*/
function nat_qc_quoteCalculatorShortcode( $atts ) {
    
    global $a;
    
    if(isset($atts["bg-image"]))
        $atts["bg-image"] = nat_qc_sanitizeURL($atts["bg-image"]);
    
    if(isset($atts["default-zip"]) && !nat_qc_isValidZipcode($atts["default-zip"]))
        $atts["default-zip"] = "";
        
    // Attributes
    $a = shortcode_atts(
        array(
            'title' => 'Get Instant Quote',
            'main-color' => '#FFF',
            'secondary-color' => '#2691e4',
            'submit-bg' => '#2691e4',
            'submit-color' => '#FFF',
            'submit-hover-bg' => '#2e363a',
            'submit-hover-color' => '#FFF',
            'bg-image' => '',
            'overlay' => 'rgba(24, 54, 74, 0.9)',
            'size' => '',
            'class' => '',
            'default-zip' => '',
            'show-logo' => ''
        ),
        $atts
    );
    
    //get detailed table with price
    if (nat_qc_validateCalculatorData()) 
        $output = nat_qc_getQuotePrice();
        
    //get quote calculator code 
    else
        $output = nat_qc_getCalculator();
    
	// Return output
	return $output;

}

/* 
    Get calculator code
*/
function nat_qc_getCalculator()
{
    ob_start();
    require(plugin_dir_path( __FILE__ )."calculator.php");
    return ob_get_clean();
}


/*
    Get Quote price by details
*/
function nat_qc_getQuotePrice()
{
    global $wpdb;

    $quoteDetails = [
        'company_id' 	=> 75005,
        'pu_city' 		=> sanitize_text_field($_POST['pu_city']),
        'pu_state' 		=> sanitize_text_field($_POST['pu_state']),
        'pu_zipcode' 	=> sanitize_text_field($_POST['pu_zipcode']),
        'do_city'	 	=> sanitize_text_field($_POST['do_city']),
        'do_state' 		=> sanitize_text_field($_POST['do_state']),
        'do_zipcode' 	=> sanitize_text_field($_POST['do_zipcode']),
        'email' 		=> sanitize_text_field($_POST['email']),
        'pu_date' 		=> sanitize_text_field($_POST['pu_date']),
        'condition'		=> sanitize_text_field($_POST['v_condition']) == 'operable' ? 'operable' : 'inop',
        'carrier'		=> sanitize_text_field($_POST['carrier_type']) == 'open' ? 'open' : 'enclosed',
        'year'			=> sanitize_text_field($_POST['v_year']),
        'make'			=> sanitize_text_field($_POST['v_make']),
        'model'			=> sanitize_text_field($_POST['v_model'])
    ];

    $result = nat_qc_getQuoteForData($quoteDetails);

    if ($result->success) {

        $fields = $result->data->data;

        $pu_address = $fields->pu_city . ' ' . $fields->pu_state . ', ' . $fields->pu_zipcode;
        $do_address = $fields->do_city . ' ' . $fields->do_state . ', ' . $fields->do_zipcode;

        $html = '
            <div class="quote-details">
                <h2 class="styled-title">Quote Details:</h2>
                <table class="booking-table">
                    <tr>
                        <th>Pickup Location</th>
                        <td>' . esc_html($pu_address) . '</td>
                    </tr>

                    <tr>
                        <th>Delivery Location</th>
                        <td>' . esc_html($do_address) . '</td>
                    </tr>

                    <tr>
                        <th>Vehicle</th>
                        <td>' . esc_html($quoteDetails["year"]) . ' ' . esc_html($quoteDetails["make"]) . ' ' . esc_html($quoteDetails["model"]) . '</td>
                    </tr>

                    <tr>
                        <th>Vehicle Condition</th>
                        <td>' . esc_html($quoteDetails["condition"]) . '</td>
                    </tr>

                    <tr>
                        <th>Carrier Type</th>
                        <td>' . esc_html($quoteDetails["carrier"]) . '</td>
                    </tr>

                    <tr>
                        <th>Pickup Date</th>
                        <td>' . esc_html($fields->pu_date) . '</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>' . esc_html($fields->email) . '</td>
                    </tr>
                </table>

                <div class="booking-price-container">
                    <span>The price to ship your vehicle is: </span><span class="colored-text">$' . esc_html($fields->price) . '.00</span>
                </div>

                <div class="booking-button-container">
                    <a href="'.esc_url($result->data->link).'">Click Here to Start Your Booking</a>
                </div>
          </div>';
        
        wp_register_style( 'calculator.css', plugin_dir_url( __FILE__ ) . 'css/calculator.css');
        wp_enqueue_style( 'calculator.css');
        
    } else {
            $html = '<div class="center">
            <p>' . esc_html($result->message) . '</p>';
            if(count($result->errors)){
                $html .= '<ul>';
                foreach ($result->errors as $error) {
                    $html .= '<li>'.esc_html($error).'</li>';
                }
                $html .= '</ul>';
            }
        $html .= '</div>';
    }
    return $html;
}

/*
    Get Quote by sent Data
*/
function nat_qc_getQuoteForData($fields)
{

	$url = 'https://nationwideautotransportation.com/app/new-quote-info';

    $args = array(
        'body' => $fields,
        'timeout' => '45',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array("Referer" => $_SERVER['SERVER_NAME']),
        'cookies' => array()
    );

    $response = wp_remote_retrieve_body(wp_remote_post( $url, $args ));
    
	return json_decode($response);
}

/*
    Register Calculator Assets
*/
function nat_qc_registerCalculatorAssets()
{
    /* Register styles */
    wp_register_style( 'calculator.css', plugin_dir_url( __FILE__ ) . 'css/calculator.css');
    wp_enqueue_style( 'calculator.css');

    wp_register_style( 'jquery-ui.min.css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css');
    wp_enqueue_style( 'jquery-ui.min.css');

    wp_register_style( 'toastr.css', plugin_dir_url( __FILE__ ) . 'css/toastr.css');
    wp_enqueue_style( 'toastr.css');
    
    wp_register_style( 'bootstrap.min.css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css');
    wp_enqueue_style( 'bootstrap.min.css');

    /* Register scripts */
    wp_register_script( 'calculator.js', plugin_dir_url( __FILE__ ) . 'js/calculator.js' , array("jquery"));
    wp_enqueue_script( 'calculator.js' );
    
    //wp_register_script( 'query-ui-core', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js' , array("jquery"));
    //wp_enqueue_script( 'query-ui-core' );

    wp_enqueue_script( 'jquery-ui-core', '', array("jquery"), false, true );
    wp_enqueue_script( 'jquery-ui-autocomplete', '', array("jquery"), false, true );
    wp_enqueue_script( 'jquery-ui-datepicker', '', array("jquery"), false, true );

    wp_register_script( 'toastr.js', plugin_dir_url( __FILE__ ) . 'js/toastr.js' );
    wp_enqueue_script( 'toastr.js' );
}

/*
    Validate URL
*/
function nat_qc_sanitizeURL($url)
{
    // Remove all illegal characters from a url
    $url = filter_var($url, FILTER_SANITIZE_URL);

    // Validate url
    if(filter_var($url, FILTER_VALIDATE_URL))
        return $url;
    
    return "";
}

/*
    Validate Zipcode
*/
function nat_qc_isValidZipcode($zip)
{
    return is_numeric($zip) && (strlen((string) $zip) == 5);
}

/*
    Validate Date
*/
function nat_qc_isValidDate($date)
{
    if (strpos($date, '/') == false) 
        return false;
    
    $tmp = explode("/", $date);
    
    if(count($tmp) != 3)
        return false;
    
    return checkdate ( intval($tmp[0]) , intval($tmp[1]) , intval($tmp[2]) );
}

/*
    Validate POST Data from Calculator
*/

function nat_qc_validateCalculatorData()
{
    return (
            isset($_POST['pu_city']) &&
            isset($_POST['pu_state']) &&
            isset($_POST['pu_zipcode']) &&
            isset($_POST['do_city']) &&
            isset($_POST['do_state']) &&
            isset($_POST['do_zipcode']) &&
            isset($_POST['email']) &&
            isset($_POST['pu_date']) &&
            isset($_POST['v_condition']) &&
            isset($_POST['carrier_type']) &&
            isset($_POST['v_year']) &&
            isset($_POST['v_make']) &&
            isset($_POST['v_model']) &&

            !empty(sanitize_text_field($_POST['pu_city'])) &&
            !empty(sanitize_text_field($_POST['pu_state'])) &&
            !empty(sanitize_text_field($_POST['pu_zipcode'])) &&
            !empty(sanitize_text_field($_POST['do_city'])) &&
            !empty(sanitize_text_field($_POST['do_state'])) &&
            !empty(sanitize_text_field($_POST['do_zipcode'])) &&
            !empty(sanitize_text_field($_POST['email'])) &&
            !empty(sanitize_text_field($_POST['pu_date'])) &&
            !empty(sanitize_text_field($_POST['v_condition'])) &&
            !empty(sanitize_text_field($_POST['carrier_type'])) &&
            !empty(sanitize_text_field($_POST['v_year'])) &&
            !empty(sanitize_text_field($_POST['v_make'])) &&
            !empty(sanitize_text_field($_POST['v_model'])) &&

            nat_qc_isValidZipcode($_POST['pu_zipcode']) &&
            nat_qc_isValidZipcode($_POST['do_zipcode']) &&
            is_email($_POST['email']) &&
            nat_qc_isValidDate($_POST['pu_date'])
        );
}

/*
    Get Calculator Preview Code fot=r Admin Panel
*/
function nat_qc_getPreviewCalculator()
{ 
?>
    <style>
        .qc-quote-calculator
        {
            color: #FFF;
            box-shadow: inset 0 0 0 1000px rgba(24, 54, 74, 0.9);
        }

        .qc-quote-calculator .qc-submit-container input, .qc-quote-calculator .qc-calculate-price-container input
        {
            background-color: #2691e4;
            color: #FFF;
        }

        .qc-quote-calculator .qc-submit-container input:hover, .qc-quote-calculator .qc-calculate-price-container input:hover
        {
            background-color: #2e363a;
            color: #FFF;
        }

        qc-quote-calculator .qc-quote-price span, qc-estimated-delivery span
        {
            color: #2691e4;
        }

        .qc-quote-calculator h2
        {
            color: #FFF;
        }

        .qc-quote-calculator h2:after
        {
            background: #2691e4;
        }
    </style>
   <div class="qc-quote-calculator qc-main-color" style="margin-left: auto; margin-right: auto; text-align: left;">
       <div class="qc-header">
            <a href="https://nationwideautotransportation.com/">
                <img src="<?=plugin_dir_url( __FILE__ ) . 'img/nat-logo.png'?>" />
           </a>
        </div>
       
        <h2 data-default="Get Instant Quote" class="qc-main-color">Get Instant Quote</h2>
        <form id="qc-calculator-form">
            <div class="row">
                <div class="col-xs-5">
                    <label class="bold" for="pu_city">Pickup City:</label>
                    <input name="pu_city" id="pu_city" placeholder="Pickup City" autocomplete="off" type="text">
                </div>

                <div class="col-xs-3">
                    <label class="bold" for="pu_state">State:</label>
                    <select name="pu_state" id="pu_state">
                        <option value="">Select</option>
                    </select>
                </div>

                <div class="col-xs-4">
                    <label class="bold" for="pu_zipcode">Zip:</label>
                    <input name="pu_zipcode" id="pu_zipcode" placeholder="Zipcode" maxlength="5" autocomplete="false" type="text">
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
                       <option>2017</option>
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
                <div class="col-sm-4">
                    <label class="bold" for="v_condition">Vehicle Condition:</label>
                    <span class="info" data-info="The vehicle condition is important for us to give you more accurate quote. Please select if your vehicle is in running condition or not.">i</span>
                    <select name="v_condition" id="v_condition">
                        <option value="operable">Running</option>
                        <option value="inop">Not Running</option>
                    </select>
                </div>

                <div class="col-sm-4">
                    <label class="bold" for="carrier_type">Shipping By:</label>
                    <span class="info" data-info="Please choose between Open or Enclosed carrier, Please note that Enclosed price is higher.">i</span>
                    <select name="carrier_type" id="carrier_type">
                        <option value="open">Open Carrier</option>
                        <option value="enclosed">Enclosed Carrier</option>
                    </select>
                </div>

                <div class="col-sm-4">
                    <label class="bold" for="pu_date">Pickup Date:</label>
                    <span class="info" data-info="The pickup date is an estimated date, After you\'ve submitted the form you will recieve a call with more accurate pickup date.">i</span>
                    <input name="pu_date" id="pu_date" type="text" />
                </div>
            </div>

            <div class="row qc-calculate-price-container">
                <input value="Calculate Price" class="submit qc-submit-color qc-submit-bg-color" type="button" id="qc-calculate-price-btn" />
            </div>

            <div class="qc-quote-price">The Estimated Price to Ship the Vehicle is <span>$100.00</span><br /></div>
            
            <div class="qc-estimated-delivery">The Estimated Delivery Time is <span>1-2 Days</span><br /></div>

            <div class="row qc-email-container">
                <div class="col-sm-8 col-sm-offset-2">
                    Insert Your Email and Click the Button Below to Get a Quote and Start Your Booking:<br/><br />
                    <input name="email" placeholder="Email" id="email" type="text" />
                </div>
            </div>

            <div class="row qc-submit-container">
                <input value="Get Quote" class="submit qc-submit-color qc-submit-bg-color" type="submit" />
            </div>

        </form>
    </div>
<?php
    wp_register_style( 'calculator.css', plugin_dir_url( __FILE__ ) . 'css/calculator.css');
    wp_enqueue_style( 'calculator.css');
    
    wp_register_style( 'bootstrap.min.css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css');
    wp_enqueue_style( 'bootstrap.min.css');
}

//add shortcodes
add_shortcode( 'nat-quote-calculator', 'nat_qc_quoteCalculatorShortcode' );