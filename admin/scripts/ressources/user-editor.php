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
        $id = $GLOBALS["userdb"]["id"];
        $faecher = $GLOBALS["userdb"]["faecher"];
        $position = $GLOBALS["userdb"]["role"];
        require_once "$root/sites/credentials.php";
        $conn = get_connection();
    ?>
    <section style="text-align: left; width: clamp(360px, 95%, 1000px);">
        <div class="add-input">
            <form method="POST" enctype="multipart/form-data" style="margin-top: 25px">
                <?php
                    if($GLOBALS["user.administration"]){
                    echo'
                    <input type="text" width="" placeholder="Benutzername*" name="benutzername" title="Benutzername" value="'.$GLOBALS["userdb"]["username"].'" '. (($disabled or $ownedit)?"disabled":"").'><br>
                    <div style="display: flex;margin: auto;">
                        <input style="width: 100px;margin-right: 5px;display: inline-block;" type="text" width="" placeholder="Titel" name="titel" title="Titel" value="'.$GLOBALS["userdb"]["titel"].'">
                        <input style="margin-left: 5px; margin-right: 0;display: inline-block;"type="text" width="" placeholder="Vorname*" name="vorname" title="Vorname" value="'.$GLOBALS["userdb"]["vorname"].'" '. (($disabled or $ownedit)?"disabled":"").' required>
                    </div>
                    <br>
                    <input type="text" placeholder="Nachname*" name="nachname" title="Nachname" value="'.$GLOBALS["userdb"]["nachname"].'" '. (($disabled or $ownedit)?"disabled":"").' required><br>
                    <input type="email" placeholder="Email*" name="email" title="Email" value="'.$GLOBALS["userdb"]["email"].'" '. (($disabled or $ownedit)?"disabled":"").' required><br>
                    <div class="position">
                        <label class="heading2">Position</label>
                        <ul style="margin-bottom: 0">';
                            $result = mysqli_query($conn, 'SELECT name FROM roles');
                            $roles = array();
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $roles[$row["name"]] = $row["name"];
                                }
                            }
                            foreach($roles as $role){
                                echo('<li><label class="customradio"><input type="radio" name="position" value="'.$role.'"'. (($position == $role) ? "checked ":"") . (($disabled or $ownedit)? "disabled":"").'><span class="radiochk"></span>'.$role.'</label></li>');
                            }
                            echo'
                            <br>
                        </ul>
                    </div>
                    <div class="faecher">
                    <label class="heading2">Fächer</label>
                        <ul>';
                            $lehrerfaecher = array();
                            foreach (explode(";", $GLOBALS["userdb"]["faecher"]) as $fach){
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
                            echo'
                        </ul>
                    </div>
                    ';
                    }
                ?>
                <?php
                    if($ownedit){
                        echo'
                        <input type="text" name="display_vorname" placeholder="Sichtbarer Vorname" title="Sichtbarer Vorname" value="'.$GLOBALS["userdb"]["display_vorname"].'"><br>
                        <div class="grow-wrap">
                        <textarea name="beschreibung" columns="50%" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Infotext (Optional)" title="Infotext" '.(($disabled)? "disabled":"").'>'.$GLOBALS["userdb"]["infotext"].'</textarea>
                        </div>
                        <br>
                        ';
                    }
                ?>
                <!-- <textarea rows="10" columns="50%" placeholder="Infotext (Optional)" name="beschreibung" <?php //if($disabled){echo "disabled";} ?>><?php //echo $GLOBALS["infotext"]; ?></textarea><br> -->
                <!-- <input type="date" placeholder="Geburtstag (Optional)" name="geburtstag" value="<?php //echo $GLOBALS["date"]; ?>" Optional <?php //if($disabled){echo "disabled";} ?>><br> -->
                <!-- <?php //dropzone("pictureUpload", array("jpg","jpeg","png", "webp"), "site-ressources/lehrer-bilder/", strtolower(str_replace(" ","_",$_POST["vorname"])."_".str_replace(" ","_",$_POST["nachname"])), false, false, true); ?>
                <style>/* #drop_zone{width: 90%} */</style>
                <div id="preview">
                    <?php
                        // $file_exists = false;
                        // $imgpath = "/files/site-ressources/lehrer-bilder/" . strtolower(str_replace(" ","_",$GLOBALS["vorname"])."_".str_replace(" ","_",$GLOBALS["nachname"])).".";
                        // $phppath = $root.$imgpath;
                        // foreach(array("jpg","jpeg","png", "webp") as $extens){
                        //     if (file_exists($phppath.$extens)) {
                        //         $imgpath = $imgpath.$extens;
                        //         $file_exists = true;
                        //         break;
                        //     }
                        // }
                        // if($file_exists){echo('<img src="'.$imgpath.'" width="300" height="auto"/>');}
                    ?>
                    <input type="hidden" id="deletefile" name="deletefile" value="" />
                </div><br>
                <div id="invalidfiletype" style="display:none"><p>Nur .jpg, .jpeg, .png und .webp Dateien sind erlaubt!</p></div><br>
                <?php //if($file_exists){echo("<script>$('#drop_zone .popupCloseButton').show();</script>");} ?> -->
                <!-- New Dropzone -->
                <?php
                    if($ownedit){
                        $accepted_files = array("jpg","jpeg","png", "webp");
                        $accept_string = "";
                        foreach($accepted_files as $accepted_type) {
                            $accept_string = $accept_string.".".$accepted_type.",";
                        }
                        echo ('
                        <input type="file" name="pictureUpload[]" id="pictureUpload" accept="'.$accept_string.'" hidden>
                        <div id="dropzone_new" class="normal" ondragover="dragOverHandler(event);" style="">
                            <img id="img_preview" src=""></img>
                            <div style="display: none" onclick="event.stopPropagation();resetupload(\'\');" class="popupCloseButton">&times;</div>
                            <p>Bild hochladen</p>
                        </div>
                        <script>
                            var dropzone = $("#dropzone_new")
                                // click input file field
                                dropzone.on(\'click\', function () {
                                $("#pictureUpload").trigger("click");
                                })
        
                                // prevent default browser behavior
                                dropzone.on("drag dragstart dragend dragover dragenter dragleave drop", function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                })
        
                                // add visual drag information
                                    dropzone.on("dragover", function() {
                                        if(window.matchMedia("(prefers-color-scheme: dark)").matches){
                                            dropzone.attr("style", "background-color: #676565");
                                        }else{
                                            dropzone.attr("style", "background-color: rgb(174, 178, 178)");
                                        }
                                    })
                                    dropzone.on("dragleave", function() {
                                        dropzone.attr("style", "background-color: ");
                                    })
        
                                function resetupload() {
                                    dropzone.children(".popupCloseButton").hide();
                                    dropzone.children("p").html("Datei hochladen");
                                    // $("#dropzone_new").val("");
                                    $("#pictureUpload").val("");
                                }
        
                                // catch file drop and add it to input
                                dropzone.on("drop", e => {
                                    e.preventDefault();
                                    if ($("#pictureUpload")[0].getAttribute("disabled") == null) {
                                        let files = e.originalEvent.dataTransfer.files
                                        if (files.length) {
                                            $("#pictureUpload").prop("files", files);
                                            onupload();
                                        }
                                    }
                                });
        
                                // trigger file submission behavior
                                $("#pictureUpload").on("change", function (e) {
                                if (e.target.files.length) {
                                    onupload();
                                    // document.getElementById("file_upload").submit();
                                }
                                })
        
                                function dragOverHandler(ev) {
                                    // console.log("File(s) in drop zone");
        
                                    // Prevent default behavior (Prevent file from being opened)
                                    ev.preventDefault();
                                }
                                function imagePreview(fileInput) {
                                    if (fileInput.files && fileInput.files[0]) {
                                        var fileReader = new FileReader();
                                        fileReader.onload = function (event) {
                                            $("#img_preview").attr("src", event.target.result);
                                            dropzone.children("p").hide();
                                        };
                                        fileReader.readAsDataURL(fileInput.files[0]);
                                        var fileName = fileInput.value; //Check of Extension
                                        var extension = fileName.substring(fileName.lastIndexOf(".") + 1);
                                        if ((extension == "jpg" || extension == "jpeg" || extension == "png" || extension == "webp")){
                                            document.getElementById("invalidfiletype").style.display = "none";
                                            document.getElementById("img_preview").style.display = "";
                                        }else{
                                            document.getElementById("invalidfiletype").style.display = "";
                                            document.getElementById("img_preview").style.display = "none";
                                            resetupload();
                                        }
                                    }
                                };
                                function rmimage() { // TODO: Delete Picture button not removing picture from server and fix img replacing
                                    dropzone.children("p").html("Datei hochladen");
                                    document.getElementById("deletefile").value = "true";
                                    $("#img_preview").attr("src", "");
                                    dropzone.children("p").show();
                                    document.getElementById("invalidfiletype").style.display = "none";
                                }
                                dropzone.children(".popupCloseButton").click(function() {
                                    rmimage();
                                })
                        </script>
                        <div>
                        ');
                        $GLOBALS["file_exists"] = false;
                        $imgpath = "/files/site-ressources/lehrer-bilder/" . strtolower(str_replace(" ","_",$GLOBALS["userdb"]["vorname"])."_".str_replace(" ","_",$GLOBALS["userdb"]["nachname"])).".";
                        $phppath = $root.$imgpath;
                        foreach(array("jpg","jpeg","png", "webp") as $extens){
                            if (file_exists($phppath.$extens)) {
                                $imgpath = $imgpath.$extens;
                                $GLOBALS["file_exists"] = true;
                                break;
                            }
                        }
                        if (file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath)) {
                            $GLOBALS["file_exists"] = true;
                        }
                        if($GLOBALS["file_exists"]){echo('
                            <script>$("#img_preview").attr("src", "'.$imgpath.'")</script>
                            ');}
                        if(!$viewer) {
                            echo '
                                <input type="hidden" id="deletefile" name="deletefile" value="" />
                            ';
                        }
                        echo '
                        </div>
                        <div id="invalidfiletype" style="display:none;"><p>Nur .jpg, .jpeg, .png und .webp Dateien sind erlaubt!</p></div>';
                        if($GLOBALS["file_exists"]){echo("<script>dropzone.children('p').hide();$('#dropzone_new .popupCloseButton').show();</script>");}
                        echo '
                        <script>
                            function onupload() {
                                imagePreview($("#pictureUpload")[0]);';
                                if($GLOBALS["file_exists"] == "true"){echo 'document.getElementById("deletefile").value = "true";';}
                                echo '
                                dropzone.children(".popupCloseButton").show();
                            }
                        </script>';
                    }
                ?>
                <style>
                    #dropzone_new {text-align: center; border: none; padding: 0; min-width: 150px; min-height: 150px; margin: auto; border-radius: 15px; background-color: #514f4f ; position: relative}
                    #dropzone_new {cursor: pointer;}
                    #dropzone_new {display: flex;align-items: center;flex-direction: column;justify-content: center;}
                    #dropzone_new:hover {background-color: #676565}
                    #dropzone_new p {padding: 15px 0}
                    #dropzone_new .popupCloseButton {position: absolute; right: -15px; top: -15px; display: inline-block; font-weight: bold; font-size: 25px; line-height: 30px; width: 30px; height: 30px; text-align: center; background-color: rgb(122, 133, 131); border-radius: 50px; border: 3px solid #999; color: #414141;}
                    #dropzone_new .popupCloseButton:hover {background-color: #fff;}
                    /* #dropzone_new .popupCloseButton {visibility: hidden} */
                    /* #dropzone_new .popupCloseButton {visibility: visible} */
                    #dropzone_new [id*=img_preview] {width: 100%; height: auto; border-radius: 15px; object-fit: cover;}
                    #dropzone_new, #img_preview {max-width: 350px; max-height: 450px; width: fit-content;}
                    #invalidfiletype p {margin-bottom: 0}
                    @media (prefers-color-scheme: light){
                        #dropzone_new {background-color: rgb(174, 178, 178)}
                    }
                    #dropzone_new {background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='15' ry='15' stroke='%23333' stroke-width='5' stroke-dasharray='6%2c 14' stroke-dashoffset='14' stroke-linecap='square'/%3e%3c/svg%3e");}
                </style>
                <!-- End of new Dropzone -->
                <br>
                <?php
                if(!$GLOBALS["edit"]){
                    echo("
                    <input type='hidden' name='passwort' id='passwort'>
                    <input type='hidden' name='passwortorigin' id='passwortorigin'> <!-- can be removed when switch to ajax -->
                    <script>
                    function generatePassword() {
                        var length = 16,
                            charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
                            retVal = '';
                        for (var i = 0, n = charset.length; i < length; ++i) {
                            retVal += charset.charAt(Math.floor(Math.random() * n));
                        }
                        return retVal;
                    }
                    var password_gen = generatePassword();
                    $('#passwort').val(sha256(password_gen));
                    $('#passwortorigin').val(password_gen);
                    </script>
                    ");
                }
                ?>
                <input style="cursor: pointer;" type="submit" name="submit" <?php if($disabled){echo "disabled";} ?> value="Speichern">
            </form>
        </div>
    </section>

    <?php
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        if($ownedit && !$GLOBALS["user.administration"]){
            confirmation("Änderungen erfolgreich!", "Dein Profil wurde erfolgreich aktualisiert.", "Zurück zur Startseite", "/admin/");
        }elseif($GLOBALS["edit"]){
            confirmation("Änderungen erfolgreich!", "Der Benutzer wurde erfolgreich aktualisiert.", "Zurück zur Übersicht", "/admin/user/");
        }else{
            confirmation("Hinzufügen erfolgreich!", "Der Benutzer wurde erfolgreich hinzugefügt.<br>Passwort: ", "Weiteren Lehrer hinzufügen", "/admin/user/add/", "Zurück zur Übersicht", "/admin/user/");
        }
    ?>

    <?php
        if(isset($_POST["submit"])) {
        $username = $_POST["benutzername"];
        $titel = $_POST["titel"];
        $displayvorname = $_POST["display_vorname"];
        $vorname = $_POST["vorname"];
        $nachname = $_POST["nachname"];
        $email = $_POST["email"];
        $position = $_POST["position"];
        if(isset($_POST["chk_group"])){
            $faecher_array = $_POST["chk_group"];
            $faecher = "";
            for ($i=0; $i < count($faecher_array); $i++) {
                $faecher = $faecher.$faecher_array[$i];
                if ($i < count($faecher_array)-1) {
                    $faecher = $faecher.";";
                }
            }
        }
        $infotext = $_POST["beschreibung"];
        $geburtstag = $_POST["geburtstag"];
        $conn = getsqlconnection();
        $passwort = $_POST["passwort"]; //TODO: when switch to ajax change submission of plain text password
        if($GLOBALS["edit"]){
            if(isset($_POST["submit"])) {
                if($GLOBALS["user.administration"] && $ownedit){
                    $insert = mysqli_query($conn, "UPDATE users SET username='{$username}', titel=NULLIF('{$titel}', ''), vorname='{$vorname}', nachname='{$nachname}', email='{$email}', role='{$position}', faecher='{$faecher}', display_vorname=NULLIF('{$displayvorname}', ''), infotext=NULLIF('{$infotext}', '') WHERE id='{$id}'");
                }elseif($GLOBALS["user.administration"]){
                    $insert = mysqli_query($conn, "UPDATE users SET username='{$username}', titel=NULLIF('{$titel}', ''), vorname='{$vorname}', nachname='{$nachname}', email='{$email}', role='{$position}', faecher='{$faecher}' WHERE id='{$id}'");
                }else{
                    $insert = mysqli_query($conn, "UPDATE users SET display_vorname=NULLIF('{$displayvorname}', ''), infotext=NULLIF('{$infotext}', '') WHERE id='{$id}'");
                }
            }
        }else{
            if(isset($_POST["submit"]) && $GLOBALS["user.administration"]) {
                // $insert = mysqli_query($conn, "INSERT INTO lehrer (vorname, nachname, email, position, faecher, beschreibung) VALUES ('{$vorname}', '{$nachname}', '{$email}', NULLIF('{$position}', ''), '{$faecher}', NULLIF('{$infotext}', ''))");
                $insert = mysqli_query($conn, "INSERT INTO users (username, titel, display_vorname, vorname, nachname, password_hash, email, role, faecher, infotext) VALUES ('{$username}', NULLIF('{$titel}', ''), NULLIF('{$displayvorname}', ''), '{$vorname}', '{$nachname}', '{$passwort}', '{$email}', '{$position}', '{$faecher}', NULLIF('{$infotext}', ''))");
            }
        }
        if ($insert) {
            echo("<script>$('#confirmtext').html($('#confirmtext').html()+'".$_POST["passwortorigin"]."');$('.confirm').show();</script>");
            if($ownedit){
                if($_POST['deletefile'] == 'true' && $GLOBALS["file_exists"]){ //delete File if delete is true
                    unlink($root.$imgpath);
                }
                uploadfile("site-ressources/lehrer-bilder/", array("jpg","jpeg","png", "webp"), "pictureUpload", strtolower(str_replace(" ","_",$GLOBALS["userdb"]["vorname"])."_".str_replace(" ","_",$GLOBALS["userdb"]["nachname"])), "lehrer.own");
            }
        }
    }
    ?>
</section>