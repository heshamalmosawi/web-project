<?php
$time= date('Y-m-d');
echo "the time now is: " . $time . "<BR><BR>";
require("connection.php");
$rs=$db->query("Select * FROM surveys WHERE id=1");
//prepare
while($row = $rs->fetch(PDO::FETCH_ASSOC)){
    print_r($row);
    echo "<BR><br>" . $row['expireDate'];
    if ($row['expireDate'] > $time) {
        echo "<br>Is it expired?<br>";
    }
}
?>