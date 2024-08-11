<?php include 'connect.php';
	session_start();

	//Checks if the there is a function that needs to be done
    if (isset($_GET["function"])) {
        if ($_GET["function"] == "logout") {     
            session_destroy();
	        header("location:index.php");
            exit();
        }
        if ($_GET["function"] == "checkout") {
            $current_time = date("Y-m-d H:i:s",time());
            $userID = $_SESSION["userID"];
            $productIDs = implode(", ", $_SESSION["sales"]);
    
            $queryUserAddition = "INSERT INTO tbl_orders (order_date, user_id, product_ids) VALUES ('".$current_time."','".$userID."','".$productIDs."')";
            if ($connection->query($queryUserAddition) === TRUE) {
                header("location: cart.php?showAlert3");
                unset($_SESSION["sales"]);
                exit();
            } 
            else {
                echo "<script> alert ('Error: " . $queryUserAddition . "<br>" . $connection->error . "</script>";
            }
        }
        unset($_SESSION["sales"]);
        header("location: cart.php");
        exit();
    }

    //Checks if any product should be added to cart
    else if (isset($_POST["boughtID"])) {
        if (!isset($_SESSION["sales"])) {
            $_SESSION["sales"]= [];    
        }
        array_push($_SESSION["sales"], $_POST["boughtID"]);
    }
    if (isset($_GET["id"])) {
        header("location: products.php?id=".$_GET["id"]."&showAlert2");
    }
    else {
        header("location: products.php?showAlert2");
    }
?>