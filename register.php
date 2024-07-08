<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        include('header.php');
    ?>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "BookReservation";

    $con = new mysqli($host, $username, $password, $database);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    session_start();
    
    if (isset($_SESSION['Username'])) {
        ?>
        <br>
        <div class="loggedin">You are already registered
        <br><br>
        <a href='logout.php'>Click here to log out</a><br>
        <br><br></div>
        <?php
    } 
    else {
        if (isset($_POST['Username'])) {
            $FirstName = mysqli_real_escape_string($con, $_REQUEST['FirstName']);
            $Surname = mysqli_real_escape_string($con, $_REQUEST['Surname']);
            $Username = mysqli_real_escape_string($con, $_REQUEST['Username']);
            $AddressLine1 = mysqli_real_escape_string($con, $_REQUEST['AddressLine1']);
            $AddressLine2 = mysqli_real_escape_string($con, $_REQUEST['AddressLine2']);
            $City = mysqli_real_escape_string($con, $_REQUEST['City']);
            $Telephone = mysqli_real_escape_string($con, $_REQUEST['Telephone']);
            $Mobile = mysqli_real_escape_string($con, $_REQUEST['Mobile']);
            $Password = mysqli_real_escape_string($con, $_REQUEST['Password']);
            $ConfirmPassword = mysqli_real_escape_string($con, $_REQUEST['ConfirmPassword']);

            $errors = [];

            $checkUsername = "SELECT * FROM Users WHERE Username = '$Username'";
            $result = mysqli_query($con, $checkUsername);

            if (mysqli_num_rows($result) > 0) {
                $errors[] = "<div class='form'>
                                <h3>Username is already taken. Please choose another</h3><br/>
                            </div>"; 
            }

            // check if passwords match
            if ($Password !== $ConfirmPassword) {
                $errors[] = "<div class='form'>
                                <h3>Passwords do not match</h3><br/>
                            </div>"; "";
            }

            // check if telephone is 10 digits long
            if (strlen($Telephone) !== 10 || !ctype_digit($Telephone)) {
                $errors[] = "<div class='form'>
                                <h3>Telephone number must be 10 digits long</h3><br/>
                            </div>"; 
            }

            // check if mobile is 10 digits long
            if (strlen($Mobile) !== 10 || !ctype_digit($Mobile)) {
                $errors[] = "<div class='form'>
                                <h3>Mobile number must be 10 digits long</h3><br/>
                            </div>"; 
            }

            if (empty($errors)) {
                $insert = "INSERT INTO Users (FirstName, Surname, Username, AddressLine1, AddressLine2, City, Telephone, Mobile, Password) 
                VALUES ('$FirstName', '$Surname', '$Username','$AddressLine1', '$AddressLine2', '$City', '$Telephone', '$Mobile', '$Password')";
                mysqli_query($con, $insert);
                header("Location: home.php");
                exit();
            }
        }


        if (!empty($errors)) {
            echo '<div class="errors"><ul>';
            foreach ($errors as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul></div>';
        }

    
        ?>
        <form class="form" action="" method="post">
            <h1 class="form-title">Registration</h1>
            <label for="FirstName">First Name</label>
            <input type="text" class="login-input" name="FirstName" required />

            <label for="Surname">Surname</label>
            <input type="text" class="login-input" name="Surname" required />

            <label for="Username">Username</label>
            <input type="text" class="login-input" name="Username" required />

            <label for="AddressLine1">Address Line 1</label>
            <input type="text" class="login-input" name="AddressLine1" required />

            <label for="AddressLine2">Address Line 2</label>
            <input type="text" class="login-input" name="AddressLine2" />

            <label for="City">City</label>
            <input type="text" class="login-input" name="City" required />

            <label for="Telephone">Telephone Number</label>
            <input type="text" class="login-input" name="Telephone" />

            <label for="Mobile">Mobile Number</label>
            <input type="text" class="login-input" name="Mobile" required />

            <label for="Password">Password</label>
            <input type="password" class="login-input" name="Password" required />

            <label for="ConfirmPassword">Confirm Password</label>
            <input type="password" class="login-input" name="ConfirmPassword" required />

            <input type="submit" name="submit" value="Register" class="login-button">
            <p class="link"><a href="login.php">Click here to Login</a></p>
        </form>
    <?php
    }
    ?>
    
	</div>

</body>
</html>

