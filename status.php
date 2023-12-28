<?php

$time= date('Y-m-d');

try{
require("connection.php");
$rs=$db->query("Select * FROM surveys");
//prepare
while($row = $rs->fetch(PDO::FETCH_ASSOC)){
     if ($row['expireDate'] < $time && $row['expireDate'] != "0000-00-00") {
        $updateQuery=$db->query("UPDATE surveys SET status=0 WHERE id=" . $row['id']);
    }
}
} catch (PDOException $e){
    echo "500: internal server error";
}
?>
