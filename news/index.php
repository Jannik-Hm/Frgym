<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            include_once "$root/sites/head.html"

        ?>
        <title>News - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
            <?php

                include_once "$root/sites/header.html";
                require_once "$root/admin/scripts/news.php";
                list_news();
                include_once "$root/sites/footer.html";
            ?>
    </body>
</html>