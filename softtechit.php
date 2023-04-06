<?php
/**
 * Plugin Name:       Soft Tech IT
 * Plugin URI:        https://softtech-it.com/
 * Description:       This is a custom woocommerce Csv product upload.
 * Version:           1.0.0
 * Author:            jhfahim
 * Author URI:        https://jhfahim.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       softtechit
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Enqueue script
 */
function csv_enqueue_script()
{   
		
    wp_enqueue_style( 'style-js', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), '1.0.0', 'all' );
    wp_enqueue_script( 'custom-js', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js',array(), '1.0.0', true );

	 
}
add_action('admin_enqueue_scripts', 'csv_enqueue_script');

/**
 * Include dedhboard
 */
require plugin_dir_path( __FILE__ ) . 'inc/wc-csv-deshboard-display.php';







