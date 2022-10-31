<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $app = $_POST["action"];
    $username = $_POST["username"];
    $password = $_POST["password_hash"];
    $incadmin = filter_var($_POST["includeadmins"], FILTER_VALIDATE_BOOLEAN);
    $id = $_POST["id"];
    if($app == "get"){
        $response["performed"] = "getall";
        $select = mysqli_query(getsqlconnection(), "SELECT * FROM news ORDER BY zeit DESC".((is_null($_POST["limit"]) || $_POST["limit"] == "") ? "" : (" LIMIT ".$_POST["limit"])));
        $response["data"] = [];
        while ($db_field = mysqli_fetch_assoc($select)) {
            array_push($response["data"], $db_field);
        }
    }elseif($app == "add"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["news.all"] == 1 || ($user["perms"]["news.own"] == 1 && $user["vorname"]." ".$user["nachname"] == $autor)){
                $response["performed"] = "add news";
                $conn = getsqlconnection();
                $sql = $conn->prepare("");
                $sql->bind_param("s", $id);
                $sql->execute();
                if($sql->affected_rows != 0){
                    $response["success"] = true;
                }else{
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "Missing priviliges";
            }
        }
    }elseif($app == "update"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["news.all"] == 1 || ($user["perms"]["news.own"] == 1 && $user["vorname"]." ".$user["nachname"] == $autor)){
                $response["performed"] = "update news";
                $conn = getsqlconnection();
                $displayname = mb_substr($_POST["vorname"], 0, 1).".";
                $sql = $conn->prepare("");
                $sql->bind_param("s", $bla);
                $sql->execute();
                if($sql->affected_rows != 0){
                    $response["success"] = true;
                }else{
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "Missing priviliges";
            }
        }
    }elseif($app == "delete"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["news.all"] == 1 || ($user["perms"]["news.own"] == 1 && $user["vorname"]." ".$user["nachname"] == $autor)){
                $response["performed"] = "delete news";
                $conn = getsqlconnection();
                $sql = $conn->prepare("");
                $sql->bind_param("s", $id);
                $sql->execute();
                if($sql->affected_rows != 0){
                    $response["success"] = true;
                }else{
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "Missing priviliges";
            }
        }
    }else{
        $response["error"] = "Application unknown";
    }
    echo json_encode($response);
?>