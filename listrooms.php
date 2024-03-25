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
	<title>Browse rooms</title>

	<!-- Web page author -->
	<meta name="author" content="Tanya Suzette Boshoff">

	<!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast List all Rooms">

	<!-- The set of characters and symbols used in the web page and required by the browser -->
	<meta charset="utf-8">

	<!-- The browser should not scale content down. Necessary to make the website responsive on all devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Define keywords required by search engines -->
    <meta name="keywords" content="Motueka, Bed & Breakfast, B&B, Accommodation, List all rooms">

	<!-- Display a favicon image to the left of the page title in the browser tab -->
	<link rel="icon" type="image/x-icon" href="./images/favicon.ico">
</head>
<body>

	<!-- Page header -->
	<header>
		
		<!-- Page navigation -->
        <?php include 'navigation.php'; ?>

<?php
include "config.php"; //load in any variables
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// insert DB code from here onwards
// check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL! ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//prepare a query and send it to the server
$query = 'SELECT roomID,roomname,roomtype FROM room ORDER BY roomtype';
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>
		<h1>Room list</h1>
	</header>

	<!-- Breadcrumb menu -->
	<nav>
		<section>
			<h2>
				<a href='addroom.php'>[Add a room]</a>
				<a href="index.php">[Return to main page]</a>
			</h2>
		</section>
	</nav>

	<!-- Main page section -->
	<main>

		<!-- Page section containing the list of rooms -->
		<section>
			
			<!-- Table that displays the list of rooms -->
			<table border="1">

				<!-- Table header -->
				<thead>
					<tr><th>Room Name</th><th>Type</th><th>Action</th></tr>
				</thead>
<?php

// makes sure we have rooms
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['roomID'];	
	  echo '<tr><td>'.$row['roomname'].'</td><td>'.$row['roomtype'].'</td>';
	  echo '<td><a href="viewroom.php?id='.$id.'">[view]</a>';
	  echo '<a href="editroom.php?id='.$id.'">[edit]</a>';
	  echo '<a href="deleteroom.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>';
   }
} else echo "<h2>No rooms found!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($db_connection); //close the connection once done
?>
			</table>
		</section>
	</main>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
  