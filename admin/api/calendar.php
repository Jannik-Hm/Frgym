<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $app = $_POST["action"];
    $username = $_POST["username"];
    $password = $_POST["password_hash"];
    $incadmin = filter_var($_POST["includeadmins"], FILTER_VALIDATE_BOOLEAN);
    $id = $_POST["id"];
    if($app == "updateperm"){
        $response["data"] = [];
        foreach(json_decode($_POST["permdata"], true) as $button){
            $response["data"][str_replace("[]", "", $button["name"])][] = $button["value"];
        }
        foreach($response["data"] as $role => $calid){
            $conn = getsqlconnection();
            $sql = $conn->prepare("INSERT INTO calendars (role, calendars) VALUES (?, ?) ON DUPLICATE KEY UPDATE calendars=?;");
            $calstring = implode(";", $calid);
            $sql->bind_param("sss", $role, $calstring, $calstring);
            $sql->execute();
            $response["sql"] = $sql;
            if($sql->affected_rows != 0){
                $response["success"] = true;
            }else{
                $response["success"] = false;
            }
        }
    }elseif($app == "getperms"){
        $sql = mysqli_query(getsqlconnection(), "SELECT * FROM calendars");
        while($db_field = mysqli_fetch_assoc($sql)){
            $response["data"][] = $db_field;
        }
    }else{
        $response["error"] = "Application unknown";
    }
    echo json_encode($response);
    // TODO: add user verification
    // TODO: fix buggy behaviour
?>