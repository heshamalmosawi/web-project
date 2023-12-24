<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Polls</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .poll-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .poll-info {
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['activeuser'])) {
        die("Please login!");
    }
    echo "<h2>Welcome " . $_SESSION['activeuser'] . " these are your polls !</h2>";

    require('connection.php');

    $rs = $db->prepare("SELECT * FROM surveys WHERE creater = ?");
    $rs->bindValue(1, $_SESSION['activeuser']);
    $rs->execute();
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    $db = null;

    foreach ($rows as $row) {
        echo "<div class='poll-container'>";
        echo "<div class='poll-info'>Question: " . $row["question"] . "</div>";
        echo "<div class='poll-info'>Results: " . $row["results"] . "</div>";
        echo "<div class='poll-info'>Voters: " . $row["voters"] . "</div>";
        echo "<div class='poll-info'>Expire Date: " . $row["expireDate"] . "</div>";
        echo "<div class='poll-info'>Status: " . $row["status"] . "</div>";
        echo "</div>";
    }
    ?>
</body>
</html>
