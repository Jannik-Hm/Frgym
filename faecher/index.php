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
            <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/header.html" ?>
            <!-- TODO: add hrefs to faecher pages + missing icons -->
            <section class="faecher">
                <?php
                    $faecher = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-liste.json"), true);
                    foreach($faecher as $fachbereich){
                        echo '
                            <div class="bereichdiv">
                            <label class="fachbereich">'.$fachbereich["name"].'</label><br>
                            <ul class="faecherlist">';
                            foreach($fachbereich["faecher"] as $fach){
                                if($fach["dark-image"]){$darkpath = '<source srcset="/files/site-ressources/faecher-icons/'.$fach["filename"].'-Dark.svg" media="(prefers-color-scheme: dark)">';}else{$darkpath = NULL;}
                                echo '<a><li class="fach"><figcaption>i</figcaption><object><a href="'.$fach["pic-url"].'" class="imgsource">'.$fach["pic-url"].'</a class="imgsource"></object><div><picture>'.$darkpath.'<img src="/files/site-ressources/faecher-icons/'.$fach["filename"].'.svg" class="fachimg"></picture><br><span>'.$fach["name"].'</span></div></li></a>';
                            }
                        echo '
                            </ul>
                        </div>
                        ';
                    }
                ?>
            </section>
            <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html" ?>
    </body>
</html>