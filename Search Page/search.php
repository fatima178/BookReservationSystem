<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Search</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

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

        $Categories = mysqli_query($conn, "SELECT * FROM Category");
    ?>

    <?php
        include('header.php');
    ?>

    <div class="content">
        <h1>Search for a book</h1>

        <form class="form" action="search_results.php" method="post">
            <label for="title">Book Title</label>
            <input type="text" name="BookTitle">

            <label for="author">Author</label>
            <input type="text" name="Author">

            <input type="submit" value="Search">
        </form>

        <form class="form" action="search_results.php" method="post">
            <label for="category">Category</label>
            <select name="category">
                <?php
                while ($row = mysqli_fetch_assoc($Categories)) {
                    echo "<option value='{$row['CategoryID']}'>{$row['CategoryDescription']}</option>";
                }
                ?>
            </select>

            <input type="submit" value="Search by Category">
        </form>
    </div>

    <?php
        include('footer.php');
    ?>

</body>

</html>


