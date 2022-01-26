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
        <div class="bodyDiv">
            <?php
                include_once "$root/admin/sites/header.html";
            ?>

            <div class="page-beginning"></div>

            <?php


                if(isset($_SESSION["user_id"])) {
                    echo("<h1 id=\"adminMain\"><span>Willkommen</span> <span>" . $_SESSION["vorname"] . " " . $_SESSION["nachname"] . "</span></h1>");
                } else {
                    echo("<script>window.location.replace('/admin/login/');</script>");
                }
                // was hierunter? News-Feedeinbindung?
            ?>
            <span class="line"></span>
            <?php
                $lastlogin = $_SESSION["lastlogin"];
                $lastlogingzeitor1 = explode("-", $lastlogin);
                $lastlogingzeitor2 = explode(" ", $lastlogingzeitor1[2]);
                $lastlogingzeitor3 = explode(":", $lastlogingzeitor2[1]);
                $lastlogin = $lastlogingzeitor2[0] . "." . $lastlogingzeitor1[1] . "." . $lastlogingzeitor1[0] . "&nbsp;" . $lastlogingzeitor3[0] . ":" . $lastlogingzeitor3[1] . "&nbsp;Uhr";
                $today = date("Y-m-d");
                $todayor1 = explode("-", $today);
                $today = $todayor1[2].".".$todayor1[1].".".$todayor1[0];
            ?>
            <div class="adminwelcomeinfo">
                <div class="today"><p><b>Heute:</b> <?php echo($today); ?></p></div>
                <div class="lastlogin"><p><b>Letzter Login:</b> <?php echo($lastlogin); ?></p></div>
            </div>
            <div class="page-ending"></div>
        </div>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>