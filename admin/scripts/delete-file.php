<?php

    session_name("userid_login");
    session_start();
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/admin/scripts/admin-scripts.php";
    if(verifylogin()){
        $files_location = "$root/files/";
        $path = $files_location.$_GET["path"];
        if(is_dir($path)) {
            foreach (glob($path."/*.*") as $filename) {
                if (is_file($filename)) {
                    unlink($filename);
                }
            }
            rmdir($path);
        } else if (is_file($path)){
            unlink($path);
        }
        echo("<script>window.history.back()</script>");
    }

?>