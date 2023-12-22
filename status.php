<?php
//maybe will need more work after


$time= date('Y-m-d');
echo "the time now is: " . $time . "<BR><BR>";
try{
require("connection.php");
$rs=$db->query("Select * FROM surveys WHERE id=1");
//prepare
while($row = $rs->fetch(PDO::FETCH_ASSOC)){
    print_r($row);
    echo "<BR><br>" . $row['expireDate'];
    if ($row['expireDate'] < $time) {
        // echo "<br>Is it expired?<br>";
        $updateQuery=$db->query("UPDATE surveys SET status=0 WHERE id=" . $row['expireDate']);
    }
}
} catch (PDOException $e){
    echo "500: internal server error";
}
?>