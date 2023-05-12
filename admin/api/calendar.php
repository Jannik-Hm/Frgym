<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    setsession();
    $GLOBALS["tokens"] = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json"), true);
    $app = $_POST["action"];
    $username = $_POST["username"];
    $password = $_POST["password_hash"];
    $incadmin = filter_var($_POST["includeadmins"], FILTER_VALIDATE_BOOLEAN);
    $id = $_POST["id"];

    function getcalperms() {
        $responsearray = array();
        $sql = mysqli_query(getsqlconnection(), "SELECT * FROM calendars");
        while($db_field = mysqli_fetch_assoc($sql)){
            $temparray = array();
            $temparray["role"] = $db_field["role"];
            $temparray["calendars"] = explode(";", $db_field["calendars"]);
            $responsearray[] = $temparray;
        }
        return $responsearray;
    }

    function getcalids($username, $password) {
        $response = array();
        $calarray = array();
        $permarray = getcalperms();
        $roles = array_column($permarray, 'role');
        if(isset($username) && isset($password) && $username != "" && $password != "" && $username != NULL && $password != NULL){
            $schuelerpassword = null;
            if($username == "schueler"){
                $sql = mysqli_query(getsqlconnection(), "SELECT overwrite_password FROM calendars WHERE role='Schüler*in'");
                $schuelerpassword = mysqli_fetch_assoc($sql)["overwrite_password"];
            }
            if($username == "schueler" && $password == $schuelerpassword){ // change here for new schueler password TODO: db based
                $roleindex = array_search('Schüler*in', $roles);
            }else{
                $user = verifyapi($username, $password);
                if(!is_array($user)){
                    $response["error"] = $user;
                    $roleindex = array_search('Öffentlich', $roles);
                }else{
                    $roleindex = array_search($user["role"], $roles);
                }
            }
        }else{
            if(isset($_SESSION["user_id"])){
                $roleindex = array_search($user["role"], $roles);
            }else{
                $roleindex = array_search('Öffentlich', $roles);
            }
        }
        $calarray = $permarray[$roleindex];
        $response["data"] = $calarray;
        return $response;
    }

    function getnewtoken($refresh_token, $client_id, $client_secret){
        $curlrefresh = curl_init();
        curl_setopt_array($curlrefresh, [
        CURLOPT_URL => "https://oauth2.googleapis.com/token?client_id=".$client_id."&client_secret=".$client_secret."&grant_type=refresh_token&refresh_token=".$refresh_token."",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "",
        ]);
        $newtoken = curl_exec($curlrefresh);
        $newtokenarray = json_decode($newtoken, true);
        curl_close($curlrefresh);
        return $newtokenarray["access_token"];
    }

    function getcalendars($access_token, $token_type, $refresh_token, $client_id, $client_secret, $username, $password) {
        $responsearray = array();
        foreach(getcalids($username, $password)["data"]["calendars"] as $calid){
            $i = 0;
            begin:
            $i++;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.googleapis.com/calendar/v3/users/me/calendarList/".urlencode($calid),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => [
                "Authorization: ".$token_type." ".$access_token.""
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if($result["error"]["code"] == 401) {
                $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
                if(isset($access_token)){
                $GLOBALS["tokens"]["readonly"]["access_token"] = $access_token;;
                file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
                }
                if($i < 2){
                goto begin;
                }
                return;
            }else{
                $responsearray[] = $result;
            }
        }
        return $responsearray;
    }

    function getcalevents($calid, $access_token, $token_type, $refresh_token, $client_id, $client_secret){
        $i = 0;
        begin:
        $i++;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/".urlencode($calid)."/events?maxResults=2000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: ".$token_type." ".$access_token.""
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        if($result["error"]["code"] == 401) {
            $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
            if(isset($access_token)){
                $GLOBALS["tokens"]["readonly"]["access_token"] = $access_token;;
                file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
            }
            if($i < 2){
                goto begin;
            }
            return;
        }else{
            return $result;
        }
    }

    function getcaldata($username, $password) {
        $calarray = getcalendars($GLOBALS["tokens"]["readonly"]["access_token"], $GLOBALS["tokens"]["readonly"]["token_type"], $GLOBALS["tokens"]["readonly"]["refresh_token"], $GLOBALS["tokens"]["readonly"]["client_id"], $GLOBALS["tokens"]["readonly"]["client_secret"], $username, $password);
        foreach($calarray as $calkey => $cal){
            $eventarray = array();
            foreach (getcalevents($cal["id"], $GLOBALS["tokens"]["readonly"]["access_token"], $GLOBALS["tokens"]["readonly"]["token_type"], $GLOBALS["tokens"]["readonly"]["refresh_token"], $GLOBALS["tokens"]["readonly"]["client_id"], $GLOBALS["tokens"]["readonly"]["client_secret"])["items"] as $entry){
                $tempeventarray = array();
                $tempeventarray["name"] = trim(str_replace(["Erster", "Zweiter", "Dritter", "Vierter", "der", "Deutschen", "Neujahrstag"], ["1.", "2.", "3.", "4.", "d.", "Dt.", "Neujahr"], str_replace(["(regionaler Feiertag)", "Halloween", "St. Martin", "Volkstrauertag", "Totensonntag", "Heilige Drei Könige", "Valentinstag", "Rosenmontag", "Faschingsdienstag", "Aschermittwoch", "Palmsonntag", "Jahrestag der Befreiung vom Nationalsozialismus", "Internationaler Frauentag", "Vatertag", "Fronleichnam", "Allerheiligen", "Mariä Himmelfahrt", "Nikolaustag", "Gründonnerstag", "Karsamstag", "Muttertag"], NULL, (preg_match("(Bayern|Sachsen|Sommerzeit|Thüringen)", $entry["summary"]) === 1) ? "" : $entry["summary"])));
                if($tempeventarray["name"] == "" || $tempeventarray["name"] == NULL) continue;
                $tempeventarray["id"] = $entry["id"];
                $tempeventarray["description"] = str_replace("\nWenn Sie Gedenktage ausblenden möchten, rufen Sie die Google Kalender-Einstellungen auf > Feiertage in Deutschland", "", $entry["description"]);
                if(strpos($tempeventarray["description"], "Feiertag") !== false){
                    $tempeventarray["description"] = "Feiertag";
                }elseif(strpos($tempeventarray["description"], "Gedenktag") !== false){
                    $tempeventarray["description"] = "Gedenktag";
                }
                $tempeventarray["location"] = $entry["location"];
                if(isset($entry["start"]["dateTime"]) && isset($entry["end"]["dateTime"])){
                    $tempeventarray["start"] = strtotime($entry["start"]["dateTime"]);
                    $tempeventarray["end"] = strtotime($entry["end"]["dateTime"]);
                    $tempeventarray["istime"] = true;
                }else{
                    $tempeventarray["start"] = strtotime($entry["start"]["date"]);
                    $tempeventarray["end"] = strtotime($entry["end"]["date"]);
                    $tempeventarray["istime"] = false;
                }
                array_push($eventarray, $tempeventarray);
            }
            $calarray[$calkey]["events"] = $eventarray;
        }
        return $calarray;
    }

    function geteventdata($calid, $eventid){
        $i = 0;
        begin:
        $i++;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/".urlencode($calid)."/events/$eventid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Authorization: ".$token_type." ".$access_token.""
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        if($result["error"]["code"] == 401) {
            $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
            if(isset($access_token)){
                $GLOBALS["tokens"]["readonly"]["access_token"] = $access_token;;
                file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
            }
            if($i < 2){
                goto begin;
            }
            return;
        }else{
            return $result;
        }
    }

    function converttogoogletime($datetime){
        return date("Y-m-d\TH:i:sP", strtotime($datetime));
    }

    function create_event($username, $password, $calid, $title, $description, $location, $start, $end, $isdayevent){
        $access_token = $GLOBALS["tokens"]["edit"]["access_token"];
        $refresh_token = $GLOBALS["tokens"]["edit"]["refresh_token"];
        $token_type = $GLOBALS["tokens"]["edit"]["token_type"];
        $client_id = $GLOBALS["tokens"]["edit"]["client_id"];
        $client_secret = $GLOBALS["tokens"]["edit"]["client_secret"];
        $user = verifyapi($username, $password);
        if(is_array($user) && $user["perms"]["calendar.administration"]){
            $i = 0;
            begin:
            $i++;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/$calid/events",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                // CURLOPT_POSTFIELDS => "{\"end\": {\"dateTime\":\"$end\"},\"start\":{\"dateTime\":\"$start\"},\"summary\":\"$summary\"}",
                CURLOPT_HTTPHEADER => [
                    "Authorization: ".$token_type." ".$access_token,
                    "Content-Type: application/json"
                ],
            ]);
            if($isdayevent){
                curl_setopt($curl, CURLOPT_POSTFIELDS, "{\"end\": {\"date\":\"$end\"},\"start\":{\"date\":\"$start\"},\"summary\":\"$title\", \"description\":\"$description\", \"location\":\"$location\"}");
            }else{
                curl_setopt($curl, CURLOPT_POSTFIELDS, "{\"end\": {\"dateTime\":\"".converttogoogletime($end)."\"},\"start\":{\"dateTime\":\"".converttogoogletime($start)."\"},\"summary\":\"$title\", \"description\":\"$description\", \"location\":\"$location\"}");
            }
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if($result["error"]["code"] == 401) {
                $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
                if(isset($access_token)){
                    $GLOBALS["tokens"]["edit"]["access_token"] = $access_token;;
                    file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
                }
                if($i < 2){
                    goto begin;
                }
                return;
            }else{
                return $result;
            }
        }else{
            $response["error"] = $user;
            return $response;
        }
    }

    function update_event($username, $password, $calid, $eventid, $title, $description, $location, $start, $end, $isdayevent){
        $access_token = $GLOBALS["tokens"]["edit"]["access_token"];
        $refresh_token = $GLOBALS["tokens"]["edit"]["refresh_token"];
        $token_type = $GLOBALS["tokens"]["edit"]["token_type"];
        $client_id = $GLOBALS["tokens"]["edit"]["client_id"];
        $client_secret = $GLOBALS["tokens"]["edit"]["client_secret"];
        $user = verifyapi($username, $password);
        if(empty($title) || empty($description) || empty($location) || empty($start) || empty($end)){
            $prevdata = geteventdata($calid, $eventid);
            if(empty($title)) $title = $prevdata["summary"];
            if(empty($description)) $description = $prevdata["description"];
            if(empty($location)) $location = $prevdata["location"];
            if(empty($start)){
                if(isset($prevdata["start"]["date"])){
                    $start = $prevdata["start"]["date"];
                    $isdayevent = true;
                }
                if(isset($prevdata["start"]["dateTime"])){
                    $start = $prevdata["start"]["dateTime"];
                    $isdayevent = false;
                }
            }
            if(empty($end)){
                if(isset($prevdata["end"]["date"])){
                    $end = $prevdata["end"]["date"];
                    $isdayevent = true;
                }
                if(isset($prevdata["end"]["dateTime"])){
                    $start = $prevdata["end"]["dateTime"];
                    $isdayevent = false;
                }
            }
        }
        if(is_array($user) && $user["perms"]["calendar.administration"]){
            $i = 0;
            begin:
            $i++;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/$calid/events/$eventid",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                // CURLOPT_POSTFIELDS => "{\"end\": {\"dateTime\":\"$end\"},\"start\":{\"dateTime\":\"$start\"},\"summary\":\"$summary\"}",
                CURLOPT_HTTPHEADER => [
                    "Authorization: ".$token_type." ".$access_token,
                    "Content-Type: application/json"
                ],
            ]);
            if($isdayevent){
                curl_setopt($curl, CURLOPT_POSTFIELDS, "{\"end\": {\"date\":\"$end\"},\"start\":{\"date\":\"$start\"},\"summary\":\"$title\", \"description\":\"$description\", \"location\":\"$location\"}");
            }else{
                curl_setopt($curl, CURLOPT_POSTFIELDS, "{\"end\": {\"dateTime\":\"".converttogoogletime($end)."\"},\"start\":{\"dateTime\":\"".converttogoogletime($start)."\"},\"summary\":\"$title\", \"description\":\"$description\", \"location\":\"$location\"}");
            }
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if($result["error"]["code"] == 401) {
                $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
                if(isset($access_token)){
                    $GLOBALS["tokens"]["edit"]["access_token"] = $access_token;;
                    file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
                }
                if($i < 2){
                    goto begin;
                }
                return;
            }else{
                return $result;
            }
        }else{
            $response["error"] = $user;
            return $response;
        }
    }

    function delete_event($username, $password, $calid, $eventid){
        $access_token = $GLOBALS["tokens"]["edit"]["access_token"];
        $refresh_token = $GLOBALS["tokens"]["edit"]["refresh_token"];
        $token_type = $GLOBALS["tokens"]["edit"]["token_type"];
        $client_id = $GLOBALS["tokens"]["edit"]["client_id"];
        $client_secret = $GLOBALS["tokens"]["edit"]["client_secret"];
        $user = verifyapi($username, $password);
        if(is_array($user) && $user["perms"]["calendar.administration"]){
            $i = 0;
            begin:
            $i++;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/$calid/events/$eventid",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                // CURLOPT_POSTFIELDS => "{\"end\": {\"dateTime\":\"$end\"},\"start\":{\"dateTime\":\"$start\"},\"summary\":\"$summary\"}",
                CURLOPT_HTTPHEADER => [
                    "Authorization: ".$token_type." ".$access_token
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if($result["error"]["code"] == 401) {
                $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
                if(isset($access_token)){
                    $GLOBALS["tokens"]["edit"]["access_token"] = $access_token;;
                    file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
                }
                if($i < 2){
                    goto begin;
                }
                return;
            }else{
                return $result;
            }
        }else{
            $response["error"] = $user;
            return $response;
        }
    }

    function create_calendar($username, $password, $title, $description=NULL){
        $access_token = $GLOBALS["tokens"]["edit"]["access_token"];
        $refresh_token = $GLOBALS["tokens"]["edit"]["refresh_token"];
        $token_type = $GLOBALS["tokens"]["edit"]["token_type"];
        $client_id = $GLOBALS["tokens"]["edit"]["client_id"];
        $client_secret = $GLOBALS["tokens"]["edit"]["client_secret"];
        $user = verifyapi($username, $password);
        if(is_array($user) && $user["perms"]["calendar.administration"]){
            $i = 0;
            begin:
            $i++;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\n\t\"summary\": \"$title\",\n\t\"description\": \"$description\",\n\t\"timeZone\": \"Europe/Berlin\"\n}",
                CURLOPT_HTTPHEADER => [
                    "Authorization: ".$token_type." ".$access_token,
                    "Content-Type: application/json"
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if($result["error"]["code"] == 401) {
                $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
                if(isset($access_token)){
                    $GLOBALS["tokens"]["edit"]["access_token"] = $access_token;;
                    file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
                }
                if($i < 2){
                    goto begin;
                }
                return;
            }else{
                return $result;
            }
        }else{
            $response["error"] = $user;
            return $response;
        }
    }

    function update_calendar($username, $password, $calid, $title, $description=NULL){
        $access_token = $GLOBALS["tokens"]["edit"]["access_token"];
        $refresh_token = $GLOBALS["tokens"]["edit"]["refresh_token"];
        $token_type = $GLOBALS["tokens"]["edit"]["token_type"];
        $client_id = $GLOBALS["tokens"]["edit"]["client_id"];
        $client_secret = $GLOBALS["tokens"]["edit"]["client_secret"];
        $user = verifyapi($username, $password);
        if(is_array($user) && $user["perms"]["calendar.administration"]){
            $i = 0;
            begin:
            $i++;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/$calid",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => "{\n\t\"summary\": \"$title\",\n\t\"description\": \"$description\",\n\t\"timeZone\": \"Europe/Berlin\"\n}",
                CURLOPT_HTTPHEADER => [
                    "Authorization: ".$token_type." ".$access_token,
                    "Content-Type: application/json"
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if($result["error"]["code"] == 401) {
                $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
                if(isset($access_token)){
                    $GLOBALS["tokens"]["edit"]["access_token"] = $access_token;;
                    file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
                }
                if($i < 2){
                    goto begin;
                }
                return;
            }else{
                return $result;
            }
        }else{
            $response["error"] = $user;
            return $response;
        }
    }

    function delete_calendar($username, $password, $calid){
        $access_token = $GLOBALS["tokens"]["edit"]["access_token"];
        $refresh_token = $GLOBALS["tokens"]["edit"]["refresh_token"];
        $token_type = $GLOBALS["tokens"]["edit"]["token_type"];
        $client_id = $GLOBALS["tokens"]["edit"]["client_id"];
        $client_secret = $GLOBALS["tokens"]["edit"]["client_secret"];
        $user = verifyapi($username, $password);
        if(is_array($user) && $user["perms"]["calendar.administration"]){
            $i = 0;
            begin:
            $i++;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/$calid",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => [
                    "Authorization: ".$token_type." ".$access_token
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if($result["error"]["code"] == 401) {
                $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
                if(isset($access_token)){
                    $GLOBALS["tokens"]["edit"]["access_token"] = $access_token;;
                    file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json", json_encode($GLOBALS["tokens"]));
                }
                if($i < 2){
                    goto begin;
                }
                return;
            }else{
                return $result;
            }
        }else{
            $response["error"] = $user;
            return $response;
        }
    }

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
                http_response_code(403);
                $response["error"] = "Missing priviliges";
                $response["success"] = false;
            }
        }
    }elseif($app == "getperms"){
        $response = getcalperms();
    }elseif($app == "getids"){
        $response["data"] = getcalids($username, $password);
    }elseif($app == "getcallist"){
        $response["data"] = getcalendars($GLOBALS["tokens"]["readonly"]["access_token"], $GLOBALS["tokens"]["readonly"]["token_type"], $GLOBALS["tokens"]["readonly"]["refresh_token"], $GLOBALS["tokens"]["readonly"]["client_id"], $GLOBALS["tokens"]["readonly"]["client_secret"], $username, $password);
    }elseif($app == "getcaldata"){
        $response["data"] = getcaldata($username, $password);
    }elseif($app == "geteventlist"){
        $caldata = getcaldata($username, $password);
        $event_array = array();
        foreach($caldata as $cal){
            foreach($cal["events"] as $event){
                $temparray = array();
                foreach($event as $key => $value){
                    $temparray[$key] = (($value == "")?NULL:$value);
                }
                // Adjust php date format to javascript date format
                $temparray["start"] = $temparray["start"]*1000;
                $temparray["end"] = $temparray["end"]*1000;
                $temparray["color"] = $cal["backgroundColor"];
                $temparray["eventtype"] = $cal["summary"];
                array_push($event_array, $temparray);
            }
        }
         // Sort event_array by begin date
        $keys = array_column($event_array, 'start');
        array_multisort($keys, SORT_ASC, $event_array);
        $response["data"] = $event_array;
    }elseif($app == "create_event"){
        $response["data"] = create_event($username, $password, $_POST["calid"], $_POST["title"], $_POST["description"], $_POST["location"], $_POST["start"], $_POST["end"], filter_var($_POST["isdayevent"], FILTER_VALIDATE_BOOLEAN));
    }elseif($app == "update_event"){
        $response["data"] = create_event($username, $password, $_POST["calid"], $_POST["eventid"], $_POST["title"], $_POST["description"], $_POST["location"], $_POST["start"], $_POST["end"], filter_var($_POST["isdayevent"], FILTER_VALIDATE_BOOLEAN));
    }elseif($app == "delete_event"){
        $response["data"] = delete_event($username, $password, $_POST["calid"], $_POST["eventid"]);
    }elseif($app == "create_calendar"){
        $response["data"] = create_calendar($username, $password, $_POST["title"], $_POST["description"]);
    }elseif($app == "update_calendar"){
        $response["data"] = update_calendar($username, $password, $_POST["calid"], $_POST["title"], $_POST["description"]);
    }elseif($app == "delete_calendar"){
        $response["data"] = delete_calendar($username, $password, $_POST["calid"]);
    }else{
        http_response_code(404);
        $response["error"] = "Application unknown";
    }
    echo json_encode($response);
?>