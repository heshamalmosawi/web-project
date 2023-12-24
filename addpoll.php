<?php
require ("status.php");
  session_start();
  echo "Welcome ".$_SESSION['activeuser'];
  if (!isset($_SESSION['activeuser'])){
    die("please login!");
  }
  if (isset($_POST["create"])){
    // if(!isset($_POST['close']))  {   echo "<script>alert('chose how to close poll')</script>";}
    // else{
    try{
      $questionName = $_POST["questionName"];
      $op = $_POST["op"];

      require('connection.php');
      
      $rs= $db->prepare("INSERT INTO surveys(question, results, voters, expireDate, creater, status) VALUES(?,?,?,?,?,?)");
      // this one is question name
      $rs->bindParam(1, $questionName);
      
      // this is to store the options with results in json string, and initialize votes to 0
      foreach ($op as $key){
          $results[$key] = 0;
      }
      $resultsJ = json_encode($results);
      $rs->bindParam(2, $resultsJ);
      
      // no voters yet
      $voters = '[]';
      $rs->bindParam(3, $voters);

      // expire date depending on manual or scedhuled
      if ($_POST['close'] == 'manual') {
        $rs->bindValue(4, '');
      } else {
        $rs->bindParam(4, $_POST['dateExpiry']);
      }
      
      $rs->bindValue(5, $_SESSION['activeuser']);

      $rs->bindValue(6, 1);

      $rs->execute();
      
      #update the users table for the pollsCreated
      $pollsCreatedStmt = $db->prepare("UPDATE users SET pollsCreated = pollsCreated + 1 WHERE Username = ?");
      $pollsCreatedStmt->bindValue(1, $_SESSION['activeuser']);
      $pollsCreatedStmt->execute();

      $db = null;
      
      header("Location:userpolls.php"); #for redirecting to addpoll.php
      exit;

    }catch (PDOException $ex){
      echo "error: ";
      die($ex->getMessage());
      } 
    

  // }
}


?>

<head>
<style>

body{
  background-color:grey;
}
.o1 {
  text-align:center;

  width: 250px;
  border: 10px solid white ;
  border-radius: 10px;
}
.o2{
  text-align:center;
 
  width: 230px;
  border: 10px solid blue ;
  border-radius: 10px;
  margin:-20 0  ;
}
  </style>
</head>
<body>

<form method="POST" onsubmit="return empty()" action="" name="form">
<h1>make your poll</h1>
  <label>
    <input type="text" placeholder="Type your question here" name="questionName">
  </label>

        <div id="pollOptions">
          <label>
             <input  placeholder="Option 1" type="text" name=op[]>
          </label>
          <label>
           <input placeholder="Option 2" type="text" name=op[]>
          </label>
        </div>
        
        <button type="button" onclick="add()">add more option</button>
        <button type="button" onclick="remove()">remove more option</button>
          <br>
            <div>
              <p>How do you want to close the poll?</p>
              <input type="radio" id="automatic" name="close" value=automatic onchange="showOrHideDates()">
              <label for="automatic">By Scheduled date</label>
              <div id="DateE"></div>
              <input type="radio" id="manual" value=manual name="close" onchange="showOrHideDates()">
              <label for="manual">Manual</label>
            </div>

          <input type="submit"  name="create" id="">
      </form>
      
      <script>
          var optionCount= 2;
      function add() {
        ++optionCount;
        var pollOptionsContainer = document.getElementById("pollOptions");
        var newPollOption = document.createElement("label");
        newPollOption.innerHTML = " <input placeholder='Option "+optionCount+"' name=op[] type='text'>";

        pollOptionsContainer.appendChild(newPollOption);
      }
      function remove() 
      {
        if (optionCount > 2)
        {
          var pollOptionsContainer = document.getElementById("pollOptions");
          pollOptionsContainer.removeChild(pollOptionsContainer.lastChild);
          --optionCount;

        }else {
          alert("Atleast two option");
        }

      }
      function empty()
      {
        var questionName = document.forms["form"]["questionName"].value;
        if (questionName=="" ){
          alert('Must be write your question');
          return false;
        }
        var op =document.getElementsByName("op[]");
        for (let x in op)
        {
          if(op[x].value.trim()=="")
          alert("answer");
        }
      
      }
      function zeroPad(number) {
  return number < 10 ? '0' + number : '' + number;
}

function showOrHideDates() {
  var automaticRadio = document.getElementById("automatic");
  var DateE = document.getElementById("DateE");

  const date = new Date();

  let day = date.getDate();
  let month = date.getMonth() + 1;
  let year = date.getFullYear();

  let currentDate = `${year}-${month}-${day}`;

  if (automaticRadio.checked) {
    // Show the date input when "By Scheduled date" is selected
    DateE.innerHTML = '<input name=dateExpiry value="'+currentDate+'" type="date" min="' + currentDate +'">';
  } else {
    // Hide the date input for other options
    DateE.innerHTML = "";
  }
}
</script>

</body>
</html>
