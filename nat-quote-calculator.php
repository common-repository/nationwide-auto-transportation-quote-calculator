<?php

/*
* Plugin Name: Nationwide Auto-Transportation Quote Calculator
* Description: Quote Calculator Plugin for Getting Free Quotes from Nationwide Auto-Transportation
* Plugin URI:  https://nationwideautotransportation.com/
* Author: Evyatar Daud
* Version: 1.0
*/

//includes
require_once(plugin_dir_path( __FILE__ ).'functions.php');

//actions
add_action('admin_menu', 'nat_qc_setupMenu');


/* 
    Admin menu setup
*/
function nat_qc_setupMenu()
{
    add_menu_page( 'Nationwide Auto-Transportation Quote Calculator', 'Quote Calculator', 'manage_options', 'nat-qc-plugin', 'nat_qc_adminPageInit', plugin_dir_url( __FILE__ ) . 'img/qc-icon.png' );
}

/*
    Initialize plugin admin page
*/
function nat_qc_adminPageInit()
{
    wp_register_style( 'admin-page.css', plugin_dir_url( __FILE__ ) . 'css/admin-page.css');
    wp_enqueue_style( 'admin-page.css');
    
    wp_register_script( 'admin-page.js', plugin_dir_url( __FILE__ ) . 'js/admin-page.js' , array("jquery"));
    wp_enqueue_script( 'admin-page.js' );?>


    <h1>Nationwide Auto-Transportation Quote Calculator</h1>

    <p style="font-size: 15px; margin-top: 80px; margin-bottom: 30px;">Here you can customize your own calculator and get a shortcode that can be placed everywhere in your site.</p>

    <div class="row" style="margin: 0 auto;">
        <div class="col-md-6">
            <h3>Settings:</h3>

            <div class="input-container">
                <label for='title'>Title: </label>
                <span>Text that appears in the title</span>
                <input type="text" id="title" />
            </div>

            <div class="input-container">
                <label for='main-color'>Main Color: </label>
                <span>Color of the title and the labels</span>
                <input type="text" placeholder="#FFF, White, etc" id="main-color" />
            </div>

            <div class="input-container">
                <label for='secondary-color'>Secondary Color: </label>
                <span>Color of the title's underline and price</span>
                <input type="text" placeholder="#FFF, White, etc" id="secondary-color" />
            </div>

            <div class="input-container">
                <label for='submit-bg'>Submit Button Background Color: </label>
                <span>Background color of the buttons</span>
                <input type="text" placeholder="#FFF, White, etc" id="submit-bg" />
            </div>

            <div class="input-container">
                <label for='submit-color'>Submit Button Color: </label>
                <span>Color of the buttons</span>
                <input type="text" placeholder="#FFF, White, etc" id="submit-color" />
            </div>

            <div class="input-container">
                <label for='submit-hover-bg'>Submit Button Hover Background Color: </label>
                <span>Background color of hovered button</span>
                <input type="text" placeholder="#FFF, White, etc" id="submit-hover-bg" />
            </div>

            <div class="input-container">
                <label for='submit-hover-color'>Submit Button Hover Color: </label>
                <span>Color of hovered button</span>
                <input type="text" placeholder="#FFF, White, etc" id="submit-hover-color" />
            </div>
            
            <div class="input-container">
                <label for='additional-class'>Additional Class: </label>
                <span>Additional CSS Classes (Space Separated)</span>
                <input type="text" id="additional-class" />
            </div>

            <div class="input-container">
                <label for='bg-image'>Background Image Url: </label>
                <span>Calculator background image</span>
                <input type="text" id="bg-image" />
            </div>
            
            <div class="input-container">
                <label for='background-overlay'>Background Overlay: </label>
                <span>Background overlay color</span>
                <input type="text" placeholder="#FFF, rgba(0,0,0,0.5), etc" id="background-overlay" />
            </div>
            
            <div class="input-container">
                <label for='calculator-size'>Size: </label>
                <span>Calculator Size</span>
                <select id="calculator-size">
                    <option value="">Full-Width (Default)</option>
                    <option value="medium">Medium</option>
                    <option value="small">Small</option>
                </select>
            </div>
            
            <div class="input-container">
                <label for='default-zip'>Default Zipcode: </label>
                <span>Default Pickup Zipcode</span>
                <input type="text" id="default-zip" />
            </div>
            
            <div class="input-container">
                <label for='show-logo'>Show Logo: </label>
                <span>Show Nationwide Auto Transportation Logo in the header</span>
                <select id="show-logo">
                    <option value="">Yes</option>
                    <option value="false">No</option>
                </select>
            </div>

            <h3>Shortcode:</h3>
            <code id="shortcode">[nat-quote-caclculator]</code>

        </div>

        <div class="col-md-6">
            <h3>Preview:</h3>
            <?php
                nat_qc_getPreviewCalculator();
            ?>
        </div>
    </div>

 <?php   
}