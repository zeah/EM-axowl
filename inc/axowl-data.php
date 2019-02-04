<?php 




defined('ABSPATH') or die('Blank Space');

final class Axowl_data {

	/**
	 * from_form() recieves ajax from front end
	 *
	 * send_axo() sends data to axo and waits for response
	 *
	 * accepted() if response from axo is accepted
	 * 		-> anonymized info to datastore
	 * 		-> slack for conversion upate
	 * 		-> sql for conversion storage
	 * 		-> google docs for google ads import
	 *   	-> GA for event hit (accepted) (or ecommerce)
	 * 
	 * rejected() if response from axo is rejected
	 * 		-> all info to datastore (without confidential info)
	 * 		-> google docs with email and phone number (or just get from datastore?)
	 * 		-> GA for event hit (rejected)
	 *
	 * ga() sends post data to google analytics
	 * 
	 * helper functions:
	 * send() get method with query and name of url
	 * sql() fixes data for database
	 * gdocs_ads() fixed data for gdocs for gads.
	 * get_url() gets url from WP options
	 * remove_confidential()
	 * anon()
	 * get_clid() gets the google or bing click id - either from cookie or query string
	 * 
	 */

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




	/**
	 * [from_form description]
	 * @return [type] [description]
	 */
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




	/**
	 * [send_axo description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function send_axo($data) {
		$settings = get_option('em_axowl');
		if (!isset($settings['form_url']) || !isset($settings['name'])) return;

		// axo url
		$url = $settings['form_url'].'?';
		
		// name of partner as agreed with axo 
		$data['source'] = $settings['name'];

		$data['customer_ip'] = '';

		$url .= http_build_query($data);

		echo 'axo url: '.$url."\n\n";

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




	/**
	 * [accepted description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function accepted($data) {

		$data['status'] = 'accepted';

		// send all anonymized gfunc datastore
		$this->send(http_build_query($this->anon($data)), 'google_functions');

		// sending conversion details to sql
		$this->sql($data);

		// sending to gdocs for google ads
		$this->gdocs_ads(http_build_query($data));
		

		// google analytics
		$this->ga('accepted', $value);

		// send event or/and ecommerce data to GA
		// google ads import from GA?
	}




	/**
	 * [rejected description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function rejected($data) {
		$data['status'] = 'rejected';
		
		// send email and phone to gdcos
		$this->send(http_build_query($data), 'gdocs_email');

		// send data to datastore
		$this->send(http_build_query($data), 'google_functions');

		// google analytics
		// $this->ga('rejected', 0);

	}




	/**
	 * [send description]
	 * @param  [type] $query [description]
	 * @param  [type] $name  [description]
	 * @return [type]        [description]
	 */
	private function send($query, $name) {

		$url = $this->get_url($name);

		if (!$url) return;

		if (strpos($url, '?') === false) $url .= '?';


		echo $name.': '.$url.$query."\n\n";
		// wp_remote_get($url.$query, ['blocking' => false]);
	}




	/**
	 * [sql description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function sql($data) {

		// TODO -- if no clid then add referer from cookie

		$opt = get_option('em_axowl');
		$d = [
			'campaign' => 'axo',
			'media' => $_SERVER['SERVER_NAME'],
			'payout' => isset($opt['payout']) ? $opt['payout'] : 'not set',
			'tracking' => $this->get_clid(),
			'status' => 'accepted',
			'currency' => isset($opt['currency']) ? $opt['currency'] : 'not set'
			// last parameter is timestamp which sql fills out all by itself.
		];

		$this->send(http_build_query($d), 'sql');
	}




	/**
	 * [gdocs_ads description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function gdocs_ads($data) {
		// Google Click ID, Conversion Name, Conversion Time, Conversion Value, Conversion Currency

		$opt = get_option('em_axowl');

		if (!isset($opt['payout']) || !isset($opt['currency'])) return;

		$clid = isset($_COOKIE['gclid']) ? $_COOKIE['gclid'] : false; 

		preg_match('/^.*(?:gclid=)(.*?)(?:&|$)/', $_SERVER['QUERY_STRING'], $match);

		if (isset($match[1])) $clid = $match[1];

		if (!$clid) return;

		$d = [
			'Google Click ID' => $clid,
			'Conversion Name' => 'AXO',
			'Conversion Time' => date('M d, Y h:i:s A'),
			'Conversion Value' => $opt['payout'],
			'Conversion Currency' => $opt['currency']
		];

		$this->send(http_build_query($d), 'gdocs_ads');
	}
	// /**
	//  * [query description]
	//  * @param  [type] $url  [description]
	//  * @param  [type] $data [description]
	//  * @return [type]       [description]
	//  */
	// private function query($url, $data) {

	// 	$v = get_option('em_axowl');

	// 	// gclid
	// 	// _ga
	// 	// 
	// 	// $url = (strpos($url[$value], '?') === false) ? $url.'?' : $url;

	// 	foreach ($data as $key => $value)
	// 		$url = str_replace('{'.$key.'}', $value, $url);

	// 	$url = preg_replace('/{.*?}/', '', $url);

	// 	$url = str_replace('&amp;', '&', $url);

	// 	$url .= '&gclid=';

	// 	$url .= '&msclkid=';

	// 	$url .= '&referer=';

	// 	if (isset($v['currency'])) $url .= '&currency='.$v['currency'];

	// 	if (isset($v['payout'])) $url .= '&payout='.$v['payout'];
	// 	// echo print_r($data, true);

	// 	// echo 'query: '.$url;

	// 	return $url;

	// }



	/**
	 * [get_url description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	private function get_url($value) {
		$url = get_option('em_axowl');

		if (isset($url[$value])) return $url[$value];

		return false;
	}




	/**
	 * [remove_confidential description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function remove_confidential($data) {
		if (isset($data['account_number'])) unset($data['account_number']);
		if (isset($data['social_number'])) unset($data['social_number']);
		if (isset($data['co_applicant_social_number'])) unset($data['co_applicant_social_number']);

		return $data;
	}




	/**
	 * [anon description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function anon($data) {

		$unset = ['email', 'mobile_number'];

		foreach ($unset as $value)
			if (isset($data[$value])) unset($data[$value]);

		return $data;
	}




	// /**
	//  * [slack description]
	//  * @param  [type] $data [description]
	//  * @param  [type] $name [description]
	//  * @return [type]       [description]
	//  */
	// private function slack($data, $name) {

	// 	$hook = get_option('em_axowl');

	// 	if (!isset($hook['slack'])) return;

	// 	$hook = $hook['slack'];

	// 	$send = 'Axo | Norskfinans :flag-no: | '.$data['payout'];

	// 	$posting_to_slack = wp_remote_post($hook, array(
	// 		'method' => 'POST',
	// 		'timeout' => 30,
	// 		'redirection' => 5,
	// 		'httpversion' => '1.0',
	// 		'blocking' => false,
	// 		'headers' => array(),
	// 		'body' => ['payload' => json_encode(['text' => $this->message($send)])],
	// 		'cookies' => array()
	// 		)
	// 	);
	// }

	private function ga($status, $value) {
		// status: accepted, rejected, incomplete
		// value: event value (2200)
		if (is_user_logged_in()) return;

		$tag = get_option('em_axowl');

		if (!isset($tag['ga_code'])) return;

		$tag = $tag['ga_code'];

		// $tag = get_option('theme_google_scripts');

		global $post;

		$post_name = $post->post_name ? $post->post_name : 'na postname';

		// if (!isset($tag['adwords'])) {
		// 	$tag = get_opton('em_axowl');

		// 	if (!isset($tag['ga_code'])) return;

		// 	$tag = $tag['ga_code'];
		// }
		// else $tag = $tag['adwords'];


		// getting site url without query string
		global $wp;
		$dl = home_url($wp->request);
		$dl = preg_replace('/\?.*$/', '', $dl);

		// sending to google analytics
		wp_remote_post('https://www.google-analytics.com/collect', [
			'method' => 'POST',
			'timeout' => 30,
			'redirection' => 5,
			// 'httpversion' => '1.0',
			'blocking' => false,
			'headers' => [],
			'body' => [
				'v' => '1', 
				'tid' => $tag, 
				'cid' => isset($_COOKIE['_ga']) ? $_COOKIE['_ga'] : rand(1000000,5000000),
				'uip' => $_SERVER['REMOTE_ADDR'],
				'ua' => $_SERVER['HTTP_USER_AGENT'],
				't' => 'event', 
				'ec' => 'axo form', 
				'ea' => $post_name, // for ab-testing
				'el' => $el, // accepted or rejected
				'dl' => $status, // url without query
				'ev' => $value // value of conversion
				],
			'cookies' => []
			]
		);
	}


	private function get_clid() {

		if (isset($_COOKIE['clid'])) return $_COOKIE['clid'];

		$query = $_SERVER['QUERY_STRING'];

		$check = ['gclid', 'msclkid'];

		foreach ($check as $c) {
			$pattern = '/^.*(?:'.$c.'=)(.*?)(?:&|$)/';

			preg_match($pattern, $query, $match);

			if (isset($match[1])) return $match[1];
		}

		return 'NULL';
	}


}