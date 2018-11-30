<?php 

defined('ABSPATH') or die('Blank Space');


final class Axowl_shortcode {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->hooks();
	}

	private function hooks() {

		add_shortcode('axowl', [$this, 'shortcode']);

	}


	public function shortcode($atts, $content = null) {


		$data = get_option('em_axowl');
		$data = $this->sanitize($data);

		$inputs = ['account_number'];

		$html = '<form>';

		$html .= '<input type="hidden" name="'.$data['name'].'">';

		// loan amount
		
		// years 
		

		foreach($inputs as $key)
			$html .= $this->input($key, $data[$key], $data[$key.'_ht']);

		$html .= '<button class="em-b em-b-submit" type="submit">Send</button>';

		$html .= '</form>';

		return $html;

	}

	private function input($name, $text, $ht = false) {

		$html = '<div class="em-ic em-ic-'.$name.'">
					<label for="em-i-'.$name.'">
						<h4 class="em-it em-it-'.$name.'">'.$text.'</h4>';

		if ($ht) $html .= ' <div class="em-ht em-ht-'.$ht.'">'.$ht.'</div>';

		$html .= '	</label>
					<input class="em-i em-i-'.$name.'" id="em-i-'.$name.'" type="text" name="'.$name.'">
				</div>';

		return $html;
	}

	public static function sanitize($data) {
		if (!is_array($data)) return sanitize_text_field($data);

		$d = [];
		foreach($data as $key => $value)
			$d[$key] = Axowl_settings::sanitize($value);

		return $d;
	}

}