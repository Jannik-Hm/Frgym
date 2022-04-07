<?php
    session_name("userid_login");
    session_start();
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
            require "$root/admin/scripts/news.php";
            delete_news($_GET["id"]);
        ?>
    </body>
</html