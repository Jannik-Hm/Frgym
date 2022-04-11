<section>
    <link rel="stylesheet" href="/new-css/lehrer.css">
    <?php
        $root = realpath($_SERVER["DOCUMENT_ROOT"]);
        require_once "$root/admin/scripts/file-upload.php";
    ?>

    <?php
        $ownedit = $GLOBALS["ownedit"];
        $disabled = $GLOBALS["disabled"];
        $edit = $GLOABLS["edit"];
        $id = $GLOBALS["id"];
        $faecher = $GLOBALS["faecher"];
        $position = $GLOBALS["position"];
    ?>

    <div class="add-input">
        <form method="POST" enctype="multipart/form-data">
            <input id="first" type="text" width="" placeholder="Vorname*" name="vorname" value="<?php echo $GLOBALS["vorname"]; ?>"  <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
            <input type="text" placeholder="Nachname*" name="nachname" value="<?php echo $GLOBALS["nachname"]; ?>" <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
            <input type="email" placeholder="Email*" name="email" value="<?php echo $GLOBALS["email"]; ?>" <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
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
            <textarea rows="10" columns="50%" placeholder="Infotext (Optional)" name="beschreibung" <?php if($disabled){echo "disabled";} ?>><?php echo $GLOBALS["infotext"]; ?></textarea><br>
            <input type="date" placeholder="Geburtstag (Optional)" name="geburtstag" value="<?php echo $GLOBALS["date"]; ?>" Optional <?php if($disabled){echo "disabled";} ?>><br>
            <?php dropzone("pictureUpload", array("jpg","jpeg","png", "webp"), "site-ressources/lehrer-bilder/", strtolower(str_replace(" ","_",$_POST["vorname"])."_".str_replace(" ","_",$_POST["nachname"])), false, false); ?>
            <style>#drop_zone{width: 90%}</style>
            <div id="preview">
                <?php
                    $file_exists = false;
                    $imgpath = "/files/site-ressources/lehrer-bilder/" . strtolower(str_replace(" ","_",$GLOBALS["vorname"])."_".str_replace(" ","_",$GLOBALS["nachname"])).".";
                    $phppath = $root.$imgpath;
                    foreach(array("jpg","jpeg","png", "webp") as $extens){
                        if (file_exists($phppath.$extens)) {
                            $imgpath = $imgpath.$extens;
                            $file_exists = true;
                            break;
                        }
                    }
                    if($file_exists){echo('<img src="'.$imgpath.'" width="300" height="auto"/>');}
                ?>
                <input type="hidden" id="deletefile" name="deletefile" value="" />
            </div><br>
            <div id="invalidfiletype" style="display:none"><p>Nur .jpg, .jpeg, .png und .webp Dateien sind erlaubt!</p></div><br>
            <?php if($file_exists){echo("<script>$('#drop_zone .popupCloseButton').show();</script>");} ?>
            <input style="cursor: pointer;" type="submit" name="submit" <?php if($disabled){echo "disabled";} ?> value="Speichern">
        </form>
    </div>

    <?php
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        if($GLOBALS["edit"]){
            confirmation("Änderungen erfolgreich!", "Der Lehrer wurde erfolgreich aktualisiert.", "Zurück zur Übersicht", "/admin/lehrer/");
        }else{
            confirmation("Hinzufügen erfolgreich!", "Der Lehrer wurde erfolgreich hinzugefügt.", "Weiteren Lehrer hinzufügen", "/admin/lehrer/add/", "Zurück zur Übersicht", "/admin/lehrer/");
        }
    ?>

    <!-- <div style='left: 0;' class='confirm'>
        <span class='helper'></span>
        <div class='scroll'>
            <div class='confirmation'>
                <h1>Änderungen erfolgreich!</h1><br>
                <p>Der Lehrer wurde erfolgreich aktualisiert.</p><br>
                <a href='/admin/lehrer/' class='back'>Zurück zur Übersicht</a>
            </div>
        </div>
    </div> -->

    <script>
            function imagePreview(fileInput) {
                if (fileInput.files && fileInput.files[0]) {
                    var fileReader = new FileReader();
                    fileReader.onload = function (event) {
                        $('#preview').html('<img src="'+event.target.result+'" width="300" height="auto"/>');
                    };
                    fileReader.readAsDataURL(fileInput.files[0]);
                    var fileName = fileInput.value; //Check of Extension
                    var extension = fileName.substring(fileName.lastIndexOf('.') + 1);
                    if ((extension == "jpg" || extension == "jpeg" || extension == "png" || extension == "webp")){
                        document.getElementById('invalidfiletype').style.display = "none";
                        document.getElementById('preview').style.display = "";
                    }else{
                        document.getElementById('invalidfiletype').style.display = "";
                        document.getElementById('preview').style.display = "none";
                    }
                }
            };
            function rmimage() { // TODO: Delete Picture button not removing picture from server and fix img replacing
                $("#drop_zone p").html("Datei hochladen");
                <?php if($GLOBALS["edit"]){echo "document.getElementById('deletefile').value = 'true';";}else{echo "document.getElementById('pictureUpload').value = '';";} ?>
                document.getElementById('preview').style.display = "none";
                document.getElementById('invalidfiletype').style.display = "none";
            }
            $("#pictureUpload").change(function () {
                imagePreview(this);
                document.getElementById('deletefile').value = 'false';
            });
            $("#drop_zone .popupCloseButton").click(function() {
                rmimage();
            })
        </script>

    <?php
        if(isset($_POST["submit"])) {
        $vorname = $_POST["vorname"];
        $nachname = $_POST["nachname"];
        $email = $_POST["email"];
        $position = $_POST["position"];
        $faecher_array = $_POST["chk_group"];
        $faecher = "";
        $infotext = $_POST["beschreibung"];
        $geburtstag = $_POST["geburtstag"];
        $conn = getsqlconnection();
        for ($i=0; $i < count($faecher_array); $i++) {
            $faecher = $faecher.$faecher_array[$i];
            if ($i < count($faecher_array)-1) {
                $faecher = $faecher.";";
            }
        }
        if($GLOBALS["edit"]){
            if(isset($_POST["submit"]) && ($disabled == false && $ownedit == false)) {
                $insert = mysqli_query($conn, "UPDATE lehrer SET vorname='{$vorname}', nachname='{$nachname}', email='{$email}', position=NULLIF('{$position}', ''), faecher='{$faecher}', beschreibung=NULLIF('{$infotext}', ''), datum=NULLIF('{$geburtstag}','') WHERE id='{$id}'");
            }elseif(isset($_POST["submit"]) && ($disabled == false && $ownedit)){
                $insert = mysqli_query($conn, "UPDATE lehrer SET faecher='{$faecher}', beschreibung=NULLIF('{$infotext}', ''), datum=NULLIF('{$geburtstag}','') WHERE id='{$id}'");
            }
        }else{
            if(isset($_POST["submit"]) && $GLOBALS["lehrer.all"] == 1) {
                $insert = mysqli_query($conn, "INSERT INTO lehrer (vorname, nachname, email, position, faecher, beschreibung, datum) VALUES ('{$vorname}', '{$nachname}', '{$email}', NULLIF('{$position}', ''), '{$faecher}', NULLIF('{$infotext}', ''), NULLIF('{$geburtstag}',''))");
            }
        }
        if ($insert) {
            echo("<script>$('.confirm').show();</script>");
            if($_POST['deletefile'] == 'true' && $file_exists){ //delete File if delete is true
                unlink($root.$imgpath);
            } else {
                uploadfile("site-ressources/lehrer-bilder/", array("jpg","jpeg","png", "webp"), "pictureUpload", strtolower(str_replace(" ","_",$vorname)."_".str_replace(" ","_",$nachname)), "lehrer.all");
            }
        }
    }
    ?>
</section>