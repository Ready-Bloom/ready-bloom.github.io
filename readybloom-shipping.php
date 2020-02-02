<?php
 
/**
 * Plugin Name: Ready Bloom Shipping
 * Plugin URI: https://www.readybloom.ca
 * Description: Ready Bloom Shipping Method for WooCommerce
 * Version: 1.0.0
 * Author: Ready Bloom
 * Author URI: http://www.readybloom.ca
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /lang
 * Text Domain: readybloom
 */
 
if ( ! defined( 'WPINC' ) ) {
 
    die;
 
}
 
/*
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
    function readybloom_shipping_method() {
        if ( ! class_exists( 'ReadyBloom_Shipping_Method' ) ) {
            class ReadyBloom_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'readybloom'; 
                    $this->method_title       = __( 'Ready Bloom Shipping', 'readybloom' );  
                    $this->method_description = __( 'Custom Shipping Method for Ready Bloom', 'readybloom' ); 
                    
                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array(
                        'CA', // Canada
                        );
                    
                    $this->init();
 
                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Ready Bloom Shipping', 'readybloom' );
                }
 
                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields(); 
                    $this->init_settings(); 
 
                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
 
                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                function init_form_fields() { 
                    $this->form_fields = array(
 
                        'enabled' => array(
                             'title' => __( 'Enable', 'readybloom' ),
                             'type' => 'checkbox',
                             'description' => __( 'Enable this shipping.', 'readybloom' ),
                             'default' => 'yes'
                             ),
                
                        'title' => array(
                           'title' => __( 'Title', 'readybloom' ),
                             'type' => 'text',
                             'description' => __( 'Title to be display on site', 'readybloom' ),
                             'default' => __( 'Ready Bloom Shipping', 'readybloom' )
                             ),
                        );
                }
 
                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package ) {

                    $country = $package["destination"]["country"];
                    $province = $package["destination"]["state"];
                    $postal_code = $package["destination"]["postcode"];
                    $city = $package["destination"]["city"];
                    $address_1 = $package["destination"]["address_1"];
                    $address_2 = $package["destination"]["address_2"];

                    $origin = "814 Cockshutt Rd, Simcoe, ON N3Y 4K4";

                    $body = array(
                        'toAddress' => $address_1." ".$address_2." ".$city." ".$province." ".$country." ".$postal_code,
                        'fromAddress' => $origin
                    );
                     
                    $args = array(
                        'body' => $body,
                        'timeout' => '5',
                        'redirection' => '5',
                        'httpversion' => '1.0',
                        'blocking' => true,
                        'headers' => array(),
                        'cookies' => array()
                    );
                     
                    $response = wp_remote_post( 'https://1a918a0c.ngrok.io/api/v1/rate/getrate', $args );

                    $cost = json_decode($response['body'])->{'data'};
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $cost
                    );
                        
                    $this->add_rate( $rate );
                }
            }
        }
    }
 
    add_action( 'woocommerce_shipping_init', 'readybloom_shipping_method' );
 
    function add_readybloom_shipping_method( $methods ) {
        $methods[] = 'ReadyBloom_Shipping_Method';
        return $methods;
    }
 
    add_filter( 'woocommerce_shipping_methods', 'add_readybloom_shipping_method' );

    function readybloom_validate_order( $posted )   {
        // LEFT IF NEEDED
    }

    add_action( 'woocommerce_review_order_before_cart_contents', 'readybloom_validate_order' , 10 );
    add_action( 'woocommerce_after_checkout_validation', 'readybloom_validate_order' , 10 );
}