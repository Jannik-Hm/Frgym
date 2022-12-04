<?php
    session_name("userid_login");
    session_start();
    if(!isset($_SESSION["user_id"])) {
        header("Location: /admin/login/");
    }
?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
            <?php
                $root = realpath($_SERVER["DOCUMENT_ROOT"]);
                include_once "$root/admin/sites/head.html";
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js" integrity="sha512-szJ5FSo9hEmXXe7b5AUVtn/WnL8a5VofnFeYC2i2z03uS2LhAch7ewNLbl5flsEmTTimMN0enBZg/3sQ+YOSzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <title>Kalendarzugang - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
        </head>
        <body>
            <div class="bodyDiv">
            <?php
                include_once "$root/admin/sites/header.php";
                $roles = array("Öffentlich", "Schüler*in");
                $result = mysqli_query(getsqlconnection(), "SELECT name FROM roles");
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        array_push($roles, $row["name"]);
                    }
                }
                $GLOBALS["tokens"] = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/secrets/GoogleApisecrets.json"), true);

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

                function getcalendars($access_token, $token_type, $refresh_token, $client_id, $client_secret) {
                    $i = 0;
                    begin:
                    $i++;
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                    CURLOPT_URL => "https://www.googleapis.com/calendar/v3/users/me/calendarList",
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

                    if($err){
                    echo("test2");
                    }

                    curl_close($curl);

                    $result = json_decode($response, true);

                    if($result["error"]["code"] == 401) {
                    echo("newtoken");
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
            ?>
            <link rel="stylesheet" href="/new-css/form.css">
            <link rel="stylesheet" href="/new-css/lehrer.css">
            <style>
                table{
                    border-collapse: collapse;
                    margin-bottom: 25px;
                }
                th {
                    padding: 10px 20px;
                    text-align: center;
                }
                tr:first-child th {
                    border-bottom: 1px solid #fff;
                }
                tr th:first-child {
                    border-right: 1px solid #fff;
                }
            </style>
            <section style="text-align: left; width: clamp(360px, 95%, 2500px);">
                <script>
                    function checkboxes(data){
                        JSON.parse(data).forEach(function(entry) {
                            entry.calendars.forEach(function(calendar){
                                $('input[type="checkbox"][name="'+entry.role+'[]"][value="'+calendar+'"]').attr("checked", "checked");
                            })
                        })
                    }
                    $.post("https://frgym.greenygames.de/admin/api/calendar.php", {action: "getperms"}, checkboxes);
                </script>
                <div class="add-input reset-pass">
                    <form method="POST" enctype="multipart/form-data" style="margin-top: 25px">
                        <table>
                            <tr>
                                <th></th>
                                <?php
                                    foreach($roles as $role){
                                        echo("<th>".$role."</th>");
                                    }
                                ?>
                            </tr>
                            <?php
                                foreach(getcalendars($GLOBALS["tokens"]["readonly"]["access_token"], $GLOBALS["tokens"]["readonly"]["token_type"], $GLOBALS["tokens"]["readonly"]["refresh_token"], $GLOBALS["tokens"]["readonly"]["client_id"], $GLOBALS["tokens"]["readonly"]["client_secret"])["items"] as $calendar){
                                    echo("
                                    <tr>
                                        <th>".$calendar["summary"]."</th>");
                                        foreach($roles as $role){
                                            echo("<th><label class='chkbx_label'><input type='checkbox' name='".$role."[]' value='".$calendar["id"]."'><span class='chkbx' style='left: 40%'></span></label></th>");
                                        }
                                    echo("
                                    </tr>");
                                }
                            ?>
                            </table>
                            <input style="cursor: pointer;" type="button" name="submit" class="submit" onclick="save();" value="Speichern">
                    </form>
                    <script>
                        function save(){
                            $.post("https://frgym.greenygames.de/admin/api/calendar.php", {action: "updateperm", permdata: JSON.stringify($("input[type='checkbox']").serializeArray())}, success);
                        }
                        function success(data){
                            if(JSON.parse(data).success){
                                $('.confirm').show();
                            }else{
                                console.log(data);
                                $('#errormessage').show();
                            }
                        }
                    </script>
                </div>
            </section>
        </div>
        <?php
        confirmation("Änderung erfolgreich!", "Die Kalendar-Berechtigungen wurden erfolgreich aktualisiert.", "Zurück zur Startseite", "/admin/");
        ?>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>