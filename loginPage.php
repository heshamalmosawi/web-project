<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php 

    $regex_username = '/^[a-z]{3,15}$/i';
    $regex_email = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]{3,10}\.[a-zA-Z]{2,4}$/';
    $regex_password='/^(?=.*[A-Z])(?=.*[a-z])(?=.*[_#@$%\*\-])(?=.*[0-9])[A-Za-z0-9_#@%\*\-]{8,24}$/';
    $action ='#';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if (isset($_POST['signup']))
        {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password=$_POST['password'];
            if (!preg_match($regex_username, $username)) {
                echo "<script>alert('Invalid username. Must be between 3 and 15 characters.')</script>";
            }elseif (!preg_match($regex_email,$email)){
                echo "<script>alert('Email taken. Please try again!')</script>";
            }elseif (!preg_match($regex_password,$password)){
                echo "<script>alert('Weak password. Must contain: Atleast 8 characters (including one lower and upper case), a digit and a special character(_#@%\*\-)')</script>";
            }
            else 
            {
                echo '<script>alert("Sign up successful. Please login.")</script>';
                try{
                    require('connection.php');
                    $db->beginTransaction();
                    $rs =$db->prepare("INSERT INTO users(Username, email, password, pollsCreated) VALUES(?,?,?,?)");
              
                    $rs->bindParam(1, $username);
                    $rs->bindParam(2, $email);
                    $pword=password_hash($password, PASSWORD_DEFAULT);
                    $rs->bindParam(3, $pword);
                    $rs->bindValue(4, '[]');
                    $rs->execute();

                    $db->commit();
                    $db = null;
                  } catch (PDOException $ex){
                    $db->rollBack();
                    echo "error: ";
                    die($ex->getMessage());
                    }

            }
        }

        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            try {
                require('connection.php');
                $db->beginTransaction();
                $rs = $db->prepare("SELECT Username, password FROM users WHERE Username = ?");
                $rs->bindParam(1, $username);
                $rs->execute();
                $row = $rs->fetch(PDO::FETCH_ASSOC);
                

                if ($row && password_verify($password, $row['password'])) {
                    echo '<h2>Login successful</h2>';#no need for it, it will redirect to the page, this won't show up
                    session_start();
                    $_SESSION['activeuser']= $username;
                    $action="addpoll.php";
                    // Redirect or perform other actions after successful login

                    header("Location:$action"); #for redirecting to addpoll.php
                    exit;

                } else {
                    echo "<script>alert('Invalid login credentials.')</script>";
                }
                $db->commit();
                $db = null;
            } catch (PDOException $ex) {
                echo "error: ";
                die($ex->getMessage());
            }
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup</title>
    <body>
    <div class="container1">
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
    
    <style>

html {
    height:100%;
    width: 100%;
}

body {
    background-image:url(images/logo2-removebg-preview.png), linear-gradient(rgb(193, 191, 241), rgb(165, 109, 105));
    background-position:center; 
    background-repeat: no-repeat;
     font-family: 'Roboto', sans-serif;
    font-size: 1.125rem;
}

a {
    color: white;
    text-decoration: none;
}

.container1 {
    width: 95%;
    min-height: 300px;
    margin-left: auto;
    margin-right: auto;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid lightgrey;
    width:95%;
}


body {
    font-family: Arial, sans-serif;
}

.container {
    
    display: flex;
    justify-content: center;
    align-items: center;
    background-size: cover;
    margin: 0;
    padding: 0;
}
.navbutton {
    border: 1px solid white;
    padding: 11px 25px;
    font-family: 'Playfair Display', serif;
    display: inline-block;
    position: relative;
}

.form-container {
    width: 40%;
    padding: 100px;
    margin-top: 7%;
    box-shadow: 0px 2px 2px rgba(44, 26, 26, 0.794);
}

form {
    display: flex;
    flex-direction: column;
}

input {
    margin-bottom: 15px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

button {
    cursor: pointer;
    background-color: #007BFF;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    text-transform: uppercase;
    font-weight: bold;
}
    </style>
</head>
<body>
<div class="container">
        <div class="form-container" id="login-container">
            
            <form id="login-form" method="post" action="<?php echo $action; ?>">
                <input type="text" placeholder="Username" name="username" required>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" name="login">Login</button>
            </form>
            <div style="margin: 5px; text-align: center; border-radius: 5px; background-color: lightgray;"> Don't have an account? <a href="#" id="signup"><b style="color: red;">sign up</b></a></div>
        </div>

        <div class="form-container" id="signup-container">
           
            <form id="signup-form" method="post" action="">

                <p>Suggestions: <span id="txtHint"></span></p>
                <input type="text" placeholder="Username" name="username" required onkeyup ="showHint(this.value)">
                <input type="text" placeholder="Email"  name="email" required onkeyup="emailV(this.value)">
                <span id ="emailv"></span>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" name="signup" id="signup-button">signup</button>

            </form>
            <div style="margin: 5px; text-align: center; border-radius: 5px; background-color: lightgray;" >Already have an account? <a href="#" id="login"><b style="color: red;">login</b></a></div>
        </div>
    </div>
<script>
    document.getElementById('login-container').style.display = 'block';
    document.getElementById('signup-container').style.display = 'none';

    function showLoginForm() {
    document.getElementById('login-container').style.display = 'block';
    document.getElementById('signup-container').style.display = 'none';
    }

    function showSignupForm() {
    document.getElementById('login-container').style.display = 'none';
    document.getElementById('signup-container').style.display = 'block';
    }

    document.getElementById('signup').addEventListener('click', function(e) {

    showSignupForm();
    });

    document.getElementById('login').addEventListener('click', function(e) {
    e.preventDefault();
    showLoginForm();
    });

    function showHint(str) {
            if (str.length == 0) {
                document.getElementById("txtHint").innerHTML = "";
                return;
            }
            const xhttp = new XMLHttpRequest();
            xhttp.onload = myAJAXFunction;
            xhttp.open("GET", "gethint.php?hint=" + str, true);
            xhttp.send();
        }

        function myAJAXFunction() {
            document.getElementById("txtHint").innerHTML = this.responseText;
        }

        function emailV(str) {
    if (str.length == 0) {
        document.getElementById("emailv").innerHTML = "";
        enablesignupButton();
        return;
    }

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        handleEmailValidation(this.responseText);
    };
    xhttp.open("GET", "emailv.php?emailv=" + str, true);
    xhttp.send();
}

function handleEmailValidation(response) {
    var emailvMessage = document.getElementById("emailv");
    var signupButton = document.getElementById("signup-button");

    if (response.trim() === "true") {
        emailvMessage.innerHTML = "Email already exists!";
        emailvMessage.style.color = "red";
        emailvMessage.style.fontWeight = "bold";
        disablesignupButton();
    } else {
        emailvMessage.innerHTML = "";
        emailvMessage.style.color = "black";
        enablesignupButton();
    }
}

function disablesignupButton() {
    document.getElementById("signup-button").disabled = true;
}

function enablesignupButton() {
    document.getElementById("signup-button").disabled = false;
}


</script>
</body>
</html>
