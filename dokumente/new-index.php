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

            include_once "$root/sites/header.html"

        ?>
        <section>
            <?php
            if(isset($_GET["dir"])){
                $dir = "/".$_GET["dir"];
            }else{
                $dir = "/";
            }
            if(strlen($dir) >= 2 && substr($dir, -1)  == "/"){
                $dir = substr($dir, 0, -1);
            }
            $scriptpath = "https://".$_SERVER['SERVER_NAME'].str_replace("?".$_SERVER['QUERY_STRING'],'', $_SERVER['REQUEST_URI']);
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $path = "$root/files/document-page".$dir;
            $files = array_diff(scandir($path), array('.', '..'));
            $dirup = pathinfo($dir, PATHINFO_DIRNAME); // Path of one directory up for Back button
            echo("<ul class='docs-list' style='list-style-type: none'>");
            // TODO: Style Back/Directory up button
            echo("<li><div onclick='window.location=\"".$scriptpath."?dir=".$dirup."\"' class='dirup'>
                <p><i class='fas fa-chevron-left' style='margin-right: 5px;'></i>Zurück</p>
            </div></li>");
            foreach($files as $i){
                echo('<span class="line"></span>');
                if (is_dir($path."/".$i)) { // Check if object is a directory
                    echo("<li><div class='folder'>
                        <p>".$i."folder"."</p>
                    </div></li>");
                    // TODO: Add href link or onclick redirect to directory
                }elseif (is_file($path."/".$i)){ // Check if object is a file
                    $extension = pathinfo($i, PATHINFO_EXTENSION);
                    if ($extension=="jpg" || $extension=="jpeg" || $extension=="png"){
                        $is_image = true; // TODO: Create Image preview popup
                        $previewaction = '$("#preview'.pathinfo($i, PATHINFO_FILENAME).').show()"';
                    } else {
                        $is_image = false; // TODO: Create redirect to online file preview
                        $previewaction = '';
                    }
                    echo("<li><div onclick='".$previewaction."' class='file'>
                        <p>".pathinfo($i, PATHINFO_FILENAME)."</p>
                        <p class='editdate'>Hochgeladen am: ".date("d.m.Y H:i:s", filemtime($path."/".$i))."</p>
                    </div></li>");
                    // TODO: Add href link or onclick redirect to online preview
                }else {
                    echo("unknown type");
                }
            }
            echo("</ul>");
        ?>  <!-- TODO: Add buttons for files and folders -->
        </section>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>