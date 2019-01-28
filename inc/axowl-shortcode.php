<?php 

defined('ABSPATH') or die('Blank Space');

require_once 'axowl-inputs.php';

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


		$inputs = AXOWL_inputs::$inputs;


		$html = sprintf('<form class="emowl-form"%s>',
					(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
			    );
		// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
		// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
		// 	    );

		$html .= '<input type="hidden" name="'.$data['name'].'">';

		$html .= '<div class="em-part-container">';

		$html .= '<div class="em-part em-part-1"><div class="em-part-title-container"><h2 class="em-part-title"></h2></div>';

		foreach($inputs as $key => $value) {
			if (isset($value['page'])) $html .= '</div><div class="em-part em-part-'.$value['page'].'"><div class="em-part-title-container"><h2 class="em-part-title em-part-title-slide"></h2></div>';
			$html .= $this->element($key, $value, $data);
		}
			

		$html .= '</div></div>';

		$html .= '<div class="em-b-container">';
		$html .= '<button class="em-b em-b-next" type="button">Neste</button>';
		$html .= '<div class="em-progress-container"><progress title="framdriftsbar" class="em-progress" value="0" max="100"></progress><div class="em-progress-text">0%</div></div>';
		// $html .= '<button class="em-b em-b-submit" type="button">Send inn</button>';
		$html .= '<button class="em-b em-b-submit em-hidden" type="button">Send inn</button>';
		$html .= '<button class="em-b em-b-back em-hidden" type="button">Tilbake</button>';
		$html .= '</div>';

		$html .= '</form>';

		$html .= '<div class="em-popup">
					<h2 class="em-popup-title">Din søknad er sendt til Axo.</h2>
					<button type="button" class="em-popup-x"></button>
					<div class="em-popup-content"></div>
					<button type="button" class="em-popup-button">Ok</button>
				  </div>';

		// TODO set transient

		return $html;

	}

	private function element($key, $value, $data) {

		if (substr($key, 0,3) == 'div') {
			$html = sprintf('<div class="%s%s">', 
				$value['class'] ? $value['class'] : '',
				$value['hidden'] ? ' em-hidden' : ''
			);

			return $html;
		}

		if (substr($key, 0,4) == '/div') {
			$html = '</div>';

			return $html;
		}


		$html = sprintf('<div class="em-element-container em-element-%1$s%2$s">', 
							$key, 
							(isset($value['hidden']) ? ' em-hidden' : '')
						);

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

			if (isset($value['check'])) $html .= $this->check_input([
														'name' => $key,
														'text' => $data[$key],
														'ht' => $data[$key.'_ht'],
														'value' => $value
													]);

			if (isset($value['list'])) $html .= $this->list_input([
													'name' => $key,
													'text' => $data[$key],
													'ht' => $data[$key.'_ht'],
													'list' => $value['list'],
													'validation' => $value['validation'],
													'key_as_value' => $value['key_as_value']
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
		// wp_die('<xmp>'.print_r($o, true).'</xmp>');
		
		return sprintf('<div class="em-ic em-ic-%1$s">
							<label for="%1$s" class="em-label em-label-%1$s">
								<h4 class="em-it em-it-%1$s">%2$s</h4>
								%3$s
							</label>
							<input class="em-i em-i-%1$s" id="%1$s" name="%1$s"%4$s%5$s type="%6$s" value="%7$s"%8$s%9$s%10$s%11$s>
							<div class="em-val-marker"></div>
						</div>',

						$o['name'],
						$o['text'],
						($o['ht'] ? sprintf('<button type="button" class="em-ht-q" tabindex="0"><span>?</span></button type="button"><div class="em-ht em-hidden em-ht-%1$s"><div class="arrow-right"></div><div>%2$s</div></div>', $o['name'], $o['ht']) : ''),
						(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
						(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''),
						(isset($o['value']['type']) ? $o['value']['type'] : 'text'),
						(isset($o['value']['default']) ? $o['value']['default'] : ''),
						(isset($o['value']['validation']) ? ' data-val="'.$o['value']['validation'].'"' : ''),
						(isset($o['value']['format']) ? ' data-format="'.$o['value']['format'].'"' : ''),
						(isset($o['value']['digits']) ? ' data-digits="'.$o['value']['digits'].'"' : ''),
						(isset($o['value']['show']) ? ' data-show="'.$o['value']['show'].'"' : '')
					);
	}

	/**
	 * [range_input description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	private function range_input($o = []) {
		if (!isset($o['name'])) return;

		return sprintf('<input class="em-r em-r-%1$s" id="em-r-%1$s" type="range"%2$s%3$s%4$s%5$s>',

						$o['name'],
						(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
						(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''),
						(isset($o['value']['step']) ? ' step='.$o['value']['step'] : ''),
						(isset($o['value']['default']) ? ' value='.$o['value']['default'] : '')
				);

		// return sprintf('<div class="em-rc em-rc-%1$s">
		// 					<input class="em-r em-r-%1$s" id="em-r-%1$s" type="range"%2$s%3$s%4$s%5$s>
		// 				</div>',

		// 				$o['name'],
		// 				(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
		// 				(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''),
		// 				(isset($o['value']['step']) ? ' step='.$o['value']['step'] : ''),
		// 				(isset($o['value']['default']) ? ' value='.$o['value']['default'] : '')
		// 		);
	}

	private function checkbox_input($o = []) {
		if (!isset($o['name'])) return;
		// wp_die('<xmp>'.print_r($o, true).'</xmp>');
		return sprintf('<div class="em-cc em-cc-%1$s">
								<h4 class="em-it em-it-%1$s">%2$s</h4>
								%3$s
							<input class="em-c em-c-%1$s" name="%1$s" type="hidden" value="%6$s"%7$s>
							<div class="em-cc-selector">
								<button type="button" class="em-i em-cc-yes%4$s">Ja</button>
								<button type="button" class="em-i em-cc-no%5$s">Nei</button>
							</div>
						</div>',
						$o['name'],
						$o['text'],
						($o['ht'] ? sprintf('<button type="button" class="em-ht-q"><span>?</span></button><div class="em-ht em-hidden em-ht-%2$s"><div class="arrow-right"></div><div>%1$s</div></div>', $o['ht'], $o['name']) : ''),
						$o['value']['yes'] ? ' em-cc-green' : '',
						$o['value']['no'] ? ' em-cc-green' : '',
						$o['value']['yes'] ? '1' : '0',
						$o['value']['show'] ? ' data-show="'.$o['value']['show'].'"' : ''
						);
	}

	private function check_input($o = []) {

		return sprintf('<div class="em-element em-element-check em-element-check-%1$s">
							<input type="checkbox" id="em-check-%1$s" class="em-i em-check em-check-%1$s"%3$s>
							<label for="em-check-%1$s">%2$s</label>
						</div>',
						$o['name'],
						$o['text'],
						(isset($o['value']['validation']) ? ' data-val="'.$o['value']['validation'].'"' : '')

					);

	}

	private function text_field($o = []) {
		return sprintf('<div style="display: flex; justify-content: center;">
			<div class="em-container-%2$s">%1$s</div><input type="text" class="em-if em-if-%2$s" disabled value=50></div>', 
			$o['text'],
			$o['name']);
	}


	/**
	 * 
	 */
	private function list_input($o = []) {
		// wp_die('<xmp>'.print_r($o, true).'</xmp>');
		$html = sprintf('<div class="em-lc em-lc-%1$s">', $o['name']);

		$html .= sprintf('<label class="em-label" for="%1$s"><h4 class="em-it em-it-%1$s">%2$s</h4>%3$s</label>',
							$o['name'],
							$o['text'],
							($o['ht'] ? sprintf('<button type="button" class="em-ht-q"><span>?</span></button><div class="em-ht em-hidden em-ht-%2$s"><div class="arrow-right"></div><div>%1$s</div></div>', $o['ht'], $o['name']) : '')
						);

		$html .= sprintf('<select class="em-i em-i-%1$s" id="%1$s" name="%1$s"%2$s>', 
					$o['name'],
					$o['validation'] ? ' data-val="'.$o['validation'].'"' : ''
				);

		$html .= '<option></option>';

		if (isset($o['list']))
			foreach($o['list'] as $key => $value)
				$html .= sprintf('<option value="%1$s">%2$s</option>', 
									$o['key_as_value'] ? $key : $value,
									$value
								);

		$html .= '</select><div class="em-val-marker"></div></div>';
		return $html;
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
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.0.2', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'/assets/js/pub/emaxo.js', array(), '1.0.2', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}
}