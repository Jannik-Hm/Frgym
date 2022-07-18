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
                include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/faecher-editor.php";
                include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/faecher-editor.php";
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
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