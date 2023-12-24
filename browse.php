<?php
try {
    require('connection.php');

    $all_poll = 'SELECT * FROM surveys';

    $res = $db->query($all_poll);

    $fet = $res->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($fet); ++$i) {
        echo "<div id=" . $i . " class='allQ' onclick='show(" . $i . ")'> <a href='#'>" . $fet[$i]['question'] . "</a> </div>";
    }



    echo "<div id='resultpoll'></div>";
} catch (PDOException $e) {
    die($e->getMessage());
}
?>

<script>
    function show(id) {
        var resultpoll = document.getElementById("resultpoll");

        var a = document.getElementsByClassName("allQ");
        for (var i = 0; i < a.length; i++) {
            a[i].style.display = "none";
        }


        resultpoll.innerHTML = "<div class='poll-container'>" +
            "<div class='poll-info'>Question: " + <?php echo json_encode($fet); ?>[id]["question"] + "</div>" +
            "<div class='poll-info'>Results: " + <?php echo json_encode($fet); ?>[id]["results"] + "</div>" +
            "<div class='poll-info'>Voters: " + <?php echo json_encode($fet); ?>[id]["voters"] + "</div>" +
            "<div class='poll-info'>Expire Date: " + <?php echo json_encode($fet); ?>[id]["expireDate"] + "</div>" +
            "<div class='poll-info'>Status: " + <?php echo json_encode($fet); ?>[id]["status"] + "</div>" +
            "</div>";

    }

</script>