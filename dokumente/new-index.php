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
            <!-- TODO: Add Back/Directory up button -->
            <?php
            if(isset($_GET["dir"])){
                $dir = "/".$_GET["dir"];
            }else{
                $dir = "/";
            }
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $path = "$root/files/document-page".$dir;
            $files = array_diff(scandir($path), array('.', '..'));
            $dirup = pathinfo($path, PATHINFO_DIRNAME)."<br>"; // Path of one directory up for Back button
            echo $dirup;
            foreach($files as $i){
                if (is_dir($path."/".$i)) { // Check if object is a directory
                    echo($i."folder"); // TODO: Add href link or onclick redirect to directory
                }elseif (is_file($path."/".$i)){ // Check if object is a file
                    echo($i); // TODO: Add href link or onclick redirect to online preview
                }else {
                    echo("unknown type");
                }
            }
        ?>  <!-- TODO: Add buttons for files and folders -->
        </section>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>