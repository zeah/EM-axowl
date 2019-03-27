<?php 

defined('ABSPATH') or die('Blank Space');


require_once 'axowl-shortcode-parts.php';
require_once 'axowl-inputs.php';

final class Axowl_shortcode {
	/* singleton */
	private static $instance = null;

	private static $parts;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {

		self::$parts = Axowl_shortcode_parts::get_instance(); 		

		$this->hooks();
	}

	/**
	 * [hooks description]
	 */
	private function hooks() {
		add_shortcode('axowl', [$this, 'shortcode']);

		add_shortcode('axowlicon', [$this, 'icon']);
	}


	/**
	 * for AB testing
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode($atts, $content = null) {

		if (!isset($atts[0])) return $this->shortcode_1($atts, $content);

		switch ($atts[0]) {
			case '1': return $this->shortcode_1($atts, $content); break;
			case '2': return $this->shortcode_2($atts, $content); break;
			case '3': return $this->shortcode_3($atts, $content); break;
			case '4': return $this->shortcode_4($atts, $content); break;
			case '5': return $this->shortcode_5($atts, $content); break;
			case '6': return $this->shortcode_6($atts, $content); break;
			case '10': return $this->shortcode_10($atts, $content); break;
		}
	}

	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_1($atts, $content = null) {

		$p = self::$parts;
		
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);

		$inputs = AXOWL_inputs::$inputs;

		$html = sprintf(
			'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
			isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
		);


		// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
		// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
		// 	    );

		$html .= '<input type="hidden" name="fax">';

		$html .= '<div class="em-part-container">';

		$html .= $p->page_top(1);

		foreach($inputs as $key => $value) {
			// if new page
			if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
			// page content
			$html .= $p->element($key, $value, $data);
		}
			
		// ends last page and ends part container
		$html .= '</div></div>';

		$html .= $p->form_buttons(['hidden' => true]);

		$html .= '</form>';

		$html .= $p->popup().'</div>';

		$html .= '<input type="hidden" id="abtesting-sc" value="1">';

		// TODO set transient

		return $html;
	}




	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_2($atts, $content = null) {

		$p = self::$parts;
		
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands2']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);

		$inputs = AXOWL_inputs::$inputs;

		$html = sprintf(
			'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
			isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
		);


		// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
		// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
		// 	    );

		$html .= '<input type="hidden" name="fax">';

		$html .= '<div class="em-part-container">';

		$html .= $p->page_top(1);

		foreach($inputs as $key => $value) {
			// if new page
			if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
			// page content
			$html .= $p->element($key, $value, $data, 5);
		}
			
		// ends last page and ends part container
		$html .= '</div></div>';

		$html .= $p->form_buttons(['hidden' => true]);

		$html .= '</form>';

		$html .= $p->popup().'</div>';

		$html .= '<input type="hidden" id="abtesting-sc" value="1">';

		// TODO set transient

		return $html;
	}
	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_3($atts, $content = null) {

		$p = self::$parts;
		
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands3']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);

		$inputs = AXOWL_inputs::$inputs;

		$html = sprintf(
			'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
			isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
		);


		// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
		// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
		// 	    );

		$html .= '<input type="hidden" name="fax">';

		$html .= '<div class="em-part-container">';

		$html .= $p->page_top(1);

		foreach($inputs as $key => $value) {
			// if new page
			if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
			// page content
			$html .= $p->element($key, $value, $data, 5);
		}
			
		// ends last page and ends part container
		$html .= '</div></div>';

		$html .= $p->form_buttons(['hidden' => true]);

		$html .= '</form>';

		$html .= $p->popup().'</div>';

		$html .= '<input type="hidden" id="abtesting-sc" value="1">';

		// TODO set transient

		return $html;
	}




	/**
	 * [shortcode_4 description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_4($atts, $content = null) {

		$p = self::$parts;
		
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands4']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);

		$inputs = AXOWL_inputs::$inputs;

		$html = sprintf(
			'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
			isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
		);


		// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
		// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
		// 	    );

		$html .= '<input type="hidden" name="fax">';

		$html .= '<div class="em-part-container">';

		$html .= $p->page_top(1);

		foreach($inputs as $key => $value) {
			// if new page
			if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
			// page content
			$html .= $p->element($key, $value, $data);
		}
			
		// ends last page and ends part container
		$html .= '</div></div>';

		$html .= $p->form_buttons(['hidden' => true]);

		$html .= '</form>';

		$html .= $p->popup().'</div>';

		$html .= '<input type="hidden" id="abtesting-sc" value="1">';

		// TODO set transient

		return $html;
	}


	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_5($atts, $content = null) {

		$p = self::$parts;
		
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands5']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);

		$inputs = AXOWL_inputs::$inputs;

		$html = sprintf(
			'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
			isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
		);


		// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
		// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
		// 	    );

		$html .= '<input type="hidden" name="fax">';

		$html .= '<div class="em-part-container">';

		$html .= $p->page_top(1);

		foreach($inputs as $key => $value) {
			// if new page
			if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
			// page content
			$html .= $p->element($key, $value, $data, 5);
		}
			
		// ends last page and ends part container
		$html .= '</div></div>';

		$html .= $p->form_buttons(['hidden' => true, 'hide_prog' => true]);

		$html .= '</form>';

		$html .= $p->popup().'</div>';

		$html .= '<input type="hidden" id="abtesting-sc" value="1">';

		// TODO set transient

		return $html;
	}


	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_6($atts, $content = null) {

		$p = self::$parts;
		
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands6']);
		add_filter('google_link', [$this, 'fonts6']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);

		$inputs = AXOWL_inputs::$inputs2;

		$epop = '<div class="em-glass"></div>
				 <div class="email-popup"><div class="email-popup-grid">

				 	<h2 class="pop-title">VIL DU FYLLE UT SØKNADSSKJEMA SENERE?</h2>

				 	<div class="pop-input-container pop-phone-container">
					 	<label for="pop-phone" class="pop-label-phone">Telefon</label>
					 	<input type="text" class="em-i em-pop-phone" name="pop-phone" id="pop-phone">
				 	</div>

				 	<div class="pop-input-container pop-email-container">
					 	<label for="pop-email" class="pop-label-email">E-Post</label>
					 	<input type="text" class="em-i em-pop-email" name="pop-email" id="pop-email">
					</div>
				 	
				 	<button type="button" class="em-b pop-neste">Neste</button>

				 	<div class="pop-text">Klikker du på "neste" kommer vi til å sende deg en lenke til søknadsskjemaet på e-post og SMS.
				 	<br>Du samtykker da til at Norsk Finans AS kan behandle dine personopplysninger <a href="" target="_blank" class="pop-link">som beskrevet her.</a></div>

				 	</div><buttton type="button" class="em-pop-email-x"><img class="em-close" src="'.EM_AXOWL_PLUGIN_URL.'assets/img/close.png"></buttton>
				 </div>';

		$html = sprintf(
			'<div class="em-form-container"%s>%s%s<form class="emowl-form">',
			
			isset($atts['style']) ? ' style="'.$atts['style'].'"' : '',
			$p->popup(),
			$epop
		);


		// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
		// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
		// 	    );

		$html .= '<input type="hidden" name="fax">';

		$html .= '<div class="em-part-container">';

		$html .= $p->page_top(1);
		
		foreach($inputs as $key => $value) {
			if (is_array($value)) $value['help'] = true;
			// if new page
			if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
			// page content
			$html .= $p->element($key, $value, $data);
		}
			
		// ends last page and ends part container
		$html .= '</div></div>';

		$html .= '<div class="em-b-container">
			<button type="button" class="em-b em-b-next">Neste</button>
			<button type="button" class="em-b em-b-endre">Endre Lånebeløp</button>
			<button type="button" class="em-b em-b-send">Send Søknad</button>
			<div class="em-b-text">Du mottar et helt uforpliktende tilbud som er gyldig i 30 dager.</div>
			</div>';
		// $html .= $p->form_buttons(['hidden' => true, 'hide_prog' => true]);

		$html .= '</form></div>';

		// $html .= $p->popup().'</div>';

		$html .= '<input type="hidden" id="abtesting-sc" value="1">';

		// TODO set transient

		return $html;
	}





















	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_10($atts, $content = null) {

		$p = self::$parts;
		
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands10']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);

		$inputs = AXOWL_inputs::$inputs;

		$html = sprintf(
			'<form class="emowl-form"%s>',
			
			isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
		);


		// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
		// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
		// 	    );

		$html .= '<input type="hidden" name="fax">';

		$html .= '<div class="em-part-container">';

		$html .= $p->page_top(1);


		foreach($inputs as $key => $value) {
			// if new page
			if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
			// page content
			$html .= $p->element($key, $value, $data);
		}
			
		// ends last page and ends part container
		$html .= '</div></div>';

		// $html .= $p->form_buttons2();

		$html .= '<div class="progress-numbers"><div class="progress-nr"><span>1</span></div><div class="progress-nr"><span>2</span></div><div class="progress-nr"><span>3</span></div><div class="progress-nr"><span>4</span></div><div class="progress-line"></div></div>';

		$html .= '</form>';

		$html .= $p->popup();

		$html .= '<input type="hidden" id="abtesting-sc" value="2">';

		// TODO set transient

		return $html;
	}






















	// private function element($key, $value, $data) {

	// 	if (substr($key, 0,3) == 'div') {
			
	// 		$html = sprintf(

	// 			'<div class="%s%s">', 
			
	// 			isset($value['class']) ? $value['class'] : '',
				
	// 			isset($value['hidden']) ? ' em-hidden' : ''
	// 		);

	// 		return $html;
	// 	}

	// 	if (substr($key, 0,4) == '/div') {
	// 		$html = '</div>';

	// 		return $html;
	// 	}


	// 	$html = sprintf('
	// 		<div class="em-element-container em-element-%1$s%2$s">', 
			
	// 		$key, 
			
	// 		isset($value['hidden']) ? ' em-hidden' : ''
	// 	);


	// 	if (isset($value['notInput'])) 
	// 		$html .= $this->text_field([
	// 					'name' => $key,
	// 					'text' => $value['text']
	// 				]);

	// 	else {
	// 		if (isset($value['text'])) 
	// 			$html .= $this->text_input([
	// 						'name' => $key,
	// 						'text' => $data[$key],
	// 						'ht' => $data[$key.'_ht'],
	// 						'value' => $value
	// 					]);

	// 		if (isset($value['range'])) 
	// 			$html .= $this->range_input([
	// 						'name' => $key,
	// 						'value' => $value
	// 					 ]);

	// 		if (isset($value['checkbox'])) 
	// 			$html .= $this->checkbox_input([
	// 						'name' => $key,
	// 						'text' => $data[$key],
	// 						'ht' => $data[$key.'_ht'],
	// 						'value' => $value
	// 					]);

	// 		if (isset($value['check'])) 
	// 			$html .= $this->check_input([
	// 						'name' => $key,
	// 						'text' => $data[$key],
	// 						'ht' => $data[$key.'_ht'],
	// 						'value' => $value
	// 					]);

	// 		if (isset($value['list'])) 
	// 			$html .= $this->list_input([
	// 						'name' => $key,
	// 						'text' => $data[$key],
	// 						'ht' => $data[$key.'_ht'],
	// 						'list' => $value['list'],
	// 						'validation' => $value['validation'],
	// 						'key_as_value' => $value['key_as_value']
	// 					]);
	// 	}

	// 	$html .= '</div>';

	// 	return $html;
	// }

	/**
	 * [text_input description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	// private function text_input($o = []) {
	// 	if (!isset($o['name'])) return;
	// 	// wp_die('<xmp>'.print_r($o, true).'</xmp>');
		
	// 	return sprintf(
	// 		'<div class="em-ic em-ic-%1$s">
	// 			<label for="%1$s" class="em-label em-label-%1$s">
	// 				<h4 class="em-it em-it-%1$s">%2$s</h4>
	// 				%3$s
	// 			</label>
	// 			<input class="em-i em-i-%1$s" id="%1$s" name="%1$s"%4$s%5$s type="%6$s" value="%7$s"%8$s%9$s%10$s%11$s>
	// 			%12$s%13$s
	// 		</div>',

	// 		$o['name'], // 1
			
	// 		$o['text'], // 2
			
	// 		(isset($o['ht']) ? // 3
	// 			sprintf(
	// 				'<button type="button" class="em-ht-q" tabindex="0">
	// 					<span>?</span>
	// 				</button>
	// 				<div class="em-ht em-hidden em-ht-%1$s">
	// 					<div class="arrow-right"></div>
	// 					<div>%2$s</div>
	// 				</div>', 

	// 				$o['name'],

	// 				$o['ht']
	// 			) 
	// 			: ''
	// 		),
			
	// 		(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''), // 4
			
	// 		(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''), // 5
			
	// 		(isset($o['value']['type']) ? $o['value']['type'] : 'text'), // 6
			
	// 		(isset($o['value']['default']) ? $o['value']['default'] : ''), // 7
			
	// 		(isset($o['value']['validation']) ? ' data-val="'.$o['value']['validation'].'"' : ''), // 8
			
	// 		(isset($o['value']['format']) ? ' data-format="'.$o['value']['format'].'"' : ''), // 9
			
	// 		(isset($o['value']['digits']) ? ' data-digits="'.$o['value']['digits'].'"' : ''), // 10
			
	// 		(isset($o['value']['show']) ? ' data-show="'.$o['value']['show'].'"' : ''), // 11
			
	// 		'<img class="em-marker-valid em-marker-val em-hidden" src="'.esc_url(EM_AXOWL_PLUGIN_URL.'assets/img/greentick.png').'">', // 12
			
	// 		'<img class="em-marker-invalid em-marker-val em-hidden" src="'.esc_url(EM_AXOWL_PLUGIN_URL.'assets/img/redtick.png').'">' // 13
	// 	);
	// }

	/**
	 * [range_input description]
	 * @param  array  $o [description]
	 * @return [type]    [description]
	 */
	// private function range_input($o = []) {
	// 	if (!isset($o['name'])) return;

	// 	return sprintf(
	// 		'<input class="em-r em-r-%1$s" id="em-r-%1$s" type="range"%2$s%3$s%4$s%5$s>',

	// 		$o['name'],
			
	// 		(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
			
	// 		(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''),
			
	// 		(isset($o['value']['step']) ? ' step='.$o['value']['step'] : ''),
			
	// 		(isset($o['value']['default']) ? ' value='.$o['value']['default'] : '')
	// 	);

	// 	// return sprintf('<div class="em-rc em-rc-%1$s">
	// 	// 					<input class="em-r em-r-%1$s" id="em-r-%1$s" type="range"%2$s%3$s%4$s%5$s>
	// 	// 				</div>',

	// 	// 				$o['name'],
	// 	// 				(isset($o['value']['max']) ? ' max='.$o['value']['max'] : ''),
	// 	// 				(isset($o['value']['min']) ? ' min='.$o['value']['min'] : ''),
	// 	// 				(isset($o['value']['step']) ? ' step='.$o['value']['step'] : ''),
	// 	// 				(isset($o['value']['default']) ? ' value='.$o['value']['default'] : '')
	// 	// 		);
	// }

	// private function checkbox_input($o = []) {
	// 	if (!isset($o['name'])) return;
	// 	// wp_die('<xmp>'.print_r($o, true).'</xmp>');
	// 	return sprintf('<div class="em-cc em-cc-%1$s">
	// 							<h4 class="em-it em-it-%1$s">%2$s</h4>
	// 							%3$s
	// 						<input class="em-c em-c-%1$s" name="%1$s" type="hidden" value="%6$s"%7$s>
	// 						<div class="em-cc-selector">
	// 							<button type="button" class="em-i em-cc-yes%4$s">Ja</button>
	// 							<button type="button" class="em-i em-cc-no%5$s">Nei</button>
	// 						</div>
	// 					</div>',
	// 					$o['name'],
	// 					$o['text'],
	// 					($o['ht'] ? sprintf('<button type="button" class="em-ht-q"><span>?</span></button><div class="em-ht em-hidden em-ht-%2$s"><div class="arrow-right"></div><div>%1$s</div></div>', $o['ht'], $o['name']) : ''),
	// 					$o['value']['yes'] ? ' em-cc-green' : '',
	// 					$o['value']['no'] ? ' em-cc-green' : '',
	// 					$o['value']['yes'] ? '1' : '0',
	// 					$o['value']['show'] ? ' data-show="'.$o['value']['show'].'"' : ''
	// 					);
	// }

	// private function check_input($o = []) {

	// 	return sprintf('<div class="em-element em-element-check em-element-check-%1$s">
	// 						<input type="checkbox" name="%1$s" id="em-check-%1$s" class="em-i em-check em-check-%1$s"%3$s checked>
	// 						<label for="em-check-%1$s">%2$s</label>
	// 					</div>',
	// 					$o['name'],
	// 					$o['text'],
	// 					(isset($o['value']['validation']) ? ' data-val="'.$o['value']['validation'].'"' : '')

	// 				);

	// }

	// private function text_field($o = []) {
	// 	return sprintf('<div style="display: flex; justify-content: center;">
	// 		<div class="em-container-%2$s">%1$s</div><input type="text" class="em-if em-if-%2$s" disabled value=50></div>', 
	// 		$o['text'],
	// 		$o['name']);
	// }


	/**
	 * 
	 */
	// private function list_input($o = []) {
	// 	// wp_die('<xmp>'.print_r($o, true).'</xmp>');
	// 	$html = sprintf('<div class="em-lc em-lc-%1$s">', $o['name']);

	// 	$html .= sprintf('<label class="em-label" for="%1$s"><h4 class="em-it em-it-%1$s">%2$s</h4>%3$s</label>',
	// 						$o['name'],
	// 						$o['text'],
	// 						($o['ht'] ? sprintf('<button type="button" class="em-ht-q"><span>?</span></button><div class="em-ht em-hidden em-ht-%2$s"><div class="arrow-right"></div><div>%1$s</div></div>', $o['ht'], $o['name']) : '')
	// 					);

	// 	$html .= sprintf('<select class="em-i em-i-%1$s" id="%1$s" name="%1$s"%2$s>', 
	// 				$o['name'],
	// 				$o['validation'] ? ' data-val="'.$o['validation'].'"' : ''
	// 			);

	// 	$html .= '<option></option>';

	// 	if (isset($o['list']))
	// 		foreach($o['list'] as $key => $value)
	// 			$html .= sprintf('<option value="%1$s">%2$s</option>', 
	// 								$o['key_as_value'] ? $key : $value,
	// 								$value
	// 							);

	// 	$html .= '</select><img class="em-marker-valid em-marker-val em-hidden" src="'.esc_url(EM_AXOWL_PLUGIN_URL.'assets/img/greentick.png').'">
	// 					<img class="em-marker-invalid em-marker-val em-hidden" src="'.esc_url(EM_AXOWL_PLUGIN_URL.'assets/img/redtick.png').'"></div>';

	// 	return $html;
	// }


	public function icon($atts, $content = null) {
		add_action('wp_head', [$this, 'sands_icons']);
		
		$banks = [
					'bank-norwegian', 'bluestep', 'dnb', 'easybank', 'komplett-bank', 'nordax', 
					'resurs-bank', 'santander-consumer-bank', 'ya-bank', 'expressbank',
					'bnbank', 'remember', 'nystart-bank', 'monobank', 'thorn', 'optinbank'
				];

		$out = '';
		foreach ($banks as $b)
			$out .= sprintf('<div class="em-sprite sprite-%s"></div>', $b);

		return '<div class="em-sprites">
					<div class="em-sprite-container em-sprite-container-animation" onclick="this.classList.toggle(\'em-sprite-container-animation-paused\');">'.
					$out.'</div></div>';

		// return '<div class="em-sprites"><div class="em-sprite-container em-sprite-container-animation" onclick="this.classList.toggle(\'em-sprite-container-animation-paused\');">
		// 		<div class="em-sprite sprite-bank-norwegian"></div>
		// 		<div class="em-sprite sprite-bluestep"></div>
		// 		<div class="em-sprite sprite-dnb"></div>
		// 		<div class="em-sprite sprite-easybank"></div>
		// 		<div class="em-sprite sprite-komplett-bank"></div>
		// 		<div class="em-sprite sprite-nordax"></div>
		// 		<div class="em-sprite sprite-resurs-bank"></div>
		// 		<div class="em-sprite sprite-santander-consumer-bank"></div>
		// 		<div class="em-sprite sprite-ya-bank"></div>
		// 		<div class="em-sprite sprite-expressbank"></div>
		// 		<div class="em-sprite sprite-bnbank"></div>
		// 		<div class="em-sprite sprite-remember"></div>
		// 		<div class="em-sprite sprite-nystart-bank"></div>
		// 		<div class="em-sprite sprite-monobank"></div>
		// 		<div class="em-sprite sprite-thorn"></div>
		// 		<div class="em-sprite sprite-optinbank"></div>
		// 	</div></div>';
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

	public function sands_icons() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.0.6', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.2', '(max-width: 815px)');
	}


	public function sands() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.1.7', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.8', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo.js', array(), '1.0.13', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function sands2() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo2.css', array(), '1.0.7', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.3', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo2.js', array(), '1.0.6', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function sands3() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo3.css', array(), '1.0.0', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.00', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo3.js', ['jquery'], '1.0.6', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function sands4() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo4.css', array(), '1.0.0', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo4.js', array(), '1.0.6', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function sands5() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo5.css', array(), '1.0.0', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo5.js', ['jquery'], '1.0.6', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function sands6() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo6.css', array(), '1.0.8', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.1', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo6.js', ['jquery'], '1.0.16', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function sands10() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo10.css', array(), '1.0.0', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo10.js', array(), '1.0.6', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function fonts6($data) {

		return $data[] = ['Merriweather' => [400, 900], 'Montserrat' => [300, 700]];

	}

}