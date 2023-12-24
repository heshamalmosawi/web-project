<?php 
    if (isset($_POST['close'])){
        $id = $_POST['id'];
        

        try{
            require('connection.php');
            $rs = $db->prepare("UPDATE surveys SET status = 0, expireDate = ? WHERE id = ?");
            
            $rs->bindValue(1,"0000-00-00");
            $rs->bindParam(2, $id);
            $rs->execute();
            $db = null;
        } catch (PDOException $ex){
            echo "Error: " . $ex->getMessage();
        } 
    }

    if (isset($_POST['open'])){
        $id = $_POST['id'];
        $date = $_POST['date'];

        try{
            require('connection.php');
            $rs = $db->prepare("UPDATE surveys SET status = 1, expireDate = ? WHERE id = ?");
            
            $rs->bindParam(1, $date);
            $rs->bindParam(2, $id);
            $rs->execute();
            $db = null;
        } catch (PDOException $ex){
            echo "Error: " . $ex->getMessage();
        } 
    }
?>

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
       
        echo "<form method='post' action=''>";
            echo "<input type='hidden' name='id' value='" . $row["id"] . "' />";
            if ($row["status"] == 1)
                echo "<input type='submit' name='close' value='close'> ";
            else {
                echo "New Expire Date:  <input type='date' name='date'> ";
                echo "<input type='submit' name='open' value='open'>";
            }
        
        echo "</form>";
        echo "</div>";
    }
    echo "<a href = 'addpoll.php'>add more ?</a>"
    ?>
</body>
</html>
