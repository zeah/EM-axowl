<?php 
defined('ABSPATH') or die('Blank Space');

final class Axowl_data {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action( 'wp_ajax_nopriv_axowl', [$this, 'from_form']);
		add_action( 'wp_ajax_axowl', [$this, 'from_form']);
	}

	public function from_form() {
		$data = $_POST['data'];

		// match from inputs.php
		$data_keys = array_keys($data);
		$input_keys = array_keys(Axowl_inputs::$inputs);

		$send = [];

		foreach ($data_keys as $k)
			if (in_array($k, $input_keys))
				$send[$k] = $data[$k];


		// send to google docs
		$gdoc = 'https://script.google.com/macros/s/AKfycbzK3khU3GnwJXCNrVc_1UQUd-ocjt-TOaglEAT_hLxnl1I9GnSR/exec';
		$q = sprintf('?email=%1$s&phone=%2$s',
						$send['email'],
						$send['mobile_number']
					);

		// wp_remote_get($gdoc.$q, ['blocking' => false]);


		// send to google cloud functions -> google datastore



		// send to axo
		$send['source'] = 'eff.mark';
		// $send['content'] = '';
		// $send['medium'] = '';
		$send['customer_ip'] = $_SERVER['REMOTE_ADDR'];





		// echo print_r($_SERVER);
		// echo http_build_query($data);
		// echo http_build_query($send);
		wp_die();
	}
}