<?php

    function list_news($admin = false) {
        $GLOBALS["admin"] = $admin;
        if($GLOBALS["admin"]==true){
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            getperm();
            verifylogin();
        }
        include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/list-news.php";
    }

?>