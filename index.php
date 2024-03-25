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
    <title>Motueka Bed & Breakfast</title>
    
    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Home Page">

    <!-- The set of characters and symbols used in the web page and required by the browser -->
    <meta charset="UTF-8">

    <!-- The browser should not scale content down. Necessary to make the website responsive on all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Define keywords required by search engines -->
    <meta name="keywords" content="Motueka, Bed & Breakfast, B&B, Accommodation">

    <!-- Display a favicon image to the left of the page title in the browser tab -->
    <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
</head>
<body>

    <!-- Page header -->
    <header>

        <!-- Page navigation -->
        <?php include 'navigation.php'; ?>

        <!-- Page heading -->
        <h1>BIT608 Web Programming</h1>
    </header>

    <!-- Main page section -->
    <main>

        <!-- Page section containing links to relevant pages within the application -->
        <section>

            <!-- Section heading -->
            <h2>Assessment case study web application temporary launch page</h2>

            <!-- Links to relevant pages -->
            <ul>
                <li><a href="original_template/">Original Template example</a>
                <li><a href="converted_template/">Converted Template example</a>
                <li><a href="listcustomers.php">Customer listing</a>
                <li><a href="listrooms.php">Rooms listing</a>
                <li><a href="listbookings.php">Bookings listing</a>
                <li><a href="login.php">Login</a>
            </ul>
        </section>
    </main>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>