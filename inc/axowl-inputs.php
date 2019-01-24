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
			'div0' => ['class' => 'em-part-1-grid'],
			'monthly_cost' => ['text' => 'Månedskostnad fra', 'notInput' => true],
			'loan_amount' => ['text' => true, 'range' => true, 'validation' => 'currency', 'format' => 'currency', 'max' => 500000, 'min' => 10000, 'default' => 150000, 'step' => 10000],
			'tenure' => ['text' => true, 'range' => true, 'validation' => 'ar', 'format' => 'postfix: år', 'max' => 15, 'min' => 1, 'default' => 5],
			'collect_debt' => ['checkbox' => true, 'no' => true],
			'mobile_number' => ['text' => true, 'type' => 'text', 'validation' => 'phone', 'digits' => 8],
			'email' => ['text' => true, 'validation' => 'email'],
			'axo_accept' => ['check' => true, 'validation' => 'check'],
			'contact_accept' => ['check' => true],
			'/div0' => '',
			
			'social_number' => ['text' => true, 'page' => '2', 'validation' => 'socialnumber', 'digits' => 11],
			'employment_type' => ['list' => Axowl_list::employment_type, 'validation' => 'list'],

			'employment_since' => ['hidden' => true, 'list' => Axowl_list::years, 'validation' => 'list'],
			'employer' => ['text' => true, 'hidden' => true, 'validation' => 'notEmpty'],

			'education' => ['list' => Axowl_list::education, 'validation' => 'list'],

			'education_loan' => ['hidden' => true, 'text' => true, 'validation' => 'currency', 'format' => 'currency'],

			'norwegian' => ['checkbox' => true, 'yes' => true, 'show' => 'no: em-norwegian'],
			'div' => ['class' => 'em-norwegian', 'hidden' => true], 
			'years_in_norway' => ['key_as_value' => true, 'list' => Axowl_list::years_in_norway, 'validation' => 'list'],

			'country_of_origin' => ['key_as_value' => true, 'list' => Axowl_list::country_of_origin, 'validation' => 'list'],
			'/div' => '',
			'income' => ['text' => true, 'validation' => 'currency', 'format' => 'currency'],

			'co_applicant' => ['checkbox' => true, 'page' => '3', 'no' => true, 'show' => 'em-co-applicant'],

			'div2' => ['class' => 'em-co-applicant', 'hidden' => true], 
			'co_applicant_name' => ['text' => true, 'validation' => 'notEmpty'],
			'co_applicant_social_number' => ['text' => true, 'validation' => 'numbersOnly', 'digits' => 11],
			'co_applicant_mobile_number' => ['text' => true, 'validation' => 'numbersOnly', 'digits' => 8],
			'co_applicant_email' => ['text' => true, 'validation' => 'email'],

			'co_applicant_employment_type' => ['list' => Axowl_list::employment_type, 'validation' => 'list'],
			'co_applicant_employment_since' => ['list' => Axowl_list::years, 'validation' => 'list', 'hidden' => true],
			'co_applicant_employer' => ['text' => true, 'hidden' => true, 'validation' => 'notEmpty'],

			'co_applicant_education' => ['list' => Axowl_list::education, 'validation' => 'list'],

			'co_applicant_norwegian' => ['checkbox' => true, 'yes' => true, 'show' => 'no:em-co-applicant-norwegian'],
			'div3' => ['class' => 'em-co-applicant-norwegian', 'hidden' => true],
			'co_applicant_years_in_norway' => ['list' => Axowl_list::years_in_norway, 'validation' => 'list'],
			'co_applicant_country_of_origin' => ['key_as_value' => true, 'list' => Axowl_list::country_of_origin, 'validation' => 'list'],
			'/div3' => '',
			'co_applicant_income' => ['text' => true, 'validation' => 'currency', 'format' => 'currency'],
			'/div2' => '',

			'civilstatus' => ['page' => '4', 'list' => Axowl_list::civilstatus, 'validation' => 'list'],
			'spouse_income' => ['hidden' => true, 'text' => true, 'validation' => 'currency', 'format' => 'currency'],

			'living_conditions' => ['list' => Axowl_list::living_conditions, 'validation' => 'list'],
			'rent_income' => ['hidden' => true, 'text' => true, 'validation' => 'currency', 'format' => 'currency'],
			'mortgage' => ['hidden' => true, 'text' => true, 'validation' => 'currency', 'format' => 'currency'],
			'rent' => ['hidden' => true, 'text' => true, 'validation' => 'currency', 'format' => 'currency'],

			'address_since' => ['list' => Axowl_list::years, 'validation' => 'list'],
			
			'car_boat_mc_loan' => ['text' => true, 'validation' => 'currency', 'format' => 'currency'],

			'number_of_children' => ['key_as_value' => true, 'list' => Axowl_list::number_of_children, 'validation' => 'list'],
			'allimony_per_month' => ['text' => true, 'hidden' => true, 'validation' => 'currency', 'format' => 'currency'],






			'total_unsecured_debt' => ['text' => true, 'page' => '5', 'validation' => 'currency', 'format' => 'currency', 'show' => 'total_unsecured_debt_balance'],
			'total_unsecured_debt_balance' => ['text' => true, 'hidden' => true, 'validation' => 'currency', 'format' => 'currency'],
			'account_number' => ['text' => true, 'validation' => 'bankAccount', 'digits' => '11']

		];


}