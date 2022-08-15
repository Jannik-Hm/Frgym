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
            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/head.html"
        ?>
        <script src="/js/jquery.dragndrop.js"></script>
        <link rel="stylesheet" href="/new-css/faecher.css">
    </head>
    <body>
        <ul class="test" style="list-style: none; padding: 25px; margin-left: 50px; margin-right: 50px; background-color: var(--inputbackground); border-radius: 15px; color: var(--inputcolor);">
            <?php
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
                include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/".$_GET['layout'].".php");
            ?>
        </ul>
    </body>
</html>