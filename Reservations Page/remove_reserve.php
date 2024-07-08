<?php
    session_start();

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "BookReservation";

    $con = new mysqli($host, $username, $password, $database);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if (!isset($_SESSION['Username'])) {
        header('Location: login.php'); 
        exit;
    }

    $username = $_SESSION['Username'];
    $reservedBooks = $con->query("SELECT * FROM Reserved WHERE Username = '$username'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="style.css" />
    <title>View Reserved Books</title>
</head>

<body>

    <?php
        include('header.php');
    ?>

    <div class="reserved">
        <h1>View Reserved Books</h1>

        <?php
        if ($reservedBooks->num_rows > 0) {
            ?>
            <div class="reserved-table">
                <table>
                    <tr>
                        <th>ISBN</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Reserved Date</th>
                        <th>Delete Reservation</th>
                    </tr>
                <?php
                while ($row = $reservedBooks->fetch_assoc()) {
                    $isbn = $row['ISBN'];
                    $reservedDate = $row['ReservedDate'];

                    // Fetch additional information from the Books table
                    $bookInfo = $con->query("SELECT * FROM Books WHERE ISBN = '$isbn'");
                    $book = $bookInfo->fetch_assoc();

                    $bookTitle = $book['BookTitle'];
                    $author = $book['Author'];
                    $year = $book['Year'];
                    ?>
                    <tr>
                        <td><?php echo $isbn; ?></td>
                        <td><?php echo $bookTitle; ?></td>
                        <td><?php echo $author; ?></td>
                        <td><?php echo $year; ?></td>
                        <td><?php echo $reservedDate; ?></td>
                        <td>
                            <form action='' method='post'>
                                <input type='hidden' name='action' value='remove_reserve'>
                                <input type='hidden' name='ISBN' value='<?php echo $isbn; ?>'>
                                <input type='submit' value='Remove Reservation'>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </table>
            </div>
            <?php
        } 
        else {
            ?>
            <div class='form-error'>
                <p>You currently have no books reserved.</p>
            </div>
            <?php
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove_reserve' && isset($_POST['ISBN'])) {
            $isbn = $con->real_escape_string($_POST['ISBN']);
            $remove = $con->query("DELETE FROM Reserved WHERE ISBN = '$isbn' AND Username = '$username'");

            if ($remove) {
                $updateBooks = $con->query("UPDATE Books SET Reserved = 'N' WHERE ISBN = '$isbn'");

                if ($updateBooks) {
                    echo "<p>Your reservation has been successfully removed</p>";
                    echo "<meta http-equiv='refresh' content='0'>";
                } else {
                    echo "<p>There was an issue. Please try again</p>";
                }
            } else {
                echo "<p>There has been an issue removing your reservation. Please try again</p>";
            }
        }
        ?>
    </div>

    <?php
        include('footer.php');
    ?>

</body>

</html>





