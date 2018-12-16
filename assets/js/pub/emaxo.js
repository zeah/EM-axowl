!(function() {
	var counter = 1;

	var min = 10000;
	var max = 500000;

	var min_year = 1;
	var max_year = 15;
	var postfix_year = ' år';

	var bgColor = 'hsl(270, 50%, 72%)';
	var errorColor = 'hsl(0, 100%, 50%)';
	var validColor = 'hsl(120, 100%, 50%)';

	// next button
	var nextButton = document.querySelector(".em-b-submit");
	// prev button 
	var prevButton = document.querySelector(".em-b-back");

	var currentPart = document.querySelector(".part-"+counter);

	var validator = {
		email: function(e) { return e.match(/.+?@.+?\..+/) },
		
		phone: function(e) { 
			e = e.replace(/[^0-9]/, '');

			if (e.length != 8) return false;

			return true;
		},
	
		amount: function(e) {
			e = parseInt(e);

			if (e < 10000) return false;
			if (e > 500000) return false;

			return true;
		},

		tenure: function(e) {
			e = parseInt(e);

			if (e < 1) return false;
			if (e > 15) return false;

			return true;
		}
	}

	var val = function(o = {}) {

		if (typeof validator[o.callback] !== 'function') { 
			console.error('Validator.'+o.callback+' is not a function.');
			return false; 
		}

		if (!validator[o.callback](o.value)) {
			if (o.feedbackNode && o.errorColor) o.feedbackNode.style.backgroundColor = o.errorColor;
			return false;
		}

		else if (o.feedbackNode) {
			if (o.validColor) o.feedbackNode.style.backgroundColor = o.validColor;
			else if (bgColor) o.feedbackNode.style.backgroundColor = bgColor;
		}

		return true;
	}

	var nextPage = function() {
		var cPage = document.querySelector('.part-'+counter);

		counter++;
		var page = document.querySelector('.part-'+counter);
		var nextPage = document.querySelector('.part-'+(counter+1));

		if (!nextPage) nextButton.style.display = 'none';
		else nextButton.style.display = 'inline-block';

		if (!page) return false;

		prevButton.style.display = 'inline-block';

		cPage.style.display = 'none';
		page.style.display = 'grid';
	}

	var prevPage = function() {
		var cPage = document.querySelector('.part-'+counter);

		counter--;
		var page = document.querySelector('.part-'+counter);
		var prevPage = document.querySelector('.part-'+(counter-1));
		console.log(prevPage);

		if (!prevPage) prevButton.style.display = 'none';
		else prevButton.style.display = 'inline-block';

		if (!page) return false;

		nextButton.style.display = 'inline-block';

		cPage.style.display = 'none';
		page.style.display = 'grid';
	}

	// // showing next page
	// var getNext = function() {
	// 	counter++;
	// 	currentPart = document.querySelector(".part-"+counter)
	// 	if (counter == 3) nextButton.style.display = "none";
	// 	else {
	// 		nextButton.style.display = "inline-block";
	// 		prevButton.style.display = "inline-block";
	// 	}
	// 	return currentPart;
	// }


	// // showing previous page
	// var getPrev = function() {
	// 	counter--;
	// 	currentPart = document.querySelector(".part-"+counter)

	// 	if (counter == 1) prevButton.style.display = "none";
	// 	else { 
	// 		nextButton.style.display = "inline-block";
	// 		prevButton.style.display = "inline-block";
	// 	}
	// 	return currentPart;
	// }


	// next page button
	nextButton.addEventListener("click", function() {
		nextPage();
		// currentPart.style.display = "none";
		
		// getNext().style.display = "grid";
	});

	// previous page button
	prevButton.addEventListener("click", function() {
		prevPage();
		// currentPart.style.display = "none";
		
		// getPrev().style.display = "grid";
	});








	// LOAN AMOUNT
	var loanRange = document.querySelector('.em-r-loan_amount');
	var loanText = document.querySelector('.em-i-loan_amount');

	// fixing initial value to locale
	loanText.value = parseInt(loanText.value).toLocaleString(
		'nb-NO', 
		{
			style: 'currency', 
			currency: 'NOK',
			minimumFractionDigits: 0
		});

	// hitting enter to exit input
	loanText.addEventListener('keypress', function(e) { if (e.keyCode == 13) loanText.blur() });

	// typing
	loanText.addEventListener('input', function(e) {

		// removes all but numbers
		var v = e.target.value.replace(/[^0-9]/g, '');
	
		if (v == '') v = '';

		else if (v > max) v = max;
	
		loanText.value = v; 
		loanRange.value = v; 

	});

	// focus
	loanText.addEventListener('focus', function(e) { loanText.value = loanText.value.replace(/[^0-9]/g, ''); loanText.select(); });

	// focus out (validation time)
	loanText.addEventListener('focusout', function(e) {

		// removes all but numbers
		var n = e.target.value.replace(/[^0-9]/g, '');

		if (n < min) n = min;
		else if (n > max) n = max;

		loanText.value = parseInt(n).toLocaleString(
			'nb-NO', 
			{
				style: 'currency', 
				currency: 'NOK', 
				minimumFractionDigits: 0
			});

		loanRange.value = n;
	});


	// LOAN RANGE 
	// setting the text input while changing the range input
	loanRange.addEventListener('input', function(e) { 
		loanText.value = parseInt(e.target.value).toLocaleString(
			'nb-NO', 
			{ 
				style: 'currency', 
			  	currency: 'NOK', 
			 	minimumFractionDigits: 0
			}); 
	});








	// TENURE
	var tenureText = document.querySelector('.em-i-tenure');
	var tenureRange = document.querySelector('.em-r-tenure');

	// fixing initial value
	tenureText.value += postfix_year;

	// pressing enter
	tenureText.addEventListener('keypress', function(e) { if (e.keyCode == 13) tenureText.blur() });

	// typing
	tenureText.addEventListener('input', function(e) { 
		var v = e.target.value.replace(/[^0-9]/g, '');
	
		if (v == '') v = '';

		else if (v > max_year) v = max_year;
	
		else if (v < min_year) v = min_year;
	
		tenureText.value = v; 
		tenureRange.value = v; 
	});

	// focus
	tenureText.addEventListener('focus', function(e) { 
		tenureText.value = tenureText.value.replace(/[^0-9]/g, '');
		tenureText.select(); 
	});


	// focus lost (validation time)
	tenureText.addEventListener('focusout', function(e) { 
		var n = e.target.value.replace(/[^0-9]/g, '');

		if (n < min_year) n = min_year;
		else if (n > max_year) n = max_year;

		tenureText.value = n + postfix_year; 
	});

	// TENURE RANGE
	tenureRange.addEventListener('input', function(e) { tenureText.value = e.target.value + postfix_year});









	// MOBILE NUMBER
	var mobileText = document.querySelector('.em-i-mobile_number');

	// pressing enter
	mobileText.addEventListener('keypress', function(e) { if (e.keyCode == 13) mobileText.blur() });

	// typing
	mobileText.addEventListener('input', function(e) {

		var temp = e.target.value.replace(/[^0-9]/g, '');
		mobileText.value = temp;

		if (e.target.value.length > 8) mobileText.value = temp.substr(0, 8);
	});

	// on focus
	mobileText.addEventListener('focus', function() { mobileText.select(); });

	// lost focus (validation time)
	mobileText.addEventListener('focusout', function(e) {
		val({
			callback: 'phone',
			value: e.target.value,
			errorColor: errorColor,
			feedbackNode: e.target.parentNode.parentNode
		})
	});








	// EMAIL
	var emailText = document.querySelector('.em-i-email');

	// hitting enter will exit the input
	emailText.addEventListener('keypress', function(e) { if (e.keyCode == 13) emailText.blur() });

	// typing
	emailText.addEventListener('input', function(e) {

		// setting size of font so long email addresses fits better
		if (e.target.value.length > 50) e.target.style.fontSize = '14px';
		else if (e.target.value.length > 40) e.target.style.fontSize = '16px';
		else if (e.target.value.length > 30) e.target.style.fontSize = '18px';
		else e.target.style.fontSize = '24px';
	});

	// on focus
	emailText.addEventListener('focus', function() { emailText.select() });	

	// lost focus (validation time)
	emailText.addEventListener('focusout', function(e) { 
		val({
			callback: 'email', 
			value: e.target.value, 
			errorColor: errorColor, 
			feedbackNode: e.target.parentNode.parentNode
		});
	});



	// EMPLOYMENT TYPE
	var employmentType = document.querySelector('.em-i-employment_type');
	var employmentSince = document.querySelector('.em-element-employment_since');
	var employer = document.querySelector('.em-element-employer');

	employmentType.addEventListener('change', function(e) {

		if (e.target.value) {
			employmentSince.classList.remove('em-hidden');
			employer.classList.remove('em-hidden');
		}
		else {
			employmentSince.classList.add('em-hidden');
			employer.classList.add('em-hidden');
		}
	});





	
})();