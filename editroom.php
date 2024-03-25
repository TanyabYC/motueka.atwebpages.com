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
    <title>Edit a room</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Edit a Room">

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
    include "cleaninput.php";

    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    $error=0;
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error();
        exit; // stop processing the page further
    };

// retrieve the roomid from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid room ID</h2>"; //simple error feedback
            exit;
        } 
    }

// the data was sent using a formtherefore we use the $_POST instead of $_GET
// check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {    
// validate incoming data - only the first field is done for you in this example - rest is up to you do
    
// roomID (sent via a form ti is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']); 
        } else {
            $error++; // bump the error flag
            $msg .= 'Invalid room ID '; // append error message
            $id = 0;
        } 

// roomname
       $roomname = cleanInput($_POST['roomname']);

// description
       $description = cleanInput($_POST['description']);

// roomtype
       $roomtype = cleanInput($_POST['roomtype']);

// beds
       $beds = cleanInput($_POST['beds']);
    
// save the room data if the error flag is still clear and room id is > 0
        if ($error == 0 and $id > 0) {
            $query = "UPDATE room SET roomname=?,description=?,roomtype=?,beds=? WHERE roomID=?";
            $stmt = mysqli_prepare($db_connection, $query); //prepare the query
            mysqli_stmt_bind_param($stmt,'ssssi', $roomname, $description, $roomtype, $beds, $id); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>Room details updated.</h2>";  
        } else { 
            echo "<h2>$msg</h2>";
        }      
    }

// locate the room to edit by using the roomID
// we also include the room ID in our form for sending it back for saving the data
    $query = 'SELECT roomID,roomname,description,roomtype,beds FROM room WHERE roomid='.$id;
    $result = mysqli_query($db_connection,$query);
    $rowcount = mysqli_num_rows($result);

?>
        <!-- Page heading -->
        <h1>Room Details Update</h1>
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

    <!-- Main page section -->
    <main>
        
        <!-- Page section to edit a room -->
        <section>

            <!-- Form to edit a room -->
            <form method="POST" action="editroom.php">
                
<?php 
    if ($rowcount > 0) {
        $row = mysqli_fetch_assoc($result);        
?>
                <input type="hidden" name="id" value="<?php echo $id;?>">
                <p>
                    <label for="roomname">Room name: </label>
                    <input type="text" id="roomname" name="roomname" minlength="5" 
                            maxlength="50" value="<?php echo $row['roomname']; ?>" required> 
                </p>
                <p>
                    <label for="description">Description: </label>
                    <input type="text" id="description" name="description" size="100" minlength="5" 
                            maxlength="200" value="<?php echo $row['description']; ?>" required> 
                </p>
                <p>
                    <label for="roomtype">Room type: </label>
                    <input type="radio" id="roomtype" name="roomtype" 
                            value="S" <?php echo $row['roomtype']=='S'?'Checked':''; ?>> Single 
                    <input type="radio" id="roomtype" name="roomtype" 
                            value="D" <?php echo $row['roomtype']=='D'?'Checked':''; ?>> Double
                </p>
                <p>
                    <label for="beds">Sleeps (1-5): </label>
                    <input type="number" id="beds" name="beds" min="1" 
                            max="5" value="1" value="<?php echo $row['beds']; ?>" required> 
                </p>
                <input type="submit" name="submit" value="Update">

<?php 
    } else {
        echo "<h2>room not found with that ID</h2>"; // simple error feedback
    }
    mysqli_close($db_connection); // close the connection once done
?>
            </form>
        </section>
    </main>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
  