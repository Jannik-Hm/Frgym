<?php

    session_name("userid_login");
    session_start();
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/admin/scripts/admin-scripts.php";
    function delete_directory($dirname) {
        if (is_dir($dirname)){
        $dir_handle = opendir($dirname);
        }
        if (!$dir_handle){
        return false;
        }
        
        while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
        if (!is_dir($dirname."/".$file))
        unlink($dirname."/".$file);
        else
        delete_directory($dirname.'/'.$file);
        }
        }
        
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
    if(verifylogin()){
        $files_location = "$root/files/";
        $path = $files_location.$_GET["path"];
        if(is_dir($path)) {
            // foreach (glob($path."/*.*") as $filename) {
            //     if (is_file($filename)) {
            //         unlink($filename);
            //     }
            // }
            // rmdir($path);
            delete_directory($path);
        } else if (is_file($path)){
            unlink($path);
        }
        echo("<script>window.history.back()</script>");
    }

?>