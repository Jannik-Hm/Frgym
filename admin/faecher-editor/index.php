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
            if($GLOBALS["fachbereich"] == "admin"){$GLOBALS["fachbereich"] = $_GET["fach"];}
            if($GLOBALS["fachbereich"] == $_GET["fach"] && $GLOBALS["fachbereich"] != NULL && $_GET["fach"] != NULL){
                echo '
                <section style="height: 25px"></section>
                <ul class="test" style="list-style: none; padding: 25px; margin-left: 50px; margin-right: 50px; background-color: var(--inputbackground); border-radius: 15px; color: var(--inputcolor);">';
                    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
                    $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE fach=\"{$_GET["fach"]}\" && contenttype != 'visibility' ORDER BY LENGTH(position), position ASC");
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        do {
                            if($row["contenttype"] != NULL){
                                create_segment($row["contenttype"], $row["id"]);
                            }
                        } while($row = $result->fetch_assoc());
                    }
                    save_segment();
                echo '</ul>';
                dragndrop(".test");
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
                segment_selector();
                makevisible();
            }else{
                echo("<script>$('.no_perm').show();</script>");
            }
            ?>
            </div>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html" ?>
    </body>
</html>