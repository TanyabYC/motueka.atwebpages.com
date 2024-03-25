<?php 
// provides available rooms

    include "config.php"; // load in any variables for database connection

// connect to the database using the constants
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();
 
// do some simple validation to check if sql contains a string
// GET value of 'sqf' and 'tosearch' variables from page url
    $f_date = $_GET['fd'];
    $e_date = $_GET['ed'];

// initialise response variables sent to AJAX client
    $searchresult = '';

    if (isset($f_date) and !empty($f_date) and isset($e_date) and !empty($e_date) ) {

    	/* double check date is in correct format before sending it to the database */

    	// from date
    	$unix_fdate = strtotime($f_date);  // convert date string to a Unix timestamp
    	$from_date = date("Y-m-d", $unix_fdate);

    	// end date
    	$unix_edate = strtotime($e_date);  // convert date string to a Unix timestamp
    	$end_date = date("Y-m-d", $unix_edate);
 
// prepare a query and send it to the server using our from date and end date

        $query = "SELECT roomID, roomname, roomtype, beds FROM room WHERE roomID NOT IN (SELECT roomID FROM booking WHERE checkin >= '$from_date' AND checkout <= '$end_date' ORDER BY roomID)";
        $result = mysqli_query($db_connection, $query);
        $rowcount = mysqli_num_rows($result);
 
// makes sure we have rooms
        if ($rowcount > 0) {

            $rows = []; // start an empty array    
 
// append each row in the query result to our empty array until there are no more results 
            while ($row = mysqli_fetch_assoc($result)) {             
                $rows[] = $row;
            }
 
// take the array of our 1 or more rooms and turn it into a JSON text
            $searchresult = json_encode($rows);
 
// this line is crucial for the browser to understand what data is being sent
            header("Content-Type: text/json; charset=utf-8");

        } else {
            echo "<tr><td colspan=3><h2>No rooms available!</h2></td></tr>"; 
        }

    } else { 
        echo "<tr><td colspan=3><h2>Invalid search query</h2>"; 
    }
    mysqli_free_result($result); // free any memory used by the query 
    mysqli_close($db_connection); // close the connection once done 
 
// send the response back to the Ajax client  
    echo  $searchresult;

?>