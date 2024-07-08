<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        include('header.php');
    ?>

    <?php
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
        <div class="loggedin">You are logged in
        <br><br>
        <a href='logout.php'>Click here to log out</a><br>
        <br><br></div>
        <?php
    } 
    else {
        if (isset($_POST['Username']) && isset($_POST['Password'])) {
            $Username = mysqli_real_escape_string($con, $_POST['Username']);
            $Password = mysqli_real_escape_string($con, $_POST['Password']);

            $query = "SELECT * FROM Users WHERE Username='$Username' AND Password='$Password'";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) == 1) {
                $_SESSION['Username'] = $Username;
                
        ?>
    <?php
        header('Location: home.php');
            } 
            else {
                echo "<div class='form-error'>
                <h3>Invalid username or password. Please try again.</h3><br/>
                </div>";
            }
        }

    if (!isset($_SESSION['Username'])) {
    ?>
        <form class="form" action="login.php" method="post">
            <h1 class="form-title">Login</h1>
            <label for="Username">Username</label>
            <input type="text" class="login-input" name="Username" required />

            <label for="Password">Password</label>
            <input type="password" class="login-input" name="Password" required />

            <input type="submit" name="login" value="Login" class="login-button">
            <p class="link"><a href="register.php">Click to Register</a></p>
        </form>
    <?php
        }
    }
    ?>

    <?php
        include('footer.php');
    ?>
</body>
</html>
