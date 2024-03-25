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
	<title>Current Bookings</title>

	<!-- Web page author -->
	<meta name="author" content="Tanya Suzette Boshoff">

	<!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Current Bookings">

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

<!-- Connect to database -->
<?php
// access the database constants
    include "config.php";
    include "cleaninput.php";  // clean data input

// *** Connect to the database using the constants ***
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// check if the connection was good
    if (mysqli_connect_errno()) {
    	echo "Error: Unable to connect to MySQL! ".mysqli_connect_error();
    	exit;  // stop processing the page further
    }
?>

		<!-- Page heading -->
		<h1>Current Bookings</h1>
	</header>

	<!-- Breadcrumb menu -->
	<nav>
		<section>
			<h2>
				<a href="makebooking.php" title="Make a booking">[Make a booking]</a>
				<a href="index.php" title="Main page">[Return to main page]</a>
			</h2>
		</section>
	</nav>

	<!-- Main page section -->
	<main>
		
		<!-- Page section containing the current bookings -->
		<section>

<!-- Database interaction, memory release and closure -->
<?php 

    if (isAdmin()) {
    
// *** Select records from the database to display if logged in as admin with userid (role) of 1 ***
// prepare a query and send it to the server
	    $admin_query = "SELECT booking.bookingID, room.roomname, booking.checkin, booking.checkout, customer.lastname, customer.firstname FROM booking JOIN room ON booking.roomID = room.roomID JOIN customer ON booking.customerID = customer.customerID ORDER BY booking.checkin";
	    $admin_result = mysqli_query($db_connection, $admin_query);
	    $admin_count = mysqli_num_rows($admin_result);


// check if records retrieved successfully
	    if ($admin_count > 0) {

	    	echo "<table border='1'>"; // table for current bookings for all customers
	    	echo "<thead><tr><th>Booking (room, dates)</th><th>Customer</th><th>Action</th></thead>";  // table header

// display bookings from database
	    	while ($admin_row = mysqli_fetch_assoc($admin_result)) {

	        	$bookingID = $admin_row['bookingID'];

			    echo '<tr><td>'.$admin_row['roomname'].', '.$admin_row['checkin'].', '.$admin_row['checkout'].'</td>';
	    		echo '<td>'.$admin_row['lastname'].', '.$admin_row['firstname'].'</td>';

				echo '<td><a href="viewbooking.php?id='.$bookingID.'" title="View booking details">[view]</a>';
				echo '<a href="editbooking.php?id='.$bookingID.'" title="Edit booking">[edit]</a>';
				echo '<a href="#?id='.$bookingID.'" title="Manage booking reviews">[manage reviews]</a>';
				echo '<a href="deletebooking.php?id='.$bookingID.'" title="Delete booking">[delete]</a></td></tr>';	
	    	}

	    } else {
	    	echo "<h2>No bookings found!</h2>"; // suitable feedback
	    }
	    mysqli_free_result($admin_result);  // free any memory used by the query

    } else {  // not admin

// validate session username
	    if (isset($_SESSION['username']) and !empty($_SESSION['username']) and is_string($_SESSION['username'])) {

	    	$username = cleanInput($_SESSION['username']);

	    } else {  // session error
	    	logout();
	    }

// *** Select record from the database to display if logged in as customer with userid (role) of 0 ***
// prepare a query and send it to the server
	    $customer_query = "SELECT booking.bookingID, room.roomname, booking.checkin, booking.checkout, customer.lastname, customer.firstname FROM booking JOIN room ON booking.roomID = room.roomID JOIN customer ON booking.customerID = customer.customerID WHERE customer.email = '".$username."' ORDER BY booking.checkin";
	    $customer_result = mysqli_query($db_connection, $customer_query);
	    $customer_count = mysqli_num_rows($customer_result);

	    if ($customer_count > 0) {

	    	// section heading
	    	echo "<header><h2>Your Bookings</h2></header>";

	    	// table with current bookings for this customer
	    	echo "<table border='1'>";

	    	// table header
	    	echo "<thead><tr><th>Booking (room, dates)</th><th>Customer</th><th>Action</th></thead>";  

// display bookings from database
	    	while ($customer_row = mysqli_fetch_assoc($customer_result)) {

	        	$bookingID = $customer_row['bookingID'];

			    echo '<tr><td>'.$customer_row['roomname'].', '.$customer_row['checkin'].', '.$customer_row['checkout'].'</td>';
	    		echo '<td>'.$customer_row['lastname'].', '.$customer_row['firstname'].'</td>';

				echo '<td><a href="viewbooking.php?id='.$bookingID.'" title="View booking details">[view]</a>';
				echo '<a href="editbooking.php?id='.$bookingID.'" title="Edit booking">[edit]</a>';
				echo '<a href="#?id='.$bookingID.'" title="Manage booking reviews">[manage reviews]</a>';
				echo '<a href="deletebooking.php?id='.$bookingID.'" title="Delete booking">[delete]</a></td>';
				echo '</tr>';

	    	}

	    } else {
		    	echo "<h2>No bookings found!</h2>"; // suitable feedback
	    }
	    mysqli_free_result($customer_result);  // free any memory used by the query

    }
// *** close the database connection ***
    mysqli_close($db_connection);

?>
			</table>
		</section>
	</main>

	<!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>