<?php 
// access session functions
    include "checksession.php";

// check client is logged in, otherwise redirect to login page
    checkUser();

// display login status
    loginStatus();

// check user is admin, otherwise redirect to not authorised page
    if (!isAdmin()) {
        echo("<script>location.href='http://motueka.atwebpages.com/notauthorised.php';</script>");
        exit();  // prevent any further code from being executed after redirect
    }
?>
<!DOCTYPE html>
<!-- Page language set as English New Zealand to attract New Zealand customers -->
<!-- Useful for search engines, screen readers and voice assistants to correctly pronounce content -->
<html lang="en-NZ">
<head>
    <!-- Display the page title in the browser tab to describe the meaning of the page -->
    <title>View Customer</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Delete a Customer">

    <!-- The set of characters and symbols used in the web page and required by the browser -->
    <meta charset="utf-8">

    <!-- The browser should not scale content down. Necessary to make the website responsive on all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Display a favicon image to the left of the page title in the browser tab -->
    <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
</head>
<body>

    <!-- Page header -->
    <header>

        <!-- Page navigation -->
        <?php include 'navigation.php'; ?>

<?php
    include "config.php"; //load in any variables
    include "cleaninput.php";

    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);


// insert DB code from here onwards
// check if the connection was good
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    }

// retrieve the customerid from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid Customer ID</h2>"; //simple error feedback
            exit;
        } 
    }

// the data was sent using a formtherefore we use the $_POST instead of $_GET
// check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {     
        $error = 0; //clear our error flag
        $msg = 'Error: ';

// customerID (sent via a form it is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']); 
        } else {
            $error++; // bump the error flag
            $msg .= 'Invalid Customer ID '; // append error message
            $id = 0;
        }        
    
// save the customer data if the error flag is still clear and customer id is > 0
        if ($error == 0 and $id > 0) {
            $query = "DELETE FROM customer WHERE customerID=?";
            $stmt = mysqli_prepare($db_connection, $query); // prepare the query
            mysqli_stmt_bind_param($stmt,'i', $id); 
            try {
                mysqli_stmt_execute($stmt);  // attempt to delete customer
                echo "<h2>Customer details deleted.</h2>";      
            } catch (Exception $e) {
                echo "<h2>Customers with bookings cannot be deleted!</h2>";
            }
            mysqli_stmt_close($stmt);        
        } else {
            echo "<h2>$msg</h2>".PHP_EOL;
        }
    }

// prepare a query and send it to the server
// NOTE for simplicity purposes ONLY we are not using prepared queries
// make sure you ALWAYS use prepared queries when creating custom SQL like below
    $query = 'SELECT * FROM customer WHERE customerid='.$id;
    $result = mysqli_query($db_connection,$query);
    $rowcount = mysqli_num_rows($result); 
?>

        <!-- Page heading -->
        <h1>Customer details preview before deletion</h1>
    </header>

    <!-- Breadcrumb menu -->
    <nav>
        <section>
            <h2>
                <a href='listcustomers.php'>[Return to the Customer listing]</a>
                <a href='index.php'>[Return to the main page]</a>
            </h2>
        </section>
    </nav>

    <!-- Main page section -->
    <main>

        <!-- Page section to preview room details -->
        <section>

<?php

//makes sure we have the customer
    if ($rowcount > 0) {
        echo "<fieldset><legend>Customer detail #$id</legend><dl>";

        $row = mysqli_fetch_assoc($result);
        echo "<dt>First name:</dt><dd>".$row['firstname']."</dd>".PHP_EOL;
        echo "<dt>Last name:</dt><dd>".$row['lastname']."</dd>".PHP_EOL;
        echo "<dt>Email:</dt><dd>".$row['email']."</dd>".PHP_EOL;
        echo "</dl></fieldset>".PHP_EOL;
?>

            <!-- Confirm delete action -->
            <form method="POST" action="deletecustomer.php">
                <h2>Are you sure you want to delete this customer?</h2>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" name="submit" value="Delete">
                <a href="listcustomers.php">[Cancel]</a>
            </form>

<?php    
    } else {
        echo "<h2>No customer found, possibly deleted!</h2>"; // suitable feedback
    }
    mysqli_free_result($result); // free any memory used by the query
    mysqli_close($db_connection); // close the connection once done
?>
        </section>
    </main>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
  