(function() {

	// helper function
	var qs = function(s) { return document.querySelector(s) }

	// part counter
	var counter = 1;

	// min loan amount
	var min = 10000;

	// max loan amount
	var max = 500000;

	// min tenure
	var minYear = 1;

	// max tenure
	var maxYear = 15;

	// tenure postfix
	var postfixYear = ' år';

	// valid bg color
	var bgColor = 'hsl(270, 50%, 72%)';

	// invalid bg color (test fail)
	var errorColor = 'hsl(0, 100%, 50%)';

	// next button
	var nextButton = qs('.em-b-next');

	// prev button 
	var prevButton = qs('.em-b-back');

	// submit button
	var submitButton = qs('.em-b-submit');

	// current page
	var currentPart = qs('.part-'+counter);

	var monthlyCost = qs('.em-if-monthly_cost');


	// updating monthly cost field
	var payment = function() {
		var i = '0.068'/12;
		var p = qs('.em-i-loan_amount').value.replace(/[^0-9]/g, '');
		var n = qs('.em-i-tenure').value.replace(/[^0-9]/g, '')*12;

		monthlyCost.value = norNr(Math.floor(p / ((1 - Math.pow(1 + i, -n)) / i)));
	};


	/**
	 * [showArr description]
	 * @param  {[type]} arr [description]
	 * @return {[type]}     [description]
	 */
	var showArr = function(arr) {
		for (var e in arr) {
			if (!arr[e]) {
				console.error('showArr: node is undefined.');
				continue;
			}

			// removing css class with display: hidden
			arr[e].classList.remove('em-hidden');
		}
	}


	/**
	 * [hideArr description]
	 * @param  {[type]} arr [description]
	 * @return {[type]}     [description]
	 */
	var hideArr = function(arr) {
		for (var e in arr) {
			if (!arr[e]) {
				console.error('showArr: node is undefined.');
				continue;
			}

			// adding css class with display: none
			arr[e].classList.add('em-hidden');
		}
	}




	/**
	 * [val description]
	 * @param  {Object}  {value, feedbackNode, callback, errorColor}
	 * @return {boolean} validation
	 */
	var val = function(o = {}) {

		if (o.value == null) return false;

		/**
		 * [validator description]
		 * @type {Object}
		 */
		var validator = {
			// EMAIL
			email: function(e) { return e.match(/.+?@.+?\..+/) },
			
			// MOBILE NUMBER
			phone: function(e) { 
				e = e.replace(/[^0-9]/, '');

				if (e.length != 8) return false;

				return true;
			},
		
			// LOAN AMOUNT
			amount: function(e) {
				e = parseInt(e);

				if (e < min) return false;
				if (e > max) return false;

				return true;
			},

			// LOAN PERIODE
			tenure: function(e) {
				e = parseInt(e);

				if (e < minYear) return false;
				if (e > maxYear) return false;

				return true;
			},

			// SOCIAL NUMBER
			social: function(e) {
				e = e.replace(/[^0-9]/g, '');

				if (e.length != 11) return false;

				return true;
			},

			// LIST INPUT
			list: function(e) {
				if (e == '') return false;

				return true;
			},

			// NO NUMBERS (text inputs)
			noNumbers: function(e) {
				if (/\d/.test(e)) return false;

				return true;
			},

			// BANK ACCOUNT NUMBER
			accountNumber: function(e) {
				e = e.replace(/[^0-9]/g, '');
				if (e.length != 11) return false;

				return true;
			}
		}


		// checks whether function exists
		if (typeof validator[o.callback] !== 'function') { 
			console.error('Validator.'+o.callback+' is not a function.');
			return false; 
		}

		// if validation fails
		if (!validator[o.callback](o.value)) {
			// change visual feature if set
			if (o.feedbackNode && o.errorColor) o.feedbackNode.style.backgroundColor = o.errorColor;

			return false;
		}

		// validation did not fail (resets validation fail visual feature)
		else if (o.feedbackNode) o.feedbackNode.style.backgroundColor = 'transparent';


		// validation OK
		return true;
	}


	/**
	 * [nextPage description]
	 * @return {[type]} [description]
	 */
	var nextPage = function() {
		// current page
		var cPage = qs('.part-'+counter);

		counter++;

		// page to show
		var page = qs('.part-'+counter);

		// page to show next click of the button
		var nextPage = qs('.part-'+(counter+1));

		// if no nextpage, then show submit button
		if (!nextPage) { 

			// if no next page, then show submit button
			nextButton.style.display = 'none';
			submitButton.style.display = 'inline-block';
		}

		// if next page, then show next button
		else nextButton.style.display = 'inline-block';

		if (!page) return false;

		// show prev button
		prevButton.style.display = 'inline-block';

		// show correct page
		cPage.style.display = 'none';
		page.style.display = 'grid';
	}


	/**
	 * [prevPage description]
	 * @return {[type]} [description]
	 */
	var prevPage = function() {
		// current page
		var cPage = qs('.part-'+counter);

		counter--;

		// page to show
		var page = qs('.part-'+counter);

		// page to show next time previous button is pressed
		var prevPage = qs('.part-'+(counter-1));

		// to show prev button or not
		if (!prevPage) prevButton.style.display = 'none';
		else prevButton.style.display = 'inline-block';

		if (!page) return false;
		
		// shows next button (hides submit button)
		nextButton.style.display = 'inline-block';
		submitButton.style.display = 'none';

		// hides and show current page
		cPage.style.display = 'none';
		page.style.display = 'grid';
	}


	// next page button
	nextButton.addEventListener('click', function() { nextPage() });

	// previous page button
	prevButton.addEventListener('click', function() { prevPage() });


	// clicking the submit button
	submitButton.addEventListener('click', function() {

		var get = function(o = {}) {
		// var get = function(e, v = null) {

			// getting the value
			var value = qs(o.node) ? qs(o.node).value : null;

			// if element not found
			if (value === null) return null;

			// if value fails validation
			if (o.callback && !val({value: value, callback: o.callback})) return false;

			// removes all but numbers
			if (o.number) return value.replace(/[^0-9]/g, '');

			// returns value
			return value;
		}

		var o = {
			loan_amount: get({node: '.em-i-loan_amount', number: true}),
			tenure: get({ node:'.em-i-tenure', number: true}),
			collect_debt: get({ node:'.em-c-collect_debt'}),
			mobile_number: get({ node:'.em-i-mobile_number', callback: 'phone', number: true}),
			email: get({ node:'.em-i-email'}),

			social_number: get({ node:'.em-i-social_number', number: true, number: true}),
			employment_type: get({ node:'.em-i-employment_type'}),
			employment_since: get({ node:'.em-i-employment_since'}),
			employer: get({ node:'.em-i-employer'}),
			education: get({ node:'.em-i-education'}),
			education_loan: get({ node:'.em-i-education_loan', number: true}),
			norwegian: get({ node:'.em-c-norwegian'}),
			years_in_norway: get({ node:'.em-i-years_in_norway'}),
			country_of_origin: get({ node:'.em-i-country_of_origin'}),
			income: get({ node:'.em-i-income', number: true}),
			civilstatus: get({ node:'.em-i-civilstatus'}),
			spouse_income: get({ node:'.em-i-spouse_income', number: true}),
			living_conditions: get({ node:'.em-i-living_conditions'}),
			rent_income: get({ node:'.em-i-rent_income', number: true}),
			mortgage: get({ node:'.em-i-mortgage', number: true}),
			rent: get({ node:'.em-i-rent', number: true}),
			address: get({ node:'.em-i-address'}),
			car_boat_mc_loan: get({ node:'.em-i-car_boat_mc_loan', number: true}),
			number_of_children: get({ node:'.em-i-number_of_children', number: true}),
			allimony_per_month: get({ node:'.em-i-allimony_per_month', number: true}),

			co_applicant: get({ node:'.em-i-co_applicant'}),
			co_applicant_name: get({ node:'.em-i-co_applicant_name', callback: 'noNumbers'}),
			co_applicant_social_number: get({ node:'.em-i-co_applicant_social_number', number: true}),
			co_applicant_mobile_number: get({ node:'.em-i-co_applicant_mobile_number', number: true}),
			co_applicant_email: get({ node:'.em-i-co_applicant_email'}),
			co_applicant_employment_type: get({ node:'.em-i-co_applicant_employment_type'}),
			co_applicant_employment_since: get({ node:'.em-i-co_applicant_employment_since'}),
			co_applicant_employer: get({ node:'.em-i-co_applicant_employer'}),
			co_applicant_education: get({ node:'.em-i-co_applicant_education'}),
			co_applicant_norwegian: get({ node:'.em-c-co_applicant_norwegian'}),
			co_applicant_years_in_norway: get({ node:'.em-i-co_applicant_years_in_norway'}),
			co_applicant_country_of_origin: get({ node:'.em-i-co_applicant_country_of_origin'}),
			co_applicant_income: get({ node:'.em-i-co_applicant_income', number: true}),
			
			unsecured_debt_balance: get({node: '.em-i-unsecured_debt_balance', number: true}),
			account_number: get({node: '.em-i-account_number', number: true})			
		}


		for (var t in o)
			console.log(t+': '+o[t]);

	});


	/**
	 * [numberEvents description]
	 * @param  {Object} o [description]
	 * @return {[type]}   [description]
	 */
	var numberEvents = function(o = {}) {
		
		if (!o || !o.node) return;

		if (!o.max) o.max = 8;
		if (!o.errorColor) o.errorColor = errorColor;

		// when hitting enter
		o.node.addEventListener('keypress', function(e) { if (e.keyCode == 13) e.target.blur() });

		// typing
		o.node.addEventListener('input', function(e) { o.node.value = e.target.value.replace(/[^0-9]/g, '').substr(0, o.max) });

		// on focus
		o.node.addEventListener('focus', function(e) { 
		
			if (o.currency) e.target.value = e.target.value.replace(/[^0-9]/g, '');

			e.target.select(); 
		});

		// lost focus (validation time)
		if (o.error || o.currency)
			o.node.addEventListener('focusout', function(e) {

				// e.target.parentNode.parentNode.style.backgroundColor = 'transparent';
				
				if (o.error) val({
									callback: o.error,
									value: e.target.value,
									errorColor: o.errorColor,
									feedbackNode: e.target.parentNode.parentNode
								});


				if (o.currency) o.node.value = norNr(o.node.value);
			});

	}

	/**
	 * [textEvents description]
	 * @param  {[type]} node  [description]
	 * @param  {[type]} regex [description]
	 * @return {[type]}       [description]
	 */
	var textEvents = function(node, regex) {

		if (regex) node.addEventListener('input', function(e) { e.target.value = e.target.value.replace(regex, '')	});

		node.addEventListener('keypress', function(e) { if (e.keyCode == 13) e.target.blur() });
		node.addEventListener('focus', function(e) { e.target.select() });
	}


	/**
	 * [checkEvents description]
	 * @param  {[type]} yes    [description]
	 * @param  {[type]} no     [description]
	 * @param  {[type]} hidden [description]
	 * @return {[type]}        [description]
	 */
	var checkEvents = function(yes, no, hidden) {
		if (!yes || !no || !hidden) return;

		var click = function(sel, unsel, val) {
			sel.classList.add('em-cc-green');
			unsel.classList.remove('em-cc-green');

			hidden.value = val;
		}

		yes.addEventListener('click', function() { click(yes, no, 1) });
		no.addEventListener('click', function() { click(no, yes, 0) });
	}


	/**
	 * [listEvents description]
	 * @param  {[type]} node [description]
	 * @return {[type]}      [description]
	 */
	var listEvents = function(node) {
		node.addEventListener('change', function(e) {
			val({
				callback: 'list', 
				value: node.value, 
				errorColor: errorColor, 
				feedbackNode: node.parentNode.parentNode
			});
		});
	}









	/**
	 * [norNr description]
	 * @param  {[type]} e [description]
	 * @return {[type]}   [description]
	 */
	var norNr = function(e) {
		if (!e) return '';

		return parseInt(e).toLocaleString(
							'nb-NO', 
							{
								style: 'currency', 
								currency: 'NOK',
								minimumFractionDigits: 0
							});
	}



	// LOAN AMOUNT
	var loanRange = qs('.em-r-loan_amount');
	var loanText = qs('.em-i-loan_amount');

	numberEvents({node: loanText, currency: true});

	// fixing initial value to locale
	loanText.value = norNr(loanText.value);

	// hitting enter to exit input
	// loanText.addEventListener('keypress', function(e) { if (e.keyCode == 13) loanText.blur() });

	// typing
	// loanText.addEventListener('input', function(e) {

	// 	// removes all but numbers
	// 	var v = e.target.value.replace(/[^0-9]/g, '');
	
	// 	if (!v) v = '';
	// 	else if (v > max) v = max;
	
	// 	loanText.value = v; 
	// 	loanRange.value = v; 

	// });

	// focus
	// loanText.addEventListener('focus', function(e) { loanText.value = loanText.value.replace(/[^0-9]/g, ''); loanText.select(); });

	// focus out (validation time)
	loanText.addEventListener('focusout', function(e) {

		// removes all but numbers
		var n = e.target.value.replace(/[^0-9]/g, '');

		if (n < min) n = min;
		else if (n > max) n = max;

		loanText.value = norNr(n);

		loanRange.value = n;
	});

	loanText.addEventListener('input', function(e) { payment() });


	// LOAN RANGE 
	// setting the text input while changing the range input
	loanRange.addEventListener('input', function(e) { 
		loanText.value = norNr(e.target.value) 

		payment();
	});








	// TENURE
	var tenureText = qs('.em-i-tenure');
	var tenureRange = qs('.em-r-tenure');

	// fixing initial value
	tenureText.value += postfixYear;

	// pressing enter
	tenureText.addEventListener('keypress', function(e) { if (e.keyCode == 13) tenureText.blur() });

	// typing
	tenureText.addEventListener('input', function(e) { 
		var v = e.target.value.replace(/[^0-9]/g, '');
	
		if (v == '') v = '5';

		else if (v > maxYear) v = maxYear;
	
		else if (v < minYear) v = minYear;
	
		tenureText.value = v; 
		tenureRange.value = v; 

		payment();
	});

	// focus
	tenureText.addEventListener('focus', function(e) { 
		tenureText.value = tenureText.value.replace(/[^0-9]/g, '');
		tenureText.select(); 
	});


	// focus lost (validation time)
	tenureText.addEventListener('focusout', function(e) { 
		var n = e.target.value.replace(/[^0-9]/g, '');

		if (n < minYear) n = minYear;
		else if (n > maxYear) n = maxYear;

		tenureText.value = n + postfixYear; 
	});

	// TENURE RANGE
	tenureRange.addEventListener('input', function(e) { 
		tenureText.value = e.target.value + postfixYear;
		payment();
	});









	// MOBILE NUMBER
	var mobileText = qs('.em-i-mobile_number');
	numberEvents({node: mobileText, max: 8, error: 'phone'});

	mobileText.addEventListener('focusout', function(e) {

		var n = e.target.value;

		// if (n.length > 5)
		if (n.length == 8) mobileText.value = n.substr(0, 3)+' '+n.substr(3, 2)+' '+n.substr(5, 3); 

		// else if (n.length > 3)

	});



	// EMAIL
	var emailText = qs('.em-i-email');
	textEvents(emailText);
	// hitting enter will exit the input
	// emailText.addEventListener('keypress', function(e) { if (e.keyCode == 13) emailText.blur() });

	// typing
	emailText.addEventListener('input', function(e) {

		// setting size of font so long email addresses fits better
		if (e.target.value.length > 50) e.target.style.fontSize = '14px';
		else if (e.target.value.length > 40) e.target.style.fontSize = '16px';
		else if (e.target.value.length > 30) e.target.style.fontSize = '18px';
		else e.target.style.fontSize = '24px';
	});

	// on focus
	// emailText.addEventListener('focus', function() { emailText.select() });	

	// lost focus (validation time)
	emailText.addEventListener('focusout', function(e) { 
		val({
			callback: 'email', 
			value: e.target.value, 
			errorColor: errorColor, 
			feedbackNode: e.target.parentNode.parentNode
		});
	});


	// REFINANCING
	checkEvents(
			qs('.em-cc-collect_debt .em-cc-yes'),
			qs('.em-cc-collect_debt .em-cc-no'),
			qs('.em-cc-collect_debt .em-c')
		);

	monthlyCost.value = norNr(monthlyCost.value);


	// PAGE 2

	// SOCIAL NUMBER
	var socialnumber = qs('.em-i-social_number');
	numberEvents({node: socialnumber, max: 11, error: 'social'});



	// EMPLOYMENT TYPE
	var employmentType = qs('.em-i-employment_type');
	var employmentSinceC = qs('.em-element-employment_since');
	var employerC = qs('.em-element-employer');

	listEvents(employmentType);

	employmentType.addEventListener('change', function(e) {
		var show = function() {
			employmentSinceC.classList.remove('em-hidden');
			employerC.classList.remove('em-hidden');
		}

		var hide = function() {
			employmentSinceC.classList.add('em-hidden');
			employerC.classList.add('em-hidden');
		}

		switch (e.target.value) {

			case 'Fast ansatt (privat)':
			case 'Fast ansatt (offentlig)': 
			case 'Midlertidig ansatt/vikar': 
			case 'Selvst. næringsdrivende': 
			case 'Langtidssykemeldt': show(); break;

			default: hide();

		}
	});

	// EMPLOYER

	// employer already gotten
	var employer = qs('.em-i-employer');
	textEvents(employer, /[^0-9a-xøæåA-XØÆÅ ]/);


	// eduction
	listEvents(qs('.em-i-education'));

	qs('.em-i-education').addEventListener('input', function(e) {

		switch (e.target.value) {
			case 'Høysk./universitet 1-3 år':
			case 'Høysk./universitet 4+år': qs('.em-element-education_loan').classList.remove('em-hidden'); break;

			default: qs('.em-element-education_loan').classList.add('em-hidden');
		}
	});

	numberEvents({node: qs('.em-i-education_loan'), currency: true});






	// NORWEGIAN
	var norYes = qs('.em-element-norwegian .em-cc-yes');
	var norNo = qs('.em-element-norwegian .em-cc-no');

	var norYears = qs('.em-element-years_in_norway');
	var norOrigin = qs('.em-element-country_of_origin');

	checkEvents(
			norYes,
			norNo,
			qs('.em-element-norwegian .em-c')
		);

	norYes.addEventListener('click', function(e) {
		norYears.classList.add('em-hidden');
		norOrigin.classList.add('em-hidden');
	});

	norNo.addEventListener('click', function(e) {
		norYears.classList.remove('em-hidden');
		norOrigin.classList.remove('em-hidden');
	});


	// years in norway
	listEvents(qs('.em-i-years_in_norway'));

	// country of origin
	listEvents(qs('.em-i-country_of_origin'));

	// INCOME 
	numberEvents({node: qs('.em-i-income'), currency: true});

	// CIVILSTATUS
	listEvents(qs('.em-i-civilstatus'));

	// SPOUSE INCOME
	numberEvents({node: qs('.em-i-spouse_income'), currency: true});

	qs('.em-i-civilstatus').addEventListener('input', function(e) {
		if (e.target.value == 'Gift/partner') qs('.em-element-spouse_income').classList.remove('em-hidden');
		else qs('.em-element-spouse_income').classList.add('em-hidden');
	});

	// LIVING CONDITIONS
	listEvents(qs('.em-i-living_conditions'));

	qs('.em-i-living_conditions').addEventListener('input', function(e) {

		var rent = qs('.em-element-rent');
		var rentIncome = qs('.em-element-rent_income');
		var mortgage = qs('.em-element-mortgage');

		// hide all
		var hide = function() {
			rent.classList.add('em-hidden');
			rentIncome.classList.add('em-hidden');
			mortgage.classList.add('em-hidden');
		}

		// show all
		var show = function() {
			rent.classList.remove('em-hidden');
			rentIncome.classList.remove('em-hidden');
			mortgage.classList.remove('em-hidden');
		}

		// partial (show rent, hide rent income and mortgage)
		var showRent = function() {
			rent.classList.remove('em-hidden');
			rentIncome.classList.add('em-hidden');
			mortgage.classList.add('em-hidden');
		}

		// hide rent, show rent income and mortgage
		var hideRent = function() {
			rent.classList.add('em-hidden');
			rentIncome.classList.remove('em-hidden');
			mortgage.classList.remove('em-hidden');
		}


		switch (e.target.value) {

			case 'Selveier':
			case 'Aksje/andel/borettslag': show(); break;

			case 'Leier':
			case 'Bor hos foreldre': showRent(); break;

			case 'Enebolig': hideRent(); break;

			default: hide(); 
		}
	});

	// RENT INCOME
	numberEvents({node: qs('.em-i-rent_income'), currency: true});

	// MORTGAGE
	numberEvents({node: qs('.em-i-mortgage'), currency: true});

	// ADDRESS SINCE
	listEvents(qs('.em-i-address_since'));

	// NUMBER OF CHILDREN
	listEvents(qs('.em-i-number_of_children'));


	// ALLIMONY RECEIVED
	numberEvents({node: qs('.em-i-allimony_per_month'), currency: true});

	qs('.em-i-number_of_children').addEventListener('input', function(e) {
		if (e.target.value == 0 || !e.target.value) qs('.em-element-allimony_per_month').classList.add('em-hidden');
		else qs('.em-element-allimony_per_month').classList.remove('em-hidden');
	});


	// RENT
	numberEvents({node: qs('.em-i-rent'), currency: true});

	// car, boat, mc
	numberEvents({node: qs('.em-i-car_boat_mc_loan'), currency: true});
	

	// CO-APPLICANT
	var coYes = qs('.em-element-co_applicant .em-cc-yes');
	var coNo = qs('.em-element-co_applicant .em-cc-no');
	checkEvents(
		coYes,
		coNo,
		qs('.em-element-co_applicant .em-c')
	);

	// elements to show
	var coArr = [
	 	qs('.em-element-co_applicant_name'),
	 	qs('.em-element-co_applicant_social_number'),
	 	qs('.em-element-co_applicant_mobile_number'),
	 	qs('.em-element-co_applicant_email'),
	 	qs('.em-element-co_applicant_employment_type'),
	 	// qs('.em-element-co_applicant_employment_since'),
	 	// qs('.em-element-co_applicant_employer'),
	 	qs('.em-element-co_applicant_education'),
	 	qs('.em-element-co_applicant_norwegian'),
	 	// qs('.em-element-co_applicant_years_in_norway'),
	 	// qs('.em-element-co_applicant_country_of_origin'),
	 	qs('.em-element-co_applicant_income')
	];


	coYes.addEventListener('click', function() { showArr(coArr) });
	coNo.addEventListener('click', function() { hideArr(coArr) });

	 // co applicant name
	textEvents(qs('.em-i-co_applicant_name'), /[^a-xøæåA-XØÆÅ ]/);
	 
	// co applicant social number
	numberEvents({node: qs('.em-i-co_applicant_social_number'), max: 11});

	// co applicant mobile number 
	numberEvents({node: qs('.em-i-co_applicant_mobile_number'), max: 8});

	//
	var coEmail = qs('.em-i-co_applicant_email');
	textEvents(coEmail);


	coEmail.addEventListener('input', function(e) {

		// setting size of font so long email addresses fits better
		if (e.target.value.length > 50) e.target.style.fontSize = '14px';
		else if (e.target.value.length > 40) e.target.style.fontSize = '16px';
		else if (e.target.value.length > 30) e.target.style.fontSize = '18px';
		else e.target.style.fontSize = '24px';
	});

	// lost focus (validation time)
	coEmail.addEventListener('focusout', function(e) { 
		val({
			callback: 'email', 
			value: e.target.value, 
			errorColor: errorColor, 
			feedbackNode: e.target.parentNode.parentNode
		});
	});

	// co applicant employment type
	var coEmpType = qs('.em-i-co_applicant_employment_type');
	var coEmpSince = qs('.em-element-co_applicant_employment_since');
	var coEmployer = qs('.em-element-co_applicant_employer');

	listEvents(coEmpType);

	coEmpType.addEventListener('input', function(e) {

		var show = function() {
			coEmpSince.classList.remove('em-hidden');
			coEmployer.classList.remove('em-hidden');
		}

		var hide = function() {
			coEmpSince.classList.add('em-hidden');
			coEmployer.classList.add('em-hidden');
		}

		switch (e.target.value) {

			case 'Fast ansatt (privat)':
			case 'Fast ansatt (offentlig)': 
			case 'Midlertidig ansatt/vikar': 
			case 'Selvst. næringsdrivende': 
			case 'Langtidssykemeldt': show(); break;

			default: hide();

		}

	});


	// co applicant since
	listEvents(coEmpSince);

	// co applicant employer
	textEvents(coEmployer, /[^0-9a-xøæåA-XØÆÅ]/);

	// co applicant education
	listEvents(qs('.em-i-co_applicant_education'));


	// co applicant norwegian
  	var coNorYes = qs('.em-cc-co_applicant_norwegian .em-cc-yes');
	var coNorNo = qs('.em-cc-co_applicant_norwegian .em-cc-no');
	checkEvents(
			coNorYes,
			coNorNo,
			qs('.em-cc-co_applicant_norwegian .em-c')
		);

	coNorYes.addEventListener('click', function() { 
			hideArr([
				qs('.em-element-co_applicant_years_in_norway'),
				qs('.em-element-co_applicant_country_of_origin')
			])
		}
	);

	coNorNo.addEventListener('click', function() { 
			showArr([
				qs('.em-element-co_applicant_years_in_norway'),
				qs('.em-element-co_applicant_country_of_origin')
			])
		}
	);

	// co applicant years in norway 
	listEvents(qs('.em-i-co_applicant_years_in_norway'));

	// co applicant country of origin
	listEvents(qs('.em-i-co_applicant_country_of_origin'));

	// CO APPLICANT INCOME
	numberEvents({node: qs('.em-i-co_applicant_income'), currency: true});

	// unsecured_debt_balance
	numberEvents({node: qs('.em-i-unsecured_debt_balance'), currency: true});

	// account_number
	textEvents(qs('.em-i-account_number'), /[^0-9. ]/);

	qs('.em-i-account_number').addEventListener('input', function(e) {

		var v = e.target.value;

		v = v.replace(/[^0-9]/g, '');

		if (v.length > 6) v = v.substr(0, 4) + '.' + v.substr(4, 2) + '.' + v.substr(6, 5);
		else if (v.length > 4) v = v.substr(0, 4) + '.' + v.substr(4, 2);

		e.target.value = v;

	});

	qs('.em-i-account_number').addEventListener('focusout', function(e) {

		val({
			callback: 'accountNumber',
			value: e.target.value,
			feedbackNode: e.target.parentNode.parentNode,
			errorColor: errorColor
		});


	});


	payment();
})();