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
        <title>News hinzufügen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/header.html";

            include_once "$root/admin/sites/permissions.php";

            include_once "$root/admin/no-permission.html";
            if($news_own == 0 && $news_all == 0){
                echo("<script>$('.no_perm').show();</script>");
                $disabled = true;
            };

        ?>

        <div class="add-input-news">
            <form method="POST">
                <input type="text" size="50%" placeholder="Titel*" name="titel" <?php if($disabled){echo "disabled";} ?> required><br>
                <textarea rows="10" columns="50%" placeholder="Inhalt der Nachricht*" name="inhalt" <?php if($disabled){echo "disabled";} ?> required></textarea><br>
                <input type="submit" name="submit" <?php if($disabled){echo "disabled";} ?> value="Senden">
                <div class="page-ending"></div>
            </form>
        </div>

        <div style='left: 0;' class='confirm'>
            <span class='helper'></span>
            <div class='scroll'>
                <div class='confirmation'>
                    <h1>Hinzufügen erfolgreich!</h1><br>
                    <p>Die Neuigkeit wurde erfolgreich hinzugefügt.</p><br>
                    <a href='/admin/news/add/' class='repeat'>Weitere Neuigkeit verfassen</a>
                    <a href='/admin/news/' class='back'>Zurück zur Übersicht</a>
                </div>
            </div>
        </div>

        <?php
            $autor = $_SESSION["vorname"] . " " . $_SESSION["nachname"];
            $titel = $_POST["titel"];
            $inhalt = $_POST["inhalt"];
            $date = date("Y-m-d H:i");
            require_once "$root/sites/credentials.php";
            $conn = get_connection();
            if(isset($_POST["submit"]) && ($news_own == 1 || $news_all == 1)) {
                $insert = mysqli_query($conn, "INSERT INTO news (titel, inhalt, autor, zeit) VALUES ('{$titel}', '{$inhalt}', '{$autor}', '{$date}')");
            }

            if ($insert) {
                echo("<script>$('.confirm').show();</script>");
            }
        ?>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>