<?php 
    //Array with names
$a[] = "Abdulrahman";
$a[] = "Bassim";
$a[] = "cinderella"; 
$a[] = "Danial";
$a[] = "Ebraheem";
$a[] = "fajer";
$a[] = "Abdul";
$a[] = "Alice";
$a[] = "Bob";
$a[] = "Catherine";
$a[] = "David";
$a[] = "Eva";
$a[] = "Frank";
$a[] = "Grace";
$a[] = "Henry";
$a[] = "Ivy";
$a[] = "Jack";
$a[] = "Karen";
$a[] = "Leo";
$a[] = "Mia";
$a[] = "Nathan";
$a[] = "Olivia";
$a[] = "Paul";
$a[] = "Quincy";
$a[] = "Rachel";
$a[] = "Samuel";
$a[] = "Tina";
$a[] = "Ulysses";
$a[] = "Violet";
$a[] = "William";
$a[] = "Xander";
$a[] = "Yvonne";
$a[] = "Zachary";
$a[] = "Ahmad";
$a[] = "Basma";
$a[] = "Chadi";
$a[] = "Dana";
$a[] = "Emad";
$a[] = "Fadia";
$a[] = "Ghassan";
$a[] = "Hanan";
$a[] = "Ibrahim";
$a[] = "Jamila";
$a[] = "Khalid";
$a[] = "Layla";
$a[] = "Majid";
$a[] = "Nadia";
$a[] = "Omar";
$a[] = "Randa";
$a[] = "Sami";
$a[] = "Tahira";
$a[] = "Umar";
$a[] = "Varda";
$a[] = "Waleed";
$a[] = "Xena";
$a[] = "Yasmin";
$a[] = "Zayd";
 // and more
    
    $q = $_REQUEST['hint'];
    $hint = "";
    if ($q !== "") {
        $q = strtolower($q);
        $len=strlen($q);
        foreach($a as $name) {
        if ($q == strtolower(substr($name, 0, $len))) {
            if ($hint === "") {
            $hint = $name;
            } 
            else {
            $hint .= ", $name";
            }
         }
    
        }
    }
    echo $hint === ""? "No suggestion":$hint;


?>