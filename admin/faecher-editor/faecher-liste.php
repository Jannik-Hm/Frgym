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
                include_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/sites/head.html"
            ?>
            <title>Fachseite bearbeiten - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
            <link rel="stylesheet" href="/new-css/faecher.css">
        </head>
        <body>
            <?php
            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/sites/header.html";
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            getperm();
            if($GLOBALS["fachbereich"] != "admin"){
                echo("<script>window.location.href='/admin/faecher-editor/?fach=".$GLOBALS["fachbereich"]."'</script>");
            }else{
                echo '<section class="faecher">';
                    $faecher = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-liste.json"), true);
                    foreach($faecher as $fachbereich){
                        echo '
                            <div class="bereichdiv">
                            <label class="fachbereich">'.$fachbereich["name"].'</label><br>
                            <ul class="faecherlist">';
                            foreach($fachbereich["faecher"] as $fach){
                                if($fach["dark-image"]){$darkpath = '<source srcset="/files/site-ressources/faecher-icons/'.$fach["filename"].'-Dark.svg" media="(prefers-color-scheme: dark)">';}else{$darkpath = NULL;}
                                echo '<a title="Fach Bearbeiten" href="/admin/faecher-editor/?fach='.$fach["name"].'"><li class="fach"><div><picture>'.$darkpath.'<img src="/files/site-ressources/faecher-icons/'.$fach["filename"].'.svg" class="fachimg"></picture><br><span>'.$fach["name"].'</span></div></li></a>';
                            }
                        echo '
                            </ul>
                        </div>
                        ';
                    }
            echo '</section>';
            }
            ?>
            </div>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html" ?>
    </body>
</html>
