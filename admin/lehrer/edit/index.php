<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            include_once "./../../sites/head.html"

        ?>
        <title>Lehrer bearbeiten - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "./../../sites/header.html"

        ?>

        <?php

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

        $sql = "SELECT * FROM lehrer WHERE id = " . $_GET['id'] . ";";
        $result = mysqli_query($conn,$sql);
        $myArray = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $faecher = explode(";", $row["faecher"]);
            $date = $row["datum"];
            $vorname = $row["vorname"];
            $nachname = $row["nachname"];
            $position = $row["position"];
            $email = $row["email"];
            $infotext = $row["beschreibung"];
        }

        ?>

        <div class="add-input">
            <form method="POST">
                <input type="text" width="" placeholder="Vorname*" name="vorname" value="<?php echo $vorname; ?>" required><br>
                <input type="text" placeholder="Nachname*" name="nachname" value="<?php echo $nachname; ?>" required><br>
                <input type="email" placeholder="Email*" name="email" value="<?php echo $email; ?>" required><br>
                <div class="position">
                    <label class="heading2">Position</label>
                    <ul>
                        <li><label><input type="radio" name="position" value="Lehrer*in" <?php if ($position == 'Lehrer*in') echo "checked"; ?>>Lehrer*in</label></li>
                        <li><label><input type="radio" name="position" value="Schulleiter*in" <?php if ($position == 'Schulleiter*in') echo "checked"; ?>>Schulleiter*in</label></li>
                        <li><label><input type="radio" name="position" value="stellvertretender Schulleiter*in" <?php if ($position == 'stellvertretender Schulleiter*in') echo "checked"; ?>>stellvertretender Schulleiter*in</label></li>
                        <li><label><input type="radio" name="position" value="Oberstufenkoordinator*in" <?php if ($position == 'Oberstufenkoordinator*in') echo "checked"; ?>>Oberstufenkooridnator*in</label></li>
                        <li><label><input type="radio" name="position" value="Sekretär*in" <?php if ($position == 'Sekretär*in') echo "checked"; ?>>Sekretär*in</label></li>
                        <br>
                    </ul>
                    <br>
                </div>
                <div class="faecher">
                <label class="heading2">Fächer</label>
                    <ul>
                        <ul>
                            <label class="heading">Sprachwissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="DE" <?php foreach ($faecher as $fach) if ($fach == "DE") echo "checked" ; ?>>Deutsch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="EN" <?php foreach ($faecher as $fach) if ($fach == "EN") echo "checked" ; ?>>Englisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="FR" <?php foreach ($faecher as $fach) if ($fach == "FR") echo "checked" ; ?>>Französisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PO" <?php foreach ($faecher as $fach) if ($fach == "PO") echo "checked" ; ?>>Polnisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="RU" <?php foreach ($faecher as $fach) if ($fach == "RU") echo "checked" ; ?>>Russisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="SN" <?php foreach ($faecher as $fach) if ($fach == "SN") echo "checked" ; ?>>Spanisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="TR" <?php foreach ($faecher as $fach) if ($fach == "TR") echo "checked" ; ?>>Türkisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="LA" <?php foreach ($faecher as $fach) if ($fach == "LA") echo "checked" ; ?>>Latein</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Naturwissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="MA" <?php foreach ($faecher as $fach) if ($fach == "MA") echo "checked" ; ?>>Mathe</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="BI" <?php foreach ($faecher as $fach) if ($fach == "BI") echo "checked" ; ?>>Biologie</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="CH" <?php foreach ($faecher as $fach) if ($fach == "CH") echo "checked" ; ?>>Chemie</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PH" <?php foreach ($faecher as $fach) if ($fach == "PH") echo "checked" ; ?>>Physik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="IF" <?php foreach ($faecher as $fach) if ($fach == "IF") echo "checked" ; ?>>Informatik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="NW" <?php foreach ($faecher as $fach) if ($fach == "NW") echo "checked" ; ?>>Naturwissenschaften</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Gesellschaftswissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="EK" <?php foreach ($faecher as $fach) if ($fach == "EK") echo "checked" ; ?>>Erdkunde</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="GE" <?php foreach ($faecher as $fach) if ($fach == "GE") echo "checked" ; ?>>Geschichte</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PB" <?php foreach ($faecher as $fach) if ($fach == "PB") echo "checked" ; ?>>Politische Bildung</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="EG" <?php foreach ($faecher as $fach) if ($fach == "EG") echo "checked" ; ?>>Gesellschaftswissenschaften</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="RE" <?php foreach ($faecher as $fach) if ($fach == "RE") echo "checked" ; ?>>Evangelischer Religionsunterricht</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="RK" <?php foreach ($faecher as $fach) if ($fach == "RK") echo "checked" ; ?>>Katholischer Religionsunterricht</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="LE" <?php foreach ($faecher as $fach) if ($fach == "LE") echo "checked" ; ?>>Lebensgestaltung-Ethik-Religionskunde</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="AL" <?php foreach ($faecher as $fach) if ($fach == "AL") echo "checked" ; ?>>Wirtschaft-Arbeit-Technik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="WW" <?php foreach ($faecher as $fach) if ($fach == "WW") echo "checked" ; ?>>Wirtschaftswissenschaften</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Künstlerische Fächer</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="DS" <?php foreach ($faecher as $fach) if ($fach == "DS") echo "checked" ; ?>>Darstellendes Spiel</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="KU" <?php foreach ($faecher as $fach) if ($fach == "KU") echo "checked" ; ?>>Kunst</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="MU" <?php foreach ($faecher as $fach) if ($fach == "MU") echo "checked" ; ?>>Musik</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Sonstige</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="SP" <?php foreach ($faecher as $fach) if ($fach == "SP") echo "checked" ; ?>>Sport</label></li>
                        </ul>
                    </ul>
                </div>
                <input type="text" placeholder="Infotext (Optional)" name="beschreibung" value="<?php echo $infotext; ?>"><br>
                <input type="date" placeholder="Geburtstag (Optional)" name="geburtstag" value="<?php echo $date; ?>" Optional><br>
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
        $infotext = $_POST["beschreibung"];
        $geburtstag = $_POST["geburtstag"];
        $id = $_GET['id'];
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
                $insert = mysqli_query($conn, "UPDATE lehrer SET vorname='{$vorname}', nachname='{$nachname}', email='{$email}', position=NULLIF('{$position}', ''), faecher='{$faecher}', beschreibung=NULLIF('{$infotext}', ''), datum=NULLIF('{$geburtstag}','') WHERE id='{$id}'");
                if ($insert) {
                    echo "
                        <div class=querycheck>
                            <p>Änderung erfolgreich!</p>
                        </div>
                        ";
                    echo '<script type="text/javascript">window.location = "/admin/lehrer/"</script>';
                }
            } //TODO: Query bearbeiten zum Update
        ?>
    </body>
</html>