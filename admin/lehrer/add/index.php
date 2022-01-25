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
            include_once "$root/admin/sites/head.html"

        ?>
        <title>Lehrer hinzufügen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/header.html";

            include_once "$root/admin/sites/permissions.php";

            include_once "$root/admin/no-permission.html";
            if($lehrer_all == 0){
                echo("<script>$('.no_perm').show();</script>");
                $disabled = true;
            };

        ?>

        <div class="add-input">
            <form method="POST">
                <input type="text" width="" placeholder="Vorname*" name="vorname" <?php if($disabled){echo "disabled";} ?> required><br>
                <input type="text" placeholder="Nachname*" name="nachname" <?php if($disabled){echo "disabled";} ?> required><br>
                <input type="email" placeholder="Email*" name="email" <?php if($disabled){echo "disabled";} ?> required><br>
                <div class="position">
                    <label class="heading2">Position</label>
                    <ul>
                        <li><label><input type="radio" name="position" <?php if($disabled){echo "disabled";} ?> value="Lehrer*in">Lehrer*in</label></li>
                        <li><label><input type="radio" name="position" <?php if($disabled){echo "disabled";} ?> value="Referendar*in">Referendar*in</label></li>
                        <li><label><input type="radio" name="position" <?php if($disabled){echo "disabled";} ?> value="Schulleiter*in">Schulleiter*in</label></li>
                        <li><label><input type="radio" name="position" <?php if($disabled){echo "disabled";} ?> value="stellvertretender Schulleiter*in">stellvertretender Schulleiter*in</label></li>
                        <li><label><input type="radio" name="position" <?php if($disabled){echo "disabled";} ?> value="Oberstufenkoordinator*in">Oberstufenkooridnator*in</label></li>
                        <li><label><input type="radio" name="position" <?php if($disabled){echo "disabled";} ?> value="Sekretär*in">Sekretär*in</label></li>
                        <br>
                    </ul>
                    <br>
                </div>
                <div class="faecher">
                    <label class="heading2">Fächer</label>
                    <ul>
                        <ul>
                            <label class="heading">Sprachwissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="DE">Deutsch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="EN">Englisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="FR">Französisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="PO">Polnisch</label></li>
                        <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="RU">Russisch</label></li>
                        <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="SN">Spanisch</label></li>
                        <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="TR">Türkisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="LA">Latein</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Naturwissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="MA">Mathematik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="BI">Biologie</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="CH">Chemie</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="PH">Physik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="IF">Informatik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="NW">Naturwissenschaften</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Gesellschaftswissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="EK">Erdkunde</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="GE">Geschichte</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="PB">Politische Bildung</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="EG">Gesellschaftswissenschaften</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="RE">Evangelischer Religionsunterricht</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="RK">Katholischer Religionsunterricht</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="LE">Lebensgestaltung-Ethik-Religionskunde</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="AL">Wirtschaft-Arbeit-Technik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="WW">Wirtschaftswissenschaften</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Künstlerische Fächer</label>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="DS">Darstellendes Spiel</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="KU">Kunst</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="MU">Musik</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Sonstige</label>
                            <li><label><input type="checkbox" name="chk_group[]" <?php if($disabled){echo "disabled";} ?> value="SP">Sport</label></li>
                        </ul>
                    </ul>
                </div>
                <textarea rows="10" columns="50%" placeholder="Infotext (Optional)" name="beschreibung" <?php if($disabled){echo "disabled";} ?>></textarea><br>
                <input type="date" placeholder="Geburtstag (Optional)" <?php if($disabled){echo "disabled";} ?> name="geburtstag" Optional><br>
                <input type="submit" name="submit" <?php if($disabled){echo "disabled";} ?> value="Speichern">
                <div class="page-ending"></div>
            </form>
        </div>

        <div style='left: 0;' class='confirm'>
            <span class='helper'></span>
            <div class='scroll'>
                <div class='confirmation'>
                    <h1>Hinzufügen erfolgreich!</h1><br>
                    <p>Der Lehrer wurde erfolgreich hinzugefügt.</p><br>
                    <a href='/admin/lehrer/add/' class='repeat'>Weiteren Lehrer hinzufügen</a>
                    <a href='/admin/lehrer/' class='back'>Zurück zur Übersicht</a>
                </div>
            </div>
        </div>

        <?php
        $vorname = $_POST["vorname"];
        $nachname = $_POST["nachname"];
        $email = $_POST["email"];
        $position = $_POST["position"];
        $faecher_array = $_POST["chk_group"];
        $faecher = "";
        $infotext = $_POST["beschreibung"];
        $geburtstag = $_POST["geburtstag"];
            require_once "$root/sites/credentials.php";
            $conn = get_connection();
            for ($i=0; $i < count($faecher_array); $i++) {
                $faecher = $faecher.$faecher_array[$i];
                if ($i < count($faecher_array)-1) {
                    $faecher = $faecher.";";
                }
            }
            if(isset($_POST["submit"]) && $lehrer_all == 1) {
                $insert = mysqli_query($conn, "INSERT INTO lehrer (vorname, nachname, email, position, faecher, beschreibung, datum) VALUES ('{$vorname}', '{$nachname}', '{$email}', NULLIF('{$position}', ''), '{$faecher}', NULLIF('{$infotext}', ''), NULLIF('{$geburtstag}',''))");
            }

            if ($insert) {
                echo("<script>$('.confirm').show();</script>");
            }
        include_once "$root/sites/footer.html"
        ?>
        <div class="page-ending"></div>
    </body>
</html>