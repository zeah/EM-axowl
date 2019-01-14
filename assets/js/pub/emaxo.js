
(function() {

"use strict";

	/**
	 * helper function for getting element and adding validation 
	 * @param  {String} e class, id or name
	 * @param  {String} v validation function
	 * @return {HTML element}   html element
	 */
	var qs = function(e) { 
		// get element
		var t = document.querySelector(e);

		// if element not found
		if (!t) return null;

		return t;
	}

	var current = qs('.em-part');


	var kroner = function(n) {
		if (!n) return '';

		n = String(n).replace(/[^0-9]/g, '');

		if (n == '' || !n) return '';

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

	var numb = function(n) { return n.replace(/[^0-9]/g, '') }

	var payment = function() {
		var i = '0.068'/12;
		try { 
			var p = numb(qs('.em-i-loan_amount').value);
			var n = numb(qs('.em-i-tenure').value)*12;

			qs('.em-if-monthly_cost').value = kroner(Math.floor(p / ((1 - Math.pow(1 + i, -n)) / i))) 
		} catch (e) { console.error('Cost calculation: '+e) }
	};

	payment();



	var val = {

		numbersOnly: function(d) {

			if (/^\d+$/.test(d)) return true;

			return false
		},

		textOnly: function(d) {

			return true;

		},

		list: function(d) {
			if (!d) return false;

			return true;
		},

		phone: function(d) {

			var n = val.numbersOnly(d);
			// console.log('hi '+n);

			if (!n) return false;

			if (d.length == 8) return true;


			// console.log('true');
			return false;
		},

		socialnumber: function(d) {

			var n = val.numbersOnly(d);

			if (!n) return false;

			if (d.length == 11) return true;

			return false;

		},

		email: function(d) {

			return /.+@.+\..+/.test(d);

		},

		name: function(d) {
			
		},

		currency: function(d) {

			d = d.replace(/\s/g, '');
			d = d.replace(/kr/, '');

			return val.numbersOnly(d);
		},

		ar: function(d) {
			d = d.replace(/\s/g, '');
			d = d.replace(/år/, '');

			return val.numbersOnly(d);
		},

		notEmpty: function(d) {
			if (d.length > 0) return true;
			return false;
		}

	}

	var v = function(e, format, valid) {
		try { 

			var data = e.value;
			var pa = e.parentNode.parentNode;

			// removing postfix
			if (format && format.indexOf('postfix:') -1) {
				var temp = format.replace('postfix:', '');

				data = e.value.replace(temp, '');
			}

			// validating
			if (!val[valid](data)) {
				pa.style.backgroundColor = 'red';
				return false;
			} 
			
			else { 
				pa.style.backgroundColor = 'transparent'; 
				return true;
			}
		}

		catch (e) { console.error('Error during validation: '+e) }
	}


	var blur = function(e) {
		var l = current.querySelectorAll('.em-element-container');

		for (var i = 0; i < l.length; i++) 
			if (l[i] != e.target.parentNode.parentNode) l[i].style.opacity = '.1';
	}

	var unblur = function() {
		var l = current.querySelectorAll('.em-element-container');

		for (var i = 0; i < l.length; i++)
			l[i].style.opacity = 1;
	}


	var progress = function() {

	}


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
				var digits = n.getAttribute('data-digits') ? parseInt(n.getAttribute('data-digits')) : '';

				// hitting enter
				n.addEventListener('keypress', function(e) { if (e.keyCode == 13) e.target.blur() });

				// if input has a max attribute
				if (max) n.addEventListener('input', function(e) {
					if (max < numb(e.target.value))
						e.target.value = max;
				});


				if (digits) n.addEventListener('input', function(e) {

					if (e.target.value.length > digits) e.target.value = e.target.value.slice(0, -1)

				});

				// if input has a min attribute
				if (min) n.addEventListener('focusout', function(e) {
					if (min > numb(e.target.value)) {

						// formating currency or not
						if (format == 'currency') e.target.value = kroner(min);
						else e.target.value = min;

					}
				});


				// formating currency
				if (format == 'currency') {
					n.value = kroner(n.value);

					n.addEventListener('focus', function(e) { e.target.value = numb(e.target.value) });
					n.addEventListener('focusout', function(e) { e.target.value = kroner(e.target.value) });
				}

				// numbers only
				switch (valid) {
					case 'currency':
					case 'numbersOnly':
					case 'phone':
					case 'ar':
					case 'socialnumber': n.addEventListener('input', function(e) { e.target.value = numb(e.target.value) });
				}

				// formatting with postfix
				if (format.indexOf('postfix:') != -1) {
					var pf = format.replace('postfix:', '');

					n.value = n.value.replace(/[^0-9]/g, '') + pf;

					n.addEventListener('focusout', function(e) { e.target.value = numb(e.target.value) + pf });

					n.addEventListener('focus', function(e) { e.target.value = numb(e.target.value )});
				}


				// selecting all text when focusing input
				n.addEventListener('focus', function(e) { 
					e.target.select(); 
					blur(e);
				});

				n.addEventListener('focusout', function(e) { unblur(); });

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
				if (valid) {
					n.addEventListener('input', function(e) { v(e.target, format, valid) });
					n.addEventListener('focusout', function(e) { v(e.target, format, valid) });
				}

				// SPECIAL RULES
				switch (n.classList[1]) {
					case 'em-i-tenure':
					case 'em-i-loan_amount': 
						n.addEventListener('input', function(e) { payment() });
						n.addEventListener('focusout', function(e) { payment() });
						break;

					case 'em-i-email':
						n.addEventListener('input', function(e) {
							var l = e.target.value.length;
							var s = function(p) { e.target.style.fontSize = p }

							if (l > 10) s('18px');
							if (l > 20) s('16px');
							if (l > 30) s('14px');
							if (l > 40) s('12px');

						});
						break;
				}


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

				switch (r.classList[1]) {
					case 'em-r-tenure':
					case 'em-r-loan_amount': r.addEventListener('input', function(e) {
						payment();
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
		var lists = document.querySelectorAll('.emowl-form select');

		for (var i = 0; i < lists.length; i++) {

			(function() {

				var n = lists[i];
				var val = n.getAttribute('data-val');

				if (val) n.addEventListener('input', function(e) { unblur(); v(e.target, null, val)});

				n.addEventListener('focus', function(e) { blur(e) });
				// n.addEventListener('focusout', function(e) { unblur() });


				var show = function(o) {
					try {
						for (var i = 0; i < o.length; i++) 
							qs(o[i]).classList.remove('em-hidden');
						
					} catch (e) {}
				}

				var hide = function(o) {
					try {
						for (var i = 0; i < o.length; i++) 
							qs(o[i]).classList.add('em-hidden');
						
					} catch (e) {}
				}

				switch (n.classList[1]) {

					case 'em-i-education':
						n.addEventListener('change', function(e) {
							switch (e.target.value) {
								case 'Høysk./universitet 1-3 år':
								case 'Høysk./universitet 4+år': show(['.em-element-education_loan']); break;
								default: hide(['.em-element-education_loan']);
							}
						})
						break;

					case 'em-i-employment_type':
						n.addEventListener('change', function(e) {
							switch (e.target.value) {
								case 'Fast ansatt (privat)':
								case 'Fast ansatt (offentlig)':
								case 'Midlertidig ansatt/vikar':
								case 'Selvst. næringsdrivende':
								case 'Langtidssykemeldt': show(['.em-element-employment_since', '.em-element-employer']); break;
								default: hide(['.em-element-employment_since', '.em-element-employer']);
							}
						});
						break;

					case 'em-i-civilstatus':
						n.addEventListener('change', function(e) {
							switch (e.target.value) {
								case 'Gift/partner':
								case 'Samboer':
									try {
										if (qs('.em-c-co_applicant').value === '0') show(['.em-element-spouse_income']);
										else hide(['.em-element-spouse_income']);
									} catch (e) {}
									break;

								default: hide(['.em-element-spouse_income']);
							}
						});
						break;

					case 'em-i-living_conditions':
						n.addEventListener('change', function(e) {
							switch (e.target.value) {
								case 'Leier':
								case 'Bor hos foreldre': show(['.em-element-rent']); hide(['.em-element-rent_income', '.em-element-mortgage']); break;
								
								case 'Aksje/andel/borettslag':
								case 'Selveier': show(['.em-element-rent', '.em-element-rent_income', '.em-element-mortgage']); break;

								case 'Enebolig': show(['.em-element-rent_income', '.em-element-mortgage']); hide(['.em-element-rent']); break;

								default: hide(['.em-element-rent', '.em-element-rent_income', '.em-element-mortgage']);
							}
						});
						break;

					case 'em-i-co_applicant_employment_type':
						n.addEventListener('change', function(e) {
							switch (e.target.value) {
								case 'Fast ansatt (privat)':
								case 'Fast ansatt (offentlig)':
								case 'Midlertidig ansatt/vikar':
								case 'Selvst. næringsdrivende':
								case 'Langtidssykemeldt': show(['.em-element-co_applicant_employment_since', '.em-element-co_applicant_employer']); break;
								default: hide(['.em-element-co_applicant_employment_since', '.em-element-co_applicant_employer']);
							}
						});
						break;

				}

			})();
		}




		// NEXT/PREV/SUBMIT BUTTONS
		try {
			qs('.em-b-next').addEventListener('click', function(e) {


				var test = current.querySelectorAll('.em-i');

				var success = true;

				for (var i = 0; i < test.length; i++) {

					(function() {

						var n = test[i];


						if (n.parentNode.parentNode.classList.contains('em-hidden'))
							return;

						if (n.parentNode.parentNode.parentNode.classList.contains('em-hidden'))
							return;


						if (n.getAttribute('data-val')) {

							var val = n.getAttribute('data-val');

							var f = n.getAttribute('format');

							var ver = v(n, null, val);

							if (!ver) success = false;
						}

					})();

				}

				if (!success) {
					success = true;
					return;
				}
				
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

		// back button
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

		// console.log('hi');

		// helper text
		var hm = document.querySelectorAll('.em-ht-q');
		for (var i = 0; i < hm.length; i++) 
			(function() { 
				var q = hm[i];
				var p = q.parentNode;

				q.addEventListener('mouseover', function(e) {
					try { p.querySelector('.em-ht').classList.remove('em-hidden');
					} catch (e) {}
				});

				q.addEventListener('mouseout', function(e) {
					try { p.querySelector('.em-ht').classList.add('em-hidden')
					} catch (e) {}
				});

				q.addEventListener('click', function(e) {
					try { p.querySelector('.em-ht').classList.toggle('em-hidden')
					} catch (e) {}

				});
			})();
		
		// console.log('hi');

		// progress bar
		var il = document.querySelectorAll('.em-i');
		for (var i = 0; i < il.length; i++) (function() {
			switch (il[i].tagName) {
				case 'INPUT':
				case 'SELECT': break;
				default: return;
			}

			il[i].addEventListener('focusout', function(e) { progress() });
		})();

	} // end of init


	init();

})();