<?php

    function verifylogin() {
        // session_name("userid_login");
        // session_start();

        if(!isset($_SESSION["user_id"])) {
            // header("Location: /admin/login/");
            echo "<script>window.location.replace('/admin/login/')</script>";
        }else{
            return true;
        }
    }

?>