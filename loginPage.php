<?php 

    $regex_username = '/^[a-z]{3,15}$/i';
    $regex_email = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]{3,10}\.[a-zA-Z]{2,4}$/';
    $regex_password='/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9_#@%\*\-]{8,24}$/';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if (isset($_POST['signup']))
        {
            $Username = $_POST['username'];
            $email = $_POST['email'];
            $password=$_POST['password'];
            if (!preg_match($regex_username, $Username)) {
                echo "<script>alert('Invalid username for sign up.')</script>";
            }elseif (!preg_match($regex_email,$email)){
                echo "<script>alert('Invalid email for sign up.')</script>";
            }elseif (!preg_match($regex_password,$password)){
                echo "<script>alert('Invalid password for sign up.')</script>";
            }
            else 
            {// 3dl 3la de
                echo 'Sign up successful';
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
            <form id="login-form" method="post" action="">
                <input type="text" placeholder="Username" required>
                <input type="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            Don't have an account? <a href="#" id="signup">sign up</a>
        </div>

        <div class="form-container" id="signup-container">
            <h2>Sign up</h2>
            <form id="signup-form" method="post" action="">
                <input type="text" name='username' placeholder="Username" required>
                <input type="password" name='password' placeholder="Password" required>
                <input type="email" name='email' placeholder="Email" required>
                <button type="submit" name="signup">Sign up</button>
            </form>

            Already have an account? <a href="#" id="login">login</a>
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
</script>


</body>
</html>