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

		echo print_r($send, true);

		// sending to axo
		// $this->send_axo($send);

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

		echo $url;

		// sending to axo
		$response = wp_remote_get($url);

		if (is_wp_error($response)) {
			echo '{"status": "error", "code": "'.wp_remote_retrieve_response_code($response).'"}';
			return;
		}

		$res = json_decode(wp_remote_retrieve_body($response), true);

		if (!is_array($res) || !isset($res['status'])) return;

		/**
		 * loan amount
		 * tenure
		 * email
		 * phone
		 * employment_type
		 * employment_since
		 * education
		 * norwegian
		 * country_of_origin
		 * years_in_norway
		 * income
		 *
		 *
		 * medsøker info?
		 *
		 * civilstatus
		 * spouse_income?
		 * living_condition
		 * address_since
		 * number_of_children
		 * 
		 * total_unsecured_debt
		 * total_unsecured_debt_balance
		 */


		if ($res['status'] == 'Accepted') {

			// send anonymized info to datastore

			// send to kredittkort.rocks em-live

		}

		elseif ($res['status'] == 'Rejected') {

			// send email and phone to gdcos

			// send email, phone, other info to datastore

		}

		elseif ($res['status'] == 'ValidationError') {

			// should never happen - ask user to please check their form or fill it in again

		}

		elseif ($res['status'] == 'TechnicalError') {

			// warn of technical error and ask user to try again

		}



		// do $this->send here if rejected?
		// store anonymized data either way?
	}



}