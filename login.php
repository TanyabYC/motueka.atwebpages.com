<?php
// access session functions
    include 'checksession.php';

// assign customer log in status to a variable to display different page sections
    $loginStatus = loginStatus();
?>
<!DOCTYPE html>
<!-- Page language set as English New Zealand to attract New Zealand customers -->
<!-- Useful for search engines, screen readers and voice assistants to correctly pronounce content -->
<html lang="en-NZ">
<head>
	<!-- Display the page title in the browser tab to describe the meaning of the page -->
	<title>Login</title>

	<!-- Web page author -->
	<meta name="author" content="Tanya Suzette Boshoff">

	<!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Login">

	<!-- The set of characters and symbols used in the web page and required by the browser -->
	<meta charset="utf-8">

	<!-- The browser should not scale content down. Necessary to make the website responsive on all devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Define keywords required by search engines -->
    <meta name="keywords" content="Motueka, Bed & Breakfast, B&B, Accommodation, Login">

	<!-- Display a favicon image to the left of the page title in the browser tab -->
	<link rel="icon" type="image/x-icon" href="./images/favicon.ico">
</head>
<body>

	<!-- Page header -->
	<header>

		<!-- Page navigation -->
        <?php include 'navigation.php'; ?>

<?php 


// database interaction    
	    include "config.php"; // load in any variables
	    include "cleaninput.php";

	    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

	    if (mysqli_connect_errno()) {
	        echo  "Error: Unable to connect to MySQL. ".mysqli_connect_error();
	        exit; // stop processing further
	    }

// the data was sent using a form therefore we use the $_POST instead of $_GET
// check if we are saving data first by checking if the submit button exists in the array
	    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Login')) {

// validate incoming data
	        $error = 0; // set an error flag
	        $msg = 'Error: ';

// if the login form has been filled in 

// username (also the email address)
		    if (isset($_POST['username']) and !empty($_POST['username']) and is_string($_POST['username'])) {

		    	$username = cleaninput($_POST['username']);  

		    } else {
	            $error++; // increment the error flag
	            $msg .= 'Invalid e-mail address entered '; // append error message
	            $username = ' ';
		    }

// password
		    if (isset($_POST['password']) and !empty($_POST['password']) and is_string($_POST['password'])) {

		        $password = cleaninput($_POST['password']);

		    } else {
		    	$error++;  // increment the error flag
		    	$msg .= 'Invalid password entered ';  // append error message
		    	$password = ' ';
		    }

// *** Select records from the database to verify ***
// prepare a query and send it to the server
		    if ($error == 0) {

			    $stmt = mysqli_stmt_init($db_connection);		    
		        mysqli_stmt_prepare($stmt, "SELECT customerID, password, role FROM customer WHERE email=?");

		        mysqli_stmt_bind_param($stmt,'s', $username);  // username is used as the email   
		        mysqli_stmt_execute($stmt);

			    mysqli_stmt_bind_result($stmt, $customerID, $hashed_password, $role);
			    mysqli_stmt_fetch($stmt);

		    	if (!$customerID) {  // customerID not found for username/email entered by the user

		            echo '<h2 class="error">Unable to find customer with username "'.$username.'"</h2>';
		            echo '<h3>Please register an account</h3>';

		    	} else {

// the password entered by user is verified against the password in the database
		            if (password_verify($password, $hashed_password)) {

		            	login($role, $username);  // log user in

		            } else {

		                echo '<h2>Username or password combination is wrong! ';
		                echo 'Please try again.</h2>';

		            }

		        }
			    mysqli_stmt_close($stmt);  // free any memory used by the query

		    } else {
	            echo "<h2>$msg</h2>";
		    }
		}
    	mysqli_close($db_connection); // close the connection once done

?>

		<!-- Page heading -->
		<h1>Customer Login</h1>

	</header>

    <!-- Breadcrumb menu -->
    <nav>
        <section>
            <h2>
                <a href="index.php" title="Main page">[Return to main page]</a>
            </h2>
        </section>
    </nav>

	<!-- Main page section -->
	<main>

<?php
    if (!$loginStatus) {  // customer is not logged in, display login form
    	echo "<h2>Please log in to access your account</h2>";
?>

		<!-- Login section -->
		<section>

			<!-- Login form -->
			<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
				<p>
					<label for="username">Username: </label>
					<input type="text" id="username" minlength="4" 
                        maxlength="100" size="30" name="username" autocomplete="on" required>

					<!-- Commented out for admin testing purposes to allow 'root'
					<input type="email" id="username" minlength="6" 
                        maxlength="100" size="30" name="username" required>	 -->		
				</p>
				<p>
					<label for="password">Password: </label> 
					<input type="password" id="password" minlength="1" 
						maxlength="255" size="30" name="password" required>

					<!-- Commented out for admin testing purposes to allow 'root'
					<input type="password" id="password" minlength="8" 
						maxlength="255" size="30" name="password" required> -->
				</p>
				<input type="submit" name="submit" value="Login">
				<a href="registercustomer.php" title="Register an account">[New Customer Registration]</a>
			</form>
		</section>
			
<?php
    } else {  // customer is logged in, display logout option

?>

		<!-- Log out section -->
		<section>
			<h2>You are already logged in!</h2>
			<h3>Would you like to log out?</h3>			
			<p>
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<input type="submit" name="submit" value="Logout">
				</form>
			</p>
		</section>
<?php 
	}

// logout processing
    if (($_SERVER['REQUEST_METHOD'] == "POST") and ($_POST['submit'] == 'Logout')) {    	
    	logout();  // log customer out
    }
?>
	</main>

	<!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>