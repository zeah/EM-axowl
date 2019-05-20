<?php 

/*
Plugin Name: EM Axo WL
Description: Axo White Label
Version: 0.0.79
GitHub Plugin URI: zeah/EM-axowl
*/

defined('ABSPATH') or die('Blank Space');

require_once 'inc/axowl-settings.php';
require_once 'inc/axowl-shortcode.php';
require_once 'inc/axowl-data.php';
require_once 'inc/axowl-ads.php';
// require_once 'inc/axowl-cookie.php';
require_once 'inc/axowl-unsub.php';
// require_once 'inc/axowl-abfp.php';


function init_em_axowl() {
	EM_axowl::get_instance();
}

init_em_axowl();

define('EM_AXOWL_PLUGIN_URL', plugin_dir_url(__FILE__));


final class EM_axowl {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		wp_deregister_script('jquery');
        wp_register_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js', [], false, true);
        wp_register_script('jquery-ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js', ['jquery'], false, true);
        wp_register_script('jquery-touch', '//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', ['jquery-ui'], false, true);

		// wp_die('<xmp>'.print_r(parse_url($_SERVER['HTTP_REFERER']), true).'</xmp>');
		// $temp = '260410';

		// wp_die('<xmp>'.print_r('hi'.sprintf('%s-%s-%s', 
		// 	(intval(substr($temp, 4, 2)) < 20) ? '20'.substr($temp, 4, 2) : '19'.substr($temp, 4, 2), 
		// 	substr($temp, 2, 2), 
		// 	substr($temp, 0, 2)), true).'</xmp>');


		// Axowl_abfp::get_instance();
		Axowl_settings::get_instance();
		Axowl_shortcode::get_instance();
		Axowl_data::get_instance();
		Axowl_ads::get_instance();
		// Axowl_cookie::get_instance();
		Axowl_unsub::get_instance();
	}

}