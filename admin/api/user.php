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
        $response["performed"] = "add";
        $conn = getsqlconnection();
        $sql = $conn->prepare("INSERT INTO users (username, titel, vorname, nachname, password_hash, email, role, faecher) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("ssssssss", $_POST["addusername"], $_POST["titel"], $_POST["vorname"], $_POST["nachname"], $_POST["generatedpassword"], $_POST["email"], $_POST["position"], $_POST["faecher"]);
        $sql->execute();
        if($sql->affected_rows != 0){
            $response["data"] = true;
        }else{
            $response["data"] = false;
        }
    }elseif($app == "adminupdate"){
        $response["performed"] = "update";
        $response["id"] = $id;
        $response["username"] = $_POST["addusername"];
        $response["titel"] = $_POST["titel"];
        $response["vorname"] = $_POST["vorname"];
        $response["nachname"] = $_POST["nachname"];
        $response["email"] = $_POST["email"];
        $response["position"] = $_POST["position"];
        $response["faecher"] = $_POST["faecher"];
    }elseif($app == "selfupdate"){
        $response["performed"] = "update";
        $response["id"] = $id;
        $response["displayname"] = $_POST["displayname"];
        $response["description"] = $_POST["description"];
    }elseif($app == "delete"){
        $response["performed"] = "delete user";
        if(isset($id)){
            $response["data"] = mysqli_query(getsqlconnection(), "DELETE FROM users WHERE id='".$id."'");
        }else{
            $response["error"] = "Missing id";
        }
    }elseif($app == "changepassword"){
        $response["id"] = $_POST["id"];
        $response["oldpassword"] = $_POST["password"];
        $response["newpassword"] = $_POST["newpassword"];
        $response["performed"] = "changedpassword";
    }else{
        $response["error"] = "Application unknown";
    }
    $response["success"] = true;
    echo json_encode($response);
    // TODO: add user verification
?>