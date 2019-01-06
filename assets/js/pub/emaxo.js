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

	var val = {

		numbersOnly: function(d) {

		}

		textOnly: function(d) {

		}

		phone: function(d) {

		}

		email: function(d) {

		}

		name: function(d) {
			
		}


	}


	// container for parts and inputs
	// var P = {

	// }

	var init = function() {
	// 	document.querySelectorAll('.part').forEach(function(p) {
	// 		var t = p.classList[1];
	// 		if (/\d/.test(t)) P[t] = {};
	// 	});

		// get all range inputs
		// add event listenere to range and text input

		// get all text inputs add default events

		// string format

	}


	init();

})();