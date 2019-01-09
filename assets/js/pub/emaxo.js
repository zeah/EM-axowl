
(function() {

"use strict";

	/**
	 * helper function for getting element and adding validation 
	 * @param  {String} e class, id or name
	 * @param  {String} v validation function
	 * @return {HTML element}   html element
	 */
	var qs = function(e) { 
	
		// if val is not set
		// if (!v) return document.querySelector(e);

		// get element
		var t = document.querySelector(e);

		// if element not found
		if (!t) return null;

		return t;

		// add validation to element
		// t.validate = function() {

		// } 
	
	// 	// returns element
	// 	return t;
	}

	var current = qs('.em-part');

	var kroner = function(n) {
		if (!n) return '';


		n = String(n).replace(/[^0-9]/g, '');

		if (n == '') return '';

		return parseInt(n).toLocaleString(
							// 'sv-SE', 
							'nb-NO', 
							{
								style: 'currency', 
								// currency: 'SEK',
								currency: 'NOK',
								minimumFractionDigits: 0
							});
	}

	var numb = function(n) { return parseInt(n.replace(/[^0-9]/g, '')) }

	var val = {

		numbersOnly: function(d) {
			// testing number input
			if (/^[0-9\s]+$/.test(d)) return true;

			// testing NOK currency input
			if (/^kr\s\d+\s\d+$/.test(d)) return true;
			
			// testing SEK currency input
			if (/^\d+\s\d+\skr$/.test(d)) return true;

		},

		textOnly: function(d) {

		},

		phone: function(d) {

			var n = val.numbersOnly(d);

			if (n.length == 8) return true;

			return false;
		},

		email: function(d) {

		},

		name: function(d) {
			
		}


	}


	// container for parts and inputs
	// var P = {

	// }

	var init = function() {

		// TEXT INPUTS
		var textInput = document.querySelectorAll('.emowl-form input[type=text]');

		for (var i = 0; i < textInput.length; i++) {

			// scoping for events
			(function() { 
				var n = textInput[i];

				var format = n.getAttribute('data-format') ? n.getAttribute('data-format') : '';
				var min = n.getAttribute('min') ? parseInt(n.getAttribute('min')) : '';
				var max = n.getAttribute('max') ? parseInt(n.getAttribute('max')) : '';
				var valid = n.getAttribute('data-val') ? n.getAttribute('data-val') : '';

				// hitting enter
				n.addEventListener('keypress', function(e) { if (e.keyCode == 13) e.target.blur() });

				// if input has a max attribute
				if (max) n.addEventListener('input', function(e) {
					if (max < numb(e.target.value))
						e.target.value = max;
				});


				// if input has a min attribute
				if (min) n.addEventListener('focusout', function(e) {
					if (min > numb(e.target.value)) {

						// formating currency or not
						if (format == 'currency') e.target.value = kroner(min);
						else e.target.value = min;

					}
				});


				// formating currency when typing
				if (format == 'currency') {
					n.value = kroner(n.value);

					n.addEventListener('focus', function(e) { e.target.value = numb(e.target.value) });
					n.addEventListener('focusout', function(e) { e.target.value = kroner(e.target.value) });
				}

				// formatting with postfix
				if (format.indexOf('postfix:') != -1) {
					var pf = format.replace('postfix:', '');

					n.value = n.value + pf;

					n.addEventListener('focusout', function(e) { e.target.value = numb(e.target.value) + pf });

					n.addEventListener('focus', function(e) { e.target.value = numb(e.target.value )});
				}


				// selecting all text when focusing input
				n.addEventListener('focus', function(e) { e.target.select() });


				// if parent has range input
				var innerRange = n.parentNode.parentNode.querySelectorAll('input[type=range]');
				for (var j = 0; j < innerRange.length; j++) {
					// scoping for events
					(function() {
						var r = innerRange[j];
						n.addEventListener('input', function(e) {
							r.value = numb(e.target.value);
						});
					})();
				}


				// validation
				if (valid) n.addEventListener('focusout', function(e) {

					try { 

						var data = e.target.value;

						if (format.indexOf('postfix:') -1) {
							var temp = format.replace('postfix:', '');

							data = e.target.value.replace(temp, '');
						}

						if (!val[valid](data)) 
							 e.target.parentNode.parentNode.style.backgroundColor = 'red'; 
						else e.target.parentNode.parentNode.style.backgroundColor = 'transparent'; 
					}

					catch (e) { console.error('Error during validation: '+e) }

				});
			})();
		}
		

		// RANGE INPUTS
		var rangeInput = document.querySelectorAll('.emowl-form input[type=range]');

		for (var i = 0; i < rangeInput.length; i++) {

			(function() { 
				var r = rangeInput[i];

				var innerText = r.parentNode.querySelectorAll('input[type=text]');

				for (var j = 0; j < innerText.length; j++) {
					var n = innerText[j];

					r.addEventListener('input', function(e) {

						var a = n.getAttribute('data-format');

						if (a == 'currency') n.value = kroner(e.target.value);

						else if (a.indexOf('postfix:') != -1) 
							n.value = e.target.value+a.replace('postfix:', '');

						else n.value = e.target.value;
					});
				}
			})();
		}


		// CHECK INPUTS
		var checkInput = document.querySelectorAll('.em-cc');

		for (var i = 0; i < checkInput.length; i++) {
			(function() {
				var c = checkInput[i];

				var yes = c.querySelector('.em-cc-yes');
				var no = c.querySelector('.em-cc-no');
				var input = c.querySelector('.em-c');

				var show = input.getAttribute('data-show');


				yes.addEventListener('click', function(e) {

					input.value = 1;

					if (show) {
						var c = show.replace(/^(yes:\s?)|(no:\s?)/, '');

						var temp = qs('.'+c);

						if (show.indexOf('no:') != -1) temp.classList.add('em-hidden');
						else temp.classList.remove('em-hidden');

					}

					yes.classList.add('em-cc-green');
					no.classList.remove('em-cc-green');


				});

				no.addEventListener('click', function(e) {

					input.value = '';

					if (show) {
						var c = show.replace(/^(yes:\s?)|(no:\s?)/, '');

						var temp = qs('.'+c);

						if (show.indexOf('no:') != -1) temp.classList.remove('em-hidden');
						else temp.classList.add('em-hidden');
						
					}

					yes.classList.remove('em-cc-green');
					no.classList.add('em-cc-green');

				});

			})();
		}


		// LIST INPUTS


		// NEXT/PREV/SUBMIT BUTTONS
		try {
			qs('.em-b-next').addEventListener('click', function(e) {
				
				// hiding current part
				current.style.display = 'none';

				// showing next part
				current.nextSibling.style.display = 'grid';

				// showing prev button
				try {
					qs('.em-b-back').classList.remove('em-hidden');
				} catch (e) {}

				// replace next button with submit button if no more parts
				if (!current.nextSibling.nextSibling) {

					e.target.classList.add('em-hidden');

					try {
						qs('.em-b-submit').classList.remove('em-hidden');
					} catch (e) {}
				}

				current = current.nextSibling;

			});
		} catch (e) {}

		try {
			qs('.em-b-back').addEventListener('click', function(e) {

				current.style.display = 'none';

				current.previousSibling.style.display = 'grid';

				if (!current.previousSibling.previousSibling)
					e.target.classList.add('em-hidden');

				try {
					qs('.em-b-next').classList.remove('em-hidden');
					qs('.em-b-submit').classList.add('em-hidden');
				} catch (e) {}

				current = current.previousSibling;
			});
		} catch (e) {}




	}


	init();

})();