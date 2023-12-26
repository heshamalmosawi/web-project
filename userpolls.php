<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Polls</title>
    <style>
  html {
    height:100%;
    width: 100%;
    }
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    padding-bottom: 20px;
    margin:0 auto;
    width:95%;
    border-bottom: 1px solid lightgrey;
}
body {
    background-image:url(images/logo2-removebg-preview.png), linear-gradient(rgb(193, 191, 241), rgb(165, 109, 105));
    background-position:center; 
    background-repeat: no-repeat;
     font-family: 'Roboto', sans-serif;
    font-size: 1.125rem;
}

.navbutton {
    border: 1px solid white;
    padding: 11px 25px;
    font-family: 'Playfair Display', serif;
    display: inline-block;
    position: relative;
}
        h2 {
            color: #007BFF;
        }

        .poll-container {
            margin-top:10px;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px #007BFF;
            border-radius: 5px;
            margin-left:auto;
            margin-right:auto;
            width:92%;
        }

        .poll-info {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .button-container {
            margin-top: 10px;
        }

        input[type='submit'] {
            background-color: #007BFF;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type='date'] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            color: white;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px){
        .poll-container {
            margin:10px auto;
        }
        .poll-info
        {
            font-size: 30px;
        }
        }
        @media (max-width: 450px){
        .poll-container {
            margin:5px auto;
        }
        .poll-info
        {
            font-size: 20px;
        }
        }
    </style>
</head>
<body>
<header>
            <div class="logo">
                <a href="index.html">
                    <img src="images/logo2-removebg-preview.png " width="100px" height="100px">
                </a>
            </div>

            <nav>


            
                <div class="navbutton">
                    <a href="index.html">Home</a>
                </div>


            </nav>
        </header>
<?php
    session_start();
    if (!isset($_SESSION['activeuser'])) {
            echo "<script>
            alert('Cannot view page. Please log in.');
            window.location.href = 'loginPage.php';
          </script>";
        exit();
    } 
    echo "<div class='poll-container'>";
    echo "<h2>Welcome " . $_SESSION['activeuser'] . ", these are your polls!</h2>";

    require('connection.php');
    
    $rs = $db->prepare("SELECT * FROM surveys WHERE creater = ?");
    $rs->bindValue(1, $_SESSION['activeuser']);
    $rs->execute();
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    $db = null;

    
    foreach ($rows as $row) {
        echo "<div class='poll-info'>Question: " . $row["question"] . "</div>";
        
        echo "<div class='poll-info'>Results: <br><br>";
        
        $result =json_decode($row["results"],true);
        $voters= json_decode($row['voters'],true);
        foreach ($result as $key => $value){
         
            echo "$key<pre> <progress  value='$value' max='".count($voters)."'></progress> ";
            if (count($voters) == 0){
                echo  "0.00% <br>" ;
            }
            else{
                  echo number_format(($value*100/count($voters)), 2)."% <Br>" ;
            }
        }
        echo "</div>";
       if ($row["expireDate"] !="0000-00-00")
                echo "<div class='poll-info'>Expire Date: " . $row["expireDate"] . "</div>";
       

        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='id' value='" . $row["id"] . "' />";
        if ($row["status"] == 1) {
            echo "<div class='button-container'><input type='submit' name='close' value='Close Poll'></div>";
            echo "<div class='poll-info'>Status:Open</div>";
        } else {
            echo "<div class='poll-info'>Status:Close</div>";
            echo "<div class='button-container'>";
            echo "<input type='date' name='date' >";
            echo "<input type='submit' name='open' value='Open Poll'>";
            echo "</div>";
        }
        echo "</form>";
        echo "<hr>";
    }

    echo "<a style='color:#007BFF;'href='addpoll.php'>Add More Polls?</a>";
    echo "</div>";
    ?>
</body>
</html>

<?php
  require('status.php');

if (isset($_POST['close'])) {
    $id = $_POST['id'];

    try {
        require('connection.php');
        $db->beginTransaction();
        $rs = $db->prepare("UPDATE surveys SET status = 0, expireDate = ? WHERE id = ?");
        $rs->bindValue(1, "0000-00-00");
        $rs->bindParam(2, $id);
        $rs->execute();
        $db->commit();
        $db = null;
    } catch (PDOException $ex) {
        $db->rollBack();
        echo "Error: " . $ex->getMessage();
    }
}

if (isset($_POST['open'])) {
    $id = $_POST['id'];
    $date = $_POST['date'];

    try {
        require('connection.php');
        $db->beginTransaction();
        $rs = $db->prepare("UPDATE surveys SET status = 1, expireDate = ? WHERE id = ?");
        $rs->bindParam(1, $date);
        $rs->bindParam(2, $id);
        $rs->execute();
        $db->commit();
        $db = null;
    } catch (PDOException $ex) {
        echo "Error: " . $ex->getMessage();
        $db->rollBack();
    }
}
?>
