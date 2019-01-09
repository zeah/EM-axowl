<?php 
defined('ABSPATH') or die('Blank Space');


require_once 'axowl-list.php';

final class Axowl_inputs {

	public static function years() {
		$years = [];
		for ($i = 2018; $i > 1959; $i--)
			array_push($years, $i);

		return $years;
	}

	public static $inputs = [
			'monthly_cost' => ['text' => 'Månedskostnad fra', 'notInput' => true],
			'loan_amount' => ['text' => true, 'range' => true, 'validation' => 'numbersOnly', 'format' => 'currency', 'max' => 500000, 'min' => 10000, 'default' => 150000, 'step' => 10000],
			'tenure' => ['text' => true, 'range' => true, 'validation' => 'numbersOnly', 'format' => 'postfix: år', 'max' => 15, 'min' => 1, 'default' => 5],
			'collect_debt' => ['checkbox' => true, 'no' => true],
			'mobile_number' => ['text' => true, 'type' => 'text', 'validation' => 'phone'],
			'email' => ['text' => true],

			
			'social_number' => ['text' => true, 'page' => '2'],
			'employment_type' => ['list' => Axowl_list::employment_type ],

			'employment_since' => ['hidden' => true, 'list' => Axowl_list::years ],
			'employer' => ['text' => true, 'hidden' => true],

			'education' => ['list' => Axowl_list::education ],

			'education_loan' => ['hidden' => true, 'text' => true],

			'norwegian' => ['checkbox' => true, 'yes' => true, 'show' => 'no: em-norwegian'],
			'div' => ['class' => 'em-norwegian', 'hidden' => true], 
			'years_in_norway' => [
								'key_as_value' => true,
								'list' => Axowl_list::years_in_norway 
								],

			'country_of_origin' => ['key_as_value' => true, 'list' => Axowl_list::country_of_origin ],
			'/div' => '',
			'income' => ['text' => true],


			'civilstatus' => ['page' => '3', 'list' => Axowl_list::civilstatus ],
			'spouse_income' => ['hidden' => true, 'text' => true],

			'living_conditions' => ['list' => Axowl_list::living_conditions ],
			'rent_income' => ['hidden' => true, 'text' => true],
			'mortgage' => ['hidden' => true, 'text' => true],
			'rent' => ['hidden' => true, 'text' => true],

			'address_since' => ['list' => Axowl_list::years ],
			
			'car_boat_mc_loan' => ['text' => true],

			'number_of_children' => ['key_as_value' => true, 'list' => Axowl_list::number_of_children ],
			'allimony_per_month' => ['text' => true, 'hidden' => true],




			'co_applicant' => ['checkbox' => true, 'page' => '4', 'no' => true, 'show' => 'em-co-applicant'],

			'div2' => ['class' => 'em-co-applicant', 'hidden' => true], 
			'co_applicant_name' => ['text' => true],
			'co_applicant_social_number' => ['text' => true],
			'co_applicant_mobile_number' => ['text' => true],
			'co_applicant_email' => ['text' => true],

			'co_applicant_employment_type' => ['list' => Axowl_list::employment_type ],
			'co_applicant_employment_since' => ['list' => Axowl_list::years ],
			'co_applicant_employer' => ['text' => true],

			'co_applicant_education' => ['list' => Axowl_list::education ],

			'co_applicant_norwegian' => ['checkbox' => true, 'yes' => true, 'show' => 'no:em-co-applicant-norwegian'],
			'div3' => ['class' => 'em-co-applicant-norwegian', 'hidden' => true],
			'co_applicant_years_in_norway' => ['list' => Axowl_list::years_in_norway ],
			'co_applicant_country_of_origin' => ['key_as_value' => true, 'list' => Axowl_list::country_of_origin ],
			'/div3' => '',
			'co_applicant_income' => ['text' => true],
			'/div2' => '',


			'unsecured_debt_balance' => ['text' => true, 'page' => '5'],

			'account_number' => ['text' => true]

			];


}