<?php 

/*
Plugin Name: EM Axo WL
Description: Axo White Label
Version: 0.0.5
GitHub Plugin URI: zeah/EM-axowl
*/

defined('ABSPATH') or die('Blank Space');

require_once 'inc/axowl-settings.php';
require_once 'inc/axowl-shortcode.php';
require_once 'inc/axowl-data.php';


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
		Axowl_settings::get_instance();
		Axowl_shortcode::get_instance();
		Axowl_data::get_instance();
	}

}