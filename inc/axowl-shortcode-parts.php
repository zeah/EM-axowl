<?php 
defined('ABSPATH') or die('Blank Space');

final class Axowl_shortcode_parts {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
	}

	/**
	 * [page_top description]
	 * @param  [type] $nr [description]
	 * @return [type]     [description]
	 */
	public function page_top($nr = null) {
		if (!$nr) return '';

		return sprintf(
			'<div class="em-part em-part-%s">
				<div class="em-part-title-container">
					<h2 class="em-part-title"></h2>
				</div>',

				$nr
		);
	}

	public function form_buttons($o = []) {
		return sprintf('
			<div class="em-b-container%s"><div class="em-b-inner">
				<button class="em-b em-b-next" type="button">Neste</button>
				<div class="em-progress-container">
					<progress title="framdriftsbar" class="em-progress" value="0" max="100"></progress>
					<div class="em-progress-text">0%%</div>
				</div>
				<button class="em-b em-b-submit em-hidden" type="button">Send inn</button>
			</div></div>
		',
			isset($o['hidden']) ? ' em-hidden' : ''
		);
				// <button class="em-b em-b-back em-hidden" type="button">Tilbake</button>
	}

	public function form_buttons2() {
		// return '<button class="em-b em-b-next" type="button">Neste</button>';
				// <div class="em-progress-container">
				// 	<progress title="framdriftsbar" class="em-progress" value="0" max="100"></progress>
				// 	<div class="em-progress-text">0%</div>
				// </div>
		return '
			<div class="em-b-container">
				<button class="em-b em-b-next" type="button">Neste</button>
				<button class="em-b em-b-submit em-hidden" type="button">Send inn</button>
				<button class="em-b em-b-back em-hidden" type="button">Tilbake</button>
			</div>
		';
	}

	public function popup() {
		return '
			<div class="em-popup">
				<h2 class="em-popup-title">Din søknad er sendt til Axo.</h2>
				<button type="button" class="em-popup-x"></button>
				<div class="em-popup-content"></div>
				<button type="button" class="em-popup-button">Ok</button>
			</div>
		';
	}


	/**
	 * [element description]
	 * @param  String $key   name of html element
	 * @param  Array $value html settings
	 * @param  Array $data  content
	 * @return String        html element
	 */
	public function element($key, $value, $data) {

		// div element (container)
		if (substr($key, 0,3) == 'div') {
			return sprintf(
				'<div class="%s%s">', 
			
				isset($value['class']) ? $value['class'] : '',
				
				isset($value['hidden']) ? ' em-hidden' : ''
			);
		}

		// ending div element (container)
		if (substr($key, 0,4) == '/div')  return '</div>';


		// html settings
		$d = [
			'name' => $key,
			'value' => $value
		];

		// text for disabled text input
		if (isset($value['notInput'])) $d['text'] = isset($value['text_field']) ? $value['text_field'] : '';
		else $d['text'] = isset($data[$key]) ? $data[$key] : '';

		// help text 
		if (isset($data[$key.'_ht']) && $data[$key.'_ht']) $d['ht'] = $data[$key.'_ht'];
		
		// error text
		if (isset($data[$key.'_error']) && $data[$key.'_error']) $d['et'] = $data[$key.'_error'];

		if (isset($value['button_text'])) $d['text'] = $value['button_text'];
		// html element container
		$html = sprintf('
			<div class="em-element-container em-element-%s%s">', 
			
			$key, 
			
			isset($value['hidden']) ? ' em-hidden' : ''
		);

		// disable text input
		if (isset($value['notInput'])) $html .= $this->text_field($d);
		
		// text input
		if (isset($value['text'])) $html .= $this->text($d);

		// range slider input
		if (isset($value['range'])) $html .= $this->range($d);

		// checkbox (yes/no buttons)
		if (isset($value['checkbox'])) $html .= $this->checkbox($d);

		// check input
		if (isset($value['check'])) $html .= $this->check($d);

		// list input
		if (isset($value['list'])) $html .= $this->list($d);

		if (isset($value['button'])) $html .= $this->button($d);

		// end of html element container		
		$html .= '</div>';

		return $html;
	}

	/**
	 * [text description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	private function text($o = []) {
		if (!isset($o['name'])) return '';
		
		return sprintf(
			'<div class="em-ic em-ic-%1$s">
				<label for="%1$s" class="em-label em-label-%1$s">
					<h4 class="em-it em-it-%1$s">%2$s</h4>
					%3$s
				</label>
				<input class="em-i em-i-%1$s" id="%1$s" name="%1$s"%4$s%5$s type="%6$s" value="%7$s"%8$s%9$s%10$s%11$s>
				%12$s%13$s
			</div>',

			$o['name'], // 1
			
			$o['text'], // 2
			
			isset($o['ht']) ? $this->help_element($o['name'], $o['ht']) : '', // 3

			isset($o['value']['max']) ? ' max='.$o['value']['max'] : '', // 4
			
			isset($o['value']['min']) ? ' min='.$o['value']['min'] : '', // 5
			
			isset($o['value']['type']) ? $o['value']['type'] : 'text', // 6
			
			isset($o['value']['default']) ? $o['value']['default'] : '', // 7
			
			isset($o['value']['validation']) ? ' data-val="'.$o['value']['validation'].'"' : '', // 8
			
			isset($o['value']['format']) ? ' data-format="'.$o['value']['format'].'"' : '', // 9
			
			isset($o['value']['digits']) ? ' data-digits="'.$o['value']['digits'].'"' : '', // 10
			
			isset($o['value']['show']) ? ' data-show="'.$o['value']['show'].'"' : '', // 11

			$this->valid_element(), // 12

			isset($o['et']) ? $this->error_element($o['name'], $o['et']) : '' // 13
		);
	}


	/**
	 * [range description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	private function range($o = []) {
		if (!isset($o['name'])) return '';

		// wp_die('<xmp>'.print_r($o, true).'</xmp>');

		return sprintf(
			'<input class="em-r em-r-%1$s" id="em-r-%1$s" type="range"%2$s%3$s%4$s%5$s>',

			$o['name'],
			
			isset($o['value']['max']) ? ' max='.$o['value']['max'] : '',
			
			isset($o['value']['min']) ? ' min='.$o['value']['min'] : '',
			
			isset($o['value']['step']) ? ' step='.$o['value']['step'] : '',
			
			isset($o['value']['default']) ? ' value='.$o['value']['default'] : ''
		);
	}


	private function checkbox($o = []) {
		if (!isset($o['name'])) return '';
		// wp_die('<xmp>'.print_r($o, true).'</xmp>');
		
		return sprintf('
			<div class="em-cc em-cc-%1$s">
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

			isset($o['ht']) ? $this->help_element($o['name'], $o['ht']) : '',
			
			isset($o['value']['yes']) ? ' em-cc-green' : '',
			
			isset($o['value']['no']) ? ' em-cc-green' : '',

			isset($o['value']['yes']) ? '1' : '0',

			isset($o['value']['show']) ? ' data-show="'.$o['value']['show'].'"' : ''
		);
	}

	/**
	 * [check description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	private function check($o = []) {
		return sprintf('
			<div class="em-element em-element-check em-element-check-%1$s">
				<input type="checkbox" name="%1$s" id="em-check-%1$s" class="em-i em-check em-check-%1$s"%3$s checked>
				<label for="em-check-%1$s"><span>%2$s</span></label>
			</div>',
			
			$o['name'],
			
			$o['text'],
			
			isset($o['value']['validation']) ? ' data-val="'.$o['value']['validation'].'"' : ''
		);
	}

	/**
	 * [list description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	private function list($o = []) {
		if (!$o) return '';

		if (isset($o['value']['empty']) && $o['value']['empty'] === false) $options = '';
		else $options = '<option></option>';

		if (isset($o['value']['list']))
			foreach ($o['value']['list'] as $key => $value) {

				$sel = false;
				if (isset($o['value']['start']) && $o['value']['start'] == $key) $sel = true;

				$options .= sprintf(
					'<option value="%s"%s>%s</option>',

					isset($o['value']['key_as_value']) ? $key : $value,

					$sel ? ' selected' : '',

					$value
				);
			}

		if ($options == '') return ''; 

		return sprintf(
			'<div class="em-lc em-lc-%1$s">
				<label class="em-label" for="%1$s">
					<h4 class="em-it em-it-%1$s">%2$s</h4>
					%3$s
				</label>
				<select class="em-i em-i-%1$s" id="%1$s" name="%1$s"%4$s>
					%5$s
				</select>
				%6$s%7$s
			</div>
			',

			$o['name'],

			$o['text'],

			isset($o['ht']) ? $this->help_element($o['name'], $o['ht']) : '',

			isset($o['value']['validation']) ? ' data-val="'.$o['value']['validation'].'"' : '',

			$options,

			$this->valid_element(),

			isset($o['et']) ? $this->error_element($o['name'], $o['et']) : ''

		);
	}

	private function text_field($o = []) {
		return sprintf('
			<div style="display: flex; justify-content: center;">
				<div class="em-container-%2$s">%1$s</div>
				<input type="text" class="em-if em-if-%2$s" disabled value=50>
			</div>',

			$o['text'],
			
			$o['name']
		);
	}

	private function button($o = []) {
		return sprintf('
			<button class="em-b-neste" type="button">%s</button>',
			$o['text']
		);
	}

	private function help_element($name, $text) {
		return sprintf(
			'<button type="button" class="em-ht-q">
					<span>?</span>
				</button>
				<div class="em-ht em-hidden em-ht-%s">
					<div class="arrow-right"></div>
					<div>%s</div></div>',
			
			$name,

			$text
		);
	}

	private function error_element($name, $text) {
		return sprintf(
			'<div class="em-error em-error-%s em-hidden">%s</div>',

			$name, // class name

			$text // element content
		);
	}

	private function valid_element() {
		return sprintf(
			'<img class="em-marker-valid em-marker-val em-hidden" src="%s">
			<img class="em-marker-invalid em-marker-val em-hidden" src="%s">',
			
			esc_url(EM_AXOWL_PLUGIN_URL.'assets/img/greentick.png'),
			
			esc_url(EM_AXOWL_PLUGIN_URL.'assets/img/redtick.png')
		);		
	}


}