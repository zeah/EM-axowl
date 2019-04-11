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
			case '7': return $this->shortcode_7($atts, $content); break;
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
		global $post;
		// TODO get transient



		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands']);
		add_filter('google_link', [$this, 'fonts']);

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
			'<div class="em-form-container" style="opacity: 0;%s">%s%s<form class="emowl-form">',
			
			isset($atts['style']) ? $atts['style'] : '',
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
			if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page'], (isset($value['page_class']) ? $value['page_class'] : null));
			
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
		$html .= '<input type="hidden" id="abtesting-post" value="'.$post->post_name.'">';

		if (!isset($data['abtesting']))
			$html .= sprintf('<input type="hidden" id="abtesting-name" value="%s">', $post->post_name);
		// wp_die('<xmp>'.print_r($data, true).'</xmp>');
		
		// TODO set transient

		return $html;
	}



	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_7($atts, $content = null) {

		$p = self::$parts;
		
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands7']);

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
        wp_enqueue_style('jqslid', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', false);
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '2.0.7', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '2.0.7', '(max-width: 815px)');
        

        // wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo.js', ['jquery', 'jquery-ui-slider'], '2.0.5', true);

        wp_enqueue_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js', false, false);
        wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', false, true);
        wp_enqueue_script('jquery-touch', '//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', false, true);

        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo.js', '2.0.14', true);
        // wp_enqueue_script('jquery-ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', false, true);
        // wp_enqueue_script('jquery-touch', '//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', false, true);
		
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function sands_icons() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.0.6', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.2', '(max-width: 815px)');
	}


	public function sands7() {
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

	// public function sands() {
 //        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.0.0', '(min-width: 816px)');
 //        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
 //        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo.js', ['jquery'], '1.0.0', true);
	// 	wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	// }

	public function sands10() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo10.css', array(), '1.0.0', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo10.js', array(), '1.0.6', true);
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function fonts($data) {

		return $data[] = ['Merriweather' => [400, 900], 'Montserrat' => [300, 700]];

	}

}