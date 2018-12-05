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
		
		getNext().style.display = "block";
	});


	prevButton.addEventListener("click", function() {
		currentPart.style.display = "none";
		
		getPrev().style.display = "block";
	});

	// loan amount
	var r = document.querySelector(".em-r-loan_amount");
	var a = document.querySelector(".em-i-loan_amount");

	r.addEventListener("input", function(e) { a.value = e.target.value });
	a.addEventListener("input", function(e) { r.value = e.target.value });
})();