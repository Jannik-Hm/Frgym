<?php

    session_name("userid_login");
    session_start();
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);

?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            include_once "$root/admin/sites/head.html";

        ?>
        <title>News - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <div class="bodyDiv">
        <?php
            include_once "$root/admin/sites/header.html";
            require_once "$root/admin/scripts/news.php";

            list_news(true);
        ?>
        <div class="page-ending"></div>
        </div>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>
