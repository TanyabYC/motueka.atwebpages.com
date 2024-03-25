<?php 
// access session functions
    include "checksession.php";

// display login status
    loginStatus();
?>
<!DOCTYPE html>
<!-- Page language set as English New Zealand to attract New Zealand customers -->
<!-- Useful for search engines, screen readers and voice assistants to correctly pronounce content -->
<html lang="en-NZ">

<head>
    <!-- Display the page title in the browser tab to describe the meaning of the page -->
    <title>Register New Customer</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Register Customer">

    <!-- The set of characters and symbols used in the web page and required by the browser -->
    <meta charset="utf-8">

    <!-- The browser should not scale content down. Necessary to make the website responsive on all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Define keywords required by search engines -->
    <meta name="keywords" content="Motueka, Bed & Breakfast, B&B, Accommodation, Register new customer">

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

// the data was sent using a form therefore we use the $_POST instead of $_GET
// check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
// if ($_SERVER["REQUEST_METHOD"] == "POST") { // alternative simpler POST test

        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. ".mysqli_connect_error();
            exit; // stop processing the page further
        };

// validate incoming data - only the first field is done for you in this example - rest is up to you do
        $error = 0; // set an error flag
        $msg = 'Error: ';

// firstname
        if (isset($_POST['firstname']) and !empty($_POST['firstname']) and is_string($_POST['firstname'])) {
            $fn = cleanInput($_POST['firstname']); 
            $firstname = (strlen($fn) > 50)?substr($fn,1,50):$fn; // check length and clip if too big
            // we would also do context checking here for contents, etc
        } else {
            $error++; // bump the error flag
            $msg .= 'Invalid firstname '; // append eror message
            $firstname = '';  
        }
        
// lastname
        $lastname = cleanInput($_POST['lastname']);

// email
        $email = cleanInput($_POST['email']);

// password
        $password = cleanInput($_POST['password']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// // role - 'root' indicates admin user - removed admin role for security reasons but could be reactivated with a subdomain for admin users.
//         if ((strcasecmp($email,"root") == 0) and (strcasecmp($password, "root") == 0)) {  // compare the username and password entered with 'root' of an admin (case-insensitive)
//             $role = 1;  // admin role
//         } else {
//             $role = 0;  // customer role
//         }

// save the customer data if the error flag is still clear
        if ($error == 0) {
            // query to include an admin role - commented out for now
            // $query = "INSERT INTO customer (firstname, lastname, email, password, role) VALUES (?,?,?,?,?)";  // removed SQL query with role variable for security reasons
            // mysqli_stmt_bind_param($stmt,'ssssi', $firstname, $lastname, $email, $hashed_password);
            
            $query = "INSERT INTO customer (firstname, lastname, email, password) VALUES (?,?,?,?)";
            $stmt = mysqli_prepare($db_connection,$query); // prepare the query		
            mysqli_stmt_bind_param($stmt,'ssss', $firstname, $lastname, $email, $hashed_password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>Customer saved</h2>";
        } else {
            echo "<h2>$msg</h2>".PHP_EOL;  // display error message followed by cross-platform new line
        }
        mysqli_close($db_connection); // close the connection once done
    }
?>
        <h1>New Customer Registration</h1>
    </header>

    <!-- Breadcrumb menu -->
    <nav>
        <section>
            <h2>
                <?php if (isAdmin()) { echo "<a href='listcustomers.php'>[Return to the Customer listing]</a>"; } ?>
                <a href='index.php'>[Return to the main page]</a>
            </h2>
        </section>
    </nav>

    <!-- Main page section -->
    <main>

        <!-- Page section containing the customer registration form -->
        <section>
<?php 
    if (isset($_SESSION['loggedin']) and ($_SESSION['loggedin'] == 1) and (!isAdmin())) {   
        echo "<h2>You are already registered!";
        echo "<h3>View your <a href='viewaccount.php'>Account</a></h3>";
    } else {

?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="on">
                <p>
                    <label for="firstname">Name: </label>
                    <input type="text" id="firstname" name="firstname" minlength="1" 
                        maxlength="50" size="30" required> 
                </p> 
                <p>
                    <label for="lastname">Last Name: </label>
                    <input type="text" id="lastname" name="lastname" minlength="1" 
                        maxlength="50" size="30" required> 
                </p>  
                <p>  
                    <label for="email">Email: </label>
                    <input type="email" id="email" name="email" minlength="6" 
                        maxlength="100" size="30" required>
                </p>
                <p>
                    <label for="password">Password: </label>
                    <input type="password" id="password" name="password" minlength="8" 
                        maxlength="255" size="30" required>
                </p> 
                  
                <input type="submit" name="submit" value="Register">
                <a href="listcustomers.php" title="Cancel customer registration">[Cancel]</a>
            </form>
<?php         
    }

    echo "</section></main>";

// Page footer
    include 'footer.php';

?>
</body>
</html>
  