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

		// using callback
		// $this->send($send);


		// sending to axo
		$this->send_axo($send);

		wp_die();
	}


	private function send($send) {

		$url = get_option('em_axowl');

		if (!isset($url['callback']) || $url['callback' == '']) return;

		$url = str_replace('&amp;', '&', $url['callback']);

		$url = explode(';', $url);

		// echo print_r($url, true);

		foreach ($url as $v) {
			$m = ['{email}', '{mobile_number}'];
			$r = [$send['email'], $send['mobile_number']];

			$v = str_replace($m, $r, $v);

			wp_remote_get($v, ['blocking' => false]);
		}
	}


	private function send_axo($send) {
		$settings = get_option('em_axowl');
		if (!isset($settings['url']) || !isset($settings['name'])) return;

		// axo url
		$url = $settings['url'].'?';
		
		// name of partner as agreed with axo 
		$send['source'] = $settings['name'];

		$url .= http_build_query($send);

		// sending to axo
		$response = wp_remote_get($url);

		if (is_wp_error($response)) {
			echo '{"status": "error", "code": "'.wp_remote_retrieve_response_code($response).'"}';
			return;
		}

		echo wp_remote_retrieve_body($response);

		// do $this->send here if rejected?
		// store anonymized data either way?
	}



}