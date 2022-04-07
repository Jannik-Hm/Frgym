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
        <title>News bearbeiten - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php
            include_once "$root/admin/sites/header.html";
            require "$root/admin/scripts/news.php";
            news_editor(true);
        ?>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>