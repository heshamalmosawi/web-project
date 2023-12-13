<?php
  
  if (isset($_POST["create"])){
  try{
    $questionName = $_POST["questionName"];
    $op = $_POST["op"];
    print_r($op);

    require('connection.php');
    
    $rs= $db->prepare("INSERT INTO questions(question,answer) VALUES(?,?)");
    
      $x = json_encode($op);
      
      $rs->bindParam(1, $questionName);
      
      $rs->bindParam(2, $x);
      $rs->execute();
      $db = null;

      if($rs->rowCount() > 0){
          echo "Inserted successfully";
      } else {
          die("There was an error");
      }


      


    // $op = $_POST['op'];
    // print_r($op);
  }
  catch (PDOException $ex){
  echo "error: ";
  die($ex->getMessage());
  } 

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
      </script>

</body>
</html>