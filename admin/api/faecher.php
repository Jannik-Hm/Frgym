<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    setsession();
    $app = $_POST["action"];
    $username = $_POST["username"];
    $password = $_POST["password_hash"];
    $fach = $_POST["fach"];
    $content = $_POST["content"];
    $db = "faecher2";

// TODO: Update layouts to new API
// TODO: Update file upload/management to ajax


    function ajaxsave($contenttype, $content){
        return 'var id=this.id; var fach = this.fach;$.post("/admin/api/faecher.php", {action: "saveelement", id: id, fach: fach, contenttype: "'.$contenttype.'", content: JSON.stringify('.$content.')}, function(){console.log(id);$.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();});resetedit();})'; //TODO: Add Action when saved
    }
    function getelementsdata($db, $fach){
        $conn = getsqlconnection();
        $sql = $conn->prepare("SELECT id, contenttype, content FROM ".$db." WHERE fach=? AND contenttype!='visibility' ORDER BY LENGTH(position), POSITION ASC");
        $sql->bind_param("s", $fach);
        $sql->execute();
        $result = $sql->get_result();
        $data = [];
        while ($db_field = mysqli_fetch_assoc($result)) {
            $temparray = array();
            foreach($db_field as $key => $entry){
                if($key == "content"){
                    $temparray[$key] = json_decode($entry, true);
                }else{
                    $temparray[$key] = $entry;
                }
            }
            array_push($data, $temparray);
        }
        return $data;
    }
    function getelementsdatabyid($db, $fach, $id){
        $conn = getsqlconnection();
        $sql = $conn->prepare("SELECT id, contenttype, content FROM ".$db." WHERE fach=? AND contenttype!='visibility' AND id=? ORDER BY LENGTH(position), POSITION ASC");
        $sql->bind_param("ss", $fach, $id);
        $sql->execute();
        $data = [];
        $db_field = mysqli_fetch_assoc($sql->get_result());
        $temparray = array();
        foreach($db_field as $key => $entry){
            if($key == "content"){
                $temparray[$key] = json_decode($entry, true);
            }else{
                $temparray[$key] = $entry;
            }
        }
        array_push($data, $temparray);
        return $data;
    }
    function create_segment($segmenttype, $existingid = NULL, $data, $fach, $editor=false) {
        $savefunction = "";
        $viewer = !$editor;
        if(isset($existingid)){
            $id = $existingid;
        }else{
            $id = uniqid();
        }
        echo '
        <li style="margin-bottom: 10px; padding: 10px; padding-bottom: 40px; border: 2px solid #fff; border-radius: 15px" title="'.$segmenttype.'" id="'.$id.'">
            <form method="POST" enctype="multipart/form-data">';
                include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/$segmenttype.php");
                if($editor) echo '
                <input name="id" type="text" value="'.$id.'" hidden></input>
                <input name="edit" type="checkbox" checked hidden></input>
                <div style="margin: auto; margin-right: 5px; display: inline-block; float: right; margin-top: 5px;">
                    <btn class="button" style="cursor:pointer; display: inline-block; text-align: center; box-sizing: border-box; padding: 7px 0;" onclick="segment'.$id.'.delete()" id="'.$id.'delete">Löschen</btn>
                    <btn class="button" style="cursor:pointer; display: inline-block; text-align: center; box-sizing: border-box; padding: 7px 0;" onclick="resetedit(); segment'.$id.'.edit();" id="'.$id.'edit">Bearbeiten</btn>
                    <input class="button" style="cursor: pointer; display: none" type="reset" name="" onclick="resetedit()" value="Abbrechen" id="'.$id.'abort">
                    <btn class="button" style="cursor: pointer; display: none; text-align: center; box-sizing: border-box; padding: 7px 0;" onclick="segment'.$id.'.save();" id="'.$id.'save">Speichern</btn>
                </div>';
                echo'
            </form>
        </li>';
        if($editor) echo'
        <script>
            var segment'.$id.' = {
                id: "'.$id.'",
                fach: "'.$fach.'",
                delete: function(){$.post("/admin/api/faecher.php", {action: "removeelement", id: this.id, fach: this.fach}, function(){$(\'#\'+elementid).remove()});},
                edit: function(){
                    $(\'#\'+this.id+\'edit\').hide();
                    $(\'#\'+this.id+\'abort\').show();
                    $(\'#\'+this.id+\'save\').show();
                    $(\'[id*="\'+this.id+\'"][id*="content"]\').attr(\'class\', \'edit\');
                    $(\'[id*="\'+this.id+\'"][id*="content"]\').removeAttr(\'disabled\');
                },
                save: Function(\''.$savefunction.'\'),
            }
            function resetedit() {
                $(\'[id*="edit"]\').show();
                $(\'[id*="abort"]\').hide();
                $(\'[id*="save"]\').hide();
                $(\'[id*="content"]\').attr(\'class\', \'normal\');
                $(\'[id*="content"]\').attr(\'disabled\', true);
            }
        </script>';
        // TODO: reset unsaved changes
    }


    if($app == "updateorder"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["fachbereich"][$fach] || $user["perms"]["fachbereich"]["admin"]){
                $response["performed"] = "updateorder";
                $positionarray = json_decode($_POST["positions"], true);
                $sqlquery = "";
                $paramtype = "";
                $paramarray = array();
                foreach($positionarray as $key => $value){
                    $sqlquery .= " SELECT ? as id, ? as position";
                    $paramtype .= "si";
                    array_push($paramarray, $value["id"], $value["index"]);
                    if($key < count($positionarray)-1){
                        $sqlquery .= " UNION ALL";
                    }
                }
                $sqlquery = "UPDATE ".$db." JOIN (".$sqlquery.") a ON ".$db.".id = a.id SET ".$db.".position = a.position;";
                $conn = getsqlconnection();
                $sql = $conn->prepare($sqlquery);
                $sql->bind_param($paramtype,...$paramarray);
                $sql->execute();
                if($sql->affected_rows != 0 && $sql->affected_rows != NULL){
                    $response["success"] = true;
                }else{
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "missing priviliges";
            }
        }
    }elseif($app == "saveelement"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["fachbereich"][$fach] || $user["perms"]["fachbereich"]["admin"]){
                $conn = getsqlconnection();
                $sqlquery = "INSERT INTO ".$db." (id, contenttype, content, fach) values (?, ?, ?, ?) ON DUPLICATE KEY UPDATE contenttype=?, content=?, fach=?;";
                $sql = $conn->prepare($sqlquery);
                $sql->bind_param("sssssss", $_POST["id"], $_POST["contenttype"], $_POST["content"], $_POST["fach"],  $_POST["contenttype"], $_POST["content"], $_POST["fach"]);
                $sql->execute();
                if($sql->affected_rows != 0){
                    http_response_code(200);
                    $response["success"] = true;
                }else{
                    http_response_code(400);
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "missing priviliges";
            }
        }
    }elseif($app == "getelementsdata"){
        $response = getelementsdata($db, $fach);
    }elseif($app == "getfachelements"){
        $editor = filter_var($_POST["editor"], FILTER_VALIDATE_BOOLEAN);
        if($editor){
            $user = verifyapi($username, $password);
            if(!is_array($user)){
                $editor = false;
            }else{
                if(!$user["perms"]["fachbereich"][$fach] && !$user["perms"]["fachbereich"]["admin"]){
                    $editor = false;
                    $response["error"] = "missing priviliges";
                    http_response_code(403);
                }
            }
        }
        foreach(getelementsdata($db, $fach) as $entry){
            create_segment($entry["contenttype"], $entry["id"], $entry["content"], $fach, $editor);
        }
        return;
        // Accept Data: $.post("https://frgym.greenygames.de/admin/api/faecher.php", {"action": "getfachelements", "fach": fach, "editor": true/false}, function(data){$(list).append(data)})
    }elseif($app == "getfachelementbyid"){
        $editor = filter_var($_POST["editor"], FILTER_VALIDATE_BOOLEAN);
        if($editor){
            $user = verifyapi($username, $password);
            if(!is_array($user)){
                $editor = false;
            }else{
                if(!$user["perms"]["fachbereich"][$fach] && !$user["perms"]["fachbereich"]["admin"]){
                    $editor = false;
                    $response["error"] = "missing priviliges";
                    http_response_code(403);
                }
            }
        }
        foreach(getelementsdatabyid($db, $fach, $_POST["id"]) as $entry){
            create_segment($entry["contenttype"], $entry["id"], $entry["content"], $fach, $editor);
        }
        return;
    }elseif($app == "createelement"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["fachbereich"][$fach] || $user["perms"]["fachbereich"]["admin"]){
                $id = uniqid();
                $conn = getsqlconnection();
                $sql = $conn->prepare("INSERT INTO ".$db." (id, fach, contenttype) VALUES (?, ?, ?)");
                $sql->bind_param("sss", $id, $fach, $_POST["contenttype"]);
                $sql->execute();
                if($sql->affected_rows != 0){
                    create_segment($_POST["contenttype"], $id, null, $fach, true);
                    return;
                }else{
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "missing priviliges";
            }
        }
    }elseif($app == "removeelement"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["fachbereich"][$fach] || $user["perms"]["fachbereich"]["admin"]){
                $id = $_POST["id"];
                $data = getelementsdata($db, $fach);
                $key = array_search($id, array_column($data, 'id'));
                foreach ($data[$key]["content"] as $content){
                    if(is_file(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-pictures/".$content)){
                        unlink(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-pictures/".$content);
                    }
                }
                $conn = getsqlconnection();
                $sql = $conn->prepare("DELETE FROM ".$db." WHERE id=? AND fach=?");
                $sql->bind_param("ss", $id, $fach);
                $sql->execute();
                if($sql->affected_rows != 0){
                    $response["success"] = true;
                }else{
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "missing priviliges";
                http_response_code(403);
            }
        }
    }elseif($app == "getvisibility"){
        $conn = getsqlconnection();
        $sql = $conn->prepare("SELECT id, content FROM ".$db." WHERE fach=? AND contenttype='visibility'");
        $sql->bind_param("s", $fach);
        $sql->execute();
        $result = mysqli_fetch_assoc($sql->get_result());
        if(!is_null($result)){
            $response["data"] = $result;
        }else{
            $response["data"] = array("id"=> uniqid(), "content"=> null);
        }
    }elseif($app == "setvisibility"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if($user["perms"]["fachbereich"][$fach] || $user["perms"]["fachbereich"]["admin"]){
                $conn = getsqlconnection();
                $sqlquery = "INSERT INTO ".$db." (id, contenttype, content, fach) values (?, ?, ?, ?) ON DUPLICATE KEY UPDATE id=?, contenttype=?, content=?, fach=?;";
                $sql = $conn->prepare($sqlquery);
                $sql->bind_param("ssssssss", $_POST["id"], "visibility", $_POST["visibility"], $_POST["fach"], $_POST["id"], "visibility", $_POST["visibility"], $_POST["fach"]);
                $sql->execute();
                if($sql->affected_rows != 0){
                    http_response_code(200);
                    $response["success"] = true;
                }else{
                    http_response_code(400);
                    $response["success"] = false;
                }
            }else{
                $response["error"] = "missing priviliges";
            }
        }
    }else{
        http_response_code(404);
        $response["error"] = "Application unknown";
    }
    echo json_encode($response);
?>