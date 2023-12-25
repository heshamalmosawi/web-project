<?php 
    $email =$_REQUEST['emailv'];
    $temp = "false";

   try{
        require('connection.php');
        $rs=$db->query('SELECT * FROM users');
        $row=$rs->fetchAll(PDO::FETCH_ASSOC);
        $allEmails= [];
        foreach($row as $slice){
            $allEmails[] = $slice['Email'];
        }
        foreach ($allEmails as $e){

            if ($e == $email)
                $temp = "true";
            
        }
        echo $temp;

        }catch (PDOException $ex){
            echo "error: ";
            die($ex->getMessage());
        }

?>
