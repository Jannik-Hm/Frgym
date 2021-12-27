<?php

    session_name("userid_login");
    session_start();

    if(!isset($_SESSION["user_id"])) {
        header("Location: /admin/login/");
    }

?>
<html>
    <head>
        <?php
            include_once "./sites/head.html";
        ?>
        <title>Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>

        <?php
            include_once "./sites/header.html";
        ?>

        <?php 


            if(isset($_SESSION["user_id"])) {
                echo("<h1>Willkommen " . $_SESSION["user_id"] . "</h1>");
            } else {
                echo("<script>window.location.replace('/admin/login/');</script>");
            }
            // was hierunter? News-Feedeinbindung?
        ?> 
    </body>
</html>