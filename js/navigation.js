//Function to transform the navigation to hamburger menu
function myFunction() {

    //Gets the navigation div
    let x = document.getElementById("topNav");

    //Changes the name which css will then change the navigation properties
    if (x.className === "navigation") {
        x.className += " responsive";
    } else {
        x.className = "navigation";
    }
}