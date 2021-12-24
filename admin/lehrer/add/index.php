<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            include_once "./../../sites/head.html"

        ?>
        <title>Lehrer hinzufügen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "./../../sites/header.html"

        ?>

        <div class="add-input">
            <form method="POST">
                <input type="text" width="" placeholder="Vorname*" name="vorname" required><br>
                <input type="text" placeholder="Nachname*" name="nachname" required><br>
                <input type="email" placeholder="Email*" name="email" required><br>
                <div class="position">
                    <label class="heading2">Position</label>
                    <ul>
                        <li><label><input type="radio" name="position" value="Lehrer*in">Lehrer*in</label></li>
                        <li><label><input type="radio" name="position" value="Schulleiter*in">Schulleiter*in</label></li>
                        <li><label><input type="radio" name="position" value="stellvertretender Schulleiter*in">stellvertretender Schulleiter*in</label></li>
                        <li><label><input type="radio" name="position" value="Oberstufenkoordinator*in">Oberstufenkooridnator*in</label></li>
                        <li><label><input type="radio" name="position" value="Sekretär*in">Sekretär*in</label></li>
                        <br>
                    </ul>
                    <br>
                </div>
                <div class="faecher">
                <label class="heading2">Fächer</label>
                    <ul>
                        <ul>
                            <label class="heading">Sprachwissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="DE">Deutsch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="EN">Englisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="FR">Französisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PO">Polnisch</label></li>
                        <li><label><input type="checkbox" name="chk_group[]" value="RU">Russisch</label></li>
                        <li><label><input type="checkbox" name="chk_group[]" value="SN">Spanisch</label></li>
                        <li><label><input type="checkbox" name="chk_group[]" value="TR">Türkisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="LA">Latein</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Naturwissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="MA">Mathe</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="BI">Biologie</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="CH">Chemie</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PH">Physik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="IF">Informatik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="NW">Naturwissenschaften</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Gesellschaftswissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="EK">Erdkunde</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="GE">Geschichte</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PB">Politische Bildung</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="EG">Gesellschaftswissenschaften</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="RE">Evangelischer Religionsunterricht</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="RK">Katholischer Religionsunterricht</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="LE">Lebensgestaltung-Ethik-Religionskunde</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="AL">Wirtschaft-Arbeit-Technik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="WW">Wirtschaftswissenschaften</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Künstlerische Fächer</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="DS">Darstellendes Spiel</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="KU">Kunst</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="MU">Musik</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Sonstige</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="SP">Sport</label></li>
                        </ul>
                    </ul>
                </div>
                <input type="text" placeholder="Infotext (Optional)" name="beschreibung"><br>
                <input type="date" placeholder="Geburtstag (Optional)" name="geburtstag" Optional><br>
                <input type="submit" name="submit" value="Speichern">
            </form>
        </div>

        <?php
        $vorname = $_POST["vorname"];
        $nachname = $_POST["nachname"];
        $email = $_POST["email"];
        $position = $_POST["position"];
        $faecher_array = $_POST["chk_group"];
        $faecher = "";
        $infotext = $_POST["infotext"];
        $geburtstag = $_POST["geburtstag"];
            $servername = "sql150.your-server.de";
            $username = "c0921922321";
            $password = "AHWNiBfs2u14AAZg"; //master
            $dbname = "friedrich_gym";
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            for ($i=0; $i < count($faecher_array); $i++) {
                $faecher = $faecher.$faecher_array[$i];
                if ($i < count($faecher_array)-1) {
                    $faecher = $faecher.";";
                }
            }
            if(isset($_POST["submit"])) {
                $insert = mysqli_query($conn, "INSERT INTO lehrer (vorname, nachname, email, position, faecher, beschreibung, datum) VALUES ('{$vorname}', '{$nachname}', '{$email}', NULLIF('{$position}', ''), '{$faecher}', NULLIF('{$infotext}', ''), NULLIF('{$geburtstag}',''))");
            }
        ?>
    </body>
</html>