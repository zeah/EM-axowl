<?php 

/*
Plugin Name: EM Axo WL
Description: Axo White Label
Version: 0.0.1
GitHub Plugin URI: zeah/EM-axowl
*/

defined('ABSPATH') or die('Blank Space');

require_once 'inc/axowl-settings.php';
require_once 'inc/axowl-shortcode.php';


function init_em_axowl() {
	EM_axowl::get_instance();
}

init_em_axowl();


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
	}

}