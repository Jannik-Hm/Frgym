
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

          function getcalendars($access_token, $token_type, $refresh_token, $client_id, $client_secret, $tokens) {
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
                $tokens["readonly"]["access_token"] = $access_token;;
                file_put_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/GoogleApisecrets.json", json_encode($tokens));
              }
              if($i < 2){
                goto begin;
              }
              return;
            }else{
              return $result;
            }
          }

          function getcalevents($calid, $access_token, $token_type, $refresh_token, $client_id, $client_secret, $tokens){
            $i = 0;
            begin:
            $i++;
            $curl = curl_init();

            curl_setopt_array($curl, [
              CURLOPT_URL => "https://www.googleapis.com/calendar/v3/calendars/".$calid."/events",
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
              if($i < 2){
                goto begin;
              }
              return;
            }else{
              return $result;
            }
          }
          // $Konferenzen = getcalevents("hsjgf6vsu77oe0kugcabr8btog@group.calendar.google.com", $tokens["readonly"]["access_token"], $tokens["readonly"]["token_type"], $tokens["readonly"]["refresh_token"], $tokens["readonly"]["client_id"], $tokens["readonly"]["client_secret"], $tokens);
  // echo (print_r($Konferenzen["items"][0]));
  // echo print_r($Konferenzen);
  $calendars = getcalendars($tokens["readonly"]["access_token"], $tokens["readonly"]["token_type"], $tokens["readonly"]["refresh_token"], $tokens["readonly"]["client_id"], $tokens["readonly"]["client_secret"], $tokens);
  foreach ($calendars["items"] as $calendar){
    if($calendar["summary"] == "support@frgym.de"){continue;}
    echo("Kalendar: ".$calendar["summary"]."<br>");
    foreach (getcalevents($calendar["id"], $tokens["readonly"]["access_token"], $tokens["readonly"]["token_type"], $tokens["readonly"]["refresh_token"], $tokens["readonly"]["client_id"], $tokens["readonly"]["client_secret"], $tokens)["items"] as $entry){
      echo ("Name: ".$entry["summary"]. " ");
      echo ("Uhrzeit: ".date("d.m.Y H:i",strtotime( $entry["start"]["dateTime"]))." - ".date("d.m.Y H:i",strtotime($entry["end"]["dateTime"]))."<br>");
    }
  }

?>
    </body>

</html>