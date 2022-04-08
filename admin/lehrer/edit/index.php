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
        <title>Lehrer bearbeiten - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <div class="bodyDiv">
            <?php
                include_once "$root/admin/sites/header.html";
                require_once "$root/admin/scripts/teacher.php";
                teacher_editor(true, $_GET["id"]);
            ?>
        </div>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>