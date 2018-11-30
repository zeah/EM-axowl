<?php 

defined('ABSPATH') or die('Blank Space');

final class Axowl_settings {
	/* singleton */
	private static $instance = null;

	private $opt;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->opt = get_option('em_axowl');
		if (!is_array($this->opt)) $this->opt = [];


		$this->hooks();
	}

	private function hooks() {
		add_action('admin_menu', [$this, 'add_menu']);
		add_action('admin_init', [$this, 'register_settings']);
	}

	public function add_menu() {
		add_submenu_page('options-general.php', 'EM Axo White Label', 'Axo WL', 'manage_options', 'em-axowl-page', [$this, 'page_callback']);
	}

	public function register_settings() {
		register_setting('em-axowl-settings', 'em_axowl', ['sanitize_callback' => array($this, 'sanitize')]);

		add_settings_section('em-axowl-data', 'Form data', [$this, 'data_section'], 'em-axowl-page');
		add_settings_field('em-axowl-url', 'Form Url', [$this, 'input'], 'em-axowl-page', 'em-axowl-data', ['url', 'Url of which to send the form to.']);
		add_settings_field('em-axowl-name', 'Partner Name', [$this, 'input'], 'em-axowl-page', 'em-axowl-data', ['name', 'Name of the partner, as agreed with Axo.']);


		add_settings_section('em-axowl-input', 'Text for form inputs', [$this, 'input_section'], 'em-axowl-page');


		$inputs = ['loan_amount', 'tenure', 'co_applicant', 'collect_debt'];

		$input = [
			'loan_amount' => 'Loan amount.',
			'tenure' => 'The repayment period in years.',
			'co_applicant' => 'Whether a co-applicant is provided.',
			'collect_debt' => 'Whether existing loans should be refinanced.',
			'account_number' => 'The bank account the loan will be paid out to, <br>without e.g. spaces and dots. CDV control is recommended',
			'social_number' => 'Valid Norwegian Soscial Security Number ("fødselsnummer"), 11 digits.',
			'mobile_number' => 'Norwegian mobile phone number, without spaces or a leading +47.',
			'email' => 'The customer\'s email address.',
			'employment_type' => 'Select a value from list.',
			'employment_since' => 'The year the customer started working for the current employer, e.g. 2012.',
			'employer' => 'Name of the customer\'s employer.',
			'education' => 'Select a value from list.',
			'norwegian' => 'Whether the customer is a Norwegian citizen.',
			'years_in_norway' => 'Select a value from list.',
			'country_of_origin' => 'Select a value from list.',
			'income' => 'Yearly income before taxes ("bruttolønn")',
			'civilstatus' => 'Select a value from list.',
			'living_conditions' => 'Select a value from list.',
			'address_since' => 'The year the customer\'s household started living at the current address, e.g. 2011. Any year after 1960.',
			'number_of_children' => 'The number of of children under 18 in the household.',
			'allimony_per_month' => 'Monthly child support ("barnebidrag")',
			'spouse_income' => 'The customer\'s spouse/partner\'s income before taxes ("bruttolønn")',
			'rent' => 'Monthly rent paid by the customer\'s household, not including interest/down payment of mortgages.',
			'rent_income' => 'Monthly rent received by the customer\'s household.',
			'mortgage' => 'Sum of mortgages ("bolliglån") and shared debt ("fellesgjeld") for the customer\'s household.',
			'education_loan' => 'Sum of student loans ("Studielån") in the household.',
			'car_boat_mc_loan' => 'Secured loans for e.g. cars, boats and MCs. Should not include consumer loans used to by e.g. a car.',
			'total_unsecured_debt' => 'Sum of other, unsecured loans in the household.',
			'co_applicant_social_number' => 'Valid Norwegian Social Security Number ("fødselesnummer"), 11 digits.',
			'co_applicant_name' => 'The co applicant\'s full name.',
			'co_applicant_mobile_number' => 'Norwegian mobile phone number, without spaces or a leading +47.',
			'co_applicant_email' => 'The co applicant\'s email address.',
			'co_applicant_employment_type' => 'Select a value from list.',
			'co_applicant_employment_since' => 'The year the customer started working for the current employer, e.g. 2012.',
			'co_applicant_employer' => 'Name of the co applicant\'s employer.',
			'co_applicant_education' => 'Select a value from list.',
			'co_applicant_norwegian' => 'Whether the co applicant holds a Norwegian citizenship.',
			'co_applicant_years_in_norway' => 'The number of years the co applicant has lived in Norway.',
			'co_applicant_country_of_origin' => 'Select a value from list.',
			'co_applicant_income' => 'Yearly income before taxes ("bruttolønn")',
			'unsecured_debt_lender' => 'List of (unsecured) lenders.',
			'unsecured_debt_balance' => 'The sum of all loans the customer wants to refinance.'
		];

		foreach ($input as $key => $value)
			add_settings_field(
				'em-axowl-'.$key, 
				ucwords(str_replace('_', ' ', $key)), 
				[$this, 'input'], 
				'em-axowl-page', 
				'em-axowl-input', 
				[$key, $value, true]
			);

	}


	/**
	 * echoing page
	 */
	public function page_callback() {
		echo '<h1>Effektiv Markedsføring Axo White Label</h1>';
		echo '<form action="options.php" method="POST">';
		settings_fields('em-axowl-settings');
		do_settings_sections('em-axowl-page');
		submit_button('save');
		echo '</form>';
	}

	public function data_section() {

	}

	public function input_section() {
		echo '<span style="font-size: 16px">Customize helper <b>text</b> for each input field.</span>';
	}


	/**
	 * echoing input field
	 * @param  String $name name of data
	 */
	public function input($name) {

		$html = '';

		if (isset($name[1])) $html .= '<h4 style="margin: 0; margin-top: 4px;">'.$name[1].'</h4>';

		$html .= '<div><div style="width: 100px; display: inline-block;">Title Text:</div><input type="text" style="width: 600px; max-width: 90%;" name="em_axowl['.$name[0].']" value="'.$this->get($name[0]).'"></div>';
		if (isset($name[2])) $html .= '<div><div style="width: 100px; display: inline-block;">Helper Text:</div><input type="text" style="width: 600px; max-width: 90%;" name="em_axowl['.$name[0].'_ht]" value="'.$this->get($name[0].'_ht').'"></div>';
	
		echo $html;
	}


	/**
	 * getting and escaping data for input
	 * @param  String $name what data to get
	 * @return String       the data for the input
	 */
	private function get($name) {
		$d = $this->opt;
		if (isset($d[$name])) return esc_attr($d[$name]);
		return;
	}

	/**
	 * helper function which sanitizes arrays and strings
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
}