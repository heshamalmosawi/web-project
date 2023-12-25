<?php
if (!isset($_GET['id'])){
    echo "<script>
    alert('Invalid Poll, please try again!');
    window.location.href = 'browse2.php';
  </script>";
exit();
}

$id=$_GET['id'];
require('connection.php');
require('status.php');
$rs= $db->query('SELECT * FROM surveys');
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
    alert('Invalid Poll, please try again!');
    window.location.href = 'browse2.php';
  </script>";
} 
session_start();
$rs = $db->prepare("SELECT * FROM surveys WHERE id =?");
$rs->bindValue(1, $id);
$rs->execute();
$rows = $rs->fetchAll(PDO::FETCH_ASSOC);

try 
{
    foreach ($rows as $row) {
 
        $result =json_decode($row["results"],true);
        $voters= json_decode($row['voters'],true);
        $isvote=false;
    
        foreach ($voters as $key)
        {
            if ($_SESSION['activeuser']==$key || $row["status"]==0)
            $isvote=true;
        }
      
        if ($isvote)
        {   
            echo "<div class='poll-container'>";
            echo "<div class='poll-info'>Creater:".$row["creater"]. "</div>";
            echo "<div class='poll-info'>Question: " . $row["question"] . "</div>";
            echo "<div class='poll-info'>Results: ";
            foreach ($result as $key => $value){
                echo  "<br>".($value*100/count($voters))."% " ;
                echo "<progress  value='$value' max='".count($voters)."'></progress> $key";}
            
                echo "</div>";
            
                if ($row["expireDate"] !="0000-00-00")
                echo "<div class='poll-info'>Expire Date: " . $row["expireDate"] . "</div>";
                
                if ($row["status"]==0)
                echo "<div class='poll-info'>Status:Close</div>";
                else 
                echo "<div class='poll-info'>Status:Open</div>";
            
        }else 
        {
            echo "<div class='poll-container'>";
            echo "<div class='poll-info'>Creater:".$row["creater"]. "</div>";
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
            
                if ($row["status"]==0)
                echo "<div class='poll-info'>Status:Close</div>";
                else 
                echo "<div class='poll-info'>Status:Open</div>";
        }
    
        $updateSQL = $db->prepare("UPDATE surveys SET results=?,voters=? WHERE id =?");

            $upr=json_encode($result);
            $upv=json_encode($voters);
            $updateSQL->bindParam(1,$upr);
            $updateSQL->bindParam(2,$upv);
            $updateSQL->bindValue(3,$id);
            $updateSQL->execute();
            $db = null;
            
            
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
        $db = null;
        
        if (isset($_POST['vote']))
        header("Location:viewpoll.php?id=".$id);

    
    }
    
}catch (PDOException $ex) {
    echo "Error: " . $ex->getMessage();
    
}



?>


