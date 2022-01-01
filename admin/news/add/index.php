<?php session_name("userid_login"); session_start(); ?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            include_once "./../../sites/head.html"

        ?>
        <title>News hinzufügen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "./../../sites/header.html"

        ?>

        <div class="add-input">
            <form method="POST">
                <input type="text" size="50%" placeholder="Titel*" name="titel" required><br>
                <textarea rows="10" columns="50%" placeholder="Inhalt der Nachricht*" name="inhalt" required></textarea><br>
                <input type="submit" name="submit" value="Senden">
            </form>
        </div>

        <?php
        $autor = $_SESSION["vorname"] . " " . $_SESSION["nachname"];
        $titel = $_POST["titel"];
        $inhalt = $_POST["inhalt"];
        $date = date("Y-m-d H:i");
            $servername = "sql150.your-server.de";
            $username = "c0921922321";
            $password = "AHWNiBfs2u14AAZg"; //master
            $dbname = "friedrich_gym";
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            if(isset($_POST["submit"])) {
                $insert = mysqli_query($conn, "INSERT INTO news (titel, inhalt, autor, zeit) VALUES ('{$titel}', '{$inhalt}', '{$autor}', '{$date}')");
            }
        ?>
    </body>
</html>