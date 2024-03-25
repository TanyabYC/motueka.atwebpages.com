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
	<title>View Booking</title>

	<!-- Web page author -->
	<meta name="author" content="Tanya Suzette Boshoff">

	<!-- Web page description -->
    <meta name="description" content="View room booking details at Motueka Bed & Breakfast">

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
    include "changeemptycontent.php";  // control empty content from database

// Connect to the database using the constants
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// check if the connection was good
    if (mysqli_connect_errno()) {
    	echo 'Error: Unable to connect to MySQL! '.mysqli_connect_error();
    	exit;  // stop processing the page further
    }

// get booking id from URL and validate if exists
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
    	$bookingID = $_GET['id'];
    	if (empty($bookingID) or !is_numeric($bookingID)) {
    		echo "<h2>Invalid Booking ID</h2>";
    		echo '<h3>Please go back to the previous page and try again.</h3>';
    		exit;
    	}
    }

// *** Select record from the database to display ***
// prepare a query and send it to the server
    $query = "SELECT booking.bookingID, customer.firstname, customer.lastname, room.roomID, room.roomname, booking.checkin, booking.checkout, booking.customer_phone, booking.extras, booking.review FROM booking JOIN room ON booking.roomID = room.roomID JOIN customer ON booking.customerID = customer.customerID WHERE bookingID = ".$bookingID;
    $result = mysqli_query($db_connection, $query);
    $row_count = mysqli_num_rows($result);
?>
		<!-- Page heading -->
		<h1>Booking Details View</h1>
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

		<!-- Page section to view booking details -->
		<section>

<!-- display booking details from the database -->
<?php 

// check if records retrieved successfully
    if ($row_count > 0) {

    	$booking = mysqli_fetch_assoc($result);

// section heading
    	echo "<header><h2>Booking for ".$booking['firstname']." ";
    	echo $booking['lastname']."</h2></header>";

// form to display the booking details
    	echo "<form><fieldset><legend>Booking detail #".$bookingID."</legend>";

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
// check whether extras in database are null  and change to 'none'
    	if ($booking['review'] == NULL) {
			echo changeEmptyContent($booking['review']);
    	} else {
    		echo $booking['review'];
    	}
    	echo "</dd></dl></fieldset>";

    } else {
    	echo "<h2>No booking found!</h2>"; // suitable feedback
    }    
    mysqli_free_result($result);  // free any memory used by the query
    mysqli_close($db_connection);  // close the database connection
?>
			</form>		
		</section>
	</main>

	<!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>