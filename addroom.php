<?php
// access session functions
    include 'checksession.php';

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
    <title>Add a new room</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Add a Room">

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
    include "cleaninput.php";

// the data was sent using a formtherefore we use the $_POST instead of $_GET
// check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
        // if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test
        include "config.php"; // load in any variables
        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
            exit; // stop processing the page further
        };

// validate incoming data - only the first field is done for you in this example - rest is up to you do

// roomname
        $error = 0; //clear our error flag
        $msg = 'Error: ';
        if (isset($_POST['roomname']) and !empty($_POST['roomname']) and is_string($_POST['roomname'])) {
            $fn = cleanInput($_POST['roomname']); 
            $roomname = (strlen($fn)>50)?substr($fn,1,50):$fn; // check length and clip if too big
            // we would also do context checking here for contents, etc
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid roomname '; //append eror message
            $roomname = '';
        } 
 
// description
        $description = cleanInput($_POST['description']);

// roomtype
        $roomtype = cleanInput($_POST['roomtype']);

// beds    
        $beds = cleanInput($_POST['beds']);
       
// save the room data if the error flag is still clear
        if ($error == 0) {
            $query = "INSERT INTO room (roomname,description,roomtype,beds) VALUES (?,?,?,?)";
            $stmt = mysqli_prepare($db_connection, $query); // prepare the query
            mysqli_stmt_bind_param($stmt,'sssd', $roomname, $description, $roomtype, $beds); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>New room added to the list</h2>";        
        } else { 
            echo "<h2>$msg</h2>".PHP_EOL;
        }
        mysqli_close($db_connection); //close the connection once done
    }
?>
    <h1>Add a new room</h1>
    </header>

    <!-- Breadcrumb menu -->
    <nav>
        <section>
            <h2>
                <a href='listrooms.php'>[Return to the room listing]</a>
                <a href='index.php'>[Return to the main page]</a>
            </h2>            
        </section>
    </nav>

    <form method="POST" action="addroom.php">
        <p>
            <label for="roomname">Room name: </label>
            <input type="text" id="roomname" name="roomname" minlength="5" 
                maxlength="50" required> 
        </p> 
        <p>
            <label for="description">Description: </label>
            <input type="text" id="description" size="100" name="description" 
                minlength="5" maxlength="200" required> 
        </p>  
        <p>  
            <label for="roomtype">Room type: </label>
            <input type="radio" id="roomtype" name="roomtype" 
                value="S"> Single 
            <input type="radio" id="roomtype" name="roomtype" 
                value="D" Checked> Double 
        </p>
        <p>
            <label for="beds">Sleeps (1-5): </label>
            <input type="number" id="beds" name="beds" min="1" max="5" 
                value="1" required> 
        </p> 
          
        <input type="submit" name="submit" value="Add">
    </form>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
  