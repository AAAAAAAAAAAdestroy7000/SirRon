// gets all elements with the class "slide" for the hero image slideshow
var slides = document.getElementsByClassName("slide");

// counter used to track which slide is currently active
var ii = 0;

// automatically switches slides every 6 seconds
setInterval(function () {

    // makes sure there are slides before trying to change them
    if (slides.length > 0) {

        // removes the active class from the current slide
        slides[ii].classList.remove("active");

        // moves to the next slide
        ii = ii + 1;

        // if it reaches the end, go back to the first slide
        if (ii >= slides.length) {
            ii = 0;
        }

        // adds active class to the new slide
        slides[ii].classList.add("active");
    }

}, 6000);

// variables used for the TripAdvisor modal content
var currentModal = null;
var bubblesElement = null;
var numberElement = null;
var reviewsElement = null;
var poweredElement = null;

// opens the modal and loads TripAdvisor data only once
function openModalInfo(name, localRating, modalId) {

    // gets the modal element using its ID
    currentModal = document.getElementById(modalId);

    // checks if the modal already loaded data to avoid duplicate API calls
    if (currentModal.getAttribute("data-loaded") == "1") {
    } else {

        // marks the modal as already loaded
        currentModal.setAttribute("data-loaded", "1");

        // gets elements inside the modal where rating data will be placed
        bubblesElement = currentModal.querySelector(".ta-bubbles");
        numberElement = currentModal.querySelector(".ta-number");
        reviewsElement = currentModal.querySelector(".ta-reviews");
        poweredElement = currentModal.querySelector(".ta-powered");

        // sets loading text before API request finishes
        if (numberElement != null) {
            numberElement.textContent = "Loading...";
        }

        if (reviewsElement != null) {
            reviewsElement.textContent = "";
        }

        if (poweredElement != null) {
            poweredElement.innerHTML = "";
        }

        var request = new XMLHttpRequest();
        request.open("GET", "../otherreqs/ta_lookup.php?name=" + encodeURIComponent(name));

        request.onload = function () {

            // parses the API response
            var data = JSON.parse(request.responseText);

            // fallback rating in case API fails
            var rating = localRating;

            // replaces local rating if TripAdvisor rating exists
            if (data != null) {
                if (data.rating != null) {
                    rating = parseFloat(data.rating);
                }
            }

            // displays rating bubbles
            renderRating(rating);

            // displays numeric rating
            if (numberElement != null) {
                numberElement.textContent = rating.toFixed(1) + "/5.0";
            }

            // displays review count if available
            if (reviewsElement != null) {
                if (data != null) {
                    if (data.num_reviews != "") {
                        reviewsElement.textContent = "(" + data.num_reviews + " reviews)";
                    } else {
                        reviewsElement.textContent = "";
                    }
                }
            }

            // shows TripAdvisor attribution
            if (poweredElement != null) {
                poweredElement.innerHTML = "<small class='text-muted'>Powered by TripAdvisor</small>";
            }
        };

        // sends the API request
        request.send();
    }
}

// renders the rating bubbles based on the rating value
function renderRating(value) {

    if (bubblesElement != null) {

        var html = "";
        var j = 1;

        // loops from 1 to 5 to build rating bubbles
        while (j <= 5) {

            if (j <= value) {
                html = html + "<span class='bubble filled'>●</span>";
            } else {
                html = html + "<span class='bubble empty'>●</span>";
            }

            j = j + 1;
        }

        // adds numeric rating after the bubbles
        html = html + " <span class='ta-number' style='color:#4a2a8a;'>" + value.toFixed(1) + "/5.0</span>";
        bubblesElement.innerHTML = html;
    }
}
