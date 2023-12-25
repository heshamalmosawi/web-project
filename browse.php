
<body>
<header>
            <div class="logo">
                <a href="index1.html">
                    <img src="images/logo2-removebg-preview.png " width="100px" height="100px" alt="Logo for Bike Repair shop Roar Bikes">
                </a>
            </div>

            <nav>


            
                <div class="navbutton">
                    <a href="index1.html">Home</a>
                </div>


            </nav>
        </header>
<?php
try {
    session_start();
    require('connection.php');

    $rs = $db->query('SELECT * FROM surveys');

    $fet = $rs->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fet as $row) {
        extract($row);
        echo "<a class='poll-link' href='viewpoll.php?id=$id'>
                <div class='poll-container'>
                    <div class='poll-info'>$question</div>
                </div>
            </a>";
    }
} catch (PDOException $e) {
    die($e->getMessage());
}
?>
</body>

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
}
body {
    background-image: url(images/logo2-removebg-preview.png) ;
    background-position:center; 
    background-repeat: no-repeat;
     font-family: 'Roboto', sans-serif;
    font-size: 1.125rem;
}
.container {
    width: 1024px;
    min-height: 300px;
    margin-left: auto;
    margin-right: auto;
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
</style>
