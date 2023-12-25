<?php
try {
    session_start();
    require('connection.php');

    $rs = $db->query('SELECT * FROM surveys');

    $fet = $rs->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fet as $row){
        extract($row);
        echo "<a href='viewpoll.php?id=$id'>$question</a>"   ;

    }    
    

//     $resjson = json_decode($fet[0]['results'],true);
//     $votjson = json_decode($fet[0]['voters'],true);
    
//      echo $fet[0]['question']."<br>";
//             $sum=0;
//      foreach ($resjson as $key => $value)
//      {   
//         $sum +=$value;
//      }
//     foreach ($resjson as $key => $value)
//     {   echo $key." ";
//         echo "<progress id='file' value='".$value."' max='".$sum."'></progress> ". $value/$sum . "% <br>";
//     }
//     if ($fet[0]["status"]==1)
//     {
//         echo "Status:Open";   
//     }
//     else 
//     echo "Status:Close";

//     echo "<br> who vote in this poll <br>";
//     for ($i=0; $i<count($votjson);++$i)
//     {
//         echo $votjson[$i]."<br>";
//     }


//     echo "<br><br>";
//     echo "<form method='POST' action=''>";
//     foreach ($resjson as $key => $value)
//     {   echo $key." ";
//         echo "<input type='radio' name='op' value='".$key."'><br>";
//     }
//     echo "<input type='submit' name='voting' value='vote'>";
//     echo "</form>";
  
//     echo $resjson[$_POST['op']];
//     echo "<br>";
//     if (isset($_POST['voting']))
//     {
//         $resjson[$_POST['op']]++;
//     }

//     echo $resjson[$_POST['op']];
//     echo "<br>";
//     $isvote =false;
//     for ($i=0; $i<count($votjson);++$i)
//     {
//         if ($_SESSION['activeuser']==$votjson[$i])
//         {
//             $isvote=true;
//         }
        
//     }
//    if ($isvote)
//    {
//     echo "no vote";
//    }else
//    echo "vote";

//    echo "<br><br>";


   

//    $updateSQL = $db->prepare("UPDATE surveys (results,voters) VALUES (?,?) WHERE id =?");
//    $upr=json_encode($resjson);
//    $upv=json_encode($votjson);
//    $updateSQL->bindParam(1,$upr);
//    $updateSQL->bindParam(2,$upv);





















  
} catch (PDOException $e) {
    die($e->getMessage());
}
?>
<style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        h2 {
            color: #007BFF;
        }

        .poll-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
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
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>

