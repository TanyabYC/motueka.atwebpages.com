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
    <title>Delete Room</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Delete a Room">

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
    include "config.php"; // load in any variables

    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// insert DB code from here onwards
// check if the connection was good
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; // stop processing the page further
    }

// function to clean input but not validate type and content
    function cleanInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

// retrieve the roomid from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid Room ID</h2>"; //simple error feedback
            exit;
        } 
    }

// the data was sent using a formtherefore we use the $_POST instead of $_GET
// check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {
        $error = 0; //clear our error flag
        $msg = 'Error: ';

// RoomID (sent via a form it is a string not a number so we try a type conversion!)
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']);
        } else {
            $error++; // bump the error flag
            $msg .= 'Invalid Room ID '; // append error message
            $id = 0;
        }

// save the Room data if the error flag is still clear and Room id is > 0
        if ($error == 0 and $id > 0) {
            $query = "DELETE FROM room WHERE roomID=?";
            $stmt = mysqli_prepare($db_connection, $query); // prepare the query
            mysqli_stmt_bind_param($stmt,'i', $id); 
            try {
                mysqli_stmt_execute($stmt); 
                echo "<h2>Room details deleted.</h2>"; 
            } catch (Exception $e) {
                echo "<h2>Rooms with bookings cannot be deleted!</h2>";            
            }
            mysqli_stmt_close($stmt);        
        } else {
            echo "<h2>$msg</h2>".PHP_EOL;
        }      
    }

// prepare a query and send it to the server
// NOTE for simplicity purposes ONLY we are not using prepared queries
// make sure you ALWAYS use prepared queries when creating custom SQL like below
    $query = 'SELECT * FROM room WHERE roomID='.$id;
    $result = mysqli_query($db_connection,$query);
    $rowcount = mysqli_num_rows($result); 
?>

        <!-- Page heading -->
        <h1>Room details preview before deletion</h1>
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

        <!-- Page section to preview room details -->
        <section>

<?php

// makes sure we have the Room
    if($rowcount > 0) {
        echo "<fieldset><legend>Room detail #$id</legend><dl>";

        $row = mysqli_fetch_assoc($result);
        echo "<dt>Room name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;
        echo "<dt>Description:</dt><dd>".$row['description']."</dd>".PHP_EOL;
        echo "<dt>Room type:</dt><dd>".$row['roomtype']."</dd>".PHP_EOL;
        echo "<dt>Sleeps:</dt><dd>".$row['beds']."</dd>".PHP_EOL; 
        echo "</dl></fieldset>".PHP_EOL;  
?>

            <!-- Confirm delete action -->
            <form method="POST" action="deleteroom.php">
                <h2>Are you sure you want to delete this Room?</h2>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" name="submit" value="Delete">
                <a href="listrooms.php">[Cancel]</a>
            </form>

<?php
    } else {
        echo "<h2>No room found, possibly deleted!</h2>"; // suitable feedback
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
