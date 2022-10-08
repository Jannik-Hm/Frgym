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
            <title>Passwort ändern - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
        </head>
        <body>
            <div class="bodyDiv">
            <?php
                include_once "$root/admin/sites/header.php";
            ?>
            <link rel="stylesheet" href="/new-css/form.css">
            <link rel="stylesheet" href="/new-css/lehrer.css">
            <section style="text-align: left; width: clamp(360px, 95%, 1000px);">
                <style>
                    .reset-pass input {
                        margin-bottom: 15px!important;
                    }
                    .reset-pass input[type="submit"]{
                        margin-top: 25px!important;
                    }
                    .reset-pass input[type="password"].match {
                        border: 2px solid limegreen!important;
                    }
                    .reset-pass input[type="password"].mismatch {
                        border: 2px solid red!important;
                    }
                </style>
                <div class="add-input reset-pass">
                    <form method="POST" enctype="multipart/form-data" style="margin-top: 25px">
                        <input type="password" id="pass_old" name="pass_old" placeholder="Altes Passwort" onkeyup="genhash('pass_old', 'pass_old_hash');" required>
                        <input type="password" id="pass_new" name="pass_new" placeholder="Neues Passwort" onkeyup="checkmatch();" required>
                        <input type="password" id="pass_new_check" placeholder="Neues Passwort wiederholen" onkeyup="checkmatch();" required>
                        <input type="hidden" id="pass_old_hash" name="pass_old_hash">
                        <input type="hidden" id="pass_new_hash" name="pass_new_hash">
                        <input style="cursor: pointer;" type="submit" name="submit" value="Speichern">
                        <p id="errormessage" style="display:none; text-align:center">Fehler bei der Aktualisierung. Bitte überprüfen sie das eingegebene Passwort.</p>
                    </form>
                    <script>
                        function checkmatch() {
                            if($("#pass_new").val() == null && $("#pass_new_check").val() == null || $("#pass_new").val() == "" && $("#pass_new_check").val() == ""){
                                $("#pass_new, #pass_new_check").attr("class", "");
                                $("#pass_new_hash").val(null);
                            }else if($("#pass_new").val() === $("#pass_new_check").val()){
                                $("#pass_new, #pass_new_check").attr("class", "match");
                                genhash('pass_new', 'pass_new_hash');
                            }else{
                                $("#pass_new, #pass_new_check").attr("class", "mismatch");
                                $("#pass_new_hash").val(null);
                            }
                        }
                        function genhash(rawinputid, hashinputid) {
                            $("#"+hashinputid).val(sha256($("#"+rawinputid).val()));
                        }
                    </script>
                </div>
            </section>
        </div>
        <?php
        confirmation("Änderung erfolgreich!", "Dein Passwort wurde erfolgreich aktualisiert.", "Zurück zur Startseite", "/admin/");
        if(isset($_POST["submit"])) {
            $conn = getsqlconnection();
            $sql = $conn->prepare("UPDATE users SET password_hash=? WHERE id=? && password_hash=?;");
            $sql->bind_param("sss", $_POST["pass_new_hash"], $_SESSION["user_id"], $_POST["pass_old_hash"]);
            $sql->execute();
            if($sql->affected_rows != 0){
                echo("<script>$('.confirm').show();</script>");
            }else{
                echo("<script>$('#errormessage').show();</script>");
            }
        }
        ?>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>