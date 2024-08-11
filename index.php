<?php
    include 'connect.php';
    session_start();

    //Checks if a user is logged in and if not it creates a sales array to store the session items of the user
    if (!isset($_SESSION["username"])) {
        $sales = [];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <link rel="icon" href="resources/images/icon.png">
    <link rel="stylesheet" href="styles.css" type="text/css">
    <script src="js/navigation.js"></script>
</head>
<body>

<!-- Give body div a grid class -->
<div class="gridContainer">

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

    <!-- Retrieves the offers using SQL -->
    <?php
        $querySearch = "SELECT * FROM tbl_offers";
        $searchResult = mysqli_query($connection, $querySearch);
        echo "<div id='offers'>";
        echo "<p class='title'>Offers</p>";
        echo "<div id='offerFrame'>";
        while ($offersArray = mysqli_fetch_array($searchResult, MYSQLI_ASSOC)) {
            echo "<button class='offerButton' data-inline='true'>";
            echo "<p class='orange'>".$offersArray["offer_title"].'<p>';
            echo $offersArray["offer_dec"];
            echo "</button>";
        }
    ?>
    </div>
    </div>

    <!-- Adds the titles, text and videos on the home page -->
    <div class="home">
        <div class="title">Where opportunity creates success</div>
        <div>Every student at the University of Central Lancashire is automatically a member of the Students' Union. We are here to make life better for students - inspiring you to succeed and achieve your goals.</div>
        <div>Everything you need to know about UCLan Students' Union. Your membership starts here.</div>
        <div class="orange"> Together</div>
        <div class="video">
            <video id="embedVideo" width="800" height="450" controls>
                <source src="resources/videos/UCLanOpenDays.webm" type="video/webm">
                <source src="resources/videos/UCLanOpenDays.mp4" type="video/mp4">
                <source src="resources/videos/UCLanOpenDays.ogg" type="video/ogg">
                This video cant be played by your internet browser
            </video>
        </div>
        <br>
        <div class="orange">Join our global community</div>
        <div class="video"><iframe width="800" height="450" src="https://www.youtube.com/embed/i2CRunZv9CU"></iframe></div>
        <br>
        <br>
    </div>

    <!-- Adds links, contact info and addresses on the bottom of the page -->
    <footer>
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

</div>
</body>
</html>