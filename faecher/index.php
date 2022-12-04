<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php
            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/head.html"
        ?>
        <title>Fächer - Friedrich-Gymnasium Luckenwalde</title>
        <link rel="stylesheet" href="/new-css/faecher.css">
    </head>
    <body>
            <?php
            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/header.html";
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            $curl = curl_init();

            curl_setopt_array($curl, [
            CURLOPT_URL => "https://frgym.greenygames.de/admin/api/faecher.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"action\"\r\n\r\ngetallvisibility\r\n-----011000010111000001101001--\r\n",
            CURLOPT_HTTPHEADER => [
                "Content-Type: multipart/form-data; boundary=---011000010111000001101001"
            ],
            ]);
            $response = json_decode(curl_exec($curl), true);
            $visibilitylist = array();
            foreach($response["data"] as $fach => $value){
                if($value == "visible"){
                    $visibilitylist[$fach] = true;
                }
            }
            $faecher = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-liste.json"), true);
            if(isset($_GET["fach"]) && $visibilitylist[$_GET["fach"]]){
                // Show Fach Page
                foreach($faecher as $fachbereich){
                    if(isset($fachbereich["faecher"][$_GET["fach"]])){
                        $fachname = $fachbereich["faecher"][$_GET["fach"]]["name"];
                    }
                }
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
                echo '
                <section style="height: 25px"></section>
                <ul class="test" style="border: none; list-style: none; padding: 25px; margin-left: 50px; margin-right: 50px; background-color: var(--inputbackground); border-radius: 15px; color: var(--inputcolor);">
                    <li><p style="font-size: 40px; height: 40px; margin-bottom: 20px">Fachbereich '.$fachname.'</p></li>
                    <script>$.post("/admin/api/faecher.php", {"action": "getfachelements", "fach": "DE"}, function(data){$(".test").append(data);})</script>
                </ul>';
            }else{
                echo '<section class="faecher">';
                foreach($faecher as $fachbereich){
                    echo '
                        <div class="bereichdiv">
                        <label class="fachbereich">'.$fachbereich["name"].'</label><br>
                        <ul class="faecherlist">';
                        foreach($fachbereich["faecher"] as $fach){
                            if($visibilitylist[$fach["short"]] == "visible"){$link = "href='".$_SERVER['PHP_SELF']."?fach=".$fach["short"]."'";}else{$link = NULL;}
                            if($fach["dark-image"]){$darkpath = '<source srcset="/files/site-ressources/faecher-icons/'.$fach["filename"].'-Dark.svg" media="(prefers-color-scheme: dark)">';}else{$darkpath = NULL;}
                            echo '<a '.$link.'><li class="fach"><figcaption>i</figcaption><object><a href="'.$fach["pic-url"].'" class="imgsource">'.$fach["pic-url"].'</a class="imgsource"></object><div><picture>'.$darkpath.'<img src="/files/site-ressources/faecher-icons/'.$fach["filename"].'.svg" class="fachimg"></picture><br><span>'.$fach["name"].'</span></div></li></a>';
                        }
                    echo '
                        </ul>
                    </div>
                    ';
                }
                echo '</section>';
            }
            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html"
            ?>
    </body>
</html>