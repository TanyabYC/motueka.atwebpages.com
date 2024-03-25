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
	<title>Make Booking</title>

	<!-- Web page author -->
	<meta name="author" content="Tanya Suzette Boshoff">

	<!-- Web page description -->
    <meta name="description" content="Make a room booking at Motueka Bed & Breakfast">

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
    	$(document).ready(function() {
    		$.datepicker.setDefaults({
    			firstDay: 1,
    			numberOfMonths: [1, 2],
                dateFormat:"yy-mm-dd",
    			changeMonth: true,
    			prevText: "Click for previous months",
    			nextText: "Click for next months"
    		});
    		$("#checkin").datepicker({
    			minDate: "+1d",
    			maxDate: "+1y +1d",
                altField: "#checkinsearch",
                altFormat: "dd-mm-yy"
    		});
    		$("#checkout").datepicker({
    			minDate: "+2d",
    			maxDate: "+1y +2d",
    			altField: "#checkoutsearch",
    			altFormat: "dd-mm-yy"
    		});
    	});
    </script>

    <script>
    	document.getElementById("checkout").onclick = refreshSearchForm;
    	function refreshSearchForm() {
		    	document.getElementById("searchform").reset();
    	}
    	
    	function searchResult() {
    		var message = document.getElementById("message");  // get paragraph that displays a message to the user

    		// initialise variables
    		var fromdate = "";
    		var enddate = "";

    		// get dates as a string
    		var checkindate = document.forms["searchform"]["checkinsearch"].value;
    		var checkoutdate = document.forms["searchform"]["checkoutsearch"].value;
			
			// check whether any date selected in datepicker
    		if (checkindate == null || checkindate == "" || checkoutdate == null || checkoutdate == "") {  // no dates selected by user

    			alert("Please select dates from the booking form before searching for availability!");
    			return;
    		} 

    		// convert date strings to arrays
    		checkindate = checkindate.split('-');
    		checkoutdate = checkoutdate.split('-');

    		/* NOTE: JavaScript counts months from 0 to 11: January = 0 and December = 11 */
	    	// from date search
    		var fromyear = checkindate[2];
    		var frommonth = checkindate[1]-1;
    		var fromday = checkindate[0];

    		// prepare the from date
    		var fdate = new Date(fromyear, frommonth, fromday);  // create date object
    		fdate.setMinutes(fdate.getMinutes() - fdate.getTimezoneOffset());  // set the timezone
    		fromdate = fdate.toISOString().slice(0,10);  // convert date object to a string so that it can be sent to the server

	    	// end date search
    		var endyear = checkoutdate[2];
    		var endmonth = checkoutdate[1]-1;
    		var endday = checkoutdate[0];

    		// prepare the end date
    		var edate = new Date(endyear, endmonth, endday);  // create date object
    		edate.setMinutes(edate.getMinutes() - edate.getTimezoneOffset());  // set the timezone
    		enddate = edate.toISOString().slice(0,10);  // convert date object to a string so that it can be sent to the server

            // create XMLHttpRequest object and initialise variable
            const xmlhttp = new XMLHttpRequest();
      
		    xmlhttp.onreadystatechange = function() {  // ready state changes

		    	if (this.readyState==4 && this.status==200) {  // response is ready ("OK")

		        	// take JSON text from the server and convert it to JavaScript objects
		        	// matchedRooms will become a two dimensional array of our rooms much like 
		        	// a PHP associative array
                    const matchedrooms = xmlhttp.response;

		    		var tbl = document.getElementById("tblrooms");  // get the table to show result
			          
	                // get length of rows currently displaying in HTML table
		        	var rowcount = tbl.rows.length;

		        	// clear any existing rows from any previous searches
		        	// if this is not cleared rows will just keep being added
		        	for (var i = 1; i < rowcount; i++) {
		        		
		        		// delete from the top - row 0 is the table header we keep
		        		tbl.deleteRow(1);
		        	}

		        	if (matchedrooms != null) {

			        	/* populate the table */

			        	// loop through json data (object with various layers of data from the server)
			        	// matchedRooms.length is the size of our array
			        	for (var i = 0; i < matchedrooms.length; i++) {

		                    // assign json data to variables
			        		var roomid 		= matchedrooms[i]['roomID'];
			        		var roomname 	= matchedrooms[i]['roomname'];
			        		var roomtype 	= matchedrooms[i]['roomtype'];
			        		var beds 		= matchedrooms[i]['beds'];

			        		// create a table row with four cells for each json data found - order by 'roomID' as per query to server
			        		var tr = tbl.insertRow(-1);

			        		var tabcell = tr.insertCell(-1);
			        		tabcell.innerHTML = roomid; // roomid

			        		var tabcell = tr.insertCell(-1);
			        		tabcell.innerHTML = roomname; // roomname

			        		var tabcell = tr.insertCell(-1);
			        		tabcell.innerHTML = roomtype; // roomtype

			        		var tabcell = tr.insertCell(-1);
			        		tabcell.innerHTML = beds; // beds  
			            }

					    if (matchedrooms.length == 1) {

                                // display successful message of 1 room found to user
                                document.getElementById("message").innerText = matchedrooms.length + " rooms available";
                                alert("1 room found");
					    } else {

                                // display successful message of many rooms found to user
                                document.getElementById("message").innerText = matchedrooms.length + " rooms available";
                                alert(matchedrooms.length + " rooms found");
                        }
		        	} else { // no room matches
                            
                        // don't populate the table; display a message to the user
                        document.getElementById("message").innerText = "No rooms available";
                    }
		    	}
		    }

            // call our php file (the AJAX server file) to look for 1/more rooms matching the searchstring, asynchronously
            xmlhttp.open("GET","roomsearch.php?fd=" + fromdate + "&ed=" + enddate,true);

            // set response type to 'json' to process JSON data
            xmlhttp.responseType = 'json';

            // send search string to php page (the AJAX server page) to query the database
            xmlhttp.send();
    	}

    </script>
</head>
<body>

	<!-- Page header -->
	<header>

		<!-- Page navigation -->
        <?php include 'navigation.php'; ?>

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

// *** Validation of variables after submitting booking form ***
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
    	$error = 0; // clear the error flag
    	$msg = 'Error: ';  // prepare error message

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
		if (isset($_POST['checkin']) and !empty($_POST['checkin'])) {
			$checkin_date = cleanInput($_POST['checkin']);  // clean date string
			$date = strtotime($checkin_date);  // convert date string to a Unix timestamp
			$checkin = date("Y-m-d", $date);
		} else {
			$error++;  // bump the error flag
			$msg = 'Invalid check-in';  // append error message
		}

// checkout
		if (isset($_POST['checkout']) and !empty($_POST['checkout'])) {
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

// customerID (string variable) - try a type conversion
		if (isset($_POST['customerID']) and !empty($_POST['customerID']) and is_integer(intval($_POST['customerID']))) {		
			$customerID = cleanInput($_POST['customerID']);
		} else {
			$error++;  // bump the error flag
			$msg = 'Invalid Room ID';  // append error message
			$customerID = 0;
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

    		$extras = cleanInput($_POST['extras']);

    		if (strlen($extras) > 255) {
    			$error++;  // bump the error flag
    			$msg = "Extras is too long. Maximum 255 characters allowed.";
		    	$valid_extras = NULL;  // reset incorrect input
    		} else {
    			$valid_extras = $extras;
    		}
	    } elseif ($_POST['extras'] == "") {  // input cleared by user
    		$valid_extras = changeEmptyContent($_POST['extras']);  // change input to 'none'
	    } else {
	    	$error++;  // bump the error flag
	    	$msg = 'Invalid	extras';  // append error message
	    	$valid_extras = NULL;  // reset malicious input
	    }
					    
// check error flag still clear, customer id > 0, room id > 0
	    if ($error == 0 and $customerID > 0 and $roomID > 0) {

// *** Insert records in the database ***
// prepare the statement with placeholders (?) for the variable data
		    $insert_query = "INSERT INTO booking (roomID, checkin, checkout, customerID, customer_phone, extras) VALUES (?,?,?,?,?,?)";
		    $insert_stmt = mysqli_prepare($db_connection, $insert_query); //prepare the query

// associate the ? with variables
		    mysqli_stmt_bind_param($insert_stmt, 'isssss', $roomID, $checkin, $checkout, $customerID, $customer_phone, $valid_extras);
		    mysqli_stmt_execute($insert_stmt);
		    mysqli_stmt_close($insert_stmt);
		    echo "<h2>Booking made!</h2>";
	    } else {
	    	echo "<h2>$msg</h2>";
	    }

	}  // end of making a booking

?>
		<!-- Page heading -->
		<h1>Make a booking</h1>
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

		<!-- Page section to make a booking -->
		<section>

<?php
// validate session username
    if (isset($_SESSION['username']) and !empty($_SESSION['username']) and is_string($_SESSION['username'])) {
    	$username = cleanInput($_SESSION['username']);

    } else {  // session error
    	logout();
    }

// *** Select record from the database to display ***
// prepare a query and send it to the server
    $customer_query = "SELECT customerID, firstname, lastname FROM customer WHERE email = '".$username."'";
    $customer_result = mysqli_query($db_connection, $customer_query);
    $customer_count = mysqli_num_rows($customer_result);

    if ($customer_count > 0) {

    	$customer_row = mysqli_fetch_assoc($customer_result);
    	
    	echo "<header><h2>";  // section heading
    	echo "Booking for ".$customer_row['firstname']." ".$customer_row['lastname'];
    	echo "</h2></header>";

    	echo "<form method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."'>";  // Form to make a booking
    	echo "<input type='hidden' name='customerID' value='".$customer_row['customerID']."'>";
    	mysqli_free_result($customer_result);  // free memory from customer query

    	echo "<label for='room'>Room (name,type,beds):</label>";
    	echo "<select id='room' name='room' required='required'>";  // Select a room

// *** Select rooms from the database to display in the dropdown list ***
    	$room_query = "SELECT roomID, roomname, roomtype, beds FROM room ORDER BY roomID";
    	$room_result = mysqli_query($db_connection, $room_query);
    	$room_count = mysqli_num_rows($room_result);

// display rooms in dropdown
	    if ($room_count > 0) {

// display rooms from database
	    	while ($room = mysqli_fetch_assoc($room_result)) {
	    		echo '<option value="'.$room['roomID'].'">';
			    echo $room['roomname'].', '.$room['roomtype'].', '.$room['beds'];
			    echo '</option>';
	    	}
	    	mysqli_free_result($room_result);  // free memory from room query

		}  // end of room query
?>
				</select>
				<br><br>

				<!-- Choose a check-in date -->
				<label for="checkin">Checkin date:</label>
				<input id="checkin" type="text" name="checkin" 
					placeholder="yyyy-mm-dd" readonly required>
				<br><br>

				<!-- Choose a check-out date -->
				<label for="checkout">Checkout date:</label>
				<input id="checkout" type="text" name="checkout" 
					placeholder="yyyy-mm-dd" readonly required>
				<br><br>

				<!-- Enter a contact phone number -->
				<label for="phone">Contact number:</label>
				<input id="phone" type="tel" name="phone" 
					autocomplete="tel-national"
					placeholder="### ### ####" 
					pattern="[0][0-9]{2}\s[0-9]{3}\s[0-9]{3}[0-9]?[0-9]?" 
					minlength="11" maxlength="13" required="required">
				<br><br>

				<!-- Enter booking extras -->
				<label for="extras">Booking extras:</label>
				<textarea id="extras" name="extras" rows="5" cols="30"></textarea>
				<br><br>

				<!-- Submit the form button -->
				<input type="submit" name="submit" value="Add" title="Add booking">
				<a href="listbookings.php" title="Cancel booking">[Cancel]</a>
			</form>	
		</section>

		<br><br>
		<hr>

		<!-- Page section to search for room availability -->
		<section>

			<!-- Section heading -->
			<header>
				<h2>Search for room availability</h2>
			</header>

			<!-- Form to search for room availability -->
			<form id="searchform" name="searchform" method="post" action="" 
				onsubmit="searchResult(); return false">
				

				<!-- Choose a check-in date -->
				<label for="checkinsearch">Start date:</label>
				<input id="checkinsearch" type="text" name="checkinsearch" 
					readonly placeholder="dd / mm / yyyy">

				<!-- Choose a check-out date -->
				<label for="checkoutsearch">End date:</label>
				<input id="checkoutsearch" type="text" name="checkoutsearch" 
					readonly placeholder="dd / mm / yyyy">

				<!-- Submit the form button -->
				<input id="searchbutton" type="submit" name="search" 
				value="Search availability">
			</form>
			<br>

			<!-- Table that displays available rooms for the date range selected -->
			<table id="tblrooms" border="1">

				<!-- Table header -->
				<thead>
					<tr><th>Room #</th><th>Room Name</th><th>Room Type</th><th>Beds</th></tr>
				</thead>
			</table>

	        <p id="message"></p>

		</section>

<?php    	
    } else {
    	echo "<h2>No customer found!</h2>";
    }
    mysqli_close($db_connection);  // Close database connection

?>
	</main>

	<!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>