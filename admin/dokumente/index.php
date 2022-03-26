<?php
        $root = realpath($_SERVER["DOCUMENT_ROOT"]);
        require_once "$root/admin/scripts/admin-scripts.php";
        setsession();
        ?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            include_once "$root/admin/sites/head.html"

        ?>
        <title>Dokumente - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/header.html";
            if(isset($_GET["dir"])){
                $dir = "/".$_GET["dir"];
            }else{
                $dir = "";
            }
            $pathworoot = "/files/document-page".$dir;

        ?>

<style>
            section {font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif;color: #fff;}
            form {display: flex; justify-content: center; margin: auto; width: 90%; flex-wrap: nowrap; align-items: center;}
            #drop_zone {cursor: pointer; text-align: center; border: none; width: 100%;  padding: 15px 0;  margin: 15px auto;  border-radius: 15px; background-color: #514f4f ;background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='15' ry='15' stroke='%23333' stroke-width='5' stroke-dasharray='6%2c 14' stroke-dashoffset='14' stroke-linecap='square'/%3e%3c/svg%3e"); position: relative}
            #drop_zone:hover {background-color: #676565}
            #drop_zone .popupCloseButton {position: absolute; right: -15px; top: -15px; display: inline-block; font-weight: bold; font-size: 25px; line-height: 30px; width: 30px; height: 30px; text-align: center; background-color: rgb(122, 133, 131); border-radius: 50px; border: 3px solid #999; color: #414141;}
            #drop_zone .popupCloseButton:hover {background-color: #fff;}
            #submitbtn {cursor: pointer; display: block; background: rgb(122, 133, 131); color: rgb(226, 226, 226); margin-left: 25px; padding: 15px 25px; border-radius: 15px; border: none; width: auto; height: auto}
            .fa-trash {cursor: pointer};
        </style>
        <section>

            <?php
            include_once "$root/admin/scripts/file-upload.php";
            dropzone("file-input");
            // createdir($pathworoot);
            // TODO: Add option to create directory
        ?>

            <?php
            if(isset($_POST["submit"])){
                uploadfile("document-page/".$dir, array("jpg", "jpeg", "png", "pdf", "webp"), "file-input");
            }
        ?>
        </section>
        <?php $GLOBALS["admin"] = true; include "$root/admin/scripts/list-files.php" ?>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>
