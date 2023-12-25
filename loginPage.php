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
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.container {
    
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding: 0;
}

.form-container {
    width: 400px;
    padding: 20px;
    background-color: white;
    box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.2);
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

button:hover {
    background-color: #0056b3;
}


    </style>
</head>
<body>
<div class="container">
        <div class="form-container" id="login-container">
            <h2>Login</h2>
            <form id="login-form" method="post" action="<?php echo $action; ?>">
                <input type="text" placeholder="Username" name="username" required>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" name="login">Login</button>
            </form>
            Don't have an account? <a href="#" id="signin">sign in</a>
        </div>

        <div class="form-container" id="signin-container">
            <h2>Signin</h2>
            <form id="signin-form" method="post" action="">

                <p>Suggestions: <span id="txtHint"></span></p>
                <p>email test: <span id ="emailv"></span></p>
                <input type="text" placeholder="Username" name="username" required onkeyup ="showHint(this.value)">
                <input type="text" placeholder="Email"  name="email" required onkeyup="emailV(this.value)">
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" name="signin" id="signin-button">Signin</button>

            </form>
            Already have an account? <a href="#" id="login">login</a>
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
