(function() {

	/**
	 * helper function for getting element and adding validation 
	 * @param  {String} e class, id or name
	 * @param  {String} v validation function
	 * @return {HTML element}   html element
	 */
	var qs = function(e, v = null) { 
	
		// if val is not set
		// if (!v) return document.querySelector(e);

		// get element
		var t = document.querySelector(e);

		// if element not found
		if (!t) return null;



		// add validation to element
		t.validate = function() {

		} 
	
		// returns element
		return t;
	}

	var kroner = function(n) {
		if (!n) return '';


		n = String(n).replace(/[^0-9]/g, '');

		if (n == '') return '';

		return parseInt(n).toLocaleString(
							'sv-SE', 
							// 'nb-NO', 
							{
								style: 'currency', 
								currency: 'SEK',
								// currency: 'NOK',
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
		document.querySelectorAll('.emowl-form input[type=text]').forEach(function(n) {

			var format = n.getAttribute('data-format') ? n.getAttribute('data-format') : '';

			// hitting enter
			n.addEventListener('keypress', function(e) { if (e.keyCode == 13) e.target.blur() });


			// if input has a max attribute
			if (n.getAttribute('max')) n.addEventListener('input', function(e) {
				if (parseInt(n.getAttribute('max')) < numb(e.target.value))
					e.target.value = n.getAttribute('max');
			});


			// if input has a min attribute
			if (n.getAttribute('min')) n.addEventListener('focusout', function(e) {
				if (parseInt(n.getAttribute('min')) > numb(e.target.value)) {

					// formating currency or not
					if (n.getAttribute('data-format') == 'currency') e.target.value = kroner(n.getAttribute('min'));
					else e.target.value = n.getAttribute('min');

				}
			});


			// formating currency when typing
			if (n.getAttribute('data-format') == 'currency') {
				n.value = kroner(n.value);

				n.addEventListener('focus', function(e) { e.target.value = numb(e.target.value) });
				n.addEventListener('focusout', function(e) { e.target.value = kroner(e.target.value) });
			}

			// formatting when prefix
			if (format.indexOf('postfix:') != -1) {
				var pf = format.replace('postfix:', '');

				n.value = n.value + pf;

				n.addEventListener('focusout', function(e) { e.target.value = numb(e.target.value) + pf });

				n.addEventListener('focus', function(e) { e.target.value = numb(e.target.value )});
			}


			// selecting all text when focusing input
			n.addEventListener('focus', function(e) { e.target.select() });


			// if parent has range input
			n.parentNode.parentNode.querySelectorAll('input[type=range]').forEach(function(r) {
				n.addEventListener('input', function(e) {
					r.value = numb(e.target.value);
				})
			})


			// validation
			if (n.getAttribute('data-val')) n.addEventListener('focusout', function(e) {

				try { 

					var data = e.target.value;

					if (format.indexOf('postfix:') -1) {
						var temp = format.replace('postfix:', '');

						data = e.target.value.replace(temp, '');
					}

					if (!val[n.getAttribute('data-val')](data)) 
						 e.target.parentNode.parentNode.style.backgroundColor = 'red'; 
					else e.target.parentNode.parentNode.style.backgroundColor = 'transparent'; 
				}

				catch (e) { console.error('Error during validation: '+e) }

			});

		});

		// RANGE INPUTS
		document.querySelectorAll('.emowl-form input[type=range]').forEach(function(r) {

			r.parentNode.querySelectorAll('input[type=text]').forEach(function(n) {

				r.addEventListener('input', function(e) {

					var a = n.getAttribute('data-format');

					if (a == 'currency') n.value = kroner(e.target.value);

					else if (a.indexOf('postfix:') != -1) 
						n.value = e.target.value+a.replace('postfix:', '');

					else n.value = e.target.value;
				});

			});

		});


		// CHECK INPUTS

	}


	init();

})();