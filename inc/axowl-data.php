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

		$data = [];

		foreach ($data_keys as $k)
			if (in_array($k, $input_keys))
				$data[$k] = $data[$k];

		// move to send_axo
		// $this->send($data);

		echo print_r($data, true);

		// sending to axo
		// $this->send_axo($data);

		wp_die();
	}


	// private function send($data) {

	// 	$url = get_option('em_axowl');

	// 	if (!isset($url['callback']) || $url['callback' == '']) return;

	// 	$url = str_replace('&amp;', '&', $url['callback']);

	// 	$url = explode(';', $url);

	// 	*
	// 	 * loan amount
	// 	 * tenure
	// 	 * email
	// 	 * phone
	// 	 * employment_type
	// 	 * employment_since
	// 	 * education
	// 	 * norwegian
	// 	 * country_of_origin
	// 	 * years_in_norway
	// 	 * income
	// 	 *
	// 	 *
	// 	 * medsøker info?
	// 	 *
	// 	 * civilstatus
	// 	 * spouse_income?
	// 	 * living_condition
	// 	 * address_since
	// 	 * number_of_children
	// 	 * 
	// 	 * total_unsecured_debt
	// 	 * total_u
		 

	// 	// echo print_r($url, true);

	// 	foreach ($url as $v) {
	// 		$m = ['{email}', '{mobile_number}'];
	// 		$r = [$data['email'], $data['mobile_number']];

	// 		$v = str_replace($m, $r, $v);

	// 		wp_remote_get($v, ['blocking' => false]);
	// 	}
	// }


	private function send_axo($data) {
		$settings = get_option('em_axowl');
		if (!isset($settings['url']) || !isset($settings['name'])) return;

		// axo url
		$url = $settings['url'].'?';
		
		// name of partner as agreed with axo 
		$data['source'] = $settings['name'];

		$url .= http_build_query($data);

		echo $url;

		// sending to axo
		$response = wp_remote_get($url);

		if (is_wp_error($response)) {
			echo '{"status": "error", "code": "'.wp_remote_retrieve_response_code($response).'"}';
			return;
		}

		$res = json_decode(wp_remote_retrieve_body($response), true);

		if (!is_array($res) || !isset($res['status'])) return;


		switch ($res['status']) {
			case 'Accepted': $this->accepted($res, $data); break;
			case 'Rejected': $this->rejected($res, $data); break;
			case 'ValidationError': $this->validation_error($res, $data); break;
			case 'TechnicalError': $this->technical_error($res, $data); break;
		}
	}


	private function accepted($res, $data) {

		// send anonymized gfunc datastore

		// send to gfunc slack 

		// send to gdocs ads

		// send event or/and ecommerce data to GA
		// google ads import from GA?
	}

	private function rejected($res, $data) {
		
		// send email and phone to gdcos

		// send gfunc datastore

	}

	private function validation_error($res, $data) {
		// should never happen - ask user to please check their form or fill it in again
	}

	private function technical_error($res, $data) {
		// warn of technical error and ask user to try again
	}

	// private function slack($data) {
	// 	// send to kredittkort.rocks for slack stats
	// 	// make gfunc/datastore
	// }

	// gdocs with email and phone
	private function send($data, $name) {

		$url = $this->get_url($name);

		if (!$url) return;

		echo $name.': '.$this->query($url, $data);
		// wp_remote_get($this->query($url, $data), ['blocking' => false]);
	}

	// private function gdocs_ads($data) {
	// 	// send to kredittkort.rocks
	
	// 	$url = $this->get_url('gdocs_ads');

	// 	if (!$url) return;

	// 	echo 'gdocs: '.$this->query($url, $data);
	// 	// wp_remote_get($this->query($url, $data), ['blocking' => false]);
	// }

	// private function datastore($data) {
	// 	// send to google function
	// }

	private function query($url, $data) {

		// gclid
		// _ga
		// 
		$url = (strpos($url[$value], '?') === false) ? $url.'?' : $url;

		foreach ($data as $key => $value)
			$url = str_replace('{'.$key.'}', $value, $url);

		$url = preg_replace('/{.*}/g', '', $url);

		return $url;

	}

	private function get_url($value) {
		$url = get_option('em_axowl');

		if (isset($url[$value])) return $url[$value];

		return false;
	}

	private function anon($data) {

		unset($data['email']);
		unset($data['mobile_number']);

		return $data;

	}


}