<?php
try {
    session_start();

    require('connection.php');

    $all_poll = 'SELECT * FROM surveys';

    $res = $db->query($all_poll);

    $fet = $res->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($fet); ++$i) {
        $date=json_encode($fet[$i]["expireDate"]);
        $resjson=json_encode($fet[$i]['results']);
        $userwhovote= json_encode($fet[$i]['voters']);
        $isstuts= ($fet[$i]["status"]);
        echo "<div id=" . $i . " class='allQ' onclick='show(" . $i . ','. $date.','.$resjson.','.$userwhovote.','.$isstuts. ")'> <a href='#'>" . $fet[$i]['question']. "</a> </div> <br>";
        
    }

   
   
    echo "<div id='tmt'>1</div>";

    


    echo "<div id=''>
    <form id='resultpoll' method='POST' action=''></form>
    </div>";
} catch (PDOException $e) {
    die($e->getMessage());
}
?>

<script>
    function show(id,date,resjson,userwhovote,isstuts) {
        var resultpoll = document.getElementById("resultpoll");

        var a = document.getElementsByClassName("allQ");
        for (var i = 0; i < a.length; i++) {
            a[i].style.display = "none";
        }

         const r =JSON.parse(resjson);
         const voters = JSON.parse(userwhovote);
         var isvote=false;
       
         for (let i=0; i<voters.length;++i)
         {
            if(voters[i]=='<?php echo $_SESSION['activeuser']; ?>')
            isvote=true;
         }
         if (isstuts==0)
         {
            isvote=true;
         }
         if (isvote)
         {
            resultpoll.innerHTML =
         "<div class='poll-container'>" +
            "<div class='poll-info'>Question: " + <?php echo json_encode($fet); ?>[id]["question"] + "</div>" +
            "<div class='poll-info'>Results:";
           var sumvote=0;
            for (let key in r)
            {
                 sumvote+=r[key];
            }
            for (let key in r) {
                    
                    resultpoll.innerHTML += key + ' <progress  value="'+r[key] +'"max="'+sumvote+'"></progress> = '+r[key]*100/sumvote + '%<br>';
                
                }
           
            resultpoll.innerHTML +="</div>"   +
                   
            "<div class='poll-info ' id='Expire'>Expire Date: " + <?php echo json_encode($fet); ?>[id]["expireDate"] + "</div>" +
            "<div class='poll-info'>Status: " + <?php echo json_encode($fet); ?>[id]["status"] + "</div>" + 
            "</div>";
         }else 
         {
                resultpoll.innerHTML ="<div class='poll-container'>" +
                "<div class='poll-info'>Question: " + <?php echo json_encode($fet); ?>[id]["question"] + "</div>" +
                "<div class='poll-info'>Results:";
             
                for (let key in r) {
                    
                        resultpoll.innerHTML += key + "<input type='radio' name='votes' value='"+key+"'>"  + "<br>";
                    
                    }
            
                resultpoll.innerHTML +="</div> <input type='submit' name='voting' value='vote' onclick=updateSQL("+id+','+resjson+")>"+
                "<div class='poll-info ' id='Expire'>Expire Date: " + <?php echo json_encode($fet); ?>[id]["expireDate"] + "</div>" +
                "<div class='poll-info'>Status: " + <?php echo json_encode($fet); ?>[id]["status"] + "</div>" + 
                "</div>";
                

         }
            var expire =document.getElementById("Expire");
            if (date=="0000-00-00")
            {
                expire.style.display="none";
            }
            
            }

            function updateSQL(id,resjson)
            {
                var io =document.getElementsByName("votes").values;
               document.getElementById("tmt").innerHTML=io;
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

