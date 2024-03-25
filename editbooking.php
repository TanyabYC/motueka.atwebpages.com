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
	<title>Edit Booking</title>

	<!-- Web page author -->
	<meta name="author" content="Tanya Suzette Boshoff">

	<!-- Web page description -->
    <meta name="description" content="Edit a room booking at Motueka Bed & Breakfast">

	<!-- The set of characters and symbols used in the web page and required by the browser -->
	<meta charset="utf-8">

	<!-- The browser should not scale content down. Necessary to make the website responsive on all devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Display a favicon image to the left of the page title in the browser tab -->
	<link rel="icon" type="image/x-icon" href="./images/favicon.ico">

	<!-- jQuery UI -->
	<link rel="stylesheet" href="jquery-ui-1.13.2.custom/jquery-ui.min.css">
	<script src="jquery-ui-1.13.2.custom/external/jquery/jquery.js"></script>
	<script src="jquery-ui-1.13.2.custom/jquery-ui.min.js"></script>

	<!-- jQuery Date Picker Widget -->
    <script>
    	$(function() {
    		$.datepicker.setDefaults({
    			firstDay: 1,
    			numberOfMonths: [1, 2],
                dateFormat:"dd-mm-yy",
    			changeMonth: true,
    			prevText: "Click for previous months",
    			nextText: "Click for next months"
    		});
    		$("#checkin").datepicker({
    			minDate: "+1d",
    			maxDate: "+1y +1d"
    		});
    		$("#checkout").datepicker({
    			minDate: "+2d",
    			maxDate: "+1y +2d"
    		});
    	});
    </script>
</head>
<body>

	<!-- Page header -->
	<header>

		<!-- Page navigation -->
        <?php include 'navigation.php'; ?>

<!-- Database connection and interaction -->
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

// Get bookingID from URL and display booking details in form
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
    	$bookingID = $_GET['id'];

    	if (empty($bookingID) or !is_numeric($bookingID)) {  // ID is empty or not numeric
    		echo "<h2>Invalid Booking ID!</h2>";  // simple error feedback
    		echo '<h2>Please go back to the previous page and try again.</h2>';
    		exit;
    	}
    }  // end of retrieving bookingID from URL

// *** Validation of variables after submitting form ***
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {
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

// roomID (string variable) - try a type conversion
		if (isset($_POST['room']) and !empty($_POST['room']) and is_integer(intval($_POST['room']))) {			
			$roomID = cleanInput($_POST['room']);
		} else {
			$error++;  // bump the error flag
			$msg = 'Invalid Room ID';  // append error message
			$roomID = 0;
		}

// Validation for processing dates
		date_default_timezone_set('NZ');  // set calendar to NZ time zone

// checkin
		if (isset($_POST['checkin']) and !empty($_POST['checkin']) and is_string($_POST['checkin'])) {
			$checkin_date = cleanInput($_POST['checkin']);  // clean date string
			$date = strtotime($checkin_date);  // convert date string to a Unix timestamp
			$checkin = date("Y-m-d", $date);
		} else {
			$error++;  // bump the error flag
			$msg = 'Invalid check-in';  // append error message
		}

// checkout
		if (isset($_POST['checkout']) and !empty($_POST['checkout']) and is_string($_POST['checkout'])) {
			$checkout_date = cleanInput($_POST['checkout']);  // clean date string
			$date = strtotime($checkout_date);  // convert date string to a Unix timestamp
			$checkout = date("Y-m-d", $date);
		} else {
			$error++;  // bump the error flag
			$msg = 'Invalid	check-out';  // append error message
		}

// verify dates are correct
		if ($checkin >= $checkout) {
			$error++;  // bump the error flag
			$msg = "Invalid dates!<br><br>Ckeckout date cannot be on or before checkin date!";
		}

// customer_phone
		if (isset($_POST['phone']) and !empty($_POST['phone']) and is_string($_POST['phone'])) {
			$phone = cleanInput($_POST['phone']);

			if (strlen($phone) < 11 or strlen($phone) > 13) {
				$error++;  // bump the error flag
				$msg = "Invalid phone number!";
			} else {
				$customer_phone = $phone;				
			}
		} else {
			$error++;  // bump the error flag
			$msg = 'Invalid	phone number';  // append error message
		}	    

// extras - optional user input
	    if (isset($_POST['extras']) and !empty($_POST['extras']) and is_string($_POST['extras'])) {
    		$e = cleanInput($_POST['extras']);

    		if (strlen($e) > 255) {
    			$error++;  // bump the error flag
    			$msg = "Extras is too long. Maximum 255 characters allowed.";
    		} else {
    			$extras = $e;
    		}
	    } elseif ($_POST['extras'] == "") {  // input cleared by user
    		$extras = changeEmptyContent($_POST['extras']);
	    } else {
	    	$error++;  // bump the error flag
	    	$msg = 'Invalid	extras';  // append error message
	    	$extras = NULL;  // reset malicious input
	    }

// review - optional user input
	    if (isset($_POST['review']) and !empty($_POST['review']) and is_string($_POST['review'])) {
    		$r = cleanInput($_POST['review']);

			if (strlen($r) > 255) {
    			$error++;  // bump the error flag
    			$msg = "Review is too long. Maximum 255 characters allowed.";				
			} else {
				$review = $r;
			}
	    } elseif ($_POST['review'] == "") {  // input cleared by user
    		$review = changeEmptyContent($_POST['review']);
	    } else {
	    	$error++;  // bump the error flag
	    	$msg = 'Invalid	review';  // append error message
	    	$review = NULL;  // reset malicious input
	    }
					    
// check error flag still clear, booking id > 0, room id > 0
	    if ($error == 0 and $bookingID > 0 and $roomID > 0) {

// *** Update records in the database ***
// prepare the statement with placeholders (?) for the variable data
		    $update_query = "UPDATE booking SET roomID=?, checkin=?, checkout=?, customer_phone=?, extras=?, review=? WHERE bookingID = ?";
		    $update_stmt = mysqli_prepare($db_connection, $update_query); // prepare the query

// associate the ? with variables
		    mysqli_stmt_bind_param($update_stmt, 'isssssi', $roomID, $checkin, $checkout, $customer_phone, $extras, $review, $bookingID);
		    mysqli_stmt_execute($update_stmt);
		    mysqli_stmt_close($update_stmt);
		    echo "<h2>Booking updated!</h2>";
	    } else {
	    	echo "<h2>$msg</h2>";
	    }

	}  // end of updating booking

// *** Select booking from the database to display ***
// prepare a query and send it to the server
    $booking_query = "SELECT customer.firstname, customer.lastname, booking.roomID, room.roomname, room.roomtype, room.beds, checkin, checkout, customer_phone, extras, review FROM booking JOIN customer ON booking.customerID = customer.customerID JOIN room ON booking.roomID = room.roomID WHERE bookingID = ".$bookingID;
    $booking_result = mysqli_query($db_connection, $booking_query);  // saves SQL object into variable
    $booking_count = mysqli_num_rows($booking_result);  // save row count of SQL object into a variable    
?>

		<!-- Page heading -->
		<h1>Edit a booking</h1>
	</header>

	<!-- Breadcrumb menu -->
	<nav>
		<section>
			<h2>
				<a href="listbookings.php" title="Bookings listing">[Return to the Bookings listing]</a>
				<a href="index.php" title="Main page">[Return to the main page]</a>
			</h2>
		</section>
	</nav>

	<!-- Main page section -->
	<main>

		<!-- Page section to edit a booking -->
		<section>
<?php 
// check if records retrieved successfully
    if ($booking_count > 0) {

    	$booking = mysqli_fetch_assoc($booking_result);
?>

			<!-- Section heading -->
			<header>
				<h2>Booking made for <?php echo $booking['firstname']." ".$booking['lastname']?></h2>
			</header>

			<!-- Form to edit a booking -->
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

				<input type='hidden' name='id' value='<?php echo $bookingID; ?>'>

				<p>
				<!-- select a room -->
				<label for='room'>Room (name,type,beds):</label>				

<?php 	
// *** Select rooms from the database to display ***
	    $room_query = "SELECT roomID, roomname, roomtype, beds FROM room ORDER BY roomID";
	    $room_result = mysqli_query($db_connection, $room_query);
	    $room_count = mysqli_num_rows($room_result);

	    if ($room_count > 0) {  // rooms found

	    	echo "<select id='room' name='room' required='required'>";

	    	while ($room = mysqli_fetch_assoc($room_result)) {  // display rooms in dropdown
	    		echo "<option value='".$room['roomID']."' ";

			    if ($room['roomID'] == $booking['roomID']) {
			    	echo "selected='selected'";  // display booked room
			    }
			    echo ">";
			    echo $room['roomname'].", ".$room['roomtype'].", ".$room['beds'];
			    echo "</option>";

	    	}
	    	echo "</select>";
	    	mysqli_free_result($room_result);  // free memory from room query

		} else {
			echo "<h2>No rooms found!</h2>";
		}
?>						
				</p>

				<p>
					<!-- Choose a check-in date -->
					<label for="checkin">Checkin date:</label>
					<input 
						id="checkin" type="text" name="checkin" 
						value="<?php echo $booking['checkin']; ?>" 
						readonly required>			
				</p>

				<p>
					<!-- Choose a check-out date -->
					<label for="checkout">Checkout date:</label>
					<input 
						id="checkout" type="text" name="checkout" 
						value="<?php echo $booking['checkout']; ?>" 
						readonly required>
				</p>

				<p>
					<!-- Enter a contact phone number -->
					<label for="phone">Contact number:</label>
					<input 
						id="phone" type="tel" name="phone" 
						value="<?php echo $booking['customer_phone']; ?>" pattern="[0][0-9]{2}\s[0-9]{3}\s[0-9]{3}[0-9]?[0-9]?" 
						minlength="11" maxlength="13" 
						required autocomplete="on">
				</p>

				<p>
					<!-- Enter booking extras -->
					<label for="extras">Booking extras:</label>
					<textarea id="extras" name="extras" rows="5" cols="30" maxlength="255">
<?php 
// check whether extras in database are null and change to 'none'
	    if ($booking['extras'] == NULL) {
	    	echo changeEmptyContent($booking['extras']);
	    } else {				    	
		    echo $booking['extras']; 
	    }
?>
					</textarea>
				</p>

				<p>
					<!-- Enter room review -->
					<label for="review">Room review:</label>
					<textarea id="review" name="review" rows="5" cols="30" maxlength="255">
<?php 
// check whether extras in database are null and change to 'none'
	    if ($booking['review'] == NULL) {
			echo changeEmptyContent($booking['review']);
	    } else {
	    	echo $booking['review'];
	    }			 
?>
					</textarea>
				</p>

				<!-- Submit the form button -->
				<input type="submit" name="submit" value="Update" title="Update booking">
				<a href="deletebooking.php?id=<?php echo $bookingID ?>" title="Delete booking">[Delete]</a>
				<a href="listbookings.php" title="Cancel update">[Cancel]</a>

<?php
	    mysqli_free_result($booking_result);  // free memory from booking query
	} else { // simple error feedback		
		echo '<h2>Booking not found with that ID and failed to load.</h2>';
		echo '<h2>Go to the previous page and try again.</h2>'; 
	}  // end of retrieving booking details
    mysqli_close($db_connection);  // Close database connection    
?>

			</form>
		</section>
	</main>

	<!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>