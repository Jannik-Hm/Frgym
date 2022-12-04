<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    setsession();
    verifylogin();
    $user = verifyapi(null, null);
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
            $fachbereichcount = count($user["perms"]["fachbereich"]);
            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/sites/header.php";
            if($fachbereichcount == 1 && !$user["perms"]["fachbereich"]["admin"]){
                echo("<script>window.location.href='/admin/faecher-editor/?fach=".array_keys($user["perms"]["fachbereich"])[0]."'</script>");
            }elseif($fachbereichcount > 1 || $user["perms"]["fachbereich"]["admin"]){
                echo '<section class="faecher">';
                $faecher = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-liste.json"), true);
                if($user["perms"]["fachbereich"]["admin"]){
                    foreach($faecher as $fachbereich){
                        echo '
                            <div class="bereichdiv">
                            <label class="fachbereich">'.$fachbereich["name"].'</label><br>
                            <ul class="faecherlist">';
                            foreach($fachbereich["faecher"] as $fach){
                                if($fach["dark-image"]){$darkpath = '<source srcset="/files/site-ressources/faecher-icons/'.$fach["filename"].'-Dark.svg" media="(prefers-color-scheme: dark)">';}else{$darkpath = NULL;}
                                echo '<a title="Fach Bearbeiten" href="/admin/faecher-editor/?fach='.$fach["short"].'"><li class="fach"><div><picture>'.$darkpath.'<img src="/files/site-ressources/faecher-icons/'.$fach["filename"].'.svg" class="fachimg"></picture><br><span>'.$fach["name"].'</span></div></li></a>';
                            }
                        echo '
                            </ul>
                        </div>
                        ';
                    }
                }else{
                    foreach($faecher as $fachbereich){
                        echo '
                            <div class="bereichdiv">
                            <label class="fachbereich">'.$fachbereich["name"].'</label><br>
                            <ul class="faecherlist">';
                            foreach($fachbereich["faecher"] as $fach){
                                if($user["perms"]["fachbereich"][$fach["short"]]){
                                    if($fach["dark-image"]){$darkpath = '<source srcset="/files/site-ressources/faecher-icons/'.$fach["filename"].'-Dark.svg" media="(prefers-color-scheme: dark)">';}else{$darkpath = NULL;}
                                    echo '<a title="Fach Bearbeiten" href="/admin/faecher-editor/?fach='.$fach["short"].'"><li class="fach"><div><picture>'.$darkpath.'<img src="/files/site-ressources/faecher-icons/'.$fach["filename"].'.svg" class="fachimg"></picture><br><span>'.$fach["name"].'</span></div></li></a>';
                                }
                            }
                        echo '
                            </ul>
                        </div>
                        ';
                    }
                }
            echo '</section>';
            }else{
                echo '<script>$(".no_perm").show()</script>';
            }
            ?>
            </div>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html" ?>
    </body>
</html>
