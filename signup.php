<?php
    include 'connect.php';
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Page</title>
    <link rel="icon" href="resources/images/icon.png">
    <link rel="stylesheet" href="styles.css" type="text/css">
    <script src="js/navigation.js"></script>
</head>
<body>
<div class="gridContainer">
      <!-- Adds logo, page name and navigation on the top of the page -->
    <header>
        <img class="logo" src="resources/images/uclanlogo.png" alt="UCLan Logo" height="100" width="300">
        <span class="headerTitle">Student Shop</span>
        <div class="navigation" id="topNav">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href='signup.php'>Sign Up</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <img src="resources/images/hamburger.png" alt="Humberger icon" height="40" width="40">
            </a>
        </div>
    </header>

    <!-- Create the sign-up form -->
    <div id="signUpForm" class="center">
        <p class="title">Sign Up</p>
            <form id="signUpForm" method="post">
            <p>In order to purchase from the Student's Union shop, you need to create an account with all fields below required.
            If you have any difficulties with the form please contact the webmaster</p><a href""></a>
            <label>Full name:</label><br>
            <input type="text" name="user_full_name" required><br><br>
            <label>Email adress:</label><br>
            <input type="email" name="user_email" required><br><br>
            <label>Password:</label>
            <p>Password must contain at least one number, one uppercase and lowercase letter and at least 8 or more characters.</p>
            <input type="password" name="user_pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required><br><br>
            <label>Confirm Password:</label><br>
            <input type="password" name="confirm_password" required><br><br>
            <label>Address:</label><br>
            <textarea id="addressBox" name="user_address" required></textarea><br><br>
            <input type="submit" id="signUpFormButton" name="Submit"> <br><br>          
        </form>
    </div>

    <?php 
        if (isset($_POST["user_full_name"])) {
            //Checks if email is already in database
            $request = "SELECT * FROM tbl_users WHERE user_email='".$_POST["user_email"]."'";
            $result = $connection->query($request);
            if ($row = $result->fetch_assoc()) {
                header('Location: cart.php?showAlert1');
                exit();
            }

            //Saves the form to the database
            if ($_POST["user_pass"] == $_POST["confirm_password"]) {
                $name = $_POST["user_full_name"];
                $address = $_POST["user_address"];
                $email = $_POST["user_email"];
                $current_time = date("Y-m-d H:i:s",time());
                $password = password_hash($_POST["user_pass"], PASSWORD_DEFAULT);
                $queryUserAddition = "INSERT INTO tbl_users (user_full_name, user_address, user_email, user_pass, user_timestamp) VALUES ('".$name."','".$address."','".$email."','".$password."','".$current_time."')";
                if ($connection->query($queryUserAddition) === TRUE) {
                    echo "<script> alert ('Account created succesfully!')</script>";
                } 
                else {
                    echo " <script> alert ('Error: " . $queryUserAddition . "<br>" . $connection->error . "</script>";
                }
                header("Location: index.php");
            }
        }
    ?>

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