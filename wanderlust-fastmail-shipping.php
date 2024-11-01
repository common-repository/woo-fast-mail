<?php
/*
  Plugin Name: Wanderlust Fast Mail para Woocommerce
	Plugin URI: https://shop.wanderlust-webdesign.com/
	Description: Obtain shipping rates dynamically via the Fast Mail API for your orders.
	Version: 0.0.1
	Author: Wanderlust Web Design
	Author URI: https://wanderlust-webdesign.com
  WC tested up to: 3.8.8
	Copyright: 2007-2020 wanderlust-webdesign.com.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


/**
 * Plugin global API URL
*/

function wanderlust_fastmail_start() {
	global $wp_session;
}
add_action('init','wanderlust_fastmail_start');

require_once( 'includes/functions.php' );

/**
 * Plugin page links
*/
function wc_fastmail_plugin_links( $links ) {

	$plugin_links = array(
		'<a href="https://wanderlust-webdesign.com/">' . __( 'Soporte', 'woocommerce-shipping-fastmail' ) . '</a>',
	);

	return array_merge( $plugin_links, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_fastmail_plugin_links' );

/**
 * WooCommerce is active
*/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	/**
	 * woocommerce_init_shipping_table_rate function.
	 *
	 * @access public
	 * @return void
	 */
	function wc_fastmail_init() {
		include_once( 'includes/class-wc-shipping-fastmail.php' );
	}
  add_action( 'woocommerce_shipping_init', 'wc_fastmail_init' );

	/**
	 * wc_fastmail_add_method function.
	 *
	 * @access public
	 * @param mixed $methods
	 * @return void
	 */
	function wc_fastmail_add_method( $methods ) {
		$methods[ 'fastmail_wanderlust' ] = 'WC_Shipping_FastMail';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'wc_fastmail_add_method' );

	/**
	 * wc_fastmail_scripts function.
	 */
	function wc_fastmail_scripts() {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	add_action( 'admin_enqueue_scripts', 'wc_fastmail_scripts' );

	$fastmail_settings = get_option( 'woocommerce_fastmail_settings', array() );

}
