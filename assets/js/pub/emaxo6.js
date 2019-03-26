/**
 * INDEX
 * qs(s) helper function: document.querySelector
 * qsa(s) helper function: document.querySelectorAll
 *
 * var current: current part of form showing
 * var isIE: whether browser is internet explorer or not
 *
 * kroner(v) : converts value to currency
 * numb(v) : converts value to number
 * payment() : updates monthly cost field
 * val{v} : validator
 * v(v) : validator with visual feedback
 * progress() : updates progressbar when fields with validation is filled
 *
 * init() initializes all event listeners
 * 
 */


(function($) {

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

	var qsa = function(e) {
		var t = document.querySelectorAll(e);

		if (!t) return [];

		return t;
	}

	var isHidden = function(n) {
		try {

			var p = n.parentNode.parentNode;

			if (p.classList.contains('em-hidden')) return true;

			if (p.parentNode.classList.contains('em-hidden')) return true;

		} catch (e) { console.error(e); return false; }


		return false;
	}

	var current = qs('.em-part');

	var isIE = !!navigator.userAgent.match(/Trident/g) || !!navigator.userAgent.match(/MSIE/g);

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

	var cost = function(i) {
		i = i / 12;

		var p = numb(qs('.em-i-loan_amount').value);
		var n = numb(qs('.em-i-tenure').value)*12;
		return Math.floor(p / ((1 - Math.pow(1 + i, -n)) / i))
	}

	var payment = function() {
		// var i = 0.0681/12;
		try { 
			var p = numb(qs('.em-i-loan_amount').value);
			var n = numb(qs('.em-i-tenure').value)*12;

			// var cost = Math.floor(p / ((1 - Math.pow(1 + i, -n)) / i));

			$('.em-if-monthly_cost').val(kroner(cost(0.0681)));
			$('.em-compare-amount').html(p);

			$('.em-compare-kk').html(cost(0.22));
			$('.em-compare-monthly').html(cost(0.0681));
			$('.em-compare-tenure').html(numb($('.em-i-tenure').val()));

			// console.log('kk: '+parseInt($('.em-compare-kk').html()));
			// console.log('cost: '+parseInt(numb($('.em-if-monthly_cost').val())));

			$('.em-compare-save').html(parseInt($('.em-compare-kk').html()) - parseInt(numb($('.em-if-monthly_cost').val())));
			// qs('.em-if-monthly_cost').value = kroner(cost);



		} catch (e) { console.error('Cost calculation: '+e) }
	};

	var val = {
		numbersOnly: function(d) {
			if (/^\d+$/.test(d)) return true;
			return false
		},

		textOnly: function(d) { return true	},

		list: function(d) {
			if (!d) return false;
			return true;
		},

		phone: function(d) {
			var n = val.numbersOnly(d);
			if (!n) return false;
			if (d.length == 8) return true;
			return false;
		},

		socialnumber: function(d) {
			var n = val.numbersOnly(d);

			if (!n) return false;

			if (d.length == 11) {
				
				// special rule
				if (d == '00000000000') return false;

				var f = d.split('');
			    
			    // first control number
			    var k1 = (11 - (((3 * f[0]) + (7 * f[1]) + (6 * f[2])
			            + (1 * f[3]) + (8 * f[4]) + (9 * f[5]) + (4 * f[6])
			            + (5 * f[7]) + (2 * f[8])) % 11)) % 11;
			    
			    // second control number
			    var k2 = (11 - (((5 * f[0]) + (4 * f[1]) + (3 * f[2])
			            + (2 * f[3]) + (7 * f[4]) + (6 * f[5]) + (5 * f[6])
			            + (4 * f[7]) + (3 * f[8]) + (2 * k1)) % 11)) % 11;
			    
			    if (k1 == 11) k1 = 0;

			    // failed validation
			    if (k1 != f[9] || k2 != f[10]) return false;
			    
			    // success
			    return true;
			}

			return false;
		},

		email: function(d) { return /.+@.+\..{2,}/.test(d) },

		name: function(d) { return true },

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
		},

		check: function(d) { return d },

		bankAccount: function(d) {
			var n = val.numbersOnly(d);
			if (!n) return false;

			if (d.length == 11) {

				var cn = [2,3,4,5,6,7];
				var cnp = -1;
				var ccn = function() {
					cnp++;
					if (cnp == cn.length) cnp = 0;
					return cn[cnp];
				}

				var control = d.toString().split('').pop();

				var c = d.substring(0, d.length-1);

				var sum = 0;
				for (var i = c.length-1; i >= 0; i--)
					sum += c[i] * ccn();


				sum = sum % 11;
				sum = 11 - sum;

				if (sum == control) return true;
			}

			return false;
		}
	}

	var v = function(e, format, valid) {
		try { 
			var data = e.value;
			var pa = e.parentNode;
			// var pa = e.parentNode.parentNode;

			// var mark = pa.querySelector('.em-val-marker');
			// removing postfix
			if (format && format.indexOf('postfix:') -1) {
				var temp = format.replace('postfix:', '');

				data = e.value.replace(temp, '');
			}

			if (e.getAttribute('type') == 'checkbox')
				data = e.checked;

			// validating
			if (!val[valid](data)) {
				// console.log(e.type);
				// if (e.type == 'checkbox') pa.style.backgroundColor = 'hsl(0, 80%, 70%)';
				// console.log(e.nextSibling.nextSibling);
				if (e.type == 'checkbox') e.nextSibling.nextSibling.style.color = 'hsl(0, 100%, 70%)';
				// console.log(e.type);
				// switch (e.type) {
				else {
					// e.style.border = "solid 3px black";
					// console.log('hey');
					// pa.querySelector('.em-marker-valid').classList.add('em-hidden');
					// pa.querySelector('.em-marker-invalid').classList.remove('em-hidden');

					e.style.border = "solid 3px hsl(0, 70%, 60%)";
					var errEl = pa.querySelector('.em-error'); 
					if (errEl) errEl.classList.remove('em-hidden');
				}

				// qs('.em-marker-valid').classList.add('em-hidden');
				// qs('.em-marker-invalid').classList.remove('em-hidden');
				// if (mark) {
					// mark.classList.remove('em-val-marker-yes');
					// mark.classList.add('em-val-marker-no');
				// }
				return false;
			} 
			
			else { 
				if (e.type == 'checkbox') e.nextSibling.nextSibling.style.color = 'hsl(0, 0%, 0%)';
				// if (e.type == 'checkbox') pa.style.backgroundColor = 'transparent';
				else {
					// pa.querySelector('.em-marker-valid').classList.remove('em-hidden');
					// pa.querySelector('.em-marker-invalid').classList.add('em-hidden');
					e.style.border = "solid 3px hsl(120, 70%, 30%)";
					
					var errEl = pa.querySelector('.em-error'); 
					if (errEl) errEl.classList.add('em-hidden');
					// pa.querySelector('.em-error').classList.add('em-hidden');
				}
				// qs('.em-marker-valid').classList.remove('em-hidden');
				// qs('.em-marker-invalid').classList.add('em-hidden');
				// pa.style.backgroundColor = 'transparent';
				// if (mark) {
					// mark.classList.remove('em-val-marker-no');
					// mark.classList.add('em-val-marker-yes');
				// }
				return true;
			}
		}

		catch (e) { console.error('Error during validation: '+e) }
	}



	var progress = function() {
		var li = qsa('.em-i:not(button)');
		// var li = document.querySelectorAll('.em-i:not(button)');

		var t = 0;
		var c = 0;

		for (var i = 0; i < li.length; i++) {
			var n = li[i];
			if (n.parentNode.parentNode.classList.contains('em-hidden')) continue;
			if (n.parentNode.parentNode.parentNode.classList.contains('em-hidden')) continue;

			var a = n.getAttribute('data-val');


			if (!a) continue;

			t++;
			try {

				var value = n.value;

				if (n.getAttribute('type') == 'checkbox') value = n.checked;
				
				if (val[a](value)) c++;
			} catch (e) { console.error(e) }	
		}

		var p = qs('.em-progress');

		p.value = (c/t) * 100 ;

		try {
			qs('.em-progress-text').innerHTML = parseInt(p.value) + '%';
		} catch (e) { }
	}


	var incomplete = function(e) {
		console.log('incomplete stopped');
		return;

		e.target.removeEventListener('click', incomplete);

		var xhttp = new XMLHttpRequest();

		xhttp.onreadystatechange = function() {
			// if (this.readyState == 4 && this.status == 200)
				// console.log(this.responseText);
		}

		var query = '';

		try {
			var email = qs('.em-i-email').value;
			var mobileNumber = qs('.em-i-mobile_number').value;
			var contactAccept = qs('.em-check-contact_accept').checked;
			
		 	if (!email && !mobileNumber) return;

		 	if (email) query += '&email='+email;
		 	if (mobileNumber) query += '&mobile_number='+mobileNumber;
		 	if (contactAccept) query += '&contact_accept='+contactAccept;

		 	var cookie = document.cookie.split('; ');
			for (var i in cookie) {
				if (cookie[i].indexOf('=') == -1) continue;

				var temp = cookie[i].split('=');
				if (temp[0] == '_ga') query += '&ga='+temp[1];
			}

		} catch (e) {}


		// sending to server
		xhttp.open('POST', emurl.ajax_url, true);
		xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhttp.send('action=wlinc'+query);
	}


	var abtesting = function() {
		var data = '';

		var ab = qs('#abtesting-name');
		if (ab) ab = ab.value;
		if (ab) data += '&abname='+ab;

		var abid = qs('#abtesting-sc');
		if (abid) abid = abid.value;
		if (abid) data += '&abid='+abid;
	
		return data;
	}

	var setCookie = function() {
		var ab = qs('#abtesting-name'); // name from wp settings
		if (ab) ab = ab.value;

		var abid = qs('#abtesting-sc'); // shortcode #
		if (abid) abid = abid.value;


		var date = new Date();
		date.setTime(date.getTime() + (90*24*60*60*1000));
		date = date.toUTCString();

		if (ab) document.cookie = 'abname='+ab+'; expires='+date;
		if (abid) document.cookie = 'abid='+abid+'; expires='+date;
	}

	// AB2
	var showFirstPagePart = function(e) {
		try {
			
			e.target.style.display = 'none';

			var el = ['.em-element-tenure', '.em-element-email', '.em-element-mobile_number',
					  '.em-element-collect_debt', '.em-b-container', '.em-element-axo_accept',
					  '.em-element-contact_accept'];


			// jQuery(el).slideDown('slow');
	        for (var i in el) {
	        	var ele = qs(el[i]);

	        	// ele.style.display = 'block';
	        	// ele.style.maxHeight = 0;
	        	// jQuery(ele).slideDown(3000);
	        	ele.classList.remove('em-hidden');
	        	ele.classList.add('em-animate-show');
	        }

	        // console.log('h');
			if (window.innerWidth > 1000) qs('.em-i-tenure').focus();

			// progress();

		} catch (e) { console.error(e) }
	}

	qs('.em-b-neste').addEventListener('click', showFirstPagePart);

	var init = function() {

		// TEXT INPUTS
		var textInput = qsa('.emowl-form input[type=text]');
		for (var i = 0; i < textInput.length; i++) (function() { // scoping for events
			var n = textInput[i];
			var format = n.getAttribute('data-format') ? n.getAttribute('data-format') : '';
			var min = n.getAttribute('min') ? parseInt(n.getAttribute('min')) : '';
			var max = n.getAttribute('max') ? parseInt(n.getAttribute('max')) : '';
			var valid = n.getAttribute('data-val') ? n.getAttribute('data-val') : '';
			var digits = n.getAttribute('data-digits') ? parseInt(n.getAttribute('data-digits')) : '';
			var show = n.getAttribute('data-show') ? n.getAttribute('data-show') : '';

			// hitting enter
			n.addEventListener('keypress', function(e) { if (e.keyCode == 13) e.target.blur() });

			// if input has a max attribute
			if (max) n.addEventListener('input', function(e) {
				if (max < numb(e.target.value)) e.target.value = max;
			});

			// if input has max digits 
			if (digits) n.addEventListener('input', function(e) {
				if (e.target.value.length > digits) e.target.value = e.target.value.slice(0, -1)
			});

			// if input has a min attribute
			if (min) n.addEventListener('focusout', function(e) {
				if (min > numb(e.target.value)) {
					// formating currency or not
					if (format == 'currency') 	e.target.value = kroner(min);
					else 						e.target.value = min;
				}
			});

			// formating currency
			if (format == 'currency') {
				// initial load
				n.value = kroner(n.value);

				// on focus - remove all but numbers
				n.addEventListener('focus', function(e) { e.target.value = numb(e.target.value) });

				// on focus out - convert number to currency
				n.addEventListener('focusout', function(e) { e.target.value = kroner(e.target.value) });
			}

			// inputs that is limited to numbers typed in
			switch (valid) {
				case 'currency':
				case 'numbersOnly':
				case 'phone':
				case 'ar':
				case 'bankAccount':
				case 'socialnumber': n.addEventListener('input', function(e) { e.target.value = numb(e.target.value) });
			}

			// formatting with postfix
			if (format.indexOf('postfix:') != -1) {
				// getting actual postfix value
				var pf = format.replace('postfix:', '');

				// initial load
				n.value = n.value.replace(/[^0-9]/g, '') + pf;

				// on focus - remove all but numbers
				n.addEventListener('focusout', function(e) { e.target.value = numb(e.target.value) + pf });

				// on focus out - convert number to value with postfix
				n.addEventListener('focus', function(e) { e.target.value = numb(e.target.value )});
			}

			// selecting all text when focusing input
			n.addEventListener('focus', function(e) { 
			
				// var mark = e.target.parentNode.querySelector('.em-val-marker');
				// console.log(e.target.parentNode);
				// mark.classList.remove('em-val-marker-yes');
				// mark.classList.remove('em-val-marker-no');

				e.target.select();
			});

			// if parent has range input
			var innerRange = n.parentNode.parentNode.querySelectorAll('input[type=range]');
			for (var j = 0; j < innerRange.length; j++) (function() {
				var r = innerRange[j];
				n.addEventListener('input', function(e) { r.value = numb(e.target.value) });
			})();

			// VALIDATION
			if (valid) {
				// n.addEventListener('input', function(e) { v(e.target, format, valid) });
				n.addEventListener('focusout', function(e) { v(e.target, format, valid) });
			}

			if (show) {
				n.addEventListener('input', function(e) {

					try {
						if (!e.target.value || e.target.value == 0) qs('.em-element-'+show).classList.add('em-hidden');
						else qs('.em-element-'+show).classList.remove('em-hidden'); 	

					} catch (e) { console.error(e) }
				});
			}

			// SPECIAL RULES
			switch (n.classList[1]) {
				// case 'em-i-tenure':
				case 'em-i-loan_amount': 
					n.addEventListener('input', function(e) { payment() });
					n.addEventListener('focusout', function(e) { payment() });
					break;

				// case 'em-i-email':
				// 	n.addEventListener('input', function(e) {
				// 		var l = e.target.value.length;
				// 		var s = function(p) { e.target.style.fontSize = p }

				// 		if (l > 10) s('18px');
				// 		if (l > 20) s('16px');
				// 		if (l > 30) s('14px');
				// 		if (l > 40) s('12px');

				// 	});
				// 	break;
			}
		})();
		

		// RANGE INPUTS
		var rangeInput = qsa('.emowl-form input[type=range]');
		for (var i = 0; i < rangeInput.length; i++) (function() { 
			var r = rangeInput[i];

			// if range belongs to a text input
			var innerText = r.parentNode.querySelectorAll('input[type=text]');

			for (var j = 0; j < innerText.length; j++) (function() {
				var n = innerText[j];

				// fun for function -- changing text input value based on range input
				var fun = function(e) {
					var a = n.getAttribute('data-format');

					if (a == 'currency') 					n.value = kroner(e.target.value);
					else if (a.indexOf('postfix:') != -1) 	n.value = e.target.value+a.replace('postfix:', '');
					else 									n.value = e.target.value;
				}

				if (isIE) r.addEventListener('change', fun);
				else r.addEventListener('input', fun);
			})();
			
			var fun = function(e) { payment(); }

			switch (r.classList[1]) {
				case 'em-r-tenure':
				case 'em-r-loan_amount': 
					if (isIE) r.addEventListener('change', fun);
					else r.addEventListener('input', fun);

				break;
			}
		})();
		


		// CHECKBOX INPUTS

		$('.em-cc').each(function() {

			var show = $(this).children('.em-c').attr('data-show');

			if (show == undefined) return;

			var yes = true;

			if (/no:/.test(show)) yes = false;

			show = show.replace(/no:( |)/, '');			


			var s = function(d) { $(d).slideDown('fast') }
			var h = function(d) { $(d).slideUp('fast') }
			var tno = function(d) { 
				$(d).children('.em-cc-no').addClass('em-cc-green');
				$(d).children('.em-cc-yes').removeClass('em-cc-green');
			}

			var tyes = function(d) { 
				$(d).children('.em-cc-yes').addClass('em-cc-green');
				$(d).children('.em-cc-no').removeClass('em-cc-green');
			}

			if (yes) {
				$(this).find('.em-cc-yes').click(function() { s('.'+show); tyes($(this).parent()) });
				$(this).find('.em-cc-no').click(function() { h('.'+show); tno($(this).parent()) });
			}
			else {
				$(this).find('.em-cc-no').click(function() { s('.'+show); tno($(this).parent()) });
				$(this).find('.em-cc-yes').click(function() { h('.'+show); tyes($(this).parent()) });
			}

			// console.log(yes);
			// console.log(show);
			// console.log($(this).children('.em-c').attr('data-show'));
		});


		// var checkboxInput = qsa('.em-cc');
		// for (var i = 0; i < checkboxInput.length; i++) (function() {
		// 	var c = checkboxInput[i];

		// 	var yes = c.querySelector('.em-cc-yes');
		// 	var no = c.querySelector('.em-cc-no');
		// 	var input = c.querySelector('.em-c');

		// 	var show = input.getAttribute('data-show');

		// 	yes.addEventListener('click', function(e) {
		// 		input.value = 1;

		// 		if (show) {
		// 			var c = show.replace(/^(yes:\s?)|(no:\s?)/, '');

		// 			var temp = qs('.'+c);

		// 			// if (show.indexOf('no:') != -1) temp.classList.add('em-hidden');
		// 			// else temp.classList.remove('em-hidden');
		// 			// console.log(qs('.'+c).parentNode);

		// 			if (show.indexOf('no:') != 1) {
		// 				qs('.'+c).parentNode.style.display = 'block';
		// 				jQuery('.'+c).slideDown(500, function(e) {
		// 					this.classList.remove('em-hidden');
		// 				});
		// 			}

		// 			else {
		// 				jQuery('.'+c).slideUp(500, function(e) {
		// 					this.classList.add('em-hidden');
		// 					// jQuery(this).hide();
		// 					// console.log(qs('.'+c).parentNode);
		// 					qs('.'+c).parentNode.style.display = 'none';
		// 				});
		// 			}

		// 		}

		// 		yes.classList.add('em-cc-green');
		// 		no.classList.remove('em-cc-green');

		// 		// progress();
		// 	});

		// 	no.addEventListener('click', function(e) {
		// 		input.value = '';

		// 		if (show) {
		// 			var c = show.replace(/^(yes:\s?)|(no:\s?)/, '');

		// 			var temp = qs('.'+c);

		// 			// if (show.indexOf('no:') != -1) temp.classList.remove('em-hidden');
		// 			// else temp.classList.add('em-hidden');


		// 			if (show.indexOf('no:') != 1) {
		// 				jQuery('.'+c).slideUp(500, function(e) {
		// 					this.classList.remove('em-hidden');
		// 					qs('.'+c).parentNode.style.display = 'none';
		// 				});
		// 			}
		// 			else {
		// 				jQuery('.'+c).slideDown(500, function(e) {
		// 					this.classList.add('em-hidden');
		// 				});
		// 			}
		// 		}

		// 		yes.classList.remove('em-cc-green');
		// 		no.classList.add('em-cc-green');

		// 		// special rule
		// 		// try {
		// 		// 	var co = e.target.parentNode.parentNode.querySelector('.em-c-co_applicant');
		// 		// 	if (co) {
		// 		// 		var hInput = qs('.em-c-co_applicant_norwegian'); 
		// 		// 		hInput.value = '1';
		// 		// 		hInput.parentNode.querySelector('.em-cc-yes').classList.add('em-cc-green');
		// 		// 		hInput.parentNode.querySelector('.em-cc-no').classList.remove('em-cc-green');

		// 		// 		qs('.em-co-applicant-norwegian').classList.add('em-hidden');
		// 		// 	}
		// 		// } catch (e) { console.error(e) }
		// 		// progress();
		// 	});
		// })();
		


		// CHECK INPUTS
		var checkInput = qsa('.em-check');
		for (var i = 0; i < checkInput.length; i++) (function() {
			var n = checkInput[i];

			if (!n.getAttribute('data-val')) return;
			n.addEventListener('change', function(e) {
				v(e.target, null, e.target.getAttribute('data-val'));
			});

		})();

		// LIST INPUTS
		var lists = qsa('.emowl-form select');
		for (var i = 0; i < lists.length; i++) (function() {
			var n = lists[i];
			var val = n.getAttribute('data-val');

			if (val) n.addEventListener('input', function(e) { v(e.target, null, val) });

			// showing html element
			var show = function(o) {
				try {
					for (var i = 0; i < o.length; i++) 
						jQuery(o[i]).slideDown(500, function(e) {
							this.classList.remove('em-hidden');
						});
					
				} catch (e) {}
			}

			// hiding html element
			var hide = function(o) {
				try {
					for (var i = 0; i < o.length; i++) 
						jQuery(o[i]).slideUp(500, function(e) {
							this.classList.add('em-hidden');
						});
					
				} catch (e) {}
			}

			// SPECIAL RULES
			switch (n.classList[1]) {

				case 'em-i-tenure':
					n.addEventListener('change', function(e) {
						payment();
					});
					break;
				// EDUCATION
				case 'em-i-education':
					n.addEventListener('change', function(e) {
						switch (e.target.value) {
							case 'Høysk./universitet 1-3 år':
							case 'Høysk./universitet 4+år': show(['.em-element-education_loan']); break;
							default: hide(['.em-element-education_loan']);
						}
					});
					break;

				// EMPLOYMENT TYPE
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

				// CIVIL STATUS
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

				// LIVING CONDITIONS
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

				// NUMBER OF CHILDREN
				case 'em-i-number_of_children':
					n.addEventListener('change', function(e) {
						if (e.target.value && e.target.value != '0') show(['.em-element-allimony_per_month']);
						else hide(['.em-element-allimony_per_month']);
					});
					break;

				// CO APPLICANT: EMPLOYMENT TYPE
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
			} // end of switch

		})();
		




		// NEXT/PREV/SUBMIT BUTTONS
		try {
			qs('.em-b-next').addEventListener('click', function(e) {
				// console.log('hi');
				// VALIDATION OF CURRENT PART
				var test = current.querySelectorAll('.em-i');
				var success = true;

				for (var i = 0; i < test.length; i++) (function() {
					var n = test[i];

					var p = n.parentNode.parentNode;

					// console.log(p);

					if (p.classList.contains('em-hidden')) return;

					if (p.parentNode.classList.contains('em-hidden')) return;

					if (n.getAttribute('data-val')) {
						var val = n.getAttribute('data-val');
						var f = n.getAttribute('format');
						var ver = v(n, null, val);

						if (!ver) success = false;
					}
				})();

				// exit ramp
				if (!success) {
					// success = true;
					// return;
				}

				// hiding current part
				// current.style.display = 'none';

				// try {
				// showing next part
				// current.nextSibling.style.display = 'block';
				// showing prev button
				// qs('.em-b-back').classList.remove('em-hidden');

					// current.nextSibling.classList.add('em-animate-show');
					// // replace next button with submit button if no more parts
					// if (!current.nextSibling.nextSibling) {
					// 	e.target.classList.add('em-hidden');
					// 	qs('.em-b-submit').classList.remove('em-hidden');
					// }
					// current = current.nextSibling;

				// qs('.em-part-1').classList.add('em-hidden');
				// qs('.em-part-1-grid').classList.add('em-part-1-done');
				// qs('.em-part-lower-container').classList.remove('em-hidden');
				// 
				// 



				// e.target.classList.add('em-hidden');
				// qs('.em-b-submit').classList.remove('em-hidden');

				$('.emtheme-footer-container, .navbar-menu').fadeOut(100);
				$('.em-b-next, .forside-overskrift, .forside-overtext').slideUp(800);
				$('.em-part-1-grid').slideUp(800, function() {

					$('.emowl-form').css('width', 'auto');
					$('.em-element-mobile_number').detach().prependTo('.em-part-2');
					$('.em-element-email').detach().prependTo('.em-part-2');
					$('.em-b-container').detach().appendTo('.em-part-5').css('margin', '0');

					$('.em-b-endre, .em-b-send, .em-b-text').show();
					$('.em-part-2 .em-part-title').detach().prependTo('.em-part-2');

					$('.em-part-1-grid').css({
						'grid-template-columns': '1fr 1fr 1fr 1fr',
						'grid-template-areas': '"loan tenure monthly refinancing" "compare compare compare compare"',
						'grid-column-gap': '2rem',
						'padding': '4rem 2rem'
					});

					$('.em-element-monthly_cost').css({
						'justify-self': 'auto',
						'align-self': 'auto',
						'margin': '0'
					});

					$('.em-container-monthly_cost').css({
						'font-family': 'Merriweather',
						'font-weight': '900'
					});

					$('.em-compare-text').css('font-size', '2rem');

					$('.em-element-axo_accept, .em-element-contact_accept').hide(50, function() {
						jQuery('.em-slidedown').slideDown(800);

					});

				});
				// });


				$('.em-b-endre').click(function() {
					$('.em-part-1-grid').slideToggle();
					$('.em-b-endre').text($('.em-b-endre').text() == 'Endre Lånebeløp' ? 'Skjul Lånebeløp' : 'Endre Lånebeløp');
				});

				// var part1 = qs('.em-part-1-grid');

				// jQuery(part1).slideUp(800);

				// var title = qs('.em-part-1 .em-part-title');

				// title.innerHTML = '<div>Endre Lånebeløp:</div><div>Lånebeløp: '+qs('.em-i-loan_amount').value + '</div><div>Månedskostnad fra '+qs('.em-if-monthly_cost').value+'</div>';
				// title.style.display = 'flex';


				// jQuery(title).one('click', function() {
				// 	jQuery(this).hide();
				// 	jQuery(part1).slideToggle();
				// });



				qs('.em-form-container').style.borderBottom = 'none';

				// qs('.em-part-1 .em-part-title').style.display = 'block';

				// jQuery('.em-part-lower-container').slideDown({duration: 3000,
				// 	  start: function () {
				// 	    qs('.em-part-lower-container').style.display = 'flex';
				// 	  }
				// 	});

				// TODO disable on mobile?
				// if (window.innerWidth > 816) window.scroll(0, 1500);


				// if (window.innerWidth > 1000) current.querySelector('.em-i').focus();

				// var o = qs('.em-progress-container');

				// o.scrollTop = o.scrollHeight;

				// console.log(qs('.em-b-next').getBoundingClientRect());

				// var y = qs('.em-progress-container').getBoundingClientRect()['y'];

				// console.log(y);

				// y = window.height - y;

				// window.scroll(0, y);
				// current.querySelector('.em-part-title').classList.add('em-part-title-slide');

				// } catch (e) { console.error(e) }
				// 
				// 

				// console.log(qs('.em-form-container').parentNode.querySelectorAll('*:not(.em-form-container)'));

				var eles = qsa('.content-post > div:not(.em-form-container)');

				for (var i = 0; i < eles.length; i++)
					jQuery(eles[i]).fadeOut('fast');
					// eles[i].style.display = 'none';
					// console.log(eles[i]);
				// for (var el in eles)
					// console.log(eles[el]);
					// 
					// 

				window.location.hash = 'form';

			});

			qs('.em-b-next').addEventListener('click', incomplete);

		} catch (e) {}



		// SUBMIT BUTTON
		try {
			var post = function() {

				var data = '';

				var valid = true;

				// var inputs = qsa('input.em-i:not(.em-check), .em-c, select.em-i');
				var inputs = qsa('.emowl-form input.em-i, .emowl-form .em-c, .emowl-form select.em-i');

				for (var i = 0; i < inputs.length; i++) {
					var n = inputs[i];

					if (isHidden(n)) continue;

					if (n.getAttribute('data-val')) {
						var val = n.getAttribute('data-val');
						var f = n.getAttribute('format');
						var ver = v(n, null, val);

						if (!ver) valid = false;
					}

					var value = n.value;
					// turning numeric values into numbers
					switch (n.getAttribute('data-val')) {
						case 'numbersOnly':
						case 'phone':
						case 'currency':
						case 'ar': value = numb(n.value); break;
					}

					// adding to query string
					data += '&data['+n.name+']='+value;
				}

				if (!valid) {
					// return;
				}

				var cookie = document.cookie.split('; ');
				for (var i in cookie) {
					if (cookie[i].indexOf('=') == -1) continue;

					var temp = cookie[i].split('=');
					if (temp[0] == '_ga') data += '&data[ga]='+temp[1];
				}


				data += abtesting();
				// console.log(data);
				// if (!valid) return;				

				qs('.em-b-send').removeEventListener('click', post);

				var close = function(e) { $('.em-popup').slideUp(1000) }

				// qs('.em-popup-button').addEventListener('click', close);
				qs('.em-popup-x').addEventListener('click', close);

				qs('.em-b-send').innerHTML = 'Søknad sendes ...';

				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {

						try {
							// qs('.em-b-submit').style.display = 'none';
							// qs('.emowl-form').style.display = 'none';
							// 
							// 
							$('.emowl-form').slideUp(800, function() {
								// $('.emowl-form').fadeOut();
								$('.em-popup').slideDown(800, function() {
									// var eles = qsa('.content-post > div:not(.em-form-container)');
									// for (var i = 0; i < eles.length; i++)
										// jQuery(eles[i]).fadeOut('fast');

									$('.content-post > div:not(.em-form-container)').each(function() {
										$(this).fadeIn(2000);
									});

									$('.navbar-menu, .emtheme-footer-container').show();

								});
							});
							// qs('.em-glass').style.display = 'none';
							// qs('.em-popup').classList.add('em-popup-show');
						} catch (e) { console.error(e) }

						console.log(this.responseText);
					}
				}

				// try {
					// qs('.em-glass').style.display = 'block';
				// } catch (e) { console.log(e) }

				// sending to server
				xhttp.open('POST', emurl.ajax_url, true);
				xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhttp.send('action=axowl'+data);
				// console.log(data);

			}

			qs('.em-b-send').addEventListener('click', post);

		} catch (e) { console.error(e) }


		// helper text
		// var hm = document.querySelectorAll('.em-ht-q');
		var hm = qsa('.em-ht-q');
		for (var i = 0; i < hm.length; i++) 
			(function() { 
				var q = hm[i];
				var p = q.parentNode;

				q.addEventListener('mouseover', function(e) {
					try { p.querySelector('.em-ht').classList.remove('em-hidden');
					} catch (e) {}
				});

				q.addEventListener('mouseout', function(e) {
					try { p.querySelector('.em-ht').classList.add('em-hidden');
					} catch (e) {}
				});

				q.addEventListener('click', function(e) {
					try { p.querySelector('.em-ht').classList.toggle('em-hidden');
					} catch (e) {}

				});
			})();
		

		var inputs = qsa('input.em-i:not(.em-check)');
		var selects = qsa('select.em-i, input.em-check');

		// for (var i = 0; i < inputs.length; i++) (function() {
		// 	inputs[i].addEventListener('focusout', function() { progress() });
		// })();

		// for (var i = 0; i < selects.length; i++) (function() {
		// 	selects[i].addEventListener('change', function() { progress() });
		// })();

		// qs('.em-i-loan_amount').focus();



	} // end of init




	window.addEventListener('hashchange', function() {

		if (window.location.hash == '') {
			var eles = qsa('.content-post > div:not(.em-form-container)');

			for (var i = 0; i < eles.length; i++)
				jQuery(eles[i]).fadeIn('fast');


			
		}

		// console.log('hash '+window.location.hash);
		//reset form 
		//change title
		//bookmarking? test to figure out
		// console.log('heya');
	});


	setCookie();
	init();
	payment();
	// progress();

	// var ajatest = new XMLHttpRequest();

	// ajatest.onreadystatechange = function() {
	// 	if (this.readyState == 4 && this.status == 200) {
	// 		console.log(this.responseText);
	// 	}
	// }

	// // sending to server
	// ajatest.open('POST', emurl.ajax_url, true);
	// ajatest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	// ajatest.send('action=wlinc');


})(jQuery);



(function($) {

	var showPopup = function() {
		$('.email-popup, .em-glass').fadeIn(1000);

		$('.em-pop-email-x').one('click', function() {
			$('.email-popup, .em-glass').fadeOut(500);
		});

		var click = function() {

			var phone = $('#pop-phone').val();
			var email = $('#pop-email').val();

			var valid = true;

			if (!/\d{8}/.test(phone)) {
				$('#pop-phone').css('border-color', 'hsl(0, 80%, 60%');
				valid = false;
			}

			if (!/.+\@.+\..{2,3}/.test(email)) {
				$('#pop-email').css('border-color', 'hsl(0, 80%, 60%');
				valid = false;
			}

			if (!valid) {
				$('.pop-neste').one('click', click);
				return;
			}

			$('.email-popup, .em-glass').fadeOut(500);

			$.post(emurl.ajax_url, 
				{
					action: 'popup',
					'pop-email': $('#pop-email').val(),
					'pop-phone': $('#pop-phone').val()
				}, 
			
				function(data) {
					console.log(data);
				}
			);
		}
		$('.pop-neste').one('click', click);

		$('#pop-phone').on('input', function() {
	  		$(this).val($(this).val().substring(0, 8).replace(/[^0-9]/g, ''));
		});

		$('#pop-phone, #pop-email').focus(function(e) {
			e.target.style.borderColor = '#000';
		})
	
		// cookie
		var date = new Date();
		date.setTime(date.getTime() + (60*24*60*60*1000));
		document.cookie = 'em_popup=tester; expires='+date.toUTCString();
	}

	// console.log(emurl.ajax_url);

	// Check cookies first
	if (!/(^| )em_popup=/.test(document.cookie))  
		$('body').one('mouseleave', function() { showPopup() });
		

})(jQuery);