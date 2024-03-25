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
	<title>Delete Booking</title>

	<!-- Web page author -->
	<meta name="author" content="Tanya Suzette Boshoff">

	<!-- Web page description -->
    <meta name="description" content="Delete a room booking at Motueka Bed & Breakfast">

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

<!-- Database interaction and connection -->
<?php
    include "config.php";  // access the database constants
    include "cleaninput.php";  // clean data input
    include "changeemptycontent.php";  // control empty content to and from database

// *** Connect to the database using the constants ***
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// check if the connection was good
    if (mysqli_connect_errno()) {
    	echo 'Error: Unable to connect to MySQL! '.mysqli_connect_error();    	
    	exit;  // stop processing the page further
    }

// Get bookingid from URL and display booking details in form
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
    	$bookingID = $_GET['id'];

    	if (empty($bookingID) or !is_numeric($bookingID)) {  // ID is empty or not numeric
    		echo "<h2>Invalid Booking ID</h2>";  // simple error feedback
    		echo '<h2>Please go back to the previous page and try again.</h2>';
    		exit;
    	}
    } // end of retrieving bookingID from URL

// *** Validation of variables after submitting form ***
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {
    	$error = 0; // clear the error flag
    	$msg = 'Error: ';  // prepare error message

// bookingID (string variable) - try a type conversion
		if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
			$bookingID = cleanInput($_POST['id']);
		} else {
			$error++;  // bump the error flag
			$msg = 'Invalid Booking ID';  // append error message
			$bookingID = 0;
		}
					    
// check error flag still clear and booking id > 0
	    if ($error == 0 and $bookingID > 0) {

// *** Delete records in the database ***
// prepare the statement with placeholders (?) for the variable data
		    $delete_query = "DELETE FROM booking WHERE bookingID = ?";
		    $delete_stmt = mysqli_prepare($db_connection, $delete_query); // prepare query
// associate the ? with variables
		    mysqli_stmt_bind_param($delete_stmt,'i',$bookingID);
		    mysqli_stmt_execute($delete_stmt);
		    mysqli_stmt_close($delete_stmt);
		    echo "<h2>Booking details deleted!</h2>";
	    } else {
	    	echo "<h2>$msg</h2>";
	    }

	} // end of deleting booking

// *** Select record from the database to display ***
// prepare a query and send it to the server
    $booking_query = 'SELECT booking.bookingID, customer.firstname, customer.lastname, room.roomID, room.roomname, checkin, checkout, customer_phone, extras, review FROM booking JOIN room ON booking.roomID = room.roomID JOIN customer ON booking.customerID = customer.customerID WHERE bookingID = '.$bookingID;
    $booking_result = mysqli_query($db_connection, $booking_query);
    $booking_count = mysqli_num_rows($booking_result);
?>

		<!-- Page heading -->
		<h1>Booking preview before deletion</h1>
	</header>

	<!-- Breadcrumb menu -->
	<nav>
		<section>
			<h2>
				<a href="listbookings.php" title="Bookings listing">[Return to the booking listing]</a>
				<a href="index.php" title="Main page">[Return to the main page]</a>
			</h2>
		</section>
	</nav>

	<!-- Main page section -->
	<main>

		<!-- Page section to preview booking details -->
		<section>

<?php
// check if records retrieved successfully
    if ($booking_count > 0) {

    	$booking = mysqli_fetch_assoc($booking_result);

// section heading
    	echo "<header><h2>Booking for ".$booking['firstname']." ";
    	echo $booking['lastname']."</h2></header>";

// display the booking details

		echo "<fieldset><legend>Booking detail #".$bookingID."</legend>";

		echo "<dl><dt>Room name:</dt><dd>".$booking['roomname']."</dd></dl>";
		echo "<dl><dt>Checkin date:</dt><dd>".$booking['checkin']."</dd></dl>";
		echo "<dl><dt>Checkout date:</dt><dd>".$booking['checkout']."</dd></dl>";
		echo "<dl><dt>Contact number:</dt><dd>".$booking['customer_phone']."</dd></dl>";
		echo "<dl><dt>Extras:</dt><dd>";

// check whether extras in database are null and change to 'none'
	    if ($booking['extras'] == NULL) {
	    	echo changeEmptyContent($booking['extras']);
	    } else {
	    	echo $booking['extras'];
	    }
	    echo "</dd></dl>";
	    echo "<dl><dt>Room review:</dt><dd>";

// check whether extras in database are null and change to 'none'
	    if ($booking['review'] == NULL) {
			echo changeEmptyContent($booking['review']);
	    } else {
	    	echo $booking['review'];
	    }
	    echo "</dd></dl></fieldset>";
?>
			<!-- Form to confirm delete action -->
			<form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">	
				<h2>Are you sure you want to delete this Booking?</h2>
				<input type="hidden" name="id" value="<?php echo $bookingID; ?>">
				<input type="submit" name="submit" value="Delete" title="Delete booking">
				<a href="listbookings.php" title="Cancel">[Cancel]</a>
			</form>

<!-- Database closure and memory release of query -->
<?php
    } else {
    	echo '<h2>No booking found, possibly deleted!</h2>';  // suitable feedback
    }    
    mysqli_free_result($booking_result);  // free memory from booking query
    mysqli_close($db_connection);  // close the database connection

?>		
		</section>
	</main>

	<!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>