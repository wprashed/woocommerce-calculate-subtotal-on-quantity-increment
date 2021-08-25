<?php

/**
 *
 * @link              https://itech-softsolutions.com/
 * @since             1.0.0
 * @package           Csqi
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce: Calculate Subtotal On Quantity Increment
 * Plugin URI:        https://itech-softsolutions.com/plugin
 * Description:       Calculate Total Price Upon Quantity Increment at WooCommerce Single Product Page.
 * Version:           1.0.0
 * Author:            iTechtheme
 * Author URI:        https://itech-softsolutions.com/
 * Text Domain:       csqi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.s.
 */
define( 'CSQI_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-csqi-activator.php
 */
function activate_csqi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-csqi-activator.php';
	Csqi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-csqi-deactivator.php
 */
function deactivate_csqi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-csqi-deactivator.php';
	Csqi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_csqi' );
register_deactivation_hook( __FILE__, 'deactivate_csqi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-csqi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_csqi() {

	$plugin = new Csqi();
	$plugin->run();

}

add_action( 'woocommerce_before_add_to_cart_button', 'csqi_product_price_recalculate' );
 
function csqi_product_price_recalculate() {
   global $product;
   echo '<div id="subtot"><strong>Total:</strong> <span></span></div>';
   $price = $product->get_price();
   $currency = get_woocommerce_currency_symbol();
   wc_enqueue_js( "      
    	$('[name=quantity]').on('input change', function() { 
         var qty = $(this).val();
         var price = '" . esc_js( $price ) . "';
         var price_string = (price*qty).toFixed(2);
         $('#subtot > span').html('" . esc_js( $currency ) . "'+price_string);}).change();" 
	);
}

run_csqi();
