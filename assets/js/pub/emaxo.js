!(function() {
	var counter = 1;

	// next button
	var nextButton = document.querySelector(".em-b-submit");
	// prev button 
	var prevButton = document.querySelector(".em-b-back");

	// var previousPart = null;
	var currentPart = document.querySelector(".part-"+counter);
	// var nextPart = null;

	var getNext = function() {
		counter++;
		currentPart = document.querySelector(".part-"+counter)
		if (counter == 3) nextButton.style.display = "none";
		else {
			nextButton.style.display = "inline-block";
			prevButton.style.display = "inline-block";
		}
		return currentPart;
	}

	var getPrev = function() {
		counter--;
		currentPart = document.querySelector(".part-"+counter)

		if (counter == 1) prevButton.style.display = "none";
		else { 
			nextButton.style.display = "inline-block";
			prevButton.style.display = "inline-block";
		}
		return currentPart;
	}

	nextButton.addEventListener("click", function() {
		currentPart.style.display = "none";
		
		getNext().style.display = "grid";
	});


	prevButton.addEventListener("click", function() {
		currentPart.style.display = "none";
		
		getPrev().style.display = "grid";
	});

	// LOAN AMOUNT
	var loanRange = document.querySelector('.em-r-loan_amount');
	var loanText = document.querySelector('.em-i-loan_amount');

	// fixing initial value to locale
	loanText.value = parseInt(loanText.value).toLocaleString('nb-NO', {style: 'currency', currency: 'NOK', minimumFractionDigits: 0});

	// when clicking the input
	loanText.addEventListener('focus', function(e) { loanText.value = loanText.value.replace(/[^0-9]/g, ''); loanText.select(); });

	// exit the input if hitting enter
	loanText.addEventListener('keypress', function(e) { if (e.keyCode == 13) loanText.blur(); });

	// when exiting the input 
	loanText.addEventListener('focusout', function(e) { 
		var n = e.target.value.replace(/[^0-9]/g, '');

		if (n < 10000) n = 10000;
		else if (n > 500000) n = 500000;

		loanText.value = parseInt(n).toLocaleString('nb-NO', {style: 'currency', currency: 'NOK', minimumFractionDigits: 0});
		loanRange.value = n;
	});

	// setting the range input while typing in text input
	loanText.addEventListener('input', function(e) { loanRange.value = e.target.value });

	// setting the text input while changing the range input
	loanRange.addEventListener('input', function(e) { loanText.value = parseInt(e.target.value).toLocaleString('nb-NO', {style: 'currency', currency: 'NOK', minimumFractionDigits: 0}) });


	// TENURE
	var tenureText = document.querySelector('.em-i-tenure');
	var tenureRange = document.querySelector('.em-r-tenure');

	tenureText.value += ' år';

	tenureText.addEventListener('focus', function(e) { tenureText.value = tenureText.value.replace(/[^0-9]/g, ''); tenureText.select(); });
	tenureText.addEventListener('keypress', function(e) { if (e.keyCode == 13) tenureText.blur(); });
	tenureText.addEventListener('focusout', function(e) { 
		var n = e.target.value.replace(/[^0-9]/g, '');

		if (n < 1) n = 1;
		else if (n > 15) n = 15;

		tenureText.value = n + ' år'; 
	});

	tenureText.addEventListener('input', function(e) { tenureRange.value = e.target.value.replace(/[^0-9]/g, '')});
	tenureRange.addEventListener('input', function(e) { tenureText.value = e.target.value + ' år'});


	// mobile number
	var mobileText = document.querySelector('.em-i-mobile_number');

	// typing
	mobileText.addEventListener('input', function(e) {

		var temp = e.target.value.replace(/[^0-9]/g, '');
		mobileText.value = temp;

		if (e.target.value.length > 8) mobileText.value = temp.substr(0, 8);
	});

	// on focus
	mobileText.addEventListener('focus', function() { mobileText.select(); });

	// lost focus - do validation
	mobileText.addEventListener('focusout', function(e) {
		console.log('test');
	});

	// pressing enter
	mobileText.addEventListener('keypress', function(e) { if (e.keyCode == 13) mobileText.blur(); });

	// email
	var e = document.querySelector('.em-i-email');

	// when typing
	e.addEventListener('input', function(e) {
		if (e.target.value.length > 50) e.target.style.fontSize = '14px';
		else if (e.target.value.length > 40) e.target.style.fontSize = '16px';
		else if (e.target.value.length > 30) e.target.style.fontSize = '18px';
		else e.target.style.fontSize = '24px';
	});

	
})();