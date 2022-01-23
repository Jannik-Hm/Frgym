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
        <title>Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>

        <?php
            include_once "$root/admin/sites/header.html";
        ?>

        <?php


            if(isset($_SESSION["user_id"])) {
                echo("<h1 id=\"adminMain\">Willkommen " . $_SESSION["vorname"] . " " . $_SESSION["nachname"] . "</h1>");
            } else {
                echo("<script>window.location.replace('/admin/login/');</script>");
            }
            // was hierunter? News-Feedeinbindung?
            include_once "$root/sites/footer.html"
        ?>
        <span class="line"></span>
    </body>
</html>