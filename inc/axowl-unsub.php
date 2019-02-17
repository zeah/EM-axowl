<?php 

defined('ABSPATH') or die('Blank Space');

final class Axowl_unsub {
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
		add_action('init', [$this, 'unsub']);
	}

	public function unsub() {
		// wp_die('<xmp>'.print_r('hi', true).'</xmp>');
		
		if (!isset($_GET['action']) || $_GET['action'] != 'unsub') return;
		if (!isset($_GET['email'])) return;

		$url = get_option('em_axowl');

		if (!isset($url['unsub'])) return;

		$url = $url['unsub'];

		$email = $_GET['email'];

		preg_match('/.*@.*?\..*/', $email, $matches);

		if (!$matches) return;

		// echo print_r($matches, true);
		// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return;

		// echo 'Email unsubbed '.$url.'?email='.$email;

		echo 'Hvis '.$email.' var registrert skal den være fjernet nå.';
		echo '<br>If '.$email.' was registered then it is removed now.';

		// wp_remote_get($url.'?email='.$email), ['blocking' => false]);
		exit;
		// wp_die();
	}
}