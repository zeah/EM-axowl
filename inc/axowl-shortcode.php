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

		add_shortcode('axowl-delete', [$this, 'delete']);

		// add_shortcode('axowlicon', [$this, 'icon']);
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
			// case '2': return $this->shortcode_2($atts, $content); break;
			// case '3': return $this->shortcode_3($atts, $content); break;
			// case '4': return $this->shortcode_4($atts, $content); break;
			// case '5': return $this->shortcode_5($atts, $content); break;
			// case '7': return $this->shortcode_7($atts, $content); break;
			// case '10': return $this->shortcode_10($atts, $content); break;
		}
	}
	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function shortcode_1($atts, $content = null) {

		// shortcode-parts.php
		$p = self::$parts;
		global $post;
		// TODO get transient

		// add_action('wp_footer', [$this, 'footer']);
		add_action('wp_head', [$this, 'sands']);
		add_filter('google_link', [$this, 'fonts']);

		$data = get_option('em_axowl');
		if (!is_array($data)) $data = [];
		$data = $this->sanitize($data);
		// wp_die('<xmp>'.print_r($data, true).'</xmp>');
		$inputs = AXOWL_inputs::$inputs;

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

				 	<div class="pop-text">'.(isset($data['popup_text']) ? $data['popup_text'] : '').'</div>

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

		$html .= '<div class="em-loan-example">Nominell rente fra 6,39% til 21,95%. Effektiv rente fra 6,81% til 24,4%. Eff. rente 13,2%, 150.000 o/10 år, kostnad: 112.573, Totalt: 262573.</div></form></div>';

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
	// public function shortcode_7($atts, $content = null) {

	// 	$p = self::$parts;
		
	// 	// TODO get transient

	// 	// add_action('wp_footer', [$this, 'footer']);
	// 	add_action('wp_head', [$this, 'sands7']);

	// 	$data = get_option('em_axowl');
	// 	if (!is_array($data)) $data = [];
	// 	$data = $this->sanitize($data);

	// 	$inputs = AXOWL_inputs::$inputs;

	// 	$html = sprintf(
	// 		'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
	// 		isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
	// 	);


	// 	// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
	// 	// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
	// 	// 	    );

	// 	$html .= '<input type="hidden" name="fax">';

	// 	$html .= '<div class="em-part-container">';

	// 	$html .= $p->page_top(1);

	// 	foreach($inputs as $key => $value) {
	// 		// if new page
	// 		if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
	// 		// page content
	// 		$html .= $p->element($key, $value, $data);
	// 	}
			
	// 	// ends last page and ends part container
	// 	$html .= '</div></div>';

	// 	$html .= $p->form_buttons(['hidden' => true]);

	// 	$html .= '</form>';

	// 	$html .= $p->popup().'</div>';

	// 	$html .= '<input type="hidden" id="abtesting-sc" value="1">';

	// 	// TODO set transient

	// 	return $html;
	// }




	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	// public function shortcode_2($atts, $content = null) {

	// 	$p = self::$parts;
		
	// 	// TODO get transient

	// 	// add_action('wp_footer', [$this, 'footer']);
	// 	add_action('wp_head', [$this, 'sands2']);

	// 	$data = get_option('em_axowl');
	// 	if (!is_array($data)) $data = [];
	// 	$data = $this->sanitize($data);

	// 	$inputs = AXOWL_inputs::$inputs;

	// 	$html = sprintf(
	// 		'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
	// 		isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
	// 	);


	// 	// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
	// 	// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
	// 	// 	    );

	// 	$html .= '<input type="hidden" name="fax">';

	// 	$html .= '<div class="em-part-container">';

	// 	$html .= $p->page_top(1);

	// 	foreach($inputs as $key => $value) {
	// 		// if new page
	// 		if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
	// 		// page content
	// 		$html .= $p->element($key, $value, $data, 5);
	// 	}
			
	// 	// ends last page and ends part container
	// 	$html .= '</div></div>';

	// 	$html .= $p->form_buttons(['hidden' => true]);

	// 	$html .= '</form>';

	// 	$html .= $p->popup().'</div>';

	// 	$html .= '<input type="hidden" id="abtesting-sc" value="1">';

	// 	// TODO set transient

	// 	return $html;
	// }
	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	// public function shortcode_3($atts, $content = null) {

	// 	$p = self::$parts;
		
	// 	// TODO get transient

	// 	// add_action('wp_footer', [$this, 'footer']);
	// 	add_action('wp_head', [$this, 'sands3']);

	// 	$data = get_option('em_axowl');
	// 	if (!is_array($data)) $data = [];
	// 	$data = $this->sanitize($data);

	// 	$inputs = AXOWL_inputs::$inputs;

	// 	$html = sprintf(
	// 		'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
	// 		isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
	// 	);


	// 	// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
	// 	// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
	// 	// 	    );

	// 	$html .= '<input type="hidden" name="fax">';

	// 	$html .= '<div class="em-part-container">';

	// 	$html .= $p->page_top(1);

	// 	foreach($inputs as $key => $value) {
	// 		// if new page
	// 		if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
	// 		// page content
	// 		$html .= $p->element($key, $value, $data, 5);
	// 	}
			
	// 	// ends last page and ends part container
	// 	$html .= '</div></div>';

	// 	$html .= $p->form_buttons(['hidden' => true]);

	// 	$html .= '</form>';

	// 	$html .= $p->popup().'</div>';

	// 	$html .= '<input type="hidden" id="abtesting-sc" value="1">';

	// 	// TODO set transient

	// 	return $html;
	// }




	/**
	 * [shortcode_4 description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	// public function shortcode_4($atts, $content = null) {

	// 	$p = self::$parts;
		
	// 	// TODO get transient

	// 	// add_action('wp_footer', [$this, 'footer']);
	// 	add_action('wp_head', [$this, 'sands4']);

	// 	$data = get_option('em_axowl');
	// 	if (!is_array($data)) $data = [];
	// 	$data = $this->sanitize($data);

	// 	$inputs = AXOWL_inputs::$inputs;

	// 	$html = sprintf(
	// 		'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
	// 		isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
	// 	);


	// 	// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
	// 	// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
	// 	// 	    );

	// 	$html .= '<input type="hidden" name="fax">';

	// 	$html .= '<div class="em-part-container">';

	// 	$html .= $p->page_top(1);

	// 	foreach($inputs as $key => $value) {
	// 		// if new page
	// 		if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
	// 		// page content
	// 		$html .= $p->element($key, $value, $data);
	// 	}
			
	// 	// ends last page and ends part container
	// 	$html .= '</div></div>';

	// 	$html .= $p->form_buttons(['hidden' => true]);

	// 	$html .= '</form>';

	// 	$html .= $p->popup().'</div>';

	// 	$html .= '<input type="hidden" id="abtesting-sc" value="1">';

	// 	// TODO set transient

	// 	return $html;
	// }


	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	// public function shortcode_5($atts, $content = null) {

	// 	$p = self::$parts;
		
	// 	// TODO get transient

	// 	// add_action('wp_footer', [$this, 'footer']);
	// 	add_action('wp_head', [$this, 'sands5']);

	// 	$data = get_option('em_axowl');
	// 	if (!is_array($data)) $data = [];
	// 	$data = $this->sanitize($data);

	// 	$inputs = AXOWL_inputs::$inputs;

	// 	$html = sprintf(
	// 		'<div class="em-form-container"><div class="em-glass"></div><form class="emowl-form"%s>',
			
	// 		isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
	// 	);


	// 	// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
	// 	// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
	// 	// 	    );

	// 	$html .= '<input type="hidden" name="fax">';

	// 	$html .= '<div class="em-part-container">';

	// 	$html .= $p->page_top(1);

	// 	foreach($inputs as $key => $value) {
	// 		// if new page
	// 		if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
	// 		// page content
	// 		$html .= $p->element($key, $value, $data, 5);
	// 	}
			
	// 	// ends last page and ends part container
	// 	$html .= '</div></div>';

	// 	$html .= $p->form_buttons(['hidden' => true, 'hide_prog' => true]);

	// 	$html .= '</form>';

	// 	$html .= $p->popup().'</div>';

	// 	$html .= '<input type="hidden" id="abtesting-sc" value="1">';

	// 	// TODO set transient

	// 	return $html;
	// }









	/**
	 * [shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	// public function shortcode_10($atts, $content = null) {

	// 	$p = self::$parts;
		
	// 	// TODO get transient

	// 	// add_action('wp_footer', [$this, 'footer']);
	// 	add_action('wp_head', [$this, 'sands10']);

	// 	$data = get_option('em_axowl');
	// 	if (!is_array($data)) $data = [];
	// 	$data = $this->sanitize($data);

	// 	$inputs = AXOWL_inputs::$inputs;

	// 	$html = sprintf(
	// 		'<form class="emowl-form"%s>',
			
	// 		isset($atts['style']) ? ' style="'.$atts['style'].'"' : ''
	// 	);


	// 	// $html = sprintf('<form class="emowl-form"%s><h1 class="form-title">Søk Lån hos Axo Finans</h1>',
	// 	// 			(isset($atts['style']) ? ' style="'.$atts['style'].'"' : '')
	// 	// 	    );

	// 	$html .= '<input type="hidden" name="fax">';

	// 	$html .= '<div class="em-part-container">';

	// 	$html .= $p->page_top(1);


	// 	foreach($inputs as $key => $value) {
	// 		// if new page
	// 		if (isset($value['page'])) $html .= '</div>'.$p->page_top($value['page']);
			
	// 		// page content
	// 		$html .= $p->element($key, $value, $data);
	// 	}
			
	// 	// ends last page and ends part container
	// 	$html .= '</div></div>';

	// 	// $html .= $p->form_buttons2();

	// 	$html .= '<div class="progress-numbers"><div class="progress-nr"><span>1</span></div><div class="progress-nr"><span>2</span></div><div class="progress-nr"><span>3</span></div><div class="progress-nr"><span>4</span></div><div class="progress-line"></div></div>';

	// 	$html .= '</form>';

	// 	$html .= $p->popup();

	// 	$html .= '<input type="hidden" id="abtesting-sc" value="2">';

	// 	// TODO set transient

	// 	return $html;
	// }





	public function delete($atts, $content = null) {
		add_action('wp_head', [$this, 'sands_delete']);

		return '<div class="axodel-container">
				<div class="axodel-form">
					<h2>Slett meg</h2>
					<p>Skriv inn epost eller telefonnummer og din personelig informasjon vil bli slettet fra Norsk Finans.</p>
					<input class="axodel-input" name="axodel">
					<button class="axodel-send" type="button">Send inn</button>
				</div>
				<div class="axodel-message">
				    <h2>Informasjonen er slettet</h2>
					Din personelig informasjon (<span class="axodel-info"></span> m.m.) har nå bli slettet og du vil ikke få flere meldinger fra Norsk Finans.
					<br>Dette er ikke en bekreftelse på at <span class="axodel-info"></span> fantes, men at hvis den eksisterte i vår database så er den nå slettet.
				</div>
			</div>
			<script>
			(function($) {
				var click = function() {

					var val = $(".axodel-input").val();

					if (!val) return;

					if (!/^\d{8}$/.test(val) && !/^.+@.+\..{2,}/.test(val)) {
						alert("Ugyldig input, må være et telefonnummer eller epost addresse.");
						return;
					}
					$.post(emurl.ajax_url, {
						action: "del",
						data: $(".axodel-input").val()
						}, function(data) {

							console.log(data);
							if (data != "success") {
								alert("Feil i maskineriet. Prøv igjen seinere eller kontakt oss på epost.");
								return;
							}

							$(".axodel-send").off("click", click);

							$(".axodel-info").html($(".axodel-input").val());

							$(".axodel-form").fadeOut(200, function() {
								$(".axodel-message").fadeIn(200);
							});

						}
					);

				}

				$(".axodel-send").on("click", click);

				$(".axodel-input").keypress(function(e) {

					if (e.keyCode == 13) click();

					//console.log(e.keyCode);
				});

				var css = "<style>.axodel-container { margin: 4rem 0; } .axodel-input { font-size: 1.6rem; padding: .5rem; min-width: 30rem; border: solid 2px #333; } .axodel-send { display: block; margin: 2rem 0; border: none; outline: none; background-color: #fc6; font-size: 1.6rem; padding: .6rem; border: solid 2px #333; } .axodel-message { display: none; }  @media only screen and (max-width: 949px) { }</style>";
				// var css = "<style>@media only screen and (min-width: 950px) { .axodel-container { margin: 4rem 0; } .axodel-input { font-size: 1.6rem; padding: .5rem; min-width: 30rem; border: solid 2px #333; } .axodel-send { display: block; margin: 2rem 0; border: none; outline: none; background-color: #fc6; font-size: 1.6rem; padding: .6rem; border: solid 2px #333; } .axodel-message { display: none; } } @media only screen and (max-width: 949px) { }</style>";

				$("head").append(css);

			})(jQuery)
			</script>

			';
	
	}


	public function sands_delete() {
       	// wp_enqueue_style('axodel-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/axodel.css', array(), '0.0.1', '(min-width: 901px)');
        // wp_enqueue_style('axodel-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/axodel-mobile.css', array(), '0.0.1', '(max-width: 900px)');
        
        wp_enqueue_script('axodel', EM_AXOWL_PLUGIN_URL.'assets/js/pub/axodel.js', ['jquery'], '0.0.1', true);
		wp_localize_script('axodel', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
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
        wp_enqueue_style('jqslid', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css', false);
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '2.0.19', '(min-width: 901px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '2.0.19', '(max-width: 900px)');
        
        wp_enqueue_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js', false, false);
        wp_enqueue_script('jquery-ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js', false, true);
        wp_enqueue_script('jquery-touch', '//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', false, true);

        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo.js', [], '2.0.31', true);
		
		wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	}

	public function sands_icons() {
        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.0.6', '(min-width: 816px)');
        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.2', '(max-width: 815px)');
	}


	// public function sands7() {
 //        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.1.7', '(min-width: 816px)');
 //        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.8', '(max-width: 815px)');
        
 //        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo.js', array(), '1.0.13', true);
	// 	wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	// }

	// public function sands2() {
 //        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo2.css', array(), '1.0.7', '(min-width: 816px)');
 //        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.3', '(max-width: 815px)');
        
 //        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo2.js', array(), '1.0.6', true);
	// 	wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	// }

	// public function sands3() {
 //        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo3.css', array(), '1.0.0', '(min-width: 816px)');
 //        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.00', '(max-width: 815px)');
        
 //        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo3.js', ['jquery'], '1.0.6', true);
	// 	wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	// }

	// public function sands4() {
 //        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo4.css', array(), '1.0.0', '(min-width: 816px)');
 //        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
 //        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo4.js', array(), '1.0.6', true);
	// 	wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	// }

	// public function sands5() {
 //        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo5.css', array(), '1.0.0', '(min-width: 816px)');
 //        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
 //        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo5.js', ['jquery'], '1.0.6', true);
	// 	wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	// }

	// public function sands() {
 //        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo.css', array(), '1.0.0', '(min-width: 816px)');
 //        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
 //        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo.js', ['jquery'], '1.0.0', true);
	// 	wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	// }

	// public function sands10() {
 //        wp_enqueue_style('emaxowl-style', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo10.css', array(), '1.0.0', '(min-width: 816px)');
 //        wp_enqueue_style('emaxowl-mobile', EM_AXOWL_PLUGIN_URL.'assets/css/pub/emaxo-mobile.css', array(), '1.0.0', '(max-width: 815px)');
        
 //        wp_enqueue_script('emaxowl', EM_AXOWL_PLUGIN_URL.'assets/js/pub/emaxo10.js', array(), '1.0.6', true);
	// 	wp_localize_script( 'emaxowl', 'emurl', ['ajax_url' => admin_url( 'admin-ajax.php')]);
	// }

	public function fonts($data) {
		return $data[] = ['Merriweather' => [400, 900], 'Montserrat' => [300, 400, 700]];
	}

}