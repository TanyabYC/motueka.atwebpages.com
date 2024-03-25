<?php
// managing sessions

// start session
    session_start();

// check client has authorisation to access page
    function isAdmin() {
        if ((isset($_SESSION['loggedin'])) and (isset($_SESSION['userid']))) {
            // simple redirect if a user tries to access a page they don't have authorisation for
            if (($_SESSION['loggedin'] == 1) and ($_SESSION['userid'] == 1)) { // is admin
                return true;
            } else {  // not admin
                return false;
            }
        } else {
            return false;
        }
    }

// check if the user is logged in, else send to the login page 
    function checkUser() {
        $_SESSION['URI'] = '';

        if ((isset($_SESSION['loggedin'])) and ($_SESSION['loggedin'] == 1)) {
            return true;
        } else {
            $_SESSION['URI'] = 'http://motueka.atwebpages.com'.$_SERVER['REQUEST_URI']; //save current url for redirect
            echo("<script>location.href='http://motueka.atwebpages.com/login.php';</script>");
            exit();    
        }
    }

// just to show we are are logged in
    function loginStatus() {
        if ((isset($_SESSION['username'])) and (isset($_SESSION['loggedin']))) {
            $username = $_SESSION['username'];  // email is used as the username
            if ($_SESSION['loggedin'] == 1) {
                echo "<h2>Logged in with $username</h2>";
                return true;
            } else {
                echo "<h2>Logged out</h2>";
                return false;
            }
        }
    }

// log a user in
    function login($userID, $username) {  // login with role/userid and username
        // simple redirect if a user tries to access a page they have not logged in to
        if (isset($_SESSION['loggedin']) and ($_SESSION['loggedin'] == 0) and (!empty($_SESSION['URI']))) {            
            $uri = $_SESSION['URI'];
        } else {
            // redirect users according to their roles if logged in
            if ($userID == 1) {  // admin login, redirect to customer list
                $_SESSION['URI'] = 'http://motueka.atwebpages.com/listcustomers.php';
                $uri = $_SESSION['URI'];
            } elseif ($userID == 0) {  // customer login
                $_SESSION['URI'] = 'http://motueka.atwebpages.com/index.php';
                $uri = $_SESSION['URI'];
            }
        }

// initialise global session variables
        $_SESSION['loggedin'] = 1;
        $_SESSION['userid'] = $userID;  // customer or admin role
        $_SESSION['username'] = $username;
        $_SESSION['URI'] = '';        

        echo("<script>location.href='".$uri."';</script>");
        exit();  // prevent any further code from being executed after redirect
    }

// simple logout function
    function logout(){

        $_SESSION['loggedin'] = 0;
        $_SESSION['userid'] = -1;  // clear assigned userid (role)
        $_SESSION['username'] = '';
        $_SESSION['URI'] = '';
    
        echo("<script>location.href='http://motueka.atwebpages.com/login.php';</script>");
        exit();  
    }
?>