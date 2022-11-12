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

    function verifyapi($username, $passhash) {
        $conn = getsqlconnection();
        $sql = $conn->prepare("SELECT id, is_enabled, vorname, nachname, role, `fachbereich-verwaltung`, `ag-verwaltung` FROM users WHERE username=? AND password_hash=?;");
        $sql->bind_param("ss", $username, $passhash);
        $sql->execute();
        $response["username"] = $username;
        $response["password_hash"] = $passhash;
        $data = $sql->get_result()->fetch_assoc();
        if(is_array($data)){
            if($data["is_enabled"]){
                foreach($data as $key => $value){
                    if($key == "fachbereich-verwaltung"){
                        $fachbereich = explode(";", $value);
                    }elseif($key == "ag-verwaltung"){
                        $ags = explode(";", $value);
                    }else{
                        $response[$key] = $value;
                    }
                }
                $sql = $conn->prepare("SELECT * FROM roles WHERE name=?;");
                $sql->bind_param("s", $response["role"]);
                $sql->execute();
                $perms = $sql->get_result()->fetch_assoc();
                foreach($perms as $key => $value){
                    if($key == "name" || $key == "id")continue;
                    $response["perms"][$key] = $value;
                }
                if(isset($fachbereich)){
                    foreach($fachbereich as $fach){
                        $response["perms"]["fachbereich"][$fach] = true;
                    }
                }
                if(isset($ags)){
                    foreach($ags as $ag){
                        if($ag != ""){
                            $response["perms"]["ags"][$ag] = true;
                        }
                    }
                }
            }else{
                http_response_code(403);
                return "account disabled";
            }
        }else{
            http_response_code(401);
            return "verification failed";
        }
        return $response;
    }

    function getperm() {
        verifylogin();

        $user_id = $_SESSION["user_id"];

        $root = realpath($_SERVER["DOCUMENT_ROOT"]);

        require_once "$root/sites/credentials.php";

        $fachbereich = explode(",", get_role($_SESSION["user_id"]))[1];
        $role = explode(",", get_role($_SESSION["user_id"]))[0];

        $sqlperm = ("SELECT * FROM roles WHERE name='".$role."';");
        $perms = mysqli_query(get_connection(), $sqlperm);
        if($perms->num_rows > 0) {
            $row = $perms->fetch_assoc();
            $GLOBALS["docs"] = $row["docs"];
            $GLOBALS["news.own"] = $row["news.own"];
            $GLOBALS["news.all"] = $row["news.all"];
            $GLOBALS["lehrer.own"] = $row["lehrer.own"];
            $GLOBALS["lehrer.all"] = $row["lehrer.all"];
            $GLOBALS["user.administration"] = $row["user.administration"];
        }
        if($GLOBALS["included-noperm"] == false){
            include_once "$root/admin/no-permission.html";
            $GLOBALS["included-noperm"] = true;
        }
        if($GLOBALS["lehrer.own"]){
            $GLOBALS["fachbereich"] = $fachbereich;
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
        echo('<link rel="stylesheet" href="/new-css/confirm.css">');
        echo("<div style='left: 0;' class='confirm'>
        <span class='helper'></span>
        <div class='scroll'>
            <div class='confirmation'>
                <h1>".$heading."</h1><br>
                <p id='confirmtext'>".$text."</p><br>
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

    function deleteconfirm($heading, $textid, $abort, $delete, $deleteid){
        echo('<link rel="stylesheet" href="/new-css/confirm.css">');
        echo("<div style='left: 0;' class='confirm'>
        <span class='helper'></span>
        <div class='scroll'>
            <div class='confirmation'>
                <h1>".$heading."</h1><br>
                <p id='".$textid."'></p><br>
                <div id='abortfirst'>
                    <a onclick=\"$('.confirm').hide();\" class='abort'>".$abort."</a>
                    <a id='".$deleteid."' class='delete'>".$delete."</a>
                </div>
                <div id='deletefirst'>
                    <a id='".$deleteid."first' class='delete'>".$delete."</a>
                    <a onclick=\"$('.confirm').hide();\" class='abort'>".$abort."</a>
                </div>
            </div>
        </div>
    </div>");
    }

    function forwardwithoutquery($newquery=""){
        return str_replace("??","?",str_replace($_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"]).$newquery);
    }

?>