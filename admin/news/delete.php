<?php

    session_name("userid_login");
    session_start();

    if(!isset($_SESSION["user_id"])) {
        header("Location: /admin/login/");
    }

?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            include_once "./../../sites/head.html"

        ?>
        <title>Neuigkeit löschen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "./../../sites/header.html"

        ?>

        <?php

            $servername = "sql150.your-server.de";
            $username = "c0921922321";
            $password = "AHWNiBfs2u14AAZg"; //master
            $dbname = "friedrich_gym";
            $id = $_GET["id"];
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $insert = mysqli_query($conn, "DELETE FROM news WHERE id='{$id}'");
            if ($insert) {
                echo '<script type="text/javascript">window.location = "/admin/news/"</script>';
            }
        ?>
    </body>
</html>