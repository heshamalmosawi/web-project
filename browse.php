<?php
try {
    session_start();

    require('connection.php');

    $all_poll = 'SELECT * FROM surveys';

    $res = $db->query($all_poll);

    $fet = $res->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($fet); ++$i) {
        $date=json_encode($fet[$i]["expireDate"]);
        echo "<div id=" . $i . " class='allQ' onclick='show(" . $i . ','. $date. ")'> <a href='#'>" . $fet[$i]['question']. "</a> </div>";
    }

  
  

    


    echo "<div id='resultpoll'></div>";
} catch (PDOException $e) {
    die($e->getMessage());
}
?>

<script>
    function show(id,s) {
        var resultpoll = document.getElementById("resultpoll");

        var a = document.getElementsByClassName("allQ");
        for (var i = 0; i < a.length; i++) {
            a[i].style.display = "none";
        }


        resultpoll.innerHTML = "<div class='poll-container'>" +
            "<div class='poll-info'>Question: " + <?php echo json_encode($fet); ?>[id]["question"] + "</div>" +
            <?php $result= json_decode($fet); ?>[id]["results"] +
            "<div class='poll-info'>Results:<?php
              foreach ($result as $key => $value)
               {
                  echo  "<br>".$value."% " ;
        
                     echo "<progress  value='$value' max='100'></progress> $key";} ?> </div>"   +
           
            "<div class='poll-info ' id='Expire'>Expire Date: " + <?php echo json_encode($fet); ?>[id]["expireDate"] + "</div>" +
            "<div class='poll-info'>Status: " + <?php echo json_encode($fet); ?>[id]["status"] + "</div>" + 
            "</div>";
            var expire =document.getElementById("Expire");
    if (s=="0000-00-00")
    {
        expire.style.display="none";
    }


    }

</script>

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

       