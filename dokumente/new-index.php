<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    $path = "$root/files/document-page";
    $files = array_diff(scandir($path), array('.', '..'));
    foreach($files as $i){
        if (is_dir($path."/".$i)) { // Check if object is a directory
            echo($i."folder");
        }elseif (is_file($path."/".$i)){ // Check if object is a file
            echo($i);
        }else {
            echo("unknown type");
        }
    }
?>