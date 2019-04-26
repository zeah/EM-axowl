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
		add_action( 'wp_ajax_nopriv_axowl', [$this, 'from_form']);
		add_action( 'wp_ajax_axowl', [$this, 'from_form']);

		add_action( 'wp_ajax_nopriv_wlinc', [$this, 'incomplete']);
		add_action( 'wp_ajax_wlinc', [$this, 'incomplete']);

		add_action( 'wp_ajax_nopriv_popup', [$this, 'popup']);
		add_action( 'wp_ajax_popup', [$this, 'popup']);


		add_action( 'wp_ajax_nopriv_gdoc', [$this, 'gdoc']);
		add_action( 'wp_ajax_gdoc', [$this, 'gdoc']);

		add_action( 'wp_ajax_nopriv_del', [$this, 'del']);
		add_action( 'wp_ajax_del', [$this, 'del']);
	}


	/**
	 * checking POST that only allowed keys are processed
	 */
	public function from_form() {
		$data = $_POST['data'];

		// TODO testes
		// if (isset($data['fax'])) return;

		if (isset($data['contact_accept'])) $this->contact_accept = $data['contact_accept'] ? true : false;

		// match from inputs.php
		$data_keys = array_keys($data);
		$input_keys = array_keys(Axowl_inputs::$inputs);

		$send = [];

		foreach ($data_keys as $k)
			if (in_array($k, $input_keys))
				$send[$k] = $data[$k];

		// sending to axo
		$this->send_axo($send);

		exit;
	}



	/**
	 * 
	 */
	public function gdoc() {
		// echo $_POST['test'];
		$url = 'https://script.google.com/macros/s/AKfycbwNrVPopf3GHOh-JoDNkHFai9wwAOlXgtBJxSq7uAXvsugorSWP/exec?';

		$url .= 'type='.$_POST['type'].'&';
		$url .= 'name='.$_POST['name'];
		// echo $url;
		wp_remote_get($url);
		exit;
	}


	/**
	 *
	 */
	public function del() {

		$settings = get_option('em_axowl');

		if (!isset($settings['unsub']) || $settings['unsub'] == '') {
			echo '';
			exit;
		}

		if (!isset($_POST['data']) || $_POST['data'] == '' || !is_string($_POST['data']))
			exit;

		$url = $settings['unsub'];

		$par = '?email='; 

		if (is_numeric($_POST['data'])) $par = '?phone=';

		echo 'success';
		// echo $url.$par.$_POST['data'];

		wp_remote_get($url.$par.$_POST['data']);


		// echo 'success';
		// echo $_POST['data'];
		exit;
	}




	/**
	 * When first next button is clicked on the form, then 
	 * an incomplete is sent.
	 * 
	 */
	public function incomplete() {
		// checkbox
		if (!isset($_POST['contact_accept'])) exit;

		$data = ['status' => 'incomplete'];

		$ga = isset($_POST['ga']) ? $_POST['ga'] : false;

		if (isset($_POST['email'])) $data['email'] = $_POST['email'];
		if (isset($_POST['mobile_number'])) $data['mobile_number'] = preg_replace('/[^0-9]/', '', $_POST['mobile_number']);

		// echo "\nincomplete\n";
		// echo $data;
		// exit;

		$this->send(http_build_query($data), 'sql_info');
		$this->ga('incomplete', 0);

		exit;
	}


	/**
	 * [popup description]
	 */
	public function popup() {
		$data = ['status' => 'popup'];

		$email = false;
		$phone = false;

		$ga = isset($_POST['ga']) ? $_POST['ga'] : [];

		if (isset($_POST['pop-email']) && $this->val_email($_POST['pop-email'])) $email = $_POST['pop-email'];
		if (isset($_POST['pop-phone']) && $this->val_phone($_POST['pop-phone'])) $phone = $_POST['pop-phone'];

		if (!$email && !$phone) exit;

		$data['email'] = $email;
		$data['mobile_number'] = $phone;

		// echo "\popup\n";
		// echo $data;
		// exit;

		$this->send(http_build_query($data), 'sql_info');
		$this->ga('popup', 0);

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


		if (isset($data['unsecured_debt_balance'])) {
			$data['unsecured_debt_lender'] = ['Til Refinansiering'];
			$data['unsecured_debt_balance'] = [$data['unsecured_debt_balance']];
		}

		$url .= http_build_query($data);


		// testing - to be deleted
		// echo "\n\nto axo:\n".print_r($data, true)."\n\n";
		// echo $url;
		// exit;

		// sending to axo
		$response = wp_remote_get($url);
		if (is_wp_error($response)) {
			echo '{"status": "error", "code": "'.wp_remote_retrieve_response_code($response).'"}';
			return;
		}

		$res = json_decode(wp_remote_retrieve_body($response), true);

		if (!is_array($res) || !isset($res['status'])) return;

		// echo "\n\nResponse\n";
		// echo print_r($res, true);
		// echo "\n\n\n";

		// $res = ['status' => 'ValidationError'];

		$data = $this->remove_confidential($data);
		$data['transactionId'] = isset($res['transactionId']) ? $res['transactionId'] : '';


		switch ($res['status']) {
			case 'Accepted': $this->accepted($data); break;
			case 'Rejected': $this->rejected($data); break;
			case 'ValidationError': $this->validation_error($data); break;
			case 'TechnicalError': $this->technical_error($data); break;
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
		// $this->gdocs_ads($data);

		// google analytics
		$value = get_option('em_axowl');
		$value = isset($value['payout']) ? $value['payout'] : 0;
		$this->ga('accepted', $value);
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

	private function validation_error($data) {
		echo 'Validation Error';
	}

	private function technical_error($data) {
		echo 'Technical Error';
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
		// echo "\n\nSENDING\n";
		// echo $name;
		// echo "\n";
		// echo $query;
		// echo "\n\n\n";

		wp_remote_get(trim($url).$query, ['blocking' => false]);
	}




	/**
	 * [sql description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function sql_conversions($data) {

		$opt = get_option('em_axowl');
		$d = [
			'campaign' => 'axo',
			'media' => $_SERVER['SERVER_NAME'],
			'payout' => isset($opt['payout']) ? $opt['payout'] : 'not set',
			'affiliate' => 'axo wl',
			'status' => 'approved',
			'currency' => isset($opt['currency']) ? $opt['currency'] : 'not set'
			// last parameter is timestamp which sql fills out all by itself.
		];

		if (isset($_POST['clid'])) $d['tracking'] = $_POST['clid'];


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

		$this->send(http_build_query($d), 'gdocs_ads');
	}



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



	/**/
	private function ga($status, $value) {
		// TODO shortcode number to event action

		if (is_user_logged_in()) return;

		// echo "\npost in ga:\n\n".print_r($_POST, true)."\n\n\n";

		$data = false;

		if (isset($_POST['ga'])) $data = $_POST['ga'];
		elseif (isset($_POST['data']['ga'])) $data = $_POST['data']['ga'];


		if (!$data) return;

		$tag = get_option('em_axowl');

		// if (!isset($tag['ga_code']) && $tag['ga_code'] != '') return;

		if (isset($tag['ga_code']) && $tag['ga_code'] != '') $tag = $tag['ga_code'];
		else return;

		// if (!$tag) $tag = 'test_tag';
		// $tag = isset($tag['ga_code']) ? $tag['ga_code'] : 'test_tag';
		// $tag = 'test_tag';


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

		// echo "\nGA:\n";
		// echo print_r($d, true);
		// echo "\n\n\n";
		// return;

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

		if (isset($_POST['clid'])) return $_POST['clid'];

		return false;
	}


	private function get_referer() {
		if (isset($_SERVER['REFERER']) && $_SERVER['REFERER']) {

			$r = $_SERVER['HTTP_REFERER'];

			if (strpos($r, $_SERVER['SERVER_NAME']) === false) return $r;
		}

		elseif (isset($_COOKIE['referer'])) return $_COOKIE['referer'];

		return false;
	}


}