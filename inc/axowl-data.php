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

		// sending to axo
		$this->send_axo($send);

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
		if (!isset($settings['form_url']) || !isset($settings['name'])) return;

		// axo url
		$url = $settings['form_url'].'?';
		
		// name of partner as agreed with axo 
		$data['source'] = $settings['name'];

		$data['customer_ip'] = '';

		$url .= http_build_query($data);

		// echo $url;

		// sending to axo
		// $response = wp_remote_get($url);

		// if (is_wp_error($response)) {
		// 	echo '{"status": "error", "code": "'.wp_remote_retrieve_response_code($response).'"}';
		// 	return;
		// }

		// $res = json_decode(wp_remote_retrieve_body($response), true);

		// echo print_r($res, true);
		// if (!is_array($res) || !isset($res['status'])) return;

		$res = ['status' => 'Accepted'];

		$data = $this->remove_confidential($data);
		$data['transactionId'] = $res['transactionId'];

		switch ($res['status']) {
			case 'Accepted': $this->accepted($data); break;
			case 'Rejected': $this->rejected($data); break;
			// case 'ValidationError': $this->validation_error($data); break;
			// case 'TechnicalError': $this->technical_error($data); break;
		}
	}


	private function accepted($data) {

		$data['status'] = 'accepted';

		// send anonymized gfunc datastore
		$this->send($this->anon($data), 'google_functions');

		// send to gfunc slack 
		$this->slack($data, 'slack');

		// send to gdocs ads
		$this->send($data, 'gdocs_ads');

		// google analytics
		$value = isset($data['payout']) ? $data['payout'] : 2200;
		// $this->ga('accepted', $value);

		// send event or/and ecommerce data to GA
		// google ads import from GA?
	}

	private function rejected($data) {
		$data['status'] = 'rejected';
		
		// send email and phone to gdcos
		$this->send($data, 'gdocs_email');

		// send data to datastore
		$this->send($data, 'google_functions');

		// google analytics
		$this->ga('rejected', 0);

	}

	private function validation_error($data) {
		// echo print_r($res);
		// should never happen - ask user to please check their form or fill it in again
	}

	private function technical_error($data) {
		// echo print_r($res);
		// warn of technical error and ask user to try again
	}


	// gdocs with email and phone
	private function send($data, $name) {

		$url = $this->get_url($name);

		if (!$url) return;

		echo $name.': '.$this->query($url, $data)."\n";
		// wp_remote_get($this->query($url, $data), ['blocking' => false]);
	}


	private function query($url, $data) {

		$v = get_option('em_axowl');

		// gclid
		// _ga
		// 
		// $url = (strpos($url[$value], '?') === false) ? $url.'?' : $url;

		foreach ($data as $key => $value)
			$url = str_replace('{'.$key.'}', $value, $url);

		$url = preg_replace('/{.*?}/', '', $url);

		$url = str_replace('&amp;', '&', $url);

		$url .= '&gclid=';

		$url .= '&msclkid=';

		$url .= '&referer=';

		if (isset($v['currency'])) $url .= '&currency='.$v['currency'];

		if (isset($v['payout'])) $url .= '&payout='.$v['payout'];
		// echo print_r($data, true);

		// echo 'query: '.$url;

		return $url;

	}

	private function get_url($value) {
		$url = get_option('em_axowl');

		if (isset($url[$value])) return $url[$value];

		return false;
	}

	private function remove_confidential($data) {
		if (isset($data['account_number'])) unset($data['account_number']);
		if (isset($data['social_number'])) unset($data['social_number']);
		if (isset($data['co_applicant_social_number'])) unset($data['co_applicant_social_number']);

		return $data;
	}

	private function anon($data) {

		$unset = ['email', 'mobile_number'];

		foreach ($unset as $value)
			if (isset($data[$value])) unset($data[$value]);

		return $data;
	}

	private function slack($data, $name) {

		$hook = get_option('em_axowl');

		if (!isset($hook['slack'])) return;

		$hook = $hook['slack'];

		$send = 'Axo | Norskfinans :flag-no: | '.$data['payout'];

		$posting_to_slack = wp_remote_post($hook, array(
			'method' => 'POST',
			'timeout' => 30,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => false,
			'headers' => array(),
			'body' => ['payload' => json_encode(['text' => $this->message($send)])],
			'cookies' => array()
			)
		);
	}

	private function ga($status, $value) {
		// status: accepted, rejected, incomplete
		// value: event value (2200)

		$tag = get_option('theme_google_scripts');

		global $post;

		$post_name = $post->post_name ? $post->post_name : 'na postname';

		$tag = $tag['adwords'];
		if (!isset($tag['adwords'])) {
			$tag = get_opton('em_axowl');

			if (!isset($tag['ga_code'])) return;

			$tag = $tag['ga_code'];
		}

		if (is_user_logged_in()) return;


		global $wp;
		$dl = home_url($wp->request);
		$dl = preg_replace('/\?.*$/', '', $dl);

		// $dl = $_SERVER['HTTP_REFERER'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$ua = $_SERVER['HTTP_USER_AGENT'];
		$t = 'event';
		$ec = 'Axo Form';
		$ea = $post_name;
		$el = $status;
		$cookie = isset($_COOKIE['_ga']) ? $_COOKIE['_ga'] : rand(1000000,5000000);

		$content = wp_remote_post('https://www.google-analytics.com/collect', [
			'method' => 'POST',
			'timeout' => 30,
			'redirection' => 5,
			// 'httpversion' => '1.0',
			'blocking' => false,
			'headers' => [],
			'body' => [
				'v' => '1', 
				'tid' => $tag, 
				'cid' => $cookie,
				'uip' => $ip,
				'ua' => $ua,
				't' => $t, 
				'ec' => $ec, 
				'ea' => $ea, 
				'el' => $el, 
				'dl' => $dl,
				'ev' => $value
				],
			'cookies' => []
			]
		);
	}

}