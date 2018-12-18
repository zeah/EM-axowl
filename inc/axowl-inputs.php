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

			'education_loan' => ['hidden' => true, 'text' => true],

			'norwegian' => ['checkbox' => true, 'yes' => true],
			'years_in_norway' => [
								'hidden' => true,
								'key_as_value' => true,
								'list' => [
											'Alltid' => 'Alltid', 
											'1' => '1 år', 
											'2' => '2 år', 
											'3' => '3 år', 
											'4' => '4 år', 
											'5' => '5 år', 
											'6' => '6 år', 
											'7' => '7 år', 
											'8' => '8 år', 
											'9' => '9 år', 
											'10' => '10 år', 
											'11' => '11 år', 
											'12' => '12 år', 
											'13' => '13 år', 
											'14' => '14 år', 
											'15' => '15 år', 
											'16' => '16 år', 
											'17' => '17 år', 
											'18' => '18 år', 
											'19' => '19 år', 
											'20' => '20 år'
										]
									],

			'country_of_origin' => ['hidden' => true, 'key_as_value' => true, 'list' => [
																		'SE' => 'Sverige',
																		'DK' => 'Danmark',
																		'PL' => 'Polen',
																		'AF' => 'Afghanistan',
																		'AL' => 'Albania',
																		'DZ' => 'Algerie',
																		'AS' => 'Amerikansk Samoa',
																		'AD' => 'Andorra',
																		'AO' => 'Angola',
																		'AI' => 'Anguilla',
																		'NN' => 'Annet',
																		'AG' => 'Antigua',
																		'AR' => 'Argentina',
																		'AM' => 'Armenia',
																		'AW' => 'Aruba',
																		'AZ' => 'Aserbajdsjan',
																		'AU' => 'Australia',
																		'BS' => 'Bahamas',
																		'BH' => 'Bahrain',
																		'BD' => 'Bangladesh',
																		'BB' => 'Barbados',
																		'BE' => 'Belgia',
																		'BZ' => 'Belize',
																		'BJ' => 'Benin',
																		'BM' => 'Bermuda',
																		'BT' => 'Bhutan',
																		'BO' => 'Bolivia',
																		'BA' => 'Bosnia-Hercegovina',
																		'BW' => 'Botswana',
																		'BV' => 'Bouvetøya',
																		'BR' => 'Brasil',
																		'BN' => 'Brunei',
																		'BG' => 'Bulgaria',
																		'BF' => 'Burkina Faso',
																		'BI' => 'Burundi',
																		'CA' => 'Canada',
																		'KY' => 'Caymanøyene',
																		'CL' => 'Chile',
																		'CO' => 'Colombia',
																		'CK' => 'Cookøyene',
																		'CR' => 'Costa Rica',
																		'CU' => 'Cuba',
																		'CW' => 'Curaçao',
																		'DK' => 'Danmark',
																		'AE' => 'De Forente Arabiske Emirater',
																		'AN' => 'De nederlandske Antiller',
																		'DO' => 'Den Dominikanske Republikk',
																		'CF' => 'Den sentralafrikanske Republikk',
																		'IO' => 'Det Britiske territoriet i Indiahavet',
																		'DJ' => 'Djibouti',
																		'DM' => 'Dominica',
																		'EC' => 'Ecuador',
																		'EG' => 'Egypt',
																		'GQ' => 'Ekvatorial-Guinea',
																		'SV' => 'El Salvador',
																		'CI' => 'Elfenbenskysten',
																		'ER' => 'Eritrea',
																		'EE' => 'Estland',
																		'ET' => 'Etiopia',
																		'FK' => 'Falklandsøyene',
																		'FJ' => 'Fiji',
																		'PH' => 'Filippinene',
																		'FI' => 'Finland',
																		'FR' => 'Frankrike',
																		'GF' => 'Fransk Guyana',
																		'PF' => 'Fransk Polynesia',
																		'FO' => 'Færøyene',
																		'GA' => 'Gabon',
																		'GM' => 'Gambia',
																		'GE' => 'Georgia',
																		'GH' => 'Ghana',
																		'GI' => 'Gibraltar',
																		'GD' => 'Grenada',
																		'GL' => 'Grønland',
																		'GP' => 'Guadeloupe',
																		'GU' => 'Guam',
																		'GT' => 'Guatemala',
																		'GG' => 'Guernsey',
																		'GN' => 'Guinea',
																		'GW' => 'Guinea-Bissau',
																		'GY' => 'Guyana',
																		'HT' => 'Haiti',
																		'GR' => 'Hellas',
																		'HN' => 'Honduras',
																		'HK' => 'Hong Kong',
																		'BY' => 'Hviterussland',
																		'IN' => 'India',
																		'ID' => 'Indonesia',
																		'IQ' => 'Irak',
																		'IR' => 'Iran',
																		'IE' => 'Irland',
																		'IS' => 'Island',
																		'IL' => 'Israel',
																		'IT' => 'Italia',
																		'JM' => 'Jamaica',
																		'JP' => 'Japan',
																		'YE' => 'Jemen',
																		'JE' => 'Jersey',
																		'VG' => 'Jomfruøyene',
																		'VI' => 'Jomfruøyene',
																		'JO' => 'Jordan',
																		'KH' => 'Kambodsja',
																		'CM' => 'Kamerun',
																		'CV' => 'Kapp Verde',
																		'KZ' => 'Kasakhstan',
																		'KE' => 'Kenya',
																		'CN' => 'Kina',
																		'KG' => 'Kirgisistan',
																		'KI' => 'Kiribati',
																		'CC' => 'Kokosøyene',
																		'KM' => 'Komorene',
																		'CD' => 'Kongo/Kongo-Kinshasa',
																		'CG' => 'Kongo-Brazzaville',
																		'KO' => 'Kosovo',
																		'XK' => 'Kosovo',
																		'HR' => 'Kroatia',
																		'KW' => 'Kuwait',
																		'CY' => 'Kypros',
																		'LA' => 'Laos',
																		'LV' => 'Latvia',
																		'LS' => 'Lesotho',
																		'LB' => 'Libanon',
																		'LR' => 'Liberia',
																		'LY' => 'Libya',
																		'LI' => 'Liechtenstein',
																		'LT' => 'Litauen',
																		'LU' => 'Luxemburg',
																		'MO' => 'Macao',
																		'MG' => 'Madagaskar',
																		'MK' => 'Makedonia',
																		'MW' => 'Malawi',
																		'MY' => 'Malaysia',
																		'MV' => 'Maldivene',
																		'ML' => 'Mali',
																		'MT' => 'Malta',
																		'IM' => 'Man',
																		'MA' => 'Marokko',
																		'MH' => 'Marshalløyene',
																		'MQ' => 'Martinique',
																		'MR' => 'Mauritania',
																		'MU' => 'Mauritius',
																		'YT' => 'Mayotte',
																		'MX' => 'Mexico',
																		'FM' => 'Mikronesia',
																		'MD' => 'Moldova',
																		'MC' => 'Monaco',
																		'MN' => 'Mongolia',
																		'ME' => 'Montenegro',
																		'MS' => 'Montserrat',
																		'MZ' => 'Mosambik',
																		'MM' => 'Myanmar',
																		'NA' => 'Namibia',
																		'NR' => 'Nauru',
																		'NL' => 'Nederland',
																		'NP' => 'Nepal',
																		'NZ' => 'New Zealand',
																		'NI' => 'Nicaragua',
																		'NE' => 'Niger',
																		'NG' => 'Nigeria',
																		'NU' => 'Niue',
																		'KP' => 'Nord-Korea',
																		'MP' => 'Nord-Marianene',
																		'NF' => 'Norfolkøya',
																		'NC' => 'Ny Caledonia',
																		'OM' => 'Oman',
																		'PK' => 'Pakistan',
																		'PW' => 'Palau',
																		'PS' => 'Palestina',
																		'PA' => 'Panama',
																		'PG' => 'Papua Ny-Guinea',
																		'PY' => 'Paraguay',
																		'PE' => 'Peru',
																		'PN' => 'Pitcairnøya',
																		'PL' => 'Polen',
																		'PT' => 'Portugal',
																		'PR' => 'Puerto Rico',
																		'QA' => 'Qatar',
																		'RE' => 'Réunion',
																		'RO' => 'Romania',
																		'RU' => 'Russland',
																		'RW' => 'Rwanda',
																		'LC' => 'Saint Lucia',
																		'VC' => 'Saint Vincent og Grenadinene',
																		'MF' => 'Saint-Martin',
																		'SB' => 'Salomonøyene',
																		'WS' => 'Samoa',
																		'SM' => 'San Marino',
																		'SH' => 'Sankt Helena',
																		'ST' => 'São Tomé og Príncipe',
																		'SA' => 'Saudi-Arabia',
																		'SN' => 'Senegal',
																		'RS' => 'Serbia',
																		'SC' => 'Seychellene',
																		'SL' => 'Sierra Leone',
																		'SG' => 'Singapore',
																		'SX' => 'Sint Maarten',
																		'SK' => 'Slovakia',
																		'SI' => 'Slovenia',
																		'SO' => 'Somalia',
																		'ES' => 'Spania',
																		'LK' => 'Sri Lanka',
																		'GB' => 'Storbritannia',
																		'SD' => 'Sudan',
																		'SR' => 'Surinam',
																		'SJ' => 'Svalbard og Jan Mayen',
																		'CH' => 'Sveits',
																		'SE' => 'Sverige',
																		'SZ' => 'Swaziland',
																		'SY' => 'Syria',
																		'ZA' => 'Sør-Afrika',
																		'GS' => 'Sør-Georgia og Sør-Sandwich-øyene',
																		'KR' => 'Sør-Korea - Republikken Korea',
																		'SS' => 'Sør-Sudan',
																		'TJ' => 'Tadsjikistan',
																		'TW' => 'Taiwan',
																		'TZ' => 'Tanzania',
																		'TH' => 'Thailand',
																		'TG' => 'Togo',
																		'TK' => 'Tokelau',
																		'TO' => 'Tonga',
																		'TT' => 'Trinidad og Tobago',
																		'TD' => 'Tsjad',
																		'CZ' => 'Tsjekkia',
																		'TN' => 'Tunisia',
																		'TM' => 'Turkmenistan',
																		'TC' => 'Turks- og Caicosøyene',
																		'TV' => 'Tuvalu',
																		'TR' => 'Tyrkia',
																		'DE' => 'Tyskland',
																		'UG' => 'Uganda',
																		'UA' => 'Ukraina',
																		'HU' => 'Ungarn',
																		'UY' => 'Uruguay',
																		'US' => 'USA',
																		'UZ' => 'Usbekistan',
																		'VU' => 'Vanuatu',
																		'VA' => 'Vatikanstaten',
																		'VE' => 'Venezuela',
																		'EH' => 'Vest-Sahara',
																		'VN' => 'Vietnam',
																		'ZM' => 'Zambia',
																		'ZW' => 'Zimbabwe',
																		'AT' => 'Østerrike',
																		'TL' => 'Øst-Timor',
																		'AX' => 'Åland',
																		'X' => 'Annet'
																	]
															],

			'income' => ['text' => true],


			'civilstatus' => ['page' => '3', 'list' => ['Gift/partner', 'Skilt', 'Ugift', 'Enke/enkemann', 'Samboer', 'Separert']],
			'spouse_income' => ['hidden' => true, 'text' => true],

			'living_conditions' => ['list' => ['Selveier', 'Enebolig', 'Aksje/andel/borettslag', 'Leier', 'Bor hos foreldre']],
			'rent_income' => ['hidden' => true, 'text' => true],
			'mortgage' => ['hidden' => true, 'text' => true],
			'rent' => ['hidden' => true, 'text' => true],

			'address_since' => ['list' => [2018, 2017, 2016, 2015, 2014, 2013, 2012, 2011, 2010, 2009, 2008, 2007, 2006, 2005, 2004, 2003, 2002, 2001, 2000, 1999, 1998, 1997, 1996, 1995, 1994, 1993, 1992, 1991, 1990, 1989, 1988, 1987, 1986, 1985, 1984, 1983, 1982, 1981, 1980, 1979, 1978, 1977, 1976, 1975, 1974, 1973, 1972, 1971, 1970, 1969, 1968, 1967, 1966, 1965, 1964, 1963, 1962, 1961, 1960]],
			
			'car_boat_mc_loan' => ['text' => true],

			'number_of_children' => ['key_as_value' => true, 'list' => [
												'0' => '0 barn',
												'1' => '1 barn',
												'2' => '2 barn',
												'3' => '3 barn',
												'4' => '4 barn',
												'5' => '5 barn',
												'6' => '6 barn',
												'7' => '7 barn',
												'8' => '8 barn',
												'9' => '9 barn'
												]
									],
			'allimony_per_month' => ['text' => true, 'hidden' => true],




			'co_applicant' => ['checkbox' => true, 'page' => '4', 'no' => true],

			'co_applicant_name' => ['hidden' => true, 'text' => true],
			'co_applicant_social_number' => ['hidden' => true, 'text' => true],
			'co_applicant_mobile_number' => ['hidden' => true, 'text' => true],
			'co_applicant_email' => ['hidden' => true, 'text' => true],

			'co_applicant_employment_type' => ['hidden' => true, 'list' => [
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
			'co_applicant_employment_since' => ['hidden' => true, 'list' => [2018, 2017, 2016, 2015, 2014, 2013, 2012, 2011, 2010, 2009, 2008, 2007, 2006, 2005, 2004, 2003, 2002, 2001, 2000, 1999, 1998, 1997, 1996, 1995, 1994, 1993, 1992, 1991, 1990, 1989, 1988, 1987, 1986, 1985, 1984, 1983, 1982, 1981, 1980, 1979, 1978, 1977, 1976, 1975, 1974, 1973, 1972, 1971, 1970, 1969, 1968, 1967, 1966, 1965, 1964, 1963, 1962, 1961, 1960]],
			'co_applicant_employer' => ['hidden' => true, 'text' => true],

			'co_applicant_education' => ['hidden' => true, 'list' => [
																		'Grunnskole',
																		'Videregående',
																		'Høysk./universitet 1-3 år',
																		'Høysk./universitet 4+år'
																	]
										],

			'co_applicant_norwegian' => ['hidden' => true, 'checkbox' => true, 'yes' => true],
			'co_applicant_years_in_norway' => ['hidden' => true, 'list' => [
																			'Alltid' => 'Alltid', 
																			'1' => '1 år', 
																			'2' => '2 år', 
																			'3' => '3 år', 
																			'4' => '4 år', 
																			'5' => '5 år', 
																			'6' => '6 år', 
																			'7' => '7 år', 
																			'8' => '8 år', 
																			'9' => '9 år', 
																			'10' => '10 år', 
																			'11' => '11 år', 
																			'12' => '12 år', 
																			'13' => '13 år', 
																			'14' => '14 år', 
																			'15' => '15 år', 
																			'16' => '16 år', 
																			'17' => '17 år', 
																			'18' => '18 år', 
																			'19' => '19 år', 
																			'20' => '20 år'
																			]
												],
			'co_applicant_country_of_origin' => ['key_as_value' => true, 'hidden' => true, 'list' => [
																										'SE' => 'Sverige',
																										'DK' => 'Danmark',
																										'PL' => 'Polen',
																										'AF' => 'Afghanistan',
																										'AL' => 'Albania',
																										'DZ' => 'Algerie',
																										'AS' => 'Amerikansk Samoa',
																										'AD' => 'Andorra',
																										'AO' => 'Angola',
																										'AI' => 'Anguilla',
																										'NN' => 'Annet',
																										'AG' => 'Antigua',
																										'AR' => 'Argentina',
																										'AM' => 'Armenia',
																										'AW' => 'Aruba',
																										'AZ' => 'Aserbajdsjan',
																										'AU' => 'Australia',
																										'BS' => 'Bahamas',
																										'BH' => 'Bahrain',
																										'BD' => 'Bangladesh',
																										'BB' => 'Barbados',
																										'BE' => 'Belgia',
																										'BZ' => 'Belize',
																										'BJ' => 'Benin',
																										'BM' => 'Bermuda',
																										'BT' => 'Bhutan',
																										'BO' => 'Bolivia',
																										'BA' => 'Bosnia-Hercegovina',
																										'BW' => 'Botswana',
																										'BV' => 'Bouvetøya',
																										'BR' => 'Brasil',
																										'BN' => 'Brunei',
																										'BG' => 'Bulgaria',
																										'BF' => 'Burkina Faso',
																										'BI' => 'Burundi',
																										'CA' => 'Canada',
																										'KY' => 'Caymanøyene',
																										'CL' => 'Chile',
																										'CO' => 'Colombia',
																										'CK' => 'Cookøyene',
																										'CR' => 'Costa Rica',
																										'CU' => 'Cuba',
																										'CW' => 'Curaçao',
																										'DK' => 'Danmark',
																										'AE' => 'De Forente Arabiske Emirater',
																										'AN' => 'De nederlandske Antiller',
																										'DO' => 'Den Dominikanske Republikk',
																										'CF' => 'Den sentralafrikanske Republikk',
																										'IO' => 'Det Britiske territoriet i Indiahavet',
																										'DJ' => 'Djibouti',
																										'DM' => 'Dominica',
																										'EC' => 'Ecuador',
																										'EG' => 'Egypt',
																										'GQ' => 'Ekvatorial-Guinea',
																										'SV' => 'El Salvador',
																										'CI' => 'Elfenbenskysten',
																										'ER' => 'Eritrea',
																										'EE' => 'Estland',
																										'ET' => 'Etiopia',
																										'FK' => 'Falklandsøyene',
																										'FJ' => 'Fiji',
																										'PH' => 'Filippinene',
																										'FI' => 'Finland',
																										'FR' => 'Frankrike',
																										'GF' => 'Fransk Guyana',
																										'PF' => 'Fransk Polynesia',
																										'FO' => 'Færøyene',
																										'GA' => 'Gabon',
																										'GM' => 'Gambia',
																										'GE' => 'Georgia',
																										'GH' => 'Ghana',
																										'GI' => 'Gibraltar',
																										'GD' => 'Grenada',
																										'GL' => 'Grønland',
																										'GP' => 'Guadeloupe',
																										'GU' => 'Guam',
																										'GT' => 'Guatemala',
																										'GG' => 'Guernsey',
																										'GN' => 'Guinea',
																										'GW' => 'Guinea-Bissau',
																										'GY' => 'Guyana',
																										'HT' => 'Haiti',
																										'GR' => 'Hellas',
																										'HN' => 'Honduras',
																										'HK' => 'Hong Kong',
																										'BY' => 'Hviterussland',
																										'IN' => 'India',
																										'ID' => 'Indonesia',
																										'IQ' => 'Irak',
																										'IR' => 'Iran',
																										'IE' => 'Irland',
																										'IS' => 'Island',
																										'IL' => 'Israel',
																										'IT' => 'Italia',
																										'JM' => 'Jamaica',
																										'JP' => 'Japan',
																										'YE' => 'Jemen',
																										'JE' => 'Jersey',
																										'VG' => 'Jomfruøyene',
																										'VI' => 'Jomfruøyene',
																										'JO' => 'Jordan',
																										'KH' => 'Kambodsja',
																										'CM' => 'Kamerun',
																										'CV' => 'Kapp Verde',
																										'KZ' => 'Kasakhstan',
																										'KE' => 'Kenya',
																										'CN' => 'Kina',
																										'KG' => 'Kirgisistan',
																										'KI' => 'Kiribati',
																										'CC' => 'Kokosøyene',
																										'KM' => 'Komorene',
																										'CD' => 'Kongo/Kongo-Kinshasa',
																										'CG' => 'Kongo-Brazzaville',
																										'KO' => 'Kosovo',
																										'XK' => 'Kosovo',
																										'HR' => 'Kroatia',
																										'KW' => 'Kuwait',
																										'CY' => 'Kypros',
																										'LA' => 'Laos',
																										'LV' => 'Latvia',
																										'LS' => 'Lesotho',
																										'LB' => 'Libanon',
																										'LR' => 'Liberia',
																										'LY' => 'Libya',
																										'LI' => 'Liechtenstein',
																										'LT' => 'Litauen',
																										'LU' => 'Luxemburg',
																										'MO' => 'Macao',
																										'MG' => 'Madagaskar',
																										'MK' => 'Makedonia',
																										'MW' => 'Malawi',
																										'MY' => 'Malaysia',
																										'MV' => 'Maldivene',
																										'ML' => 'Mali',
																										'MT' => 'Malta',
																										'IM' => 'Man',
																										'MA' => 'Marokko',
																										'MH' => 'Marshalløyene',
																										'MQ' => 'Martinique',
																										'MR' => 'Mauritania',
																										'MU' => 'Mauritius',
																										'YT' => 'Mayotte',
																										'MX' => 'Mexico',
																										'FM' => 'Mikronesia',
																										'MD' => 'Moldova',
																										'MC' => 'Monaco',
																										'MN' => 'Mongolia',
																										'ME' => 'Montenegro',
																										'MS' => 'Montserrat',
																										'MZ' => 'Mosambik',
																										'MM' => 'Myanmar',
																										'NA' => 'Namibia',
																										'NR' => 'Nauru',
																										'NL' => 'Nederland',
																										'NP' => 'Nepal',
																										'NZ' => 'New Zealand',
																										'NI' => 'Nicaragua',
																										'NE' => 'Niger',
																										'NG' => 'Nigeria',
																										'NU' => 'Niue',
																										'KP' => 'Nord-Korea',
																										'MP' => 'Nord-Marianene',
																										'NF' => 'Norfolkøya',
																										'NC' => 'Ny Caledonia',
																										'OM' => 'Oman',
																										'PK' => 'Pakistan',
																										'PW' => 'Palau',
																										'PS' => 'Palestina',
																										'PA' => 'Panama',
																										'PG' => 'Papua Ny-Guinea',
																										'PY' => 'Paraguay',
																										'PE' => 'Peru',
																										'PN' => 'Pitcairnøya',
																										'PL' => 'Polen',
																										'PT' => 'Portugal',
																										'PR' => 'Puerto Rico',
																										'QA' => 'Qatar',
																										'RE' => 'Réunion',
																										'RO' => 'Romania',
																										'RU' => 'Russland',
																										'RW' => 'Rwanda',
																										'LC' => 'Saint Lucia',
																										'VC' => 'Saint Vincent og Grenadinene',
																										'MF' => 'Saint-Martin',
																										'SB' => 'Salomonøyene',
																										'WS' => 'Samoa',
																										'SM' => 'San Marino',
																										'SH' => 'Sankt Helena',
																										'ST' => 'São Tomé og Príncipe',
																										'SA' => 'Saudi-Arabia',
																										'SN' => 'Senegal',
																										'RS' => 'Serbia',
																										'SC' => 'Seychellene',
																										'SL' => 'Sierra Leone',
																										'SG' => 'Singapore',
																										'SX' => 'Sint Maarten',
																										'SK' => 'Slovakia',
																										'SI' => 'Slovenia',
																										'SO' => 'Somalia',
																										'ES' => 'Spania',
																										'LK' => 'Sri Lanka',
																										'GB' => 'Storbritannia',
																										'SD' => 'Sudan',
																										'SR' => 'Surinam',
																										'SJ' => 'Svalbard og Jan Mayen',
																										'CH' => 'Sveits',
																										'SE' => 'Sverige',
																										'SZ' => 'Swaziland',
																										'SY' => 'Syria',
																										'ZA' => 'Sør-Afrika',
																										'GS' => 'Sør-Georgia og Sør-Sandwich-øyene',
																										'KR' => 'Sør-Korea - Republikken Korea',
																										'SS' => 'Sør-Sudan',
																										'TJ' => 'Tadsjikistan',
																										'TW' => 'Taiwan',
																										'TZ' => 'Tanzania',
																										'TH' => 'Thailand',
																										'TG' => 'Togo',
																										'TK' => 'Tokelau',
																										'TO' => 'Tonga',
																										'TT' => 'Trinidad og Tobago',
																										'TD' => 'Tsjad',
																										'CZ' => 'Tsjekkia',
																										'TN' => 'Tunisia',
																										'TM' => 'Turkmenistan',
																										'TC' => 'Turks- og Caicosøyene',
																										'TV' => 'Tuvalu',
																										'TR' => 'Tyrkia',
																										'DE' => 'Tyskland',
																										'UG' => 'Uganda',
																										'UA' => 'Ukraina',
																										'HU' => 'Ungarn',
																										'UY' => 'Uruguay',
																										'US' => 'USA',
																										'UZ' => 'Usbekistan',
																										'VU' => 'Vanuatu',
																										'VA' => 'Vatikanstaten',
																										'VE' => 'Venezuela',
																										'EH' => 'Vest-Sahara',
																										'VN' => 'Vietnam',
																										'ZM' => 'Zambia',
																										'ZW' => 'Zimbabwe',
																										'AT' => 'Østerrike',
																										'TL' => 'Øst-Timor',
																										'AX' => 'Åland',
																										'X' => 'Annet'
																			]
												],

			'co_applicant_income' => ['hidden' => true, 'text' => true],


			];


}