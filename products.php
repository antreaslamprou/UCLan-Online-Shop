<?php
    include 'connect.php';
    session_start();

    // Chcks if any alerts should be displayed and show them after a brief delay so the page will first load
    if (isset($_GET["showAlert1"])) {
        echo "<script>setTimeout(function() {alert ('Review Added!');},500);</script>";
    }
    if (isset($_GET["showAlert2"])) {
        echo "<script>setTimeout(function() {alert('Item added to cart!');},500);</script>";
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Page</title>
    <link rel="icon" href="resources/images/icon.png">
    <link rel="stylesheet" href="styles.css" type="text/css">
    <script src="js/products.js" defer></script>
    <script src="js/navigation.js"></script>
</head>
<body>

<!-- Give body div a grid class -->
<div class="gridContainer" id="productsContainer">

    <!-- Adds logo, page name and navigation on the top of the page -->
    <header>
        <img class="logo" src="resources/images/uclanlogo.png" alt="UCLan Logo" height="100" width="300">
        <span class="headerTitle">Student Shop</span>
        <div class="navigation" id="topNav">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <?php 
                if (isset($_SESSION['username'])) {
                    echo "<a href='functions.php?function=logout'>Log Out</a>";
                }
                else {
                    echo "<a href='signup.php'>Sign Up</a>";
                }
            ?>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <img src="resources/images/hamburger.png" alt="Humberger icon" height="40" width="40">
            </a>
        </div>
    </header>

    <!-- Search text and button -->
    <div id="searchProduct">
        <form method="get">
            <input id="searchProductBox" type="text" name="searchCriterion">
            <input id="searchProductButton" type="submit" value="Search">
        </form>

        <div id="filterButtons">
            <a href="products.php">All</a>
            <a href="products.php?searchCriterion=Hoodie">Hoodies</a>
            <a href="products.php?searchCriterion=Jumper">Jumpers</a>
            <a href="products.php?searchCriterion=Tshirt">T-shirts</a>
        </div>
    </div>
 
    <!-- Retrieves the products from the database using SQL -->
    <?php 
        //Checks if there are filters 
        if (isset($_GET["id"])) {
            $querySearch = "SELECT * FROM tbl_products WHERE product_id=";
            $querySearch .= $_GET["id"];
        }
        else if (isset($_GET["searchCriterion"])) {
            $querySearch = "SELECT * FROM tbl_products WHERE product_title LIKE '%";
            $querySearch .= $_GET["searchCriterion"];
            $querySearch .= "%'";
        }
        else {
            $querySearch = "SELECT * FROM tbl_products";
        }
        $functionPage = "functions.php";

        //If a single product is chosen a form is created otherwise a div
        $searchResult = mysqli_query($connection, $querySearch);
        if (isset($_GET["id"])) {
            echo "<div id='singleProduct'>";
            $functionPage .= "?id=".$_GET["id"];
        }
        else {
            echo "<div id='products'>";
        }
       
        //Loads from database all equivalent products
        while ($productsArray = mysqli_fetch_array($searchResult, MYSQLI_ASSOC)) {
            echo "<form class='productItem' method='post' action='$functionPage'>";
            echo "<img width='250'src='resources/".$productsArray["product_image"]."'>";
            echo "<p class='orange productTitle'>".$productsArray["product_title"]."</p>";
            echo "<span>".$productsArray["product_desc"].".<a href='products.php?id=".$productsArray["product_id"]."'>ReadMore</a></span>";
            echo "<br>";
            echo "<span class='price'>&pound; ".$productsArray["product_price"]."</span>";
            echo "<input value='".$productsArray["product_id"]."' name='boughtID' type='hidden'>";
            echo "<input class='productBuyBtn' type='submit' value='Buy'>";
            echo "<br></form>";
        }

        echo "</div>"; //Closes the products/product div

        //Checks if there are any reviews, if a single product is chosen and the user is logged in, and shows them
        if (isset($_GET["id"])) {
            if (isset($_SESSION["username"])) {
                $querySearch = "SELECT * FROM tbl_reviews WHERE product_id=".$_GET["id"];
                $searchReviews = mysqli_query($connection, $querySearch);
                $isReviewed = false;
                $totalRating = 0;
                $count = 0;

                echo "<div id='reviews' class='center'>";
                echo "<div id='averageReview'><h1>Average Review Score: </h1></div>";
                while ($reviewsArray = mysqli_fetch_array($searchReviews, MYSQLI_ASSOC)) {
                    echo "<div class='review'>";
                    echo "<p id='reviewTitle'><b>Review Title: </b>".$reviewsArray["review_title"]."</p>";
                    echo "<p id='reviewRating'><b>Review Rating: </b>".$reviewsArray["review_rating"]."</p>";
                    echo "<p id='reviewDesc'><b>Review Descreption: </b>".$reviewsArray["review_desc"]."</p> </div>";
                    if ($_SESSION["userID"] === $reviewsArray ["user_id"]) {
                        $isReviewed = true;
                    }

                    $totalRating += $reviewsArray["review_rating"];
                    $count++;
                }
                
                //Finds average review if there are any reviews
                if ($count > 0) {
                    $avg = $totalRating/$count;
                    echo "<script> let avg = document.getElementById('averageReview');
                            avg.innerHTML = '<h1>Average Review Score: ".$avg." /5 <h1>'; </script>";
                }
                else if ($count == 0) {
                    echo "<script> let avg = document.getElementById('averageReview');
                            avg.innerHTML = ''; </script>";
                }

                //If the user didnt reviewed the current product a review product form will be shown
                if (!$isReviewed) : ?>
                    <form id='createReview' class="center" method="post">
                        <p>Leave Review:</p>
                        <div id="reviewFirstLine"> 

                            <span id="starRating">
                                 <span class="star" id="5">&#9733</span>
                                 <span class="star" id="4">&#9733</span>
                                 <span class="star" id="3">&#9733</span>
                                 <span class="star" id="2">&#9733</span>
                                 <span class="star" id="1">&#9733</span>
                            </span>
                            <input type="text" id="reviewTitleBox" name="reviewTitle" placeholder="Review Title" required>
                        </div>
                        <input type="number" id="starReviewRating" name="reviewScore" required>
                        <div>
                            <textarea id="reviewDescBox" cols="30" rows="10" name="reviewDesc" placeholder="Review Descreption" required></textarea><br>
                        </div>
                        <div>
                            <input type='submit' id="reviewSubmitBtn" name='Submit'>
                        </div>
                    </form>

                    <?php
                endif;
            }
        }
	    
        //Sends the created review to the database
        if (isset($_POST["reviewTitle"])) {
            $title = $_POST["reviewTitle"];
            $descreption = $_POST["reviewDesc"];
            $rating = $_POST["reviewScore"];
            $current_time = date("Y-m-d H:i:s",time());
            $queryUserAddition = "INSERT INTO tbl_reviews (user_id, product_id, review_title, review_desc, review_rating, review_timestamp) VALUES (".$_SESSION["userID"].",".$_GET["id"].",'".$title."','".$descreption."',".$rating.",'".$current_time."')";
            if ($connection->query($queryUserAddition) === TRUE) {
                header("location: products.php?id=".$_GET["id"]."&showAlert1");
            } 
            else {
                echo " <script> alert ('Error: " . $queryUserAddition . "<br>" . $connection->error . "</script>";
            }
        }
    echo "</div>";
?>


    <!-- Adds links, contact info and addresses on the bottom of the page -->
    <footer id="footer">
        <div class="link"><h2>Links</h2>
            <p><a href="https://www.uclan.ac.uk/">UCLan Home Page </a></p>
            <p><a href="https://www.uclancyprus.ac.cy/">UCLan Cyprus Home Page </a></p>
        </div>
        <div class="contact"><h2>Contact</h2>
            <p>Email: <a href="mailto:info@uclancyprus.ac.cy">info@uclancyprus.ac.cy</a></p>
            <p>Phone: <a href="tel:+35724694000">+357 24 69 40 00</a></p>
        </div>
        <div class="address"><h2>Address</h2>
            <p>University of Central Lancashire Cyprus</p>
            <p>12 University Avenue Pyla, 7080, Larnaca</p>
        </div>
    </footer>
</body>
</html>