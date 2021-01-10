<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>

<?php

    if(!empty($_POST)) {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "lsform";
        //Creates a database connection
        $connection = mysqli_connect ($dbhost, $dbuser, $dbpass, $dbname);

        //Prints error message if there is a connection error
        if (mysqli_connect_errno()) {
            die ("Database connection failed: ".mysqli_connect_error()."(".mysqli_connect_errno().")");
        }

        //if user didn't enter first name, last name and email, that means they chose log in option
        if (empty($_POST['fname']) and empty($_POST['lname']) and empty($_POST['email'])) {
            
            //escapes special characters, if any
            $uname = mysqli_real_escape_string($connection, $_POST['uname']);
            $pass = mysqli_real_escape_string($connection, $_POST['pass']);

            //checks for username and password in database
            $result_uname = mysqli_query($connection, "SELECT * FROM users WHERE Username='$uname'");
            $result_pass = mysqli_query($connection, "SELECT * FROM users WHERE Password='$pass'");

            //fetches an associative array
            $user = mysqli_fetch_assoc($result_uname);
            $user1 = mysqli_fetch_assoc($result_pass);

            //checks if there is something in the arrays
            if($user or $user1) {
                //if username isn't in the array (not in database), outputs error message
                if (mysqli_num_rows($result_uname) != 1) {
                    die("Error: Username does not exist");
                }
                //if password isn't in the array (not in database), outputs error message
                if (mysqli_num_rows($result_pass) < 1) {
                    die("Error: Password is incorrect");
                }
                //checks if username and password are in the array (in database)
                if (mysqli_num_rows($result_uname) == 1 and mysqli_num_rows($result_pass) >= 1) {
                    echo "<h1>Welcome</h1>"; //displays welcome message to user
                }              
            }
            //outputs erorr message if neither username nor password are in database
            else {
                die("Error: User account not found");
            }

        }

        //sign up option
        else {
            //escapes special characters, if any
            $fname = mysqli_real_escape_string($connection, $_POST['fname']);
            $lname = mysqli_real_escape_string($connection, $_POST['lname']);
            $uname = mysqli_real_escape_string($connection, $_POST['uname']);
            $pass = mysqli_real_escape_string($connection, $_POST['pass']);
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            
            //checks for username and email in database
            $result_uname = mysqli_query($connection, "SELECT * FROM users WHERE Username='$uname'");
            $result_email = mysqli_query($connection, "SELECT * FROM users WHERE EmailAddress='$email'");

            //fetches an associative array
            $user = mysqli_fetch_assoc($result_uname);
            $user1 = mysqli_fetch_assoc($result_email);
            $error = null;
            
            //checks if there is something in the arrays
            if($user or $user1) {
                //if username is already in the array (in database), stores error message
                if (mysqli_num_rows($result_uname) == 1) {
                    $error = die("Error: Username already exists.");
                }
                //if email is already in the array (in database), stores error message
                if (mysqli_num_rows($result_email) == 1) {
                    $error = die("Error: Email already exists.");
                }
                echo $error; //outputs error message
            }

            //if array is empty, adds user to database
            else {
                //inserts values user entered into the database
                $sql = "INSERT INTO users(FirstName, LastName, Username, Password, EmailAddress) VALUES( 
                        '$fname', '$lname', '$uname', '$pass', '$email')";
                    mysqli_query($connection, $sql);
                    echo("<h1>Success!</h1>"); //displays message to user that sign up was a success
            }
        }
    }

    //closes connection 
    $connection->close();
            
?>
</body>
</html>
