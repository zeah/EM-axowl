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

		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);

		$inputs = [
			'loan_amount' => ['text' => true, 'range' => true, 'max' => 500000, 'min' => 10000, 'default' => 150000, 'step' => 10000],
			'tenure' => ['text' => true, 'range' => true, 'max' => 15, 'min' => 1, 'default' => 5],
			'monthly_cost' => ['text' => 'Måndelige kostnader', 'notInput' => true],
			'mobile_number' => ['text' => true, 'type' => 'text'],
			'email' => ['text' => true],

			
			'account_number' => ['text' => true, 'type' => 'number', 'page' => '2'], 

			'co_applicant' => ['checkbox' => true, 'page' => '3'],
			'co_applicant_email' => ['text' => true, 'type' => 'email']

		];

		$html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
					(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
			    );

		$html .= '<input type="hidden" name="'.$data['name'].'">';

		$html .= '<div class="part-container">';

		$html .= '<div class="part part-1">';

		foreach($inputs as $key => $value) {
			if ($value['page']) $html .= '</div><div class="part part-'.$value['page'].'">';
			$html .= $this->element($key, $value, $data);
		}
			

		$html .= '</div></div>';

		$html .= '<div class="em-b-container">';
		$html .= '<button class="em-b em-b-submit" type="button">Neste</button>';
		$html .= '<button class="em-b em-b-back" type="button">Tilbake</button>';
		$html .= '</div>';

		$html .= '</form>';

		// TODO set transient

		return $html;

	}

	private function element($key, $value, $data) {
		$html = sprintf('<div class="em-element-container em-element-%s">', $key);

		if (isset($value['notInput'])) $html .= $this->text_field([
													'name' => $key,
													'text' => $value['text']
												]);

		else {
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
		}

		$html .= '</div>';

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
							<label for="%1$s" class="em-label em-label-%1$s">
								<h4 class="em-it em-it-%1$s">%2$s</h4>
								%3$s
							</label>
							<input class="em-i em-i-%1$s" id="%1$s" name="%1$s"%4$s%5$s type="%6$s" value="%7$s">
						</div>',

						$o['name'],
						$o['text'],
						($o['ht'] ? sprintf('<div class="em-ht-%1$s">%2$s</div>', $o['name'], $o['ht']) : ''),
						(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
						(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''),
						(isset($o['value']['type']) ? $o['value']['type'] : 'text'),
						(isset($o['value']['default']) ? $o['value']['default'] : '')
					);
	}

	/**
	 * [range_input description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	private function range_input($o = []) {
		if (!isset($o['name'])) return;

		return sprintf('<div class="em-rc em-rc-%1$s">
							<input class="em-r em-r-%1$s" id="em-r-%1$s" type="range"%2$s%3$s%4$s%5$s>
						</div>',

						$o['name'],
						(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
						(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''),
						(isset($o['value']['step']) ? ' step='.$o['value']['step'] : ''),
						(isset($o['value']['default']) ? ' value='.$o['value']['default'] : '')
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

	private function text_field($o = []) {
		return sprintf('<div style="display: flex; justify-content: center;">
			<div class="">%1$s</div><input type="text" class="em-if em-if-%2$s" disabled value=50></div>', 
			$o['text'],
			$o['name']);
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


	public function sands() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.0.0', '(min-width: 841px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 840px)');
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'/assets/js/pub/emaxo.js', array(), '1.0.0', true);
	
	}


	// public function footer() {

	// 	echo '<script>

	// 			var c = 1;

	// 			// next button
	// 			var b = document.querySelector(".em-b-submit");

	// 			b.addEventListener("click", function() {
	// 				var t = document.querySelector(".part-"+c);
	// 				var n = document.querySelector(".part-"+(c+1));
	// 				t.style.display = "none";
	// 				n.style.display = "block";
	// 				c++;
	// 			});

	// 			// prev button 
	// 			var p = document.querySelector(".em-b-back");

	// 			p.addEventListener("click", function() {
	// 				var t = document.querySelector(".part-"+c);
	// 				var n = document.querySelector(".part-"+(c-1));
	// 				t.style.display = "none";
	// 				n.style.display = "block";
	// 				c--;
	// 			});

	// 			// loan amount
	// 			var r = document.querySelector(".em-r-loan_amount");
	// 			var a = document.querySelector(".em-i-loan_amount");

	// 			r.addEventListener("input", function(e) { a.value = e.target.value });
	// 			a.addEventListener("input", function(e) { r.value = e.target.value });

	// 		  </script>';

	// }
}