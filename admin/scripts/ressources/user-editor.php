<section style="text-align: left">
    <link rel="stylesheet" href="/new-css/lehrer.css">
    <link rel="stylesheet" href="/new-css/form.css">
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
            <input id="first" type="text" width="" placeholder="Benutzername*" name="benutzername" title="Benutzername" value="<?php echo $GLOBALS["username"]; ?>"  <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
            <div style="display: flex;width: clamp(360px, 95%, 1008px);margin: auto;">
                <input style="width: 100px;margin-right: 5px;display: inline-block;" type="text" width="" placeholder="Titel" name="titel" title="Titel" value="<?php echo $GLOBALS["titel"]; ?>">
                <input style="margin-left: 5px; margin-right: 0;display: inline-block;"type="text" width="" placeholder="Vorname*" name="vorname" title="Vorname" value="<?php echo $GLOBALS["vorname"]; ?>"  <?php if($disabled or $ownedit){echo("disabled");} ?> required>
            </div>
            <br>
            <input type="text" placeholder="Nachname*" name="nachname" title="Nachname" value="<?php echo $GLOBALS["nachname"]; ?>" <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
            <input type="email" placeholder="Email*" name="email" title="Email" value="<?php echo $GLOBALS["email"]; ?>" <?php if($disabled or $ownedit){echo("disabled");} ?> required><br>
            <div class="position">
                <label class="heading2">Position</label>
                <ul>
                    <?php
                        $result = mysqli_query($conn, 'SELECT name FROM roles WHERE name != "Admin"');
                        $roles = array();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $roles[$row["name"]] = $row["name"];
                            }
                        }
                        foreach($roles as $role){
                            echo('<li><label class="customradio"><input type="radio" name="position" value="'.$role.'"'. (($position == $role) ? "checked ":"") . (($disabled or $ownedit)? "disabled":"").'><span class="radiochk"></span>'.$role.'</label></li>');
                        }
                    ?>
                    <br>
                </ul>
                <br>
            </div>
            <div class="faecher">
            <label class="heading2">Fächer</label>
                <ul>
                    <?php
                        $lehrerfaecher = array();
                        foreach ($faecher as $fach){
                            $lehrerfaecher[$fach] = true;
                        }
                        $faecherlist = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-liste.json"), true);
                        foreach($faecherlist as $fachbereich){
                            echo '
                            <ul>
                                <label class="heading">'.$fachbereich["name"].'</label>';
                                foreach($fachbereich["faecher"] as $fach){
                                    echo '<li><label class="chkbx_label"><input type="checkbox" name="chk_group[]" value="'.$fach["short"].'"'.(($lehrerfaecher[$fach["short"]])?"checked ":"").(($disabled)? "disabled":"").'><span class="chkbx"></span>'.str_replace(["Gesellschafts-wissenschaften", "<br>"],["Gesellschaftswissenschaften", " / "],$fach["name"]).'</label></li>';
                                }
                            echo '</ul>';
                        }
                    ?>
                </ul>
            </div>
            <textarea rows="10" columns="50%" placeholder="Infotext (Optional)" name="beschreibung" <?php if($disabled){echo "disabled";} ?>><?php echo $GLOBALS["infotext"]; ?></textarea><br>
            <!-- <input type="date" placeholder="Geburtstag (Optional)" name="geburtstag" value="<?php //echo $GLOBALS["date"]; ?>" Optional <?php //if($disabled){echo "disabled";} ?>><br> -->
            <?php dropzone("pictureUpload", array("jpg","jpeg","png", "webp"), "site-ressources/lehrer-bilder/", strtolower(str_replace(" ","_",$_POST["vorname"])."_".str_replace(" ","_",$_POST["nachname"])), false, false, true); ?>
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
        $username = $_POST["username"];
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
                // $insert = mysqli_query($conn, "INSERT INTO users_neu (username, vorname, nachname, email, position, faecher, beschreibung, datum) VALUES ('{$username}','{$vorname}', '{$nachname}', '{$email}', NULLIF('{$position}', ''), '{$faecher}', NULLIF('{$infotext}', ''), NULLIF('{$geburtstag}',''))");
            }
        }
        if ($insert) {
            echo("<script>$('.confirm').show();</script>");
            if($_POST['deletefile'] == 'true' && $file_exists){ //delete File if delete is true
                unlink($root.$imgpath);
            } elseif($ownedit) {
                uploadfile("site-ressources/lehrer-bilder/", array("jpg","jpeg","png", "webp"), "pictureUpload", strtolower(str_replace(" ","_",$GLOBALS["vorname"])."_".str_replace(" ","_",$GLOBALS["nachname"])), "lehrer.own");
            } else {
                uploadfile("site-ressources/lehrer-bilder/", array("jpg","jpeg","png", "webp"), "pictureUpload", strtolower(str_replace(" ","_",$vorname)."_".str_replace(" ","_",$nachname)), "lehrer.all");
            }
        }
    }
    ?>
</section>