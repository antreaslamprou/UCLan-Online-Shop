/** For selection of stars and storage in session storage **/

//Saves stars and star box in a variable
let stars = document.getElementsByClassName("star");
let starsBox = document.getElementsByClassName("starRating");

//If the element is created
if (stars !== null) {

    //For each star add a function that will make the clicked star's attribute "isClicked" to true and the rest to false and return its value to a hidden box
    for (let star of stars) {
        star.addEventListener("click", function () {
            for (let starN of stars) {
                starN.setAttribute("isClicked", "false");
            }

            this.setAttribute("isClicked", "true");
            var rating = this.id;
            var reviewRatingBox = document.getElementById("starReviewRating");

            reviewRatingBox.setAttribute("value", rating);
        });
    }
}

/** For TOP navigation button **/

//Saves main body to a variable
let productsBody = document.getElementById("productsContainer");

//Creates the top buttom which moves the user to the top side of the page
let topButton = document.createElement("button");
topButton.innerText = "Top";
topButton.setAttribute("id", "topButton");
topButton.onclick = function(){topFunction();};
window.onscroll = function() {showButton();};
productsBody.appendChild(topButton);

//Function that will check if the page is not on top and show the button
function showButton() {
    if(document.body.scrollTop > 25 || document.documentElement.scrollTop > 20) { //For Safari and Other browsers
        topButton.style.display = "block";
    }
    else {
        topButton.style.display = "none";
    }
}

//Function that will take user to the top of the page
function topFunction() {
    document.body.scrollTop = 0; //Safari
    document.documentElement.scrollTop = 0; //Other browsers
}

