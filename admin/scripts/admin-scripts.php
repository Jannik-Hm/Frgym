<?php

    function setsession() {
        session_name("userid_login");
        session_start();
    }

    function verifylogin() {
        if(!isset($_SESSION["user_id"])) {
            echo "<script>window.location.replace('/admin/login/')</script>";
        }else{
            return true;
        }
    }

    function getsqlconnection() {
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/credentials.php";
        return get_connection();
    }

    function getperm() {
        verifylogin();

        $user_id = $_SESSION["user_id"];

        $root = realpath($_SERVER["DOCUMENT_ROOT"]);

        require_once "$root/sites/credentials.php";

        $role = get_role($_SESSION["user_id"]);

        $sqlperm = ("SELECT * FROM roles WHERE name='".$role."';");
        $perms = mysqli_query(get_connection(), $sqlperm);
        if($perms->num_rows > 0) {
            $row = $perms->fetch_assoc();
            $GLOBALS["docs"] = $row["docs"];
            $GLOBALS["news.own"] = $row["news.own"];
            $GLOBALS["news.all"] = $row["news.all"];
            $GLOBALS["lehrer.own"] = $row["lehrer.own"];
            $GLOBALS["lehrer.all"] = $row["lehrer.all"];
        }
        if($GLOBALS["included-noperm"] == false){
            include_once "$root/admin/no-permission.html";
            $GLOBALS["included-noperm"] = true;
        }
    }

    function checkperm($permneeded) {
        getperm();
        if($GLOBALS[$permneeded] == 0){
            echo("<script>$('.no_perm').show();</script>");
            $GLOBALS["disabled"] = true;
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

    function forwardwithoutquery($newquery=""){
        return str_replace("??","?",str_replace($_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"]).$newquery);
    }

?>