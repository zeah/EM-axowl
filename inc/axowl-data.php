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

		// echo print_r($send, true);
		// echo $send['email'];
		// send to axo
		// $send['source'] = 'eff.mark';
		// $send['content'] = '';
		// $send['medium'] = '';
		// $send['customer_ip'] = $_SERVER['REMOTE_ADDR'];


		// $resp = wp_remote_get('https://privatlånlistan.se/');

		// echo wp_remote_retrieve_body($resp);

		// if (is_wp_error($resp)) echo 'hello';
		// echo print_r($resp, true);

		// echo print_r($_SERVER);
		// echo http_build_query($data);
		// echo http_build_query($send);
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

		// $url = get_option('em_axowl');
		$send['source'] = 'eff.mark';
		$send['customer_ip'] = $_SERVER['REMOTE_ADDR'];

		// if (!isset($url['axo']) || !$url['axo']) return;

		// $url = $url['axo'];

		// $send = http_build_query($send);

		// $response = wp_remote_get($url.'?'.$send);

		// if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		    // $headers = $response['headers']; // array of http header lines
		    // $body    = $response['body']; // use the content

		$body = '{"status": "Accepted", "transactionID": "13454564", "errors": []}';

		$data = json_decode($body, true);

		if ($data['status'] == 'Accepted') echo '{"status": "Accepted", "transactionID": "13454564", "errors": []}';
		else echo 'rejected';
		// echo print_r($data, true);
		// 
		    // $body = wp_remote_retrieve_body($response);
		// }


	}
}