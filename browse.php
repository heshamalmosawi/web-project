<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<header>
            <div class="logo">
                <a href="index.html">
                    <img src="images/logo2-removebg-preview.png " width="100px" height="100px" alt="Logo for Bike Repair shop Roar Bikes">
                </a>
            </div>

            <nav>
                <div class="navbutton">
                    <a href="index.html">Home</a>
                </div>
            </nav>
        </header>

<?php
try {
    session_start();
    require('connection.php');

    $rs = $db->query('SELECT * FROM surveys');

    $fet = $rs->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class=allq>";
    foreach ($fet as $row){
        extract($row);
        echo "<a class='poll-link' href='viewpoll.php?id=$id'>
                <div class='poll-container'>
                    <div class='poll-info'>$question</div>
                </div>
            </a>";
    }    
    echo "</div>";
  
} catch (PDOException $e) {
    die($e->getMessage());
}
?>
<style>
  html {
    min-height:100%;
    min-width:100%
}
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid lightgrey;
    margin:0 auto;
}
body {
    background-image:url(images/logo2-removebg-preview.png), linear-gradient(rgb(193, 191, 241), rgb(165, 109, 105));
    background-position:center; 
    background-repeat: no-repeat;
    font-family: 'Roboto', sans-serif;
    font-size: 1.125rem;
    background-size: cover;
}

.allq{
    margin:5% auto;
    width:95%;
}

.navbutton {
    border: 1px solid white;
    padding: 11px 25px;
    font-family: 'Playfair Display', serif;
    display: inline-block;
    position: relative;
}
    

    a {
        color: white;
        text-decoration: none;
    }

    .poll-link {
        display: block;
        margin-top: 10px;
        color: #007BFF;
        text-decoration: none;
    }

    .poll-link:hover {
        text-decoration: underline;
    }

    .poll-container {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #fff;
        box-shadow: 0 2px 4px #007BFF;
        border-radius: 5px;
        display: flex;
        justify-content: center;
        
    }

    .poll-info {
        font-weight: bold;
        margin-bottom: 5px;
    }


    @media (max-width: 768px){
        .poll-container {
            padding: 35px;
            margin:0 5%;
        }
        .poll-info
        {
            font-size: 35px;
        }
    }
    @media (max-width: 450px){
        .poll-info{
            font-size:16px;
        }
        .poll-container{
            margin-bottom:5px;
            padding:10px;
        }
        body{
            background-size:contain;
        }
    }
</style>