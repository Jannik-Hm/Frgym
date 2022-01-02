<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php
            include_once "./../../sites/head.html";
        ?>
        <link rel="stylesheet" href="./style.css">
        <title>Altbau - Rundgang - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php 
            include_once "./../../sites/header.html";
        ?>

        <?php 
            if(isset($_GET["site"])) {
                if($_GET["site"] == "1" or $_GET["site"] == "2" or $_GET["site"] == "3" or $_GET["site"] == "4" or $_GET["site"] == "5" or $_GET["site"] == "6") {
                    include_once "./neubau" . $_GET["site"] . ".php";
                } else {
                    include_once "./neubau1.php";
                }
            } else {
                include_once "./neubau1.php";
            }
        ?>

    </body>
</html>