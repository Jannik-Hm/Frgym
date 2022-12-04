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
        <link rel="stylesheet" href="/new-css/welcome.css">
        <title>Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <div class="bodyDiv">
            <?php
                include_once "$root/admin/sites/header.php";

                if(isset($_SESSION["user_id"])) {
                    echo("<h1 id=\"adminMain\"><span>Willkommen</span> <span>" . $_SESSION["vorname"] . " " . $_SESSION["nachname"] . "</span></h1>");
                } else {
                    echo("<script>window.location.replace('/admin/login/');</script>");
                }
                // was hierunter? News-Feedeinbindung?
            ?>
            <span class="line"></span>
            <div class="adminwelcomeinfo">
                <div class="today"><p><b>Heute:</b> <?php echo(date("d.m.Y")); ?></p></div>
                <div class="lastlogin"><p><b>Letzter Login:</b> <?php echo(date_format(date_create($_SESSION["lastlogin"]), "d.m.Y H:i")); ?></p></div>
            </div>
            <div class="page-ending"></div>
        </div>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>