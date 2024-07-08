<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="style.css"/>
    <title>Reserve</title>
</head>

<body>

    <?php
        include('header.php');
    ?>

    <?php

        session_start();

        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "BookReservation";

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (!isset($_SESSION['Username'])) {
            header('Location: login.php'); 
            exit;
        }

        $isbn = $conn->real_escape_string($_POST['ISBN']);

        $bookQuery = $conn->query("SELECT * FROM Books WHERE ISBN = '$isbn'");

        if ($bookQuery->num_rows == 0) {
            ?>
            <div class='form-error2'>
                <h2>The book with the ISBN <?php echo $isbn; ?> doesn't exist.</h2>
            </div>
            <div class='form-error2'>
                <h3><a href='reservation.php'>Try again?</a> <br></h3>
            </div>
            <div class="footer">
                <p>Copyright. 2023 Fatima Alubaidi</p>
            </div>
            <?php
            exit;
        }

        $reserved = $conn->query("SELECT * FROM Books WHERE ISBN = '$isbn' AND Reserved = 'Y'");

        if ($reserved->num_rows > 0) {
            ?>
            <div class='form-error2'>
                <h3>The book with ISBN <?php echo $isbn; ?> is already reserved by a member.</h3>
            </div>
            <div class='form-error2'>
                <h3><a href='reservation.php'>Try again?</a> <br></h3>
            </div>
            <div class="footer">
                <p>Copyright. 2023 Fatima Alubaidi</p>
            </div>
            <?php
            exit;
        }

        $updateB = $conn->query("UPDATE Books SET Reserved = 'Y' WHERE ISBN = '$isbn'");

        if ($updateB) {
            ?>
            <div class='form-error2'>
                <h3>The book with ISBN <?php echo $isbn; ?> was reserved successfully.</h3>
            </div>
            <?php
        }

        $reserve = $conn->query("INSERT INTO Reserved (ISBN, Username, ReservedDate) VALUES ('$isbn', '{$_SESSION['Username']}', NOW())");
    ?>
  
    <div class="form">
    <h1>Reserve a Book</h1>
        <form action="reservation.php" method="post">
            <label for="ISBN">Enter ISBN:</label>
            <input type="text" name="ISBN" required>
            <input type="submit" value="Reserve">
        </form>
    </div>

    <?php
    // Include the header.php file
    include('footer.php');
    ?>

</body>
</html> 
