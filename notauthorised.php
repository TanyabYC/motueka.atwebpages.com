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
	<title>Not Authorised</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast Not Authorised">

    <!-- The set of characters and symbols used in the web page and required by the browser -->
    <meta charset="UTF-8">

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

        <!-- Page heading -->
        <h1>Not Authorised</h1>       

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

        <!-- Page section containing links to relevant pages within the application -->
        <section>            
            <p>The page you tried to access is restricted and requires special authorisation.</p>
            <p>Please see our <a href="#">Terms & Conditions</a> for further information.</p>
            <p>
                Alternatively, view your 
                <a href="viewaccount.php">Account</a> or 
                <a href="login.php" title="Log in as administrator">Log in</a> 
                as an administrator.
            </p>
        </section>
    </main>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>

</body>
</html>