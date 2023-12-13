<?php
$db = new PDO('mysql:host=localhost;dbname=pollsdb;', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>