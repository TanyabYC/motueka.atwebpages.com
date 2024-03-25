<?php 
// access session functions
    include "checksession.php";

// check client is logged in, otherwise redirect to login page
    checkUser();

// display login status
    loginStatus();

?>
<!DOCTYPE HTML>
<!-- Page language set as English New Zealand to attract New Zealand customers -->
<!-- Useful for search engines, screen readers and voice assistants to correctly pronounce content -->
<html lang="en-NZ">
<head>
    <!-- Display the page title in the browser tab to describe the meaning of the page -->
    <title>View Room</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast View a Room">

    <!-- The set of characters and symbols used in the web page and required by the browser -->
    <meta charset="utf-8">

    <!-- The browser should not scale content down. Necessary to make the website responsive on all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Define keywords required by search engines -->
    <meta name="keywords" content="Motueka, Bed & Breakfast, B&B, Accommodation, View a room">

    <!-- Display a favicon image to the left of the page title in the browser tab -->
    <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
</head>
<body>

    <!-- Page header -->
    <header>
      
        <!-- Page navigation -->
        <?php include 'navigation.php'; ?>

<?php
    include "config.php"; // load in any variables
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// insert DB code from here onwards
// check if the connection was good
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL! ".mysqli_connect_error();
        exit; // stop processing the page further
    }

// do some simple validation to check if id exists
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid Room ID</h2>"; // simple error feedback
        exit;
    } 

// prepare a query and send it to the server
// NOTE for simplicity purposes ONLY we are not using prepared queries
// make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT * FROM room WHERE roomid='.$id;
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>
        <!-- Page heading -->
      <h1>Room Details View</h1>
   </header>

   <!-- Breadcrumb menu -->
   <nav>
      <section>
         <h2>
            <a href='listrooms.php'>[Return to the Room listing]</a>
            <a href='index.php'>[Return to the main page]</a>
         </h2>
      </section>
   </nav>

    <!-- Main page section -->
    <main>

        <!-- Page section to view booking details -->
        <section>

            <!-- Form to display the booking details -->
            <form>

<?php
// makes sure we have the Room
if ($rowcount > 0) {  
   echo "<fieldset><legend>Room detail #$id</legend><dl>"; 
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Room name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;
   echo "<dt>Description:</dt><dd>".$row['description']."</dd>".PHP_EOL;
   echo "<dt>Room type:</dt><dd>".$row['roomtype']."</dd>".PHP_EOL;
   echo "<dt>Sleeps:</dt><dd>".$row['beds']."</dd>".PHP_EOL; 
   echo '</dl></fieldset>'.PHP_EOL;  
} else {
	echo "<h2>No room found!</h2>"; // suitable feedback
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
  