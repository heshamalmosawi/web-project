<body>

    <div class="container1">
        <header>
            <div class="logo">
                <a href="index1.html">
                    <img src="images/logo2-removebg-preview.png " width="100px" height="100px">
                </a>
            </div>

            <nav>
                
                <div class="navbutton">
                    <a href="index1.html">Home</a>
                </div>


            </nav>
        </header>
    
</body>

<?php
session_start();
if (!isset($_GET['id'])){
    echo "<script>
    alert('Invalid Poll, please try again.');
    window.location.href = 'browse.php';
  </script>";
    exit();
}


try 
{
    $id = $_GET['id'];
require('connection.php');
$db->beginTransaction();
$rs = $db->query('SELECT * FROM surveys');
$rows = $rs->fetchAll(PDO::FETCH_ASSOC);

$isFound=false;
foreach($rows as $row){
    if ($id==$row['id']){
        $isFound=true;
        break;
    }
} 
if (!$isFound){
    echo "<script>
    alert('Invalid Poll, please try again.');
    window.location.href = 'browse.php';
  </script>";
} 
$rs = $db->prepare("SELECT * FROM surveys WHERE id =?");
$rs->bindValue(1, $id);
$rs->execute();
$rows = $rs->fetchAll(PDO::FETCH_ASSOC);
if ((!isset($_SESSION['activeuser'])) && ($rows[0]['status']==1)){
    echo "<script>
    alert('Cannot vote on ongoing poll. Please log in.');
    window.location.href = 'loginPage.php';
  </script>";
exit();
}
    foreach ($rows as $row) {
 
        $result =json_decode($row["results"],true);
        $voters= json_decode($row['voters'],true);
        $isvote=false;
        if ($row['status']==0){
            $isvote=true;
        } else {
            foreach ($voters as $key)
            {
                if ($_SESSION['activeuser']==$key || $row["status"]==0)
                $isvote=true;
            }
        }
      
        if ($isvote)
        {   
            echo "<div class='poll-container'>";
            echo "<div class='poll-info'>Question: " . $row["question"] . "</div>";
            echo "<div class='poll-info'>Results: <br><br> ";
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
                echo "<div class='poll-info'>Creater:". $row['creater']. "</div>";
                if ($row["status"]==0)
                echo "<div class='poll-info'>Status:Close</div>";
                else 
                echo "<div class='poll-info'>Status:Open</div>";
            
        }else 
        {
            echo "<div class='poll-container'>";
           
            echo "<div class='poll-info'>Question: " . $row["question"] . "</div>";
            echo "<div class='poll-info'>votes:<br> ";
            echo "<form method='POST' action=''>";
            foreach ($result as $key => $value){
               
                echo "<input type='radio' name='op' value='".$key."'>" .$key."<br>";
            }
                echo "<input type='submit' name='vote' value='Vote'>";
            echo "</form>";
                echo "</div>";
            
                if ($row["expireDate"] !="0000-00-00")
                echo "<div class='poll-info'>Expire Date: " . $row["expireDate"] . "</div>";
                echo "<div class='poll-info'>Creater:". $row['creater']. "</div>";
                if ($row["status"]==0)
                echo "<div class='poll-info'>Status:Close</div>";
                else 
                echo "<div class='poll-info'>Status:Open</div>";

        }
        echo "</div>";
        echo "</div>";
        $updateSQL = $db->prepare("UPDATE surveys SET results=?,voters=? WHERE id =?");

            $upr=json_encode($result);
            $upv=json_encode($voters);
            $updateSQL->bindParam(1,$upr);
            $updateSQL->bindParam(2,$upv);
            $updateSQL->bindValue(3,$id);
            $updateSQL->execute();
            
        if (isset($_POST['vote']))
        {
            $result[$_POST['op']]++;
            $voters[] = $_SESSION['activeuser'];
        }
    
        $upr=json_encode($result);
        $upv=json_encode($voters);
        $updateSQL->bindParam(1,$upr);
        $updateSQL->bindParam(2,$upv);
        $updateSQL->bindValue(3,$id);
        $updateSQL->execute();
        $db->commit();
        $db = null;
        
        if (isset($_POST['vote'])){
            header("Location:viewpoll.php?id=".$id);
            exit;
        }
    
    }
    
}catch (PDOException $ex) {
    $db->rollBack();
    echo "Error: " . $ex->getMessage();
}

?>

<style>
  html {
    background-image: linear-gradient(rgb(193, 191, 241), rgb(165, 109, 105));
}

body {
    background-image: url(images/logo2-removebg-preview.png) ;
    background-position:center; 
    background-repeat: no-repeat;
     font-family: 'Roboto', sans-serif;
    font-size: 1.125rem;
}

a {
    color: white;
    text-decoration: none;
}

.container1 {
    width: 1024px;
    min-height: 300px;
    margin-left: auto;
    margin-right: auto;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid lightgrey;

}


body {
    font-family: Arial, sans-serif;
}

.container {
    
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-size: cover;
    margin: 0;
    padding: 0;
}
.navbutton {
    border: 1px solid white;
    padding: 11px 25px;
    font-family: 'Playfair Display', serif;
    display: inline-block;
    position: relative;
}

    .poll-container {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #fff;
        box-shadow: 0 2px 4px #007BFF;
        border-radius: 5px;
        margin-top: 10px;
    }

    .poll-info {
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type='submit'] {
        background-color: #007BFF;
        color: #fff;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type='radio'] {
        margin-right: 5px;
    }


</style>
