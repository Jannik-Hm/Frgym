<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $app = $_POST["action"];
    $username = $_POST["username"];
    $password = $_POST["password_hash"];
    $incadmin = filter_var($_POST["includeadmins"], FILTER_VALIDATE_BOOLEAN);
    $id = $_POST["id"];
    if($app == "getall"){
        $response["performed"] = "getall";
        $select = mysqli_query(getsqlconnection(), "SELECT news.id, news.titel, news.inhalt, news.zeit, CONCAT(if(users.titel IS NOT NULL, CONCAT(users.titel, ' '), ''), users.vorname,' ',users.nachname) AS autor FROM news, users WHERE news.autor = users.id ORDER BY zeit DESC".((is_null($_POST["limit"]) || $_POST["limit"] == "") ? "" : (" LIMIT ".$_POST["limit"])));
        $response["data"] = array();
        while ($db_field = mysqli_fetch_assoc($select)) {
            $response["data"][] = $db_field;
        }
    }elseif($app == "getbyid"){
        $response["performed"] = "getbyid";
        if(isset($_POST["id"])){
            $conn = getsqlconnection();
            $sql = $conn->prepare("SELECT news.id, news.titel, news.inhalt, news.zeit, CONCAT(if(users.titel IS NOT NULL, CONCAT(users.titel, ' '), ''), users.vorname,' ',users.nachname) AS autor FROM news, users WHERE news.autor = users.id AND news.id=?");
            $sql->bind_param("s", $_POST["id"]);
            $sql->execute();
            $db_field = mysqli_fetch_assoc($sql->get_result());
            $response["data"] = $db_field;
        }else{
            $response["error"] = "Missing id";
        }
    }elseif($app == "add"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["news.all"] == 1 || $user["perms"]["news.own"] == 1 ){
                $response["performed"] = "add news";
                $conn = getsqlconnection();
                $sql = $conn->prepare("INSERT INTO news (autor, titel, inhalt, zeit) VALUES (?, ?, ?, ?)");
                $sql->bind_param("ssss", $user["id"], $_POST["titel"], $_POST["inhalt"], date("Y-m-d H:i"));
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
            $conn = getsqlconnection();
            $sql = $conn->prepare("SELECT autor FROM news WHERE news.id=?");
            $sql->bind_param("s", $_POST["id"]);
            $sql->execute();
            $autor = mysqli_fetch_assoc($sql->get_result());
            if($user["perms"]["news.all"] == 1 || ($user["perms"]["news.own"] == 1 && $user["id"] == $autor["autor"])){
                $response["performed"] = "update news";
                $conn = getsqlconnection();
                $sql = $conn->prepare("UPDATE news SET autor=?, titel=?, inhalt=?, zeit=? WHERE id=?");
                $sql->bind_param("sssss", $user["id"], $_POST["titel"], $_POST["inhalt"], date("Y-m-d H:i"), $_POST["id"]);
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
            $conn = getsqlconnection();
            $sql = $conn->prepare("SELECT autor FROM news WHERE news.id=?");
            $sql->bind_param("s", $_POST["id"]);
            $sql->execute();
            $autor = mysqli_fetch_assoc($sql->get_result());
            if($user["perms"]["news.all"] == 1 || ($user["perms"]["news.own"] == 1 && $user["id"] == $autor["autor"])){
                $response["performed"] = "delete news";
                $conn = getsqlconnection();
                $sql = $conn->prepare("DELETE FROM news WHERE id=?");
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