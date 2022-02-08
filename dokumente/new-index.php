<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            include_once "$root/sites/head.html"

        ?>
        <title>Dokumente - Friedrich-Gymnasium Luckenwalde</title>
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
                $dir = "";
            }
            $scriptpath = "https://".$_SERVER['SERVER_NAME'].str_replace("?".$_SERVER['QUERY_STRING'],'', $_SERVER['REQUEST_URI']);
            function changedir($url) {
                echo("window.history.pushState({}, null, \"".$url."\")");
            }
            function listfiles($dir, $scriptpath) {
                $root = realpath($_SERVER["DOCUMENT_ROOT"]);
                if($_SERVER['QUERY_STRING'] != ""){
                    $dirpath = "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."/";
                }else{
                    $dirpath = "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?dir=";
                }
                $pathworoot = "/files/document-page".$dir;
                $path = $root.$pathworoot;
                $files = array_diff(scandir($path), array('.', '..'));
                echo("<ul class='docs-list' style='list-style-type: none'>");
                if($dir != ""){
                    if(substr(pathinfo($dir, PATHINFO_DIRNAME), 0, 1) == "/"){ $dir = (ltrim(pathinfo($dir, PATHINFO_DIRNAME), "/")); }
                    if($dir != ""){$dir="?dir=".$dir;}
                    echo("<li><div onclick='window.location=\"".$scriptpath.$dir."\";' class='dirup'>
                        <p><i class='fas fa-chevron-left' style='margin-right: 5px;'></i>Zurück</p>
                    </div></li>"); // window.history.pushState({}, null, \"".$scriptpath.$dir."\")
                }else{$hidefirstline = "<style>span.line:first-child { display: none; } </style>";}
                foreach($files as $i){
                    echo('<span class="line"></span>');
                    if (is_dir($path."/".$i)) { // Check if object is a directory
                        echo("<li><div onclick='window.location.href=\"".$dirpath.$i."\";' class='folder'>
                            <p><i class='far fa-folder'></i></p>
                            <p>".$i."folder"."</p>
                        </div></li>");
                    }elseif (is_file($path."/".$i)){ // Check if object is a file
                        $extension = pathinfo($i, PATHINFO_EXTENSION);
                        if ($extension=="jpg" || $extension=="jpeg" || $extension=="png"){
                            $icon = "far fa-file-image";
                            $is_image = true;
                            $previewaction = 'document.getElementById("imgpreviewsrc").src="'.$pathworoot."/".$i.'";$(".img").show();';
                        } else if ($extension == "pdf") {
                            $icon = "far fa-file-pdf";
                            $is_image = false;
                            $previewaction = 'window.location="'."https://".$_SERVER['SERVER_NAME'].$pathworoot."/".$i.'"';
                        } else {
                            $icon = "far fa-file-alt";
                            $is_image = false;
                            $previewaction = '';
                        }
                        echo("<li><div onclick='".$previewaction."' class='file'>
                            <p><i class='".$icon."'></i></p>
                            <p>".pathinfo($i, PATHINFO_FILENAME)."</p>
                            <div class='floatright'>
                                <a href='".$pathworoot."/".$i."' onclick=\"event.stopPropagation();\" download><p class='download' title='Herunterladen'><i class='far fa-save'></i></p></a>
                                <p class='editdate'>Hochgeladen am: ".date("d.m.Y H:i:s", filemtime($path."/".$i))."</p>
                            </div>
                        </div></li>");
                    }else {
                        echo("unknown type");
                    }
                }
                echo("<div onclick=\"event.stopPropagation();$('.img').hide()\" style='left: 0;' class='img'>
                    <span class='helper'></span>
                    <div>
                        <img onclick=\"event.stopPropagation();\" id='imgpreviewsrc' style='margin: auto'>
                        <div onclick=\"event.stopPropagation();$('.img').hide()\" class='popupCloseButton'>&times;</div>
                    </div>
                </div>");
                echo($hidefirstline);
                echo("</ul>");
            }
            listfiles($dir, $scriptpath);
        ?>
        </section>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>