<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    include('header.php');

    session_start();

    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "BookReservation";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $max_per_page = 5;

    $page = isset($_GET["page"]) ? $_GET["page"] : 1;

    $start_from = ($page - 1) * $max_per_page;

    $title = isset($_POST['BookTitle']) ? mysqli_real_escape_string($conn, $_POST['BookTitle']) : "";
    $author = isset($_POST['Author']) ? mysqli_real_escape_string($conn, $_POST['Author']) : "";
    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : "";

    $query = "SELECT * FROM Books WHERE 1";

    if (!empty($title)) {
        $query .= " AND BookTitle LIKE '%$title%'";
    }

    if (!empty($author)) {
        $query .= " AND Author LIKE '%$author%'";
    }

    if (!empty($category)) {
        $query .= " AND CategoryID = $category";
    }

    $query .= " LIMIT $start_from, $max_per_page";

    $result = mysqli_query($conn, $query);
    ?>

    <div class="content">
        <h1>Search Results</h1>

        <?php
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="search-results">';
                echo '<thead>';
                echo '<tr><th>Title</th><th>Author</th><th>Category</th><th>Reserve</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['BookTitle'] . '</td>';
                    echo '<td>' . $row['Author'] . '</td>';
                    echo '<td>' . $row['CategoryID'] . '</td>';
                    echo '<td><a href="reservation.php?ISBN=' . $row['ISBN'] . '">Reserve Book</a></td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No results found</p>';
            }
        } else {
            echo '<p>Error executing search: ' . mysqli_error($conn) . '</p>';
        }
        ?>
    </div>

    <div class="pagination">
        <?php
        $query_string = "BookTitle=$title&Author=$author&category=$category";

        $query = "SELECT COUNT(*) FROM Books WHERE 1";

        if (!empty($title)) {
            $query .= " AND BookTitle LIKE '%$title%'";
        }

        if (!empty($author)) {
            $query .= " AND Author LIKE '%$author%'";
        }

        if (!empty($category)) {
            $query .= " AND CategoryID = $category";
        }

        $rs_result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($rs_result);
        $total_records = $row[0];

        echo "</br>";

        $total_pages = ceil($total_records / $max_per_page);
        $pagLink = "";

        if ($page >= 2) {
            $pagLink .= "<a href='search_results.php?page=" . ($page - 1) . "&$query_string'> Back</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $pagLink .= "<a class='active' href='search_results.php?page=" . $i . "&$query_string'>" . $i . " </a>";
            } else {
                $pagLink .= "<a href='search_results.php?page=" . $i . "&$query_string'> " . $i . " </a>";
            }
        }

        if ($page < $total_pages) {
            $pagLink .= "<a href='search_results.php?page=" . ($page + 1) . "&$query_string'> Next</a>";
        }

        echo $pagLink;
        ?>
    </div>

    <?php
    include('footer.php');
    ?>

</body>

</html>


