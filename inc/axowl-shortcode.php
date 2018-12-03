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

	/**
	 * [hooks description]
	 */
	private function hooks() {
		add_shortcode('axowl', [$this, 'shortcode']);
	}

	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode($atts, $content = null) {

		add_action('wp_footer', [$this, 'footer']);

		$data = get_option('em_axowl');
		$data = $this->sanitize($data);

		$inputs = [
			'account_number' => ['text' => true, 'type' => 'number'], 
			'loan_amount' => ['text' => true, 'range' => true, 'max' => 200, 'min' => 50],
			'co_applicant' => ['checkbox' => true],
			'co_applicant_email' => ['text' => true, 'type' => 'email']

		];

		$html = '<form>';

		$html .= '<input type="hidden" name="'.$data['name'].'">';

		foreach($inputs as $key => $value) {
			$html .= sprintf('<div class="em-form-part em-form-%s">', $key);
			if (isset($value['text'])) $html .= $this->text_input([
													'name' => $key,
													'text' => $data[$key],
													'ht' => $data[$key.'_ht'],
													'value' => $value
												]);

			if (isset($value['range'])) $html .= $this->range_input([
													'name' => $key,
													'value' => $value
												]);

			if (isset($value['checkbox'])) $html .= $this->checkbox_input([
														'name' => $key,
														'text' => $data[$key],
														'ht' => $data[$key.'_ht'],
														'value' => $value
													]);

			$html .= '</div>';
		}
			// $html .= $this->text_input($key, $data[$key], $data[$key.'_ht']);

		$html .= '<button class="em-b em-b-submit" type="submit">Send</button>';

		$html .= '</form>';

		return $html;

	}

	/**
	 * [text_input description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	private function text_input($o = []) {
		if (!isset($o['name'])) return;

		return sprintf('<div class="em-ic em-ic-%1$s">
							<label for="%1$s">
								<h4 class="em-it em-it-%1$s">%2$s</h4>
								%3$s
							</label>
							<input class="em-i em-i-%1$s" id="%1$s" name="%1$s"%4$s%5$s type="%6$s">
						</div>',

						$o['name'],
						$o['text'],
						($o['ht'] ? sprintf('<div class="em-ht%1$s">%1$s</div>', $o['ht']) : ''),
						(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
						(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''),
						(isset($o['value']['type']) ? $o['value']['type'] : 'text')
					);

		return $html;
	}

	/**
	 * [range_input description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	private function range_input($o = []) {
		if (!isset($o['name'])) return;

		return sprintf('<div class="em-rc em-rc-%1$s">
							<input class="em-r em-r-%1$s" id="em-r-%1$s" type="range"%2$s%3$s>
						</div>',

						$o['name'],
						(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
						(isset($o['value']['min']) ? ' min='.$o['value']['min'] : '')
				);
	}

	private function checkbox_input($o = []) {
		if (!isset($o['name'])) return;

		return sprintf('<div class="em-cc em-cc-%1$s">
							<label for="%1$s">
								<h4 class="em-it em-it-%1$s">%2$s</h4>
								%3$s
							</label>
							<input class="em-c em-c-%1$s" id="em-c-%1$s" name="%1$s" type="checkbox">
						</div>',
						$o['name'],
						$o['text'],
						($o['ht'] ? sprintf('<div class="em-ht%1$s">%1$s</div>', $o['ht']) : '')

						);
	}

	/**
	 * [sanitize description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public static function sanitize($data) {
		if (!is_array($data)) return sanitize_text_field($data);

		$d = [];
		foreach($data as $key => $value)
			$d[$key] = Axowl_settings::sanitize($value);

		return $d;
	}

	public function footer() {

		echo '<script>
				var r = document.querySelector(".em-r-loan_amount");
				var a = document.querySelector(".em-i-loan_amount");

				r.addEventListener("input", function(e) { a.value = e.target.value });
				a.addEventListener("input", function(e) { r.value = e.target.value });

			  </script>';

	}
}