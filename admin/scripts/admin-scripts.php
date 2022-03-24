<?php

    function setsession() {
        session_name("userid_login");
        session_start();
    }

    function verifylogin() {

        if(!isset($_SESSION["user_id"])) {
            // header("Location: /admin/login/");
            echo "<script>window.location.replace('/admin/login/')</script>";
        }else{
            return true;
        }
    }

    function confirmation($heading, $text, $left, $leftlink, $right = null, $rightlink = null){
        echo("<div style='left: 0;' class='confirm'>
        <span class='helper'></span>
        <div class='scroll'>
            <div class='confirmation'>
                <h1>".$heading."</h1><br>
                <p>".$text."</p><br>
                <a href='".$leftlink."' class='repeat'>".$left."</a>");
                if($right != null && $rightlink != null){
                    echo("<a href='".$rightlink."' class='back'>".$right."</a>");
                }else{
                    echo("<style>div.confirmation a.repeat{margin-right:0}</style>");
                }
                echo("
            </div>
        </div>
    </div>");
    }

?>