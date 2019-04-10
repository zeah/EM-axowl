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
	 * sql_conversions() fixes data for database
	 * gdocs_ads() fixed data for gdocs for gads.
	 * get_url() gets url from WP options
	 * remove_confidential()
	 * get_clid() gets the google or bing click id - either from cookie or query string
	 * 
	 */

	/* singleton */
	private static $instance = null;

	private $contact_accept = true;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {

		// wp_die('<xmp>'.print_r($_SERVER, true).'</xmp>');

		add_action( 'wp_ajax_nopriv_axowl', [$this, 'from_form']);
		add_action( 'wp_ajax_axowl', [$this, 'from_form']);

		add_action( 'wp_ajax_nopriv_wlinc', [$this, 'incomplete']);
		add_action( 'wp_ajax_wlinc', [$this, 'incomplete']);

		add_action( 'wp_ajax_nopriv_popup', [$this, 'popup']);
		add_action( 'wp_ajax_popup', [$this, 'popup']);

		// add_action( 'wp_ajax_nopriv_test', [$this, 'test']);
		// add_action( 'wp_ajax_test', [$this, 'test']);
	}

	// public function test() {

	// 	$data = isset($_POST['data']) ? $_POST['data'] : 'nothing';

	// 	$posting_to_slack = wp_remote_post('https://hooks.slack.com/services/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', array(
	// 		'method' => 'POST',
	// 		'timeout' => 30,
	// 		'redirection' => 5,
	// 		'httpversion' => '1.0',
	// 		'blocking' => false,
	// 		'headers' => array(),
	// 		'body' => ['payload' => json_encode(['text' => $data])],
	// 		'cookies' => array()
	// 		)
	// 	);


	// 	echo 'worked';

	// 	exit;
	// }


	/**
	 * checking POST that only allowed keys are processed
	 */
	public function from_form() {
		$data = $_POST['data'];

		// TODO testes
		// if (isset($data['fax'])) return;

		if (isset($data['contact_accept'])) $this->contact_accept = $data['contact_accept'] ? true : false;

		// echo 'stopped at from_form';
		// return;

		// testing
		// echo 'Referrer: '.$this->get_referer();


		// match from inputs.php
		$data_keys = array_keys($data);
		$input_keys = array_keys(Axowl_inputs::$inputs);

		$send = [];

		foreach ($data_keys as $k)
			if (in_array($k, $input_keys))
				$send[$k] = $data[$k];

		// echo print_r($send, true);
		
		// sending to axo
		$this->send_axo($send);

		exit;
		// wp_die();
	}



	/**
	 * When first next button is clicked on the form, then 
	 * an incomplete is sent.
	 * 
	 */
	public function incomplete() {

		// echo print_r($_POST, true);
		// exit;

		// checkbox
		if (!isset($_POST['contact_accept'])) exit;

		$data = ['status' => 'incomplete'];

		$ga = isset($_POST['ga']) ? $_POST['ga'] : false;

		if (isset($_POST['email'])) $data['email'] = $_POST['email'];
		if (isset($_POST['mobile_number'])) $data['mobile_number'] = preg_replace('/[^0-9]/', '', $_POST['mobile_number']);

		$this->send(http_build_query($data), 'sql_info');
		$this->ga('incomplete', 0);

		// echo 'incomplete';
		// echo print_r($data, true);

		exit;
	}


	/**
	 * [popup description]
	 */
	public function popup() {
		// echo print_r($_POST, true);
		// exit;

		$data = ['status' => 'popup'];

		$email = false;
		$phone = false;

		$ga = isset($_POST['ga']) ? $_POST['ga'] : [];


		if (isset($_POST['pop-email']) && $this->val_email($_POST['pop-email'])) $email = $_POST['pop-email'];
		if (isset($_POST['pop-phone']) && $this->val_phone($_POST['pop-phone'])) $phone = $_POST['pop-phone'];

		if (!$email && !$phone) exit;

		$data['email'] = $email;
		$data['mobile_number'] = $phone;

		// echo 'Data to be sent from popup: '.http_build_query($data);

		$this->send(http_build_query($data), 'sql_info');
		$this->ga('popup', 0);

		// echo 'popup';
		// echo print_r($ga, true);

		exit;
	}

	private function val_email($email) {
		if (strpos($email, '@') === false) return false;
		return true;
	}

	private function val_phone($phone) {
		if (preg_match('/^\d{8}$/', $phone) === 0) return false;
		return true;
	}


	/**
	 * [send_axo description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function send_axo($data) {
		$settings = get_option('em_axowl');
		if (!isset($settings['form_url']) || !isset($settings['name'])
			|| !$settings['form_url'] || !$settings['name']) return;
		

		// axo url
		$url = $settings['form_url'].'?';
		
		// name of partner as agreed with axo 
		$data['source'] = $settings['name'];

		if (isset($data['content'])) $data['content'] = $settings['content'];

		$data['customer_ip'] = $_SERVER['REMOTE_ADDR'];

		unset($data['contact_accept']);
		unset($data['axo_accept']);

		$url .= http_build_query($data);


		// testing - to be deleted
		echo "\n\nto axo:\n".print_r($data, true)."\n\n";

		// sending to axo
		$response = wp_remote_get($url);

		if (is_wp_error($response)) {
			echo '{"status": "error", "code": "'.wp_remote_retrieve_response_code($response).'"}';
			return;
		}

		$res = json_decode(wp_remote_retrieve_body($response), true);

		if (!is_array($res) || !isset($res['status'])) return;


		echo "\n\nResponse\n";
		echo print_r($response);
		echo "\n\n\n";

		// exit;

		// echo print_r($res, true);
		// $res = ['status' => 'Accepted'];

		$data = $this->remove_confidential($data);
		$data['transactionId'] = isset($res['transactionId']) ? $res['transactionId'] : '';


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


		// send gfunc sql
		$this->send(http_build_query($data), 'sql_info');

		// sending conversion details to sql
		$this->sql_conversions($data);

		// sending to gdocs for google ads
		// $this->gdocs_ads(http_build_query($data));
		$this->gdocs_ads($data);

		// google analytics
		$value = get_option('em_axowl');
		$value = isset($value['payout']) ? $value['payout'] : 0;
		$this->ga('accepted', $value);

		// if (isset($data['email'])) {
		// 	$unsub = Axowl_unsub::get_instance();

		// 	$unsub->unsub($data['email']); // ends in exit;
		// }

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
		
		// send data to sql
		if ($this->contact_accept) $this->send(http_build_query($data), 'sql_info');
		else $this->send(http_build_query($data), 'sql_info');

		// google analytics
		$this->ga('rejected', 0);

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


		// for testing
		// echo $name.': '.$url.$query."\n\n";
		echo "\n\nSENDING\n";
		echo $name;
		echo "\n";
		echo $query;
		echo "\n\n\n";

		// return;


		wp_remote_get(trim($url).$query, ['blocking' => false]);
	}




	/**
	 * [sql description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function sql_conversions($data) {

		// TODO -- if no clid then add referer from cookie

		$opt = get_option('em_axowl');
		$d = [
			'campaign' => 'axo',
			'media' => $_SERVER['SERVER_NAME'],
			'payout' => isset($opt['payout']) ? $opt['payout'] : 'not set',
			'affiliate' => 'axo wl',
			'tracking' => $this->get_clid(),
			'status' => 'approved',
			'currency' => isset($opt['currency']) ? $opt['currency'] : 'not set'
			// last parameter is timestamp which sql fills out all by itself.
		];

		// echo 'conversion:';
		// echo print_r($d, true);
		// return;

		$this->send(http_build_query($d), 'sql_conversions');
	}




	/**
	 * [gdocs_ads description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function gdocs_ads($data) {
		// Google Click ID, Conversion Name, Conversion Time, Conversion Value, Conversion Currency

		$opt = get_option('em_axowl');

		// if not set in settings
		if (!isset($opt['gdocs_ads']) || !isset($opt['payout']) || !isset($opt['currency'])) return;

		// $data = http_build_query($data);

		// $clid = isset($_COOKIE['gclid']) ? $_COOKIE['gclid'] : false; 

		// preg_match('/^.*(?:gclid=)(.*?)(?:&|$)/', $_SERVER['QUERY_STRING'], $match);

		// if (isset($match[1])) $clid = $match[1];

		// if (!$clid) return;

		// if no click id (either google click id, or bing click id)
		$clid = $this->get_clid();
		if (!$clid) return;

		$d = [
			'Google Click ID' => $clid,
			'Conversion Name' => 'AXO',
			'Conversion Time' => date('M d, Y h:i:s A'),
			'Conversion Value' => $opt['payout'],
			'Conversion Currency' => $opt['currency']
		];

		// echo 'gdocs: ';
		// echo print_r($d, true);
		// return;

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

		// adding age from social number
		if (isset($data['social_number']) && $data['social_number']) {
			$d = $data['social_number'];
			$data['age'] = sprintf('%s-%s-%s', 
							(intval(substr($d, 4, 2)) < 20) ? '20'.substr($d, 4, 2) : '19'.substr($d, 4, 2), 
							substr($d, 2, 2), 
							substr($d, 0, 2));

			unset($data['social_number']);
		}

		if (isset($data['co_applicant_social_number'])) unset($data['co_applicant_social_number']);

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
		// TODO add dl to $d
		// TODO shortcode number to event action

		// if (is_user_logged_in()) return;

		// echo "\npost in ga:\n\n".print_r($_POST, true)."\n\n\n";

		$data = false;

		if (isset($_POST['ga'])) $data = $_POST['ga'];
		elseif (isset($_POST['data']['ga'])) $data = $_POST['data']['ga'];


		if (!$data) return;


		echo "\n\n\n GA data:\n";
		echo print_r($data, true);
		echo "\n\n\n";

		// $tag = get_option('em_axowl');

		// if (!isset($tag['ga_code']) && $tag['ga_code'] != '') return;

		// $tag = $tag['ga_code'];
		$tag = 'test_tag';


		// $abname = 'none';
		// if (isset($data['abname'])) $abname = $data['abname'];
		// $absc = isset($data['absc']) ? $data['absc'] : 'na';

		$action = isset($data['name']) ? $data['name'] : 'n/a';

		// $action .= ' - '.$absc;


		$d = [
			'v' => '1', 
			'tid' => $tag, 
			// 'cid' => $data['ga'],
			'uip' => $_SERVER['REMOTE_ADDR'],
			'ua' => $_SERVER['HTTP_USER_AGENT'],
			't' => 'event', 
			// TODO make ec into axo form # .. for ab testing
			'ec' => 'axo form', 
			'ea' => $action, // for ab-testing - abname or postname + shortcode #
			'el' => $status, // accepted, rejected or incomplete or popup
			'ev' => $value, // value of conversion
		];

		// cid
		if (!isset($data['id'])) $data['id'] = $_COOKIE['_ga'] ? $_COOKIE['_ga'] : false;
		if ($data['id']) $d['cid'] = $data['id'];


		// dr
		$ref = $this->get_referer();
		if  ($ref) $d['dr'] = $ref;

		if (isset($data['viewport'])) $d['vp'] = $data['viewport'];
		if (isset($data['screen'])) $d['sr'] = $data['screen'];

		// getting site url without query string
		global $wp;
		$dl = home_url($wp->request);
		$d['dl'] = preg_replace('/\?.*$/', '', $dl);

		echo "\nGA:\n";
		echo print_r($d, true);
		echo "\n\n\n";
		return;

		// sending to google analytics
		wp_remote_post('https://www.google-analytics.com/collect', [
			'method' => 'POST',
			'timeout' => 30,
			'redirection' => 5,
			// 'httpversion' => '1.0',
			'blocking' => false,
			'headers' => [],
			'body' => $d,
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

		return false;
	}


	private function get_referer() {

		// echo "\n\n\n";
		// echo print_r($_SERVER);
		// echo "\n\n\n";

		if (isset($_SERVER['REFERER']) && $_SERVER['REFERER']) {

			$r = $_SERVER['HTTP_REFERER'];

			if (strpos($r, $_SERVER['SERVER_NAME']) === false) return $r;
		}

		elseif (isset($_COOKIE['referer'])) return $_COOKIE['referer'];

		return false;
	}


}