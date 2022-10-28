<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $app = $_POST["action"];
    $username = $_POST["username"];
    $password = $_POST["password_hash"];
    $incadmin = filter_var($_POST["includeadmins"], FILTER_VALIDATE_BOOLEAN);
    $id = $_POST["id"];
    if($app == "updateperm"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["calendar.administration"] == 1){
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
                    if($sql->affected_rows != 0){
                        $affected = true;
                    }
                }
                if($affected){
                    $response["success"] = true;
                }else{
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "Missing priviliges";
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
?>