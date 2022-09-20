
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

    <head>
        <script src="/js/snippets.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <title>Startseite - Friedrich-Gymnasium Luckenwalde</title>
    </head>

    <body>
          <?php
          $tokens = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/GoogleApisecrets.json"), true);

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

          function getcalendars($access_token, $token_type, $refresh_token, $client_id, $client_secret, $tokens) {
            begin:
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
                $tokens["readonly"]["access_token"] = $access_token;;
                file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/GoogleApisecrets.json", json_encode($tokens));
              }
              goto begin;
              return;
            }else{
              return $result;
            }
          }

          function insertcalevents($summary, $start, $end, $dayevent, $calid, $access_token, $token_type, $refresh_token, $client_id, $client_secret, $tokens){
            begin:
            echo("test3");
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
                "Authorization: $token_type $access_token",
                "Content-Type: application/json"
              ],
            ]);
            echo("test4");
            if($dayevent){
              echo("date");
              echo("{\"end\": {\"date\":\"$end\"},\"start\":{\"date\":\"$start\"},\"summary\":\"$summary\"}");
              curl_setopt($curl, CURLOPT_POSTFIELDS, "{\"end\": {\"date\":\"$end\"},\"start\":{\"date\":\"$start\"},\"summary\":\"$summary\"}");
            }else{
              echo("time");
              echo("{\"end\": {\"dateTime\":\"$end\"},\"start\":{\"dateTime\":\"$start\"},\"summary\":\"$summary\"}");
              curl_setopt($curl, CURLOPT_POSTFIELDS, "{\"end\": {\"dateTime\":\"$end\"},\"start\":{\"dateTime\":\"$start\"},\"summary\":\"$summary\"}");
            }

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
                $tokens["edit"]["access_token"] = $access_token;;
                file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/GoogleApisecrets.json", json_encode($tokens));
              }
              goto begin;
              return;
            }else{
              return $result;
            }
          }
      ?>
      <form method="POST">
        <label>
          <select name="calendar">
            <?php
            foreach(getcalendars($tokens["readonly"]["access_token"], $tokens["readonly"]["token_type"], $tokens["readonly"]["refresh_token"], $tokens["readonly"]["client_id"], $tokens["readonly"]["client_secret"], $tokens)["items"] as $calendar){
              if($calendar["summary"] == "support@frgym.de") continue;
              echo "<option value='".$calendar["id"]."'>".$calendar["summary"]."</option>";
            }
            ?>
          </select>
          Kalender
        </label>
        <label><input type="text" name="summary" required></input> Terminname</label>
        <label><input type="checkbox" name="dayevent" id="dayevent"></input> Ganztägig</label>
        <label><input type="datetime-local" name="start" id="eventstart" required></input> Anfang</label>
        <label><input type="datetime-local" name="end" id="eventend" required></input> Ende</label>
        <input type="submit" name="submit"></input>
      </form>
      <script>
        $("#dayevent").change(function() {
          if(this.checked) {
            document.getElementById("eventstart").type="date";
            document.getElementById("eventend").type="date";
          }else{
            document.getElementById("eventstart").type="datetime-local";
            document.getElementById("eventend").type="datetime-local";
          }
        });
      </script>
      <?php
      if(isset($_POST["submit"])){
        echo("test");
        if($_POST["dayevent"] == "on"){
          echo("date");
          echo(print_r(insertcalevents($_POST["summary"], $_POST["start"], $_POST["end"], true, $_POST["calendar"], $tokens["edit"]["access_token"], $tokens["edit"]["token_type"], $tokens["edit"]["refresh_token"], $tokens["edit"]["client_id"], $tokens["edit"]["client_secret"], $tokens)));
        }else{
          $start = $_POST["start"].":00";
          $end = $_POST["end"].":00";
          $start = $start."+02:00";
          $end = $end."+02:00";
          echo("time");
          echo(print_r(insertcalevents($_POST["summary"], $start, $end, false, $_POST["calendar"], $tokens["edit"]["access_token"], $tokens["edit"]["token_type"], $tokens["edit"]["refresh_token"], $tokens["edit"]["client_id"], $tokens["edit"]["client_secret"], $tokens)));
        }
        echo("test2");
  }

?>
    </body>

</html>