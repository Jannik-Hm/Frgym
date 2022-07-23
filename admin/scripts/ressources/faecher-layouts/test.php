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
    </head>
    <body>
        <ul class="test" style="list-style: none; padding: 0; margin-left: 50px; margin-right: 50px">
            <?php
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
                create_segment("text");
                create_segment("text", "62d6cfb39fe5c");
                create_segment("bild-banner", "62dbd2f565c17");
                save_segment();
            ?>
        </ul>
        <script>
            $('.test').dragndrop({
                onDrop: function( element, droppedElement ) {
                    // console.log( 'element dropped: ' + $(droppedElement).attr("id") + " to index " + $(droppedElement).index() );
                }
            });
        </script>
    </body>
</html>