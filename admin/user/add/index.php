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
                include_once "$root/admin/sites/head.html"
            ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js" integrity="sha512-szJ5FSo9hEmXXe7b5AUVtn/WnL8a5VofnFeYC2i2z03uS2LhAch7ewNLbl5flsEmTTimMN0enBZg/3sQ+YOSzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <title>Lehrer hinzufügen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
        </head>
        <body>
            <div class="bodyDiv">
            <?php
                include_once "$root/admin/sites/header.php";
                require_once "$root/admin/scripts/teacher.php";
                teacher_editor();
            ?>
        </div>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>