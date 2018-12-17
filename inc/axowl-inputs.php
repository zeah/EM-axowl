<?php 
defined('ABSPATH') or die('Blank Space');




final class AXOWL_inputs {

	public static function years() {
		$years = [];
		for ($i = 2018; $i > 1959; $i--)
			array_push($years, $i);

		return $years;
	}

	public static $inputs = [
			'loan_amount' => ['text' => true, 'range' => true, 'max' => 500000, 'min' => 10000, 'default' => 150000, 'step' => 10000],
			'tenure' => ['text' => true, 'range' => true, 'max' => 15, 'min' => 1, 'default' => 5],
			'monthly_cost' => ['text' => 'Monthly Cost', 'notInput' => true],
			'mobile_number' => ['text' => true, 'type' => 'text'],
			'email' => ['text' => true],

			'collect_debt' => ['checkbox' => true, 'no' => true],
			
			'social_number' => ['text' => true, 'type' => 'number', 'page' => '2'],
			'employment_type' => ['list' => [
												'Fast ansatt (privat)',
												'Fast ansatt (offentlig)',
												'Midlertidig ansatt/vikar',
												'Selvst. næringsdrivende',
												'Pensjonist',
												'Student',
												'Uføretrygdet',
												'Arbeidsavklaring/attføring',
												'Arbeidsledig',
												'Langtidssykemeldt'
											]
								 ],

			'employment_since' => ['hidden' => true, 'list' => [2018, 2017, 2016, 2015, 2014, 2013, 2012, 2011, 2010, 2009, 2008, 2007, 2006, 2005, 2004, 2003, 2002, 2001, 2000, 1999, 1998, 1997, 1996, 1995, 1994, 1993, 1992, 1991, 1990, 1989, 1988, 1987, 1986, 1985, 1984, 1983, 1982, 1981, 1980, 1979, 1978, 1977, 1976, 1975, 1974, 1973, 1972, 1971, 1970, 1969, 1968, 1967, 1966, 1965, 1964, 1963, 1962, 1961, 1960]],
			'employer' => ['text' => true, 'hidden' => true],

			'education' => ['list' => [
										'Grunnskole',
										'Videregående',
										'Høysk./universitet 1-3 år',
										'Høysk./universitet 4+år'
									]
						],

			'norwegian' => ['checkbox' => true, 'yes' => true],
			'years_in_norway' => ['hidden' => true, 'list' => ['Alltid', '1 år', '2 år', '3 år', '4 år', '5 år', '6 år', '7 år', '8 år', '9 år', '10 år', '11 år', '12 år', '13 år', '14 år', '15 år', '16 år', '17 år', '18 år', '19 år', '20 år']],
			'country_of_origin' => ['hidden' => true, 'list' => []],


			'co_applicant' => ['checkbox' => true, 'page' => '3', 'no' => true],
			'co_applicant_email' => ['text' => true, 'type' => 'email']

		];


}