<?php
$id=$_GET['id'];
require('connection.php');
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
    
       
        if (isset($_POST['vote']))
        {
            $result[$_POST['op']]++;
            $voters[] = $_SESSION['activeuser'];
        }
    
          $updateSQL = $db->prepare("UPDATE surveys SET results=?,voters=? WHERE id =?");

            $upr=json_encode($result);
            $upv=json_encode($voters);
            echo $upr;
            echo $upv;
            $updateSQL->bindParam(1,$upr);
            $updateSQL->bindParam(2,$upv);
            $updateSQL->bindValue(3,$id);
            $$updateSQL->execute();
            $db = null;
    
    
    }
    
}catch (PDOException $ex) {
    echo "Error: " . $ex->getMessage();
    
}



?>


