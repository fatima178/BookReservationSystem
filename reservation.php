<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="style.css" />
    <title>Reservation</title>
</head>

<body>

    <?php
        include('header.php');
    ?>
    <div class="form">
        <h1 class="loginheader">Reserve A Book</h1>
        <?php
        
            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "BookReservation";
            
            $conn = new mysqli($host, $username, $password, $database);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $isbn = mysqli_real_escape_string($conn, $_POST['ISBN']);

                $check = "SELECT * FROM Books WHERE ISBN = '$isbn'";
                $result = mysqli_query($conn, $check);

                if ($result && mysqli_num_rows($result) > 0) {
                    $reserve = "UPDATE Books SET Reserved = 'Y' WHERE ISBN = '$isbn'";
                    $reserveResult = mysqli_query($conn, $reserve);

                    if ($reserveResult) {
                        echo '<p>Book has been reserved successfully!</p>';
                    } else {
                        echo '<p>Error reserving book: ' . mysqli_error($conn) . '</p>';
                    }
                } else {
                    echo '<p>Book with the ISBN ' . $isbn . ' not found.</p>';
                }
            }
        ?>

        <form action="reserve.php" method="POST">
            Enter ISBN Of The Book<br>
            <input type="text" name="ISBN" required><br>
            <input type="submit" value="Submit">
        </form>
    </div>

    <br><br>

    <?php
        include('footer.php');
    ?>

</body>

</html>