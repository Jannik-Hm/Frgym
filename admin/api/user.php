<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $app = $_POST["action"];
    $username = $_POST["username"];
    $password = $_POST["password_hash"];
    $incadmin = filter_var($_POST["includeadmins"], FILTER_VALIDATE_BOOLEAN);
    $id = $_POST["id"];
    if($app == "getall"){
        $response["performed"] = "getall";
        $select = mysqli_query(getsqlconnection(), "SELECT * FROM users ".(($incadmin) ? "" : "WHERE role!='Admin' ")."ORDER BY nachname ASC");
        $response["data"] = [];
        while ($db_field = mysqli_fetch_assoc($select)) {
            array_push($response["data"], $db_field);
        }
    }elseif($app == "getbyid"){
        $response["performed"] = "getprofile";
        if(isset($id)){
            $conn = getsqlconnection();
            $sql = $conn->prepare("SELECT * FROM users WHERE id=?");
            $sql->bind_param("s", $id);
            $sql->execute();
            $response["data"] = mysqli_fetch_assoc($sql->get_result());
        }else{
            $response["error"] = "Missing id";
        }
    }elseif($app == "add"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["user.administration"] == 1){
                $response["performed"] = "add";
                $conn = getsqlconnection();
                $displayname = mb_substr($_POST["vorname"], 0, 1).".";
                $sql = $conn->prepare("INSERT INTO users (username, titel, vorname, nachname, display_vorname, password_hash, email, role, faecher) VALUES (?, NULLIF(?, ''), ?, ?, ?, ?, ?, ?, NULLIF(?, ''))");
                $sql->bind_param("sssssssss", $_POST["addusername"], $_POST["titel"], $_POST["vorname"], $_POST["nachname"], $displayname, $_POST["generatedpassword"], $_POST["email"], $_POST["position"], $_POST["faecher"]);
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
    }elseif($app == "adminupdate"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["user.administration"] == 1){
                $response["performed"] = "update";
                $conn = getsqlconnection();
                $sql = $conn->prepare("UPDATE users SET username=?, titel=NULLIF(?, ''), vorname=?, nachname=?, email=?, role=?, faecher=NULLIF(?, '') WHERE id=?");
                $sql->bind_param("ssssssss", $_POST["addusername"], $_POST["titel"], $_POST["vorname"], $_POST["nachname"], $_POST["email"], $_POST["position"], $_POST["faecher"], $id);
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
    }elseif($app == "selfupdate"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["id"] == $id){
                $response["performed"] = "update";
                $conn = getsqlconnection();
                $sql = $conn->prepare("UPDATE users SET display_vorname=NULLIF(?, ''), infotext=NULLIF(?, '') WHERE id=?");
                $sql->bind_param("sss", $_POST["displayname"], $_POST["description"], $id);
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
            if($user["perms"]["user.administration"] == 1){
                $response["performed"] = "delete user";
                if(isset($id)){
                    $conn = getsqlconnection();
                    $sql = $conn->prepare("DELETE FROM users WHERE id=?");
                    $sql->bind_param("s", $id);
                    $sql->execute();
                    if($sql->affected_rows != 0){
                        $response["success"] = true;
                    }else{
                        $response["success"] = false;
                    }
                }else{
                    $response["error"] = "Missing id";
                }
            }else{
                $response["error"] = "Missing priviliges";
            }
        }
    }elseif($app == "changepassword"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            $response["performed"] = "changedpassword";
            $conn = getsqlconnection();
            $sql = $conn->prepare("UPDATE users SET password_hash=? WHERE id=? && password_hash=?;");
            $sql->bind_param("sss", $_POST["newpassword_hash"], $user["id"], $password);
            $sql->execute();
            if($sql->affected_rows != 0){
                $response["success"] = true;
            }else{
                $response["success"] = false;
            }
        }
    }elseif($app == "testverification"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }
        $response["user"] = $user;
    }else{
        $response["error"] = "Application unknown";
    }
    echo json_encode($response);
?>