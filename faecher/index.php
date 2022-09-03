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
            $result = mysqli_query(getsqlconnection(), "SELECT fach, content1 FROM faecher WHERE contenttype='visibility'");
                $visibilitylist = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()){
                        $visibilitylist[$row["fach"]] = $row["content1"];
                    }
            }
            if(isset($_GET["fach"]) && $visibilitylist[$_GET["fach"]]){
                // Show Fach Page
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
                echo '
                <section style="height: 25px"></section>
                <ul class="test" style="border: none; list-style: none; padding: 25px; margin-left: 50px; margin-right: 50px; background-color: var(--inputbackground); border-radius: 15px; color: var(--inputcolor);">
                    <li><p style="font-size: 40px; height: 40px; margin-bottom: 20px">Fachbereich '.$_GET["fach"].'</p></li>';
                    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
                    $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE fach=\"{$_GET["fach"]}\" && contenttype != 'visibility' ORDER BY LENGTH(position), position ASC");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        do {
                            if($row["contenttype"] != NULL){
                                show_segment($row["contenttype"], $row["id"]);
                            }
                        } while($row = $result->fetch_assoc());
                    }
                echo '</ul>';
            }else{
                echo '<section class="faecher">';
                $faecher = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-liste.json"), true);
                foreach($faecher as $fachbereich){
                    echo '
                        <div class="bereichdiv">
                        <label class="fachbereich">'.$fachbereich["name"].'</label><br>
                        <ul class="faecherlist">';
                        foreach($fachbereich["faecher"] as $fach){
                            if($visibilitylist[$fach["name"]] == "visible"){$link = "href='".$_SERVER['PHP_SELF']."?fach=".$fach["name"]."'";}else{$link = NULL;}
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