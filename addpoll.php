<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    html {
    background-image: linear-gradient(rgb(193, 191, 241), rgb(165, 109, 105));
    }

    header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid lightgrey;
    margin-left: auto;
    margin-right:auto;
    width:95%;
    }
    body {
        background-image: url(images/logo2-removebg-preview.png) ;
        background-position:center; 
        background-repeat: no-repeat;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 1.125rem;
        text-align: center;
    }
    

    .navbutton {
        border: 1px solid white;
        padding: 11px 25px;
        font-family: 'Playfair Display', serif;
        display: inline-block;
        position: relative;
    }  

        .o1 {
            width: 250px;
            border-radius: 10px;
            margin: 20px auto;
            background-color: #007BFF;
            color: white;
            padding: 10px;
        }

        .o2 {
            width: 230px;
            border: 10px solid blue;
            border-radius: 10px;
            margin: -20 0;
        }

        form {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007BFF;
        }

        input[type='text'],
        input[type='date'] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        input[type='submit'],
        input[type='button'] {
            background-color: #007BFF;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type='radio'] {
            margin-right: 5px;
        }

        p {
            color: #007BFF;
            font-weight: bold;
            margin-bottom: 5px;
        }
        a {
            color: white;
            text-decoration: none;
        }
        @media (max-width:768px){
          form {
            width: 80%;
            /* height: 40%; */
          }  
          h1 {
            font-size:45px;
          }
          input[type='text'],
        input[type='date']{
            width:85%;
        }
        }
    </style>
</head>

<body>
<header>
            <div class="logo">
                <a href="index.html">
                    <img src="images/logo2-removebg-preview.png " width="100px" height="100px">
                </a>
            </div>

            <nav>


            
                <div class="navbutton">
                    <a href="index.html">Home</a>
                </div>


            </nav>
        </header>
<?php
require("status.php");
session_start();
if (!isset($_SESSION['activeuser'])) {
        echo "<script>
        alert('Cannot create poll, Please log in!');
        window.location.href = 'loginPage.php';
      </script>";
    exit();
}
echo "<div class='o1'>Welcome " . $_SESSION['activeuser'] . "</div>";
if (isset($_POST["create"])) {
    try {
        $questionName = $_POST["questionName"];
        $op = $_POST["op"];

        require('connection.php');
        $db->beginTransaction();
        $rs = $db->prepare("INSERT INTO surveys(question, results, voters, expireDate, creater, status) VALUES(?,?,?,?,?,?)");
        $rs->bindParam(1, $questionName);

        foreach ($op as $key) {
            if (!isset($results[$key])){
            $results[$key] = 0;
            } else {
                echo "<script>alert('Duplicate answers. Please try again.');</script>";
                $db->commit();
                $db = null;        
                goto end;
            }
        }
        $resultsJ = json_encode($results);
        $rs->bindParam(2, $resultsJ);

        $voters = '[]';
        $rs->bindParam(3, $voters);

        if ($_POST['close'] == 'manual') {
            $rs->bindValue(4, '');
        } else {
            $rs->bindParam(4, $_POST['dateExpiry']);
        }

        $rs->bindValue(5, $_SESSION['activeuser']);
        $rs->bindValue(6, 1);
        $rs->execute();

        $pollsCreatedStmt = $db->prepare("UPDATE users SET pollsCreated = pollsCreated + 1 WHERE Username = ?");
        $pollsCreatedStmt->bindValue(1, $_SESSION['activeuser']);
        $pollsCreatedStmt->execute();

        $db->commit();
        $db = null;

        header("Location:userpolls.php");
        exit;
    } catch (PDOException $ex) {
        $db->rollBack();
        echo "<div class='o1'>Error: " . $ex->getMessage() . "</div>";
    }
    end:
}
?>
    <form method="POST" onsubmit="return empty()" action="" name="form">
        <h1>Make Your Poll</h1>
        <label>
            <input type="text" placeholder="Type your question here" name="questionName">
        </label>

        <div id="pollOptions">
            <label>
                <input placeholder="Option 1" type="text" name=op[]>
            </label>
            <label>
                <input placeholder="Option 2" type="text" name=op[]>
            </label>
        </div>

        <button type="button" onclick="add()">Add More Option</button>
        <button type="button" onclick="remove()">Remove More Option</button>
        <br>

        <div>
            <p>How do you want to close the poll?</p>
            <input type="radio" id="automatic" name="close" value=automatic onchange="showOrHideDates()">
            <label for="automatic">By Scheduled date</label>
            <div id="DateE"></div>
            <input type="radio" id="manual" value=manual name="close" onchange="showOrHideDates()">
            <label for="manual">Manual</label>
        </div>

        <input type="submit" name="create" value="Create Poll">
    </form>

    <script>
        var optionCount = 2;

        function add() {
            ++optionCount;
            var pollOptionsContainer = document.getElementById("pollOptions");
            var newPollOption = document.createElement("label");
            newPollOption.innerHTML = " <input placeholder='Option " + optionCount + "' name=op[] type='text'>";

            pollOptionsContainer.appendChild(newPollOption);
        }

        function remove() {
            if (optionCount > 2) {
                var pollOptionsContainer = document.getElementById("pollOptions");
                pollOptionsContainer.removeChild(pollOptionsContainer.lastChild);
                --optionCount;
            } else {
                alert("At least two options are required.");
            }
        }

        function empty() {
            var questionName = document.forms["form"]["questionName"].value;
            if (questionName == "") {
                alert('Please enter a question.');
                return false;
            }
            var op = document.getElementsByName("op[]");
            for (let x in op) {
                if (op[x].value.trim() == "") {
                    alert("Please fill the empty answer.");
                    return false;
                }
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

            let currentDate = `${year}-${zeroPad(month)}-${zeroPad(day)}`;

            if (automaticRadio.checked) {
                // Show the date input when "By Scheduled date" is selected
                DateE.innerHTML = '<input name=dateExpiry value="' + currentDate + '" type="date" min="' + currentDate + '">';
            } else {
                // Hide the date input for other options
                DateE.innerHTML = "";
            }
        }
    </script>
</body>
</html>
