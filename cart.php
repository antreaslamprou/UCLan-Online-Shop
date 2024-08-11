<?php
    include 'connect.php';
    session_start();

    // Chcks if any alerts should be displayed and show them after a brief delay so the page will first load
    if (isset($_GET["showAlert1"])) {
        echo "<script>setTimeout(function() {alert('You already have an account!');},500);</script>";
    }
    else if (isset($_GET["showAlert2"])) {
        echo "<script>setTimeout(function() {alert('Login Failed! Please try again!');},500);</script>";   
    }
    else if (isset($_GET["showAlert3"])) {
        echo "<script>setTimeout(function() {alert('Thank you for your order!');},500);</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart Page</title>
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

    <!-- Adds a div for cart page with the title, text, the cart headers and the empty cart button -->
    <div class="cart">
        <p class="orange">Shopping Cart</p>
        
        <?php 
            if (isset($_SESSION["username"])) {
                echo "<p class='bold'>Welcome ".$_SESSION["username"].". Here are the items in your basket:</p>";
            }
            else {
                echo " <p class='bold'>The items you have selected will be displayed below:</p>";
            }
        ?>
        
        <div id="cartTable">
            <?php 
            if (isset($_SESSION["sales"])) { ?>
                <table>
                <tbody class="head">
                    <tr>
                        <th>Item</th>
                        <th>Product</th>
                        <th>Price</th>
                    </tr>
                </tbody>

                <tbody id="cartList"> 
                <?php 
                    $index = 1;
                    for ($num = 0; $num < count($_SESSION["sales"]); $num++) {
                        $query = "SELECT * FROM tbl_products WHERE product_id=".$_SESSION["sales"][$num];
                        $searchResult = mysqli_query($connection, $query);
                        $productsArray = mysqli_fetch_array($searchResult, MYSQLI_ASSOC);
                        echo "<tr><td>".$index."</td><td><img src='resources/".$productsArray["product_image"]."'>".$productsArray["product_title"]."</td><td>".$productsArray["product_price"]."</td></tr>";     
                        $index++;
                    }
                ?>
                </tbody>
            </table>
            <?php 
            }
            else {
                echo "<h2 id='emptyCartError'>Your cart is Empty!</h2>";
            }
            ?>
        </div>
        <div id="cartBtns">
            <a href="functions.php?function=emptyCart"><button id="emptyCart">Empty Cart</button></a>
            <?php
                if (isset($_SESSION["username"]) && isset($_SESSION["sales"])) {
                    echo "<a href='functions.php?function=checkout'><button id='checkout'>Checkout</button></a>";
                }    
            ?>
        </div>
    
    <!-- Checks if a user is loged in and if not, it appears the log in form -->
    <?php 
        if (!isset($_SESSION["username"])) : ?>
            <div class="center" id="login">
                <p class="bold">In order to checkout you must login:</p>
                <form id="loginForm" method="post">
                    <label>Email adress:</label><br>
                    <input type="email" name="user_email"><br><br>
                    <label>Password:</label><br>
                    <input type="password" name="user_pass"><br><br>
                    <input type="submit" name="Submit"> <br><br> 
                </form>
            </div>
    <?php endif; ?>

    <!-- Checks if the credentials are correct for the user input -->
    <?php 
        if (isset($_POST["user_email"]) && isset($_POST["user_pass"])) {
            $request = "SELECT * FROM tbl_users";
            $result = $connection->query($request);
            while ($row = $result->fetch_assoc()) {
                if (password_verify($_POST["user_pass"], $row["user_pass"]) && $row["user_email"] == $_POST["user_email"]) {
                    $_SESSION["username"] = $row["user_full_name"];
                    $_SESSION["userID"] = $row["user_id"];
                    break;
                }
            }
            if (isset($_SESSION["username"])) {
                header("location: cart.php?");
            } 
            else {
                header("location: cart.php?showAlert2");
            }
        }
    ?>
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