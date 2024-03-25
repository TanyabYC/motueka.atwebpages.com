<?php 
// access session functions
    include "checksession.php";

// check client is logged in, otherwise redirect to login page
    checkUser();

// display login status
    loginStatus();

// check user is admin, otherwise redirect to not authorised page
    // isAdmin();

    if (!isAdmin()) {
        echo("<script>location.href='http://motueka.atwebpages.com/notauthorised.php';</script>");
        exit();  // prevent any further code from being executed after redirect
    }

?>
<!DOCTYPE HTML>
<!-- Page language set as English New Zealand to attract New Zealand customers -->
<!-- Useful for search engines, screen readers and voice assistants to correctly pronounce content -->
<html lang="en-NZ">
<head>
    <!-- Display the page title in the browser tab to describe the meaning of the page -->
   <title>View Customer</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast View a Customer">

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
      <h1>Customer Details View</h1>
   </header>

<?php

// do some simple validation to check if id exists
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid Customer ID</h2>"; // simple error feedback
        exit;
    } 

// prepare a query and send it to the server
// NOTE for simplicity purposes ONLY we are not using prepared queries
// make sure you ALWAYS use prepared queries when creating custom SQL like below
    $query = 'SELECT * FROM customer WHERE customerid='.$id;
    $result = mysqli_query($db_connection, $query);
    $rowcount = mysqli_num_rows($result); 

// makes sure we have the customer
    if ($rowcount > 0) {
        echo "<nav><section><h2>";  // breadcrumb menu
        echo "<a href='listcustomers.php'>[Return to the Customer listing]</a>";
        echo "<a href='index.php'>[Return to the main page]</a>";
        echo "</h2></section></nav>";

        echo "<main><section><form>";  // main page section and form to view customer details
        echo "<fieldset><legend>customer detail #$id</legend><dl>"; 
        $row = mysqli_fetch_assoc($result);
        echo "<dt>First name:</dt><dd>".$row['firstname']."</dd>";
        echo "<dt>Last name:</dt><dd>".$row['lastname']."</dd>";
        echo "<dt>Email:</dt><dd>".$row['email']."</dd>";
        echo "</dl></fieldset>";
    } else {
        echo "<h2>No customer found!</h2>"; // suitable feedback
    }
    mysqli_free_result($result); // free any memory used by the query
    mysqli_close($db_connection); // close the connection once done

?>
            </form>
        </section>
    </main>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
  