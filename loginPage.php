<?php 

    $regex_username = '/^[a-z]{3,15}$/i';
    $regex_email = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]{3,10}\.[a-zA-Z]{2,4}$/';
    $regex_password='/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9_#@%\*\-]{8,24}$/';
    $action ='#';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if (isset($_POST['signin']))
        {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password=$_POST['password'];
            if (!preg_match($regex_username, $username)) {
                echo "<script>alert('Invalid username for sign in.')</script>";
            }elseif (!preg_match($regex_email,$email)){
                echo "<script>alert('Invalid email for sign in.')</script>";
            }elseif (!preg_match($regex_password,$password)){
                echo "<script>alert('Invalid password for sign in.( need at least 8 characters)')</script>";
            }
            else 
            {
                echo '<h2>Sign up successful Please login</h2>';
                try{
                    require('connection.php');
                    $rs =$db->prepare("INSERT INTO users(Username, email, password, pollsCreated) VALUES(?,?,?,?)");
              
                    $rs->bindParam(1, $username);
                    $rs->bindParam(2, $email);
                    $pword=password_hash($password, PASSWORD_DEFAULT);
                    $rs->bindParam(3, $pword);
                    $rs->bindValue(4, '[]');
                    $rs->execute();
                    $db = null;
              
                  } catch (PDOException $ex){
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
                    <img src="images/logo2-removebg-preview.png " width="100px" height="100px" alt="Logo for Bike Repair shop Roar Bikes">
                </a>
            </div>

            <nav>
                
                <div class="navbutton">
                    <a href="index1.html">Home</a>
                </div>


            </nav>
        </header>
    
    <style>

html {
    background-image: linear-gradient(rgb(193, 191, 241), rgb(165, 109, 105));
}

body {
    background-image: url(images/logo2-removebg-preview.png) ;
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
    width: 1024px;
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
}


body {
    font-family: Arial, sans-serif;
}

.container {
    
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
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
    width: 400px;
    padding: 100px;
    margin: top;
   /* background-color: white; */
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
            <div style="margin: 5px; text-align: center; border-radius: 5px; background-color: lightgray;"> Don't have an account? <a href="#" id="signin"><b style="color: red;">sign in</b></a></div>
        </div>

        <div class="form-container" id="signin-container">
           
            <form id="signin-form" method="post" action="">

                <p>Suggestions: <span id="txtHint"></span></p>
                <p>email test: <span id ="emailv"></span></p>
                <input type="text" placeholder="Username" name="username" required onkeyup ="showHint(this.value)">
                <input type="text" placeholder="Email"  name="email" required onkeyup="emailV(this.value)">
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" name="signin" id="signin-button">Signin</button>

            </form>
            <div style="margin: 5px; text-align: center; border-radius: 5px; background-color: lightgray;" >Already have an account? <a href="#" id="login"><b style="color: red;">login</b></a></div>
        </div>
    </div>
<script>
    document.getElementById('login-container').style.display = 'block';
    document.getElementById('signin-container').style.display = 'none';

    function showLoginForm() {
    document.getElementById('login-container').style.display = 'block';
    document.getElementById('signin-container').style.display = 'none';
    }

    function showSignupForm() {
    document.getElementById('login-container').style.display = 'none';
    document.getElementById('signin-container').style.display = 'block';
    }

    document.getElementById('signin').addEventListener('click', function(e) {

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
        enableSigninButton();
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
    var signinButton = document.getElementById("signin-button");

    if (response.trim() === "true") {
        emailvMessage.innerHTML = "Email already exists!";
        disableSigninButton();
    } else {
        emailvMessage.innerHTML = "";
        enableSigninButton();
    }
}

function disableSigninButton() {
    document.getElementById("signin-button").disabled = true;
}

function enableSigninButton() {
    document.getElementById("signin-button").disabled = false;
}


</script>
</body>
</html>
