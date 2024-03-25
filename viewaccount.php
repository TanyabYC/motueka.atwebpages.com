<?php 
// access session functions
    include "checksession.php";

// check client is logged in, otherwise redirect to login page
    checkUser();

// display login status
    loginStatus();
?>
<!DOCTYPE html>
<!-- Page language set as English New Zealand to attract New Zealand customers -->
<!-- Useful for search engines, screen readers and voice assistants to correctly pronounce content -->
<html lang="en-NZ">
<head>
    <!-- Display the page title in the browser tab to describe the meaning of the page -->
   <title>View Account</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast View an Account">

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
    include "cleaninput.php";  // clean data input
    include "config.php"; // load in any variables

    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// insert DB code from here onwards
// check if the connection was good
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL! ".mysqli_connect_error();
        exit; // stop processing the page further
    }
?>
      <!-- Page heading -->
      <h1>Account Details View</h1>
   </header>

<?php

// validate session username
    if (isset($_SESSION['username']) and !empty($_SESSION['username']) and is_string($_SESSION['username'])) {

        $username = cleanInput($_SESSION['username']);

// *** Select record from the database to display if logged in as customer with userid (role) of 0 ***
// prepare a query and send it to the server
        $customer_query = "SELECT * FROM customer WHERE email = '".$username."'";
        $customer_result = mysqli_query($db_connection, $customer_query);
        $customer_count = mysqli_num_rows($customer_result);

        if ($customer_count > 0) {
            $row = mysqli_fetch_assoc($customer_result);
            echo "<nav><section><h2>";  // breadcrumb menu
            echo "<a href='editcustomer.php?id=".$row['customerID']."'' title='Edit your account'>[Edit your Account details]</a>";
            echo "<a href='index.php'>[Return to the main page]</a>";
            echo "</h2></section></nav>";

            echo "<main><section><form>";  // main page section and form to view customer details

            echo "<fieldset><legend>Customer detail #".$row['customerID']."</legend><dl>"; 
            echo "<dt>First name:</dt><dd>".$row['firstname']."</dd>";
            echo "<dt>Last name:</dt><dd>".$row['lastname']."</dd>";
            echo "<dt>Email:</dt><dd>".$row['email']."</dd>";
            echo "</dl></fieldset>";
        }
    } else {  // session error
        logout();
    }
?>
            </form>
        </section>
    </main>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>