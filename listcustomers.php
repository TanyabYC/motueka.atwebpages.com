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
<html lang="en_NZ">
<head>
    <!-- Display the page title in the browser tab to describe the meaning of the page -->
    <title>Browse customers with AJAX autocomplete</title>

    <!-- Web page author -->
    <meta name="author" content="Tanya Suzette Boshoff">

    <!-- Web page description -->
    <meta name="description" content="Motueka Bed & Breakfast List Customers">

    <!-- The set of characters and symbols used in the web page and required by the browser -->
    <meta charset="utf-8">

    <!-- The browser should not scale content down. Necessary to make the website responsive on all devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Display a favicon image to the left of the page title in the browser tab -->
    <link rel="icon" type="image/x-icon" href="./images/favicon.ico">

    <script>

    function searchResult(searchstr) {

      if (searchstr.length==0) {

        return;
      }

      xmlhttp=new XMLHttpRequest();

      xmlhttp.onreadystatechange=function() {

        if (this.readyState==4 && this.status==200) {

        //take JSON text from the server and convert it to JavaScript objects
        //mbrs will become a two dimensional array of our customers much like 
        //a PHP associative array
          var mbrs = JSON.parse(this.responseText);    
                    
          var tbl = document.getElementById("tblcustomers"); //find the table in the HTML
          
          //clear any existing rows from any previous searches
          //if this is not cleared rows will just keep being added
          var rowCount = tbl.rows.length;
          
          for (var i = 1; i < rowCount; i++) {
             //delete from the top - row 0 is the table header we keep
             tbl.deleteRow(1); 
          }      
          
          //populate the table
          //mbrs.length is the size of our array
          for (var i = 0; i < mbrs.length; i++) {
             var mbrid = mbrs[i]['customerID'];
             var fn    = mbrs[i]['firstname'];
             var ln    = mbrs[i]['lastname'];
          
             //concatenate our actions urls into a single string
             var urls  = '<a href="viewcustomer.php?id='+mbrid+'">[view]</a>';
                 urls += '<a href="editcustomer.php?id='+mbrid+'">[edit]</a>';
                 urls += '<a href="deletecustomer.php?id='+mbrid+'">[delete]</a>';
             
             //create a table row with three cells  
             tr = tbl.insertRow(-1);
             var tabCell = tr.insertCell(-1);
                 tabCell.innerHTML = ln; //lastname
             var tabCell = tr.insertCell(-1);
                 tabCell.innerHTML = fn; //firstname      
             var tabCell = tr.insertCell(-1);
                 tabCell.innerHTML = urls; //action URLS            
            }
        }
      }
      //call our php file that will look for a customer or customers matchign the seachstring
      xmlhttp.open("GET","customersearch.php?sq="+searchstr,true);
      xmlhttp.send();
    }
    </script>
</head>
<body>

    <!-- Page header -->
    <header>

        <!-- Page navigation -->
        <?php include 'navigation.php'; ?>
        
        <!-- Page heading -->
        <h1>Customer List Search by Lastname</h1>
    </header>

    <!-- Breadcrumb menu -->
    <nav>
        <section>
            <h2>
                <a href='registercustomer.php'>[Create new Customer]</a>
                <a href="index.php">[Return to main page]</a>
            </h2>            
        </section>
    </nav>

    <!-- Main page section -->
    <main>

        <!-- Page section containing a customer search by last name function -->
        <section>

            <!-- Search for a customer form -->
            <form>
                <label for="lastname">Lastname: </label>
                <input id="lastname" type="text" size="30" 
                    onkeyup="searchResult(this.value)" 
                    onclick="javascript: this.value = ''" 
                    placeholder="Start typing a last name">
            </form>
            <br>

            <!-- Display search result in a table -->
            <table id="tblcustomers" border="1">
                <thead>
                    <tr><th>Last name</th><th>First name</th><th>Actions</th></tr>
                </thead>
            </table>

        </section>
    </main>

    <!-- Page footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
  