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

            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            include_once "$root/admin/sites/head.html";

        ?>
        <title>Neuigkeit löschen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/header.html";

            include_once "$root/admin/sites/permissions.php";

            include_once "$root/admin/no-permission.html";

            if($news_all == 0 || ($news_own == 1 && $_SESSION["vorname"] . " " . $_SESSION["nachname"] == $autor)){
                echo("<script>$('.no_perm').show();</script>");
                $disabled = true;
            };

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
            if ($disabled==false){$insert = mysqli_query($conn, "DELETE FROM news WHERE id='{$id}'");}
            if ($insert) {
                echo '<script type="text/javascript">window.location = "/admin/news/"</script>';
            }
        ?>
    </body>
</html>