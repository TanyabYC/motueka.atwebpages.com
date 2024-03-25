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
    <title>Edit Customer</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Edit a Customer">

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
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
// validate incoming data - only the first field is done for you in this example - rest is up to you do
        $error = 0; // clear our error flag
        $msg = 'Error: ';  
     
// customerID (sent via a form ti is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']); 
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid Customer ID '; //append error message
            $id = 0;  
        }

// firstname
        $firstname = cleanInput($_POST['firstname']);

// lastname
        $lastname = cleanInput($_POST['lastname']);

// email
        $email = cleanInput($_POST['email']);         
    
// save the customer data if the error flag is still clear and customer id is > 0
        if ($error == 0 and $id > 0) {
            $query = "UPDATE customer SET firstname=?,lastname=?,email=? WHERE customerID=?";
            $stmt = mysqli_prepare($db_connection,$query); //prepare the query
            mysqli_stmt_bind_param($stmt,'sssi', $firstname, $lastname, $email, $id); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>Customer details updated.</h2>";  
        } else {
            echo "<h2>$msg</h2>";
        }      
    }

//locate the customer to edit by using the customerID
//we also include the customer ID in our form for sending it back for saving the data
    $query = 'SELECT customerID,firstname,lastname,email FROM customer WHERE customerid='.$id;
    $result = mysqli_query($db_connection, $query);
    $rowcount = mysqli_num_rows($result);

?>
        <!-- Page heading -->
        <h1>Customer Details Update</h1>
    </header>

    <!-- Breadcrumb menu -->
    <nav>
        <section>
            <h2>
                <a href='viewaccount.php'>[Return to your Account details view]</a>
<?php 
    if (isAdmin()) {
        echo "</h2><h2>";
        echo "<a href='listcustomers.php'>[Return to the Customer listing]</a>";
    } 
?>
                <a href='index.php'>[Return to the main page]</a>
            </h2>
        </section>
    </nav>

    <!-- Main page section -->
    <main>
        
        <!-- Page section to edit a customer -->
        <section>

            <!-- Form to edit a customer -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

<?php 
    if ($rowcount > 0) {
        $row = mysqli_fetch_assoc($result);
?>
                <input type="hidden" name="id" value="<?php echo $id;?>">
                <p>
                    <label for="firstname">First name: </label>
                    <input type="text" id="firstname" name="firstname" minlength="1" 
                            maxlength="50" required value="<?php echo $row['firstname']; ?>">
                </p>
                <p>
                    <label for="lastname">Last name: </label>
                    <input type="text" id="lastname" name="lastname" minlength="1" 
                            maxlength="50" required value="<?php echo $row['lastname']; ?>">
                </p>
                <p>
                    <!-- Email/Username should be readonly to protect the web administrator username -->
                    <label for="email">Email: </label>
                    <input type='email' id='email' name='email' maxlength='100' 
                    size='50' required readonly value='<?php echo $row['email']; ?>'>
                </p>
                <input type='submit' name='submit' value='Update'>
<?php 
    } else { 
        echo "<h2>Customer not found with that ID</h2>"; // simple error feedback
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
  