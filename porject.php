<?php 
#Hello Worlde

?>


<head>
<style>
body{
  background-color:grey;
}
.o1 {
  text-align:center;
  margin: 10 10 10 10;
  width: 140px;
  border: 10px solid blue ;
  border-radius: 10px;
}
.o2{
  text-align:center;
  margin: 10 10 10 10;
  width: 120px;
  border: 10px solid white ;
  border-radius: 10px;
  right: -100px;

}
  </style>
</head>
<body>

<form>
  <label>
    <input type="radio" name="Option" value="Option1"> Option 1
  </label>
  <label>
    <input type="radio" name="Option" value="Option2"> Option 2
  </label>
  <label>
    <input type="radio" name="Option" value="Option3"> Option 3
  </label>

  <p>Choose your Option</p>
  <input type="submit" value="Submit">
</form>

<div class="o1"></div>
<div class="o2" style="display:inline-block;"></div>

</body>
</html>
