
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

    <head>
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

          function getcalendars($access_token, $token_type, $refresh_token, $client_id, $client_secret) {
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

            if($result["error"]) {
              echo("newtoken");
              $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
              $GLOBALS["newtoken"]=$access_token;
              goto begin;
              return;
            }else{
              return $result;
            }
          }

          function insertcalevents($summary, $start, $end, $calid, $access_token, $token_type, $refresh_token, $client_id, $client_secret){
            begin:
            $curl = curl_init();

            curl_setopt_array($curl, [
              CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/$calid/events",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "{\"end\": {\"dateTime\":\"$end\"},\"start\":{\"dateTime\":\"$start\"},\"summary\":\"$summary\"}",
              CURLOPT_HTTPHEADER => [
                "Authorization: $token_type $access_token",
                "Content-Type: application/json"
              ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            if($err){
              echo("test2");
            }

            curl_close($curl);

            $result = json_decode($response, true);

            if($result["error"]) {
              echo("newtoken");
              $access_token = getnewtoken($refresh_token, $client_id, $client_secret);
              $GLOBALS["newtoken"]=$access_token;
              echo($GLOBALS["newtoken"]);
              goto begin;
              return;
            }else{
              return $result;
            }
          }
  //         $Konferenzen = getcalevents("hsjgf6vsu77oe0kugcabr8btog@group.calendar.google.com", $tokens["readonly"]["access_token"], $tokens["readonly"]["token_type"], $tokens["readonly"]["refresh_token"], $tokens["readonly"]["client_id"], $tokens["readonly"]["client_secret"]);
  // // echo (print_r($Konferenzen["items"][0]));
  // // echo print_r($Konferenzen);
  // $calendars = getcalendars($tokens["readonly"]["access_token"], $tokens["readonly"]["token_type"], $tokens["readonly"]["refresh_token"], $tokens["readonly"]["client_id"], $tokens["readonly"]["client_secret"]);
  // foreach ($calendars["items"] as $calendar){
    //   if($calendar["summary"] == "support@frgym.de"){continue;}
    //   echo("Kalendar: ".$calendar["summary"]."<br>");
    //   foreach (getcalevents($calendar["id"], $tokens["readonly"]["access_token"], $tokens["readonly"]["token_type"], $tokens["readonly"]["refresh_token"], $tokens["readonly"]["client_id"], $tokens["readonly"]["client_secret"])["items"] as $entry){
      //     echo ("Name: ".$entry["summary"]. " ");
      //     echo ("Uhrzeit: ".date("d.m.Y H:i",strtotime( $entry["start"]["dateTime"]))." - ".date("d.m.Y H:i",strtotime($entry["end"]["dateTime"]))."<br>");
      //   }
      // }
      ?>
      <form method="POST">
        <label>
          <select name="calendar">
            <?php
            foreach(getcalendars($tokens["readonly"]["access_token"], $tokens["readonly"]["token_type"], $tokens["readonly"]["refresh_token"], $tokens["readonly"]["client_id"], $tokens["readonly"]["client_secret"])["items"] as $calendar){
              if($calendar["summary"] == "support@frgym.de") continue;
              echo "<option value='".$calendar["id"]."'>".$calendar["summary"]."</option>";
            }
            ?>
          </select>
          Kalender
        </label>
        <label><input type="text" name="summary" required></input> Terminname</label>
        <label><input type="datetime-local" name="start" required></input> Anfang</label>
        <label><input type="datetime-local" name="end" required></input> Ende</label>
        <input type="submit" name="submit"></input>
      </form>
      <?php
      if(isset($_POST["submit"])){
        insertcalevents($_POST["summary"], $_POST["start"].":00+02:00", $_POST["end"].":00+02:00", $_POST["calendar"], $tokens["edit"]["access_token"], $tokens["edit"]["token_type"], $tokens["edit"]["refresh_token"], $tokens["edit"]["client_id"], $tokens["edit"]["client_secret"]);
        if(isset($GLOBALS["newtoken"])){
          echo($GLOBALS["newtoken"]."<br>");
          $tokens["edit"]["access_token"] = $GLOBALS["newtoken"];
          echo($tokens["edit"]["access_token"]);
          file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/GoogleApisecrets.json", json_encode($tokens));
        }
  }

?>
    </body>

</html>