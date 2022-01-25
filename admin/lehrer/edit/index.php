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

        ?>
        <title>Lehrer bearbeiten - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/header.html";

            include_once "$root/admin/sites/permissions.php";

            include_once "$root/admin/no-permission.html";
        ?>

        <?php

        require_once "$root/sites/credentials.php";
        $conn = get_connection();

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

        if($lehrer_own == 1 && $_SESSION["vorname"] == $vorname && $_SESSION["nachname"] == $nachname){
            $ownedit = true;
            $disabled = false;
        }elseif($lehrer_all == 0){
            echo("<script>$('.no_perm').show();</script>");
            $disabled = true;
        };

        ?>

        <div class="add-input">
            <form method="POST">
                <input type="text" width="" placeholder="Vorname*" name="vorname" value="<?php echo $vorname; ?>"  <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
                <input type="text" placeholder="Nachname*" name="nachname" value="<?php echo $nachname; ?>" <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
                <input type="email" placeholder="Email*" name="email" value="<?php echo $email; ?>" <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
                <div class="position">
                    <label class="heading2">Position</label>
                    <ul>
                        <li><label><input type="radio" name="position" value="Lehrer*in" <?php if ($position == 'Lehrer*in') echo "checked"; ?> <?php if($disabled or $ownedit){echo("disabled");} ?>>Lehrer*in</label></li>
                        <li><label><input type="radio" name="position" value="Referendar*in" <?php if ($position == 'Referendar*in') echo "checked"; ?> <?php if($disabled or $ownedit){echo("disabled");} ?>>Referendar*in</label></li>
                        <li><label><input type="radio" name="position" value="Schulleiter*in" <?php if ($position == 'Schulleiter*in') echo "checked"; ?> <?php if($disabled or $ownedit){echo("disabled");} ?>>Schulleiter*in</label></li>
                        <li><label><input type="radio" name="position" value="stellvertretender Schulleiter*in" <?php if ($position == 'stellvertretender Schulleiter*in') echo "checked"; ?> <?php if($disabled or $ownedit){echo("disabled");} ?>>stellvertretender Schulleiter*in</label></li>
                        <li><label><input type="radio" name="position" value="Oberstufenkoordinator*in" <?php if ($position == 'Oberstufenkoordinator*in') echo "checked"; ?> <?php if($disabled or $ownedit){echo("disabled");} ?>>Oberstufenkooridnator*in</label></li>
                        <li><label><input type="radio" name="position" value="Sekretär*in" <?php if ($position == 'Sekretär*in') echo "checked"; ?> <?php if($disabled or $ownedit){echo("disabled");} ?>>Sekretär*in</label></li>
                        <br>
                    </ul>
                    <br>
                </div>
                <div class="faecher">
                <label class="heading2">Fächer</label>
                    <ul>
                        <ul>
                            <label class="heading">Sprachwissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="DE" <?php foreach ($faecher as $fach) if ($fach == "DE") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Deutsch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="EN" <?php foreach ($faecher as $fach) if ($fach == "EN") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Englisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="FR" <?php foreach ($faecher as $fach) if ($fach == "FR") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Französisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PO" <?php foreach ($faecher as $fach) if ($fach == "PO") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Polnisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="RU" <?php foreach ($faecher as $fach) if ($fach == "RU") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Russisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="SN" <?php foreach ($faecher as $fach) if ($fach == "SN") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Spanisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="TR" <?php foreach ($faecher as $fach) if ($fach == "TR") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Türkisch</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="LA" <?php foreach ($faecher as $fach) if ($fach == "LA") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Latein</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Naturwissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="MA" <?php foreach ($faecher as $fach) if ($fach == "MA") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Mathematik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="BI" <?php foreach ($faecher as $fach) if ($fach == "BI") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Biologie</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="CH" <?php foreach ($faecher as $fach) if ($fach == "CH") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Chemie</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PH" <?php foreach ($faecher as $fach) if ($fach == "PH") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Physik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="IF" <?php foreach ($faecher as $fach) if ($fach == "IF") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Informatik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="NW" <?php foreach ($faecher as $fach) if ($fach == "NW") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Naturwissenschaften</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Gesellschaftswissenschaften</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="EK" <?php foreach ($faecher as $fach) if ($fach == "EK") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Erdkunde</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="GE" <?php foreach ($faecher as $fach) if ($fach == "GE") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Geschichte</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="PB" <?php foreach ($faecher as $fach) if ($fach == "PB") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Politische Bildung</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="EG" <?php foreach ($faecher as $fach) if ($fach == "EG") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Gesellschaftswissenschaften</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="RE" <?php foreach ($faecher as $fach) if ($fach == "RE") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Evangelischer Religionsunterricht</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="RK" <?php foreach ($faecher as $fach) if ($fach == "RK") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Katholischer Religionsunterricht</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="LE" <?php foreach ($faecher as $fach) if ($fach == "LE") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Lebensgestaltung-Ethik-Religionskunde</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="AL" <?php foreach ($faecher as $fach) if ($fach == "AL") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Wirtschaft-Arbeit-Technik</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="WW" <?php foreach ($faecher as $fach) if ($fach == "WW") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Wirtschaftswissenschaften</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Künstlerische Fächer</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="DS" <?php foreach ($faecher as $fach) if ($fach == "DS") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Darstellendes Spiel</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="KU" <?php foreach ($faecher as $fach) if ($fach == "KU") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Kunst</label></li>
                            <li><label><input type="checkbox" name="chk_group[]" value="MU" <?php foreach ($faecher as $fach) if ($fach == "MU") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Musik</label></li>
                        </ul>
                        <ul>
                            <label class="heading">Sonstige</label>
                            <li><label><input type="checkbox" name="chk_group[]" value="SP" <?php foreach ($faecher as $fach) if ($fach == "SP") echo "checked" ; ?> <?php if($disabled){echo "disabled";} ?>>Sport</label></li>
                        </ul>
                    </ul>
                </div>
                <textarea rows="10" columns="50%" placeholder="Infotext (Optional)" name="beschreibung" <?php if($disabled){echo "disabled";} ?>><?php echo $infotext; ?></textarea><br>
                <input type="date" placeholder="Geburtstag (Optional)" name="geburtstag" value="<?php echo $date; ?>" Optional <?php if($disabled){echo "disabled";} ?>><br>
                <input style="cursor: pointer;" type="submit" name="submit" <?php if($disabled){echo "disabled";} ?> value="Speichern">
                <div class="page-ending"></div>
            </form>
        </div>

        <div style='left: 0;' class='confirm'>
            <span class='helper'></span>
            <div class='scroll'>
                <div class='confirmation'>
                    <h1>Änderungen erfolgreich!</h1><br>
                    <p>Der Lehrer wurde erfolgreich aktualisiert.</p><br>
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
            $id = $_GET['id'];
            $conn = get_connection();
            for ($i=0; $i < count($faecher_array); $i++) {
                $faecher = $faecher.$faecher_array[$i];
                if ($i < count($faecher_array)-1) {
                    $faecher = $faecher.";";
                }
            }
            if(isset($_POST["submit"]) && ($disabled == false && $ownedit == false)) {
                $insert = mysqli_query($conn, "UPDATE lehrer SET vorname='{$vorname}', nachname='{$nachname}', email='{$email}', position=NULLIF('{$position}', ''), faecher='{$faecher}', beschreibung=NULLIF('{$infotext}', ''), datum=NULLIF('{$geburtstag}','') WHERE id='{$id}'");
            }elseif(isset($_POST["submit"]) && ($disabled == false && $ownedit)){
                $insert = mysqli_query($conn, "UPDATE lehrer SET faecher='{$faecher}', beschreibung=NULLIF('{$infotext}', ''), datum=NULLIF('{$geburtstag}','') WHERE id='{$id}'");
            }
            if ($insert) {
                echo("<script>$('.confirm').show();</script>");
            }
            include_once "$root/sites/footer.html"
        ?>
        <div class="page-ending"></div>
    </body>
</html>