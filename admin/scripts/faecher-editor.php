<?php

    function segment_selector() {
        echo("
        <link rel='stylesheet' href='/new-css/faecher.css'>
        <section id='faecher-selector'>
            <btn onclick='$(\".faecher-selector-popup\").show()' title='Segment hinzufügen' style=''>+</btn>
        </section>
        <div style='left: 0;' onclick=\"event.stopPropagation();$('.faecher-selector-popup').hide()\" class='faecher-selector-popup'>
        <span class='helper'></span>
            <div onclick=\"event.stopPropagation();\" class='scroll'>
                <div onclick=\"event.stopPropagation();$('.faecher-selector-popup').hide()\" class='popupCloseButton'>&times;</div>
                <div class='faecher-selector-popup-list'>
                    <ul>
                        <li onmouseover='show iframe with link (js)' onmouseout='hide iframe (js)'>Test</li>
                    </ul>
                </div>
            </div>
        </div>
        ");
        // TODO: add popup Selector listing all possible layouts
    }

    // Question: live save or save btn?

    // TODO: Dropzone for pictures with "placeholder" img as background

    function save_segment() {
        // TODO: save segment to individual DB entry
        // for every segment save to DB

        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        if(isset($_POST["submit"])) {
            if(isset($_POST["update"])){
                echo("update query");
                // TODO: add edit feature
                $insert = mysqli_query(getsqlconnection(), "UPDATE faecher SET content1=NULLIF(\"{$_POST['content1']}\", '') content2=NULLIF(\"{$_POST['content2']}\", '') content3=NULLIF(\"{$_POST['content3']}\", '') WHERE id=\"{$_POST['id']}\"");
            }else{
                $insert = mysqli_query(getsqlconnection(), "INSERT INTO faecher (id, fach, position, contenttype, content1, content2, content3) VALUES (\"{$_POST['id']}\", \"{$_GET['fach']}\", \"\", \"{$_POST['contenttype']}\", NULLIF(\"{$_POST['content1']}\", ''), NULLIF(\"{$_POST['content2']}\", ''), NULLIF(\"{$_POST['content3']}\", ''))");
            }
            if ($insert) {
            // confirm action
            }
        }
    }

    function create_segment($segmenttype, $existingid = NULL) {
        if(isset($existingid)){
            $GLOBALS["id"] = $existingid;
        }else{
            $GLOBALS["id"] = uniqid();
        }
        echo '
        <li style="margin-bottom: 40px;" id="'.$GLOBALS["id"].'">
            <form method="POST" enctype="multipart/form-data" > ';
                include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/$segmenttype.php");
        echo '
                <input name="id" type="text" value="'.$GLOBALS["id"].'" hidden></input>
                <input name="update" type="checkbox" hidden></input>
                <input name="edit" type="checkbox" checked hidden></input>
                <div style="margin: auto; margin-right: 5px; display: inline-block; float: right; margin-top: 5px;">
                    <btn style="cursor:pointer; border: 1px solid #000; width: 80px; display: inline-block; text-align: center;" onclick="resetedit(); edit(\''.$GLOBALS["id"].'\');" id="'.$GLOBALS["id"].'edit">Bearbeiten</btn>
                    <btn style="cursor:pointer; border: 1px solid #000; width: 80px; display: inline-block; text-align: center; display: none" onclick="resetedit()" id="'.$GLOBALS["id"].'abort">Abbrechen</btn>
                    <input style="cursor: pointer; display: none" type="submit" name="submit" value="Speichern" id="'.$GLOBALS["id"].'save">
                </div>
            </form>
        </li>
        <script>
            // location.reload(); return false;
            function edit(id) {
                $(\'#\'+id+\'edit\').hide();
                $(\'#\'+id+\'abort\').show();
                $(\'#\'+id+\'save\').show();
                $(\'[id*="\'+id+\'"][id*="content"]\').attr(\'class\', \'edit\');
                $(\'[id*="\'+id+\'"][id*="content"]\').removeAttr(\'disabled\');
            }
            function resetedit() {
                $(\'[id*="edit"]\').show();
                $(\'[id*="abort"]\').hide();
                $(\'[id*="save"]\').hide();
                $(\'[id*="content"]\').attr(\'class\', \'normal\');
                $(\'[id*="content"]\').attr(\'disabled\', true);
                // TODO: reset unsaved changes
            }
        </script>';
    }

    function faecher_img_dropzone($contentnum, $accepted_files, $uploaddir) {
        $accept_string = "";
        foreach($accepted_files as $accepted_type) {
            $accept_string = $accept_string.".".$accepted_type.",";
        }
        // TODO: adjust css when normal
        echo '
        <style>
            [id*=drop_zone] {text-align: center; border: none; width: 100%;  padding: 0;  margin: 15px auto;  border-radius: 15px; background-color: #514f4f ;background-image: url("data:image/svg+xml,%3csvg width=\'100%25\' height=\'100%25\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100%25\' height=\'100%25\' fill=\'none\' rx=\'15\' ry=\'15\' stroke=\'%23333\' stroke-width=\'5\' stroke-dasharray=\'6%2c 14\' stroke-dashoffset=\'14\' stroke-linecap=\'square\'/%3e%3c/svg%3e"); position: relative}
            [id*=drop_zone].edit {cursor: pointer;}
            [id*=drop_zone].edit:hover {background-color: #676565}
            [id*=drop_zone] p {padding: 15px 0}
            [id*=drop_zone] .popupCloseButton {position: absolute; right: -15px; top: -15px; display: inline-block; font-weight: bold; font-size: 25px; line-height: 30px; width: 30px; height: 30px; text-align: center; background-color: rgb(122, 133, 131); border-radius: 50px; border: 3px solid #999; color: #414141;}
            [id*=drop_zone] .popupCloseButton:hover {background-color: #fff;}
            [id*=drop_zone] .popupCloseButton {visibility: hidden}
            [id*=drop_zone].edit .popupCloseButton {visibility: visible}
            [id*=drop_zone] [id*=img_preview] {width: 100%; height: auto; border-radius: 15px; object-fit: cover;}
            @media (prefers-color-scheme: light){
                [id*=drop_zone] {background-color: rgb(205, 211, 210)}
                [id*=drop_zone]:hover {background-color: rgb(174, 178, 178)}
            }
        </style>
        <input type="file" name="'.$contentnum.'picture[]" id="'.$contentnum.$GLOBALS["id"].'picture" accept="'.$accept_string.'" hidden disabled>
        <input type="text" name="'.$contentnum.'" id="'.$contentnum.$GLOBALS["id"].'" hidden disabled>
        <div id="drop_zone'.$contentnum.$GLOBALS["id"].'" class="normal" ondragover="dragOverHandler(event);" style="">
        <img id="img_preview_'.$contentnum.$GLOBALS["id"].'" src=""></img>';
        echo '<div style="display: none" onclick="event.stopPropagation();resetupload();" class="popupCloseButton">&times;</div>';
        echo '<p>Datei hochladen</p>
        </div>
        <!-- <div id="submitbtn" onclick=\'$("#file_upload").submit()\' style="display:none">
            <p>
            </p>
        </div> -->
        <script>
            var dropzone = $("#drop_zone'.$contentnum.$GLOBALS["id"].'")
                // click input file field
                dropzone.on(\'click\', function () {
                $("#'.$contentnum.$GLOBALS["id"].'picture").trigger("click");
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
                    $("#submitbtn").hide();
                    dropzone.children(".popupCloseButton").hide();
                    dropzone.children("p").html("Datei hochladen");
                    $("#'.$contentnum.$GLOBALS["id"].'picture").val("");
                    $("#'.$contentnum.$GLOBALS["id"].'").val("");
                }

                function onupload() {
                    var filenames = {};
                    for (var i = 0; i<$("#'.$contentnum.$GLOBALS["id"].'picture")[0].files.length; ++i) {
                        filenames[i] = $("#'.$contentnum.$GLOBALS["id"].'picture")[0].files[i].name;
                    }
                    var filenames_string = "";
                    for (const element in filenames) {
                        if (element != 0) {filenames_string += " & ";}
                        filenames_string += filenames[element].replace("."+filenames[element].substr(filenames[element].lastIndexOf(".")+1), "");
                    }
                    dropzone.children("p").html(filenames_string);
                    $("#'.$contentnum.$GLOBALS["id"].'").val(filenames[0]);
                    // TODO: change dropzone background to signalise files were added and add icons
                    // $("#submitbtn").attr("value",$("#'.$contentnum.$GLOBALS["id"].'picture")[0].files.length+" Datei/en freigeben");
                    // $("#submitbtn").show();
                    dropzone.children(".popupCloseButton").show();
                }

                // catch file drop and add it to input
                dropzone.on("drop", e => {
                    e.preventDefault();
                    if ($("#'.$contentnum.$GLOBALS["id"].'picture")[0].getAttribute("disabled") == null) {
                        let files = e.originalEvent.dataTransfer.files
                        if (files.length) {
                            $("#'.$contentnum.$GLOBALS["id"].'picture").prop("files", files);
                            onupload();
                            // document.getElementById("file_upload").submit();
                        }
                    }
                });

                // trigger file submission behavior
                $("#'.$contentnum.$GLOBALS["id"].'picture").on("change", function (e) {
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
                            // $("#preview").html("<img src=""+event.target.result+"" width="300" height="auto"/>");
                            $("#img_preview_'.$contentnum.$GLOBALS["id"].'").attr("src", event.target.result);
                            // dropzone.css("background-image", "url("+event.target.result+")");
                            dropzone.children("p").hide();
                        };
                        fileReader.readAsDataURL(fileInput.files[0]);
                        var fileName = fileInput.value; //Check of Extension
                        var extension = fileName.substring(fileName.lastIndexOf(".") + 1);
                        if ((extension == "jpg" || extension == "jpeg" || extension == "png" || extension == "webp")){
                            document.getElementById("invalidfiletype").style.display = "none";
                            document.getElementById("preview").style.display = "";
                        }else{
                            document.getElementById("invalidfiletype").style.display = "";
                            document.getElementById("preview").style.display = "none";
                        }
                    }
                };
                function rmimage() { // TODO: Delete Picture button not removing picture from server and fix img replacing
                    dropzone.children("p").html("Datei hochladen");
                    document.getElementById("deletefile").value = "true";
                    $("#img_preview_'.$contentnum.$GLOBALS["id"].'").attr("src", "");
                    dropzone.children("p").show();
                    document.getElementById("invalidfiletype").style.display = "none";
                }
                $("#'.$contentnum.$GLOBALS["id"].'picture").change(function () {
                    imagePreview(this);
                    document.getElementById("deletefile").value = "false";
                });
                dropzone.children(".popupCloseButton").click(function() {
                    rmimage();
                })
        </script>
        <!-- <input type="submit" name="submit" style="width: 200px; margin-left: 20px; height: auto" value="Datei freigeben"> -->';

        echo '<div id="preview">';
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
                $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                }
                $GLOBALS["file_exists"] = false;
                $imgpath = "/files/site-ressources/faecher-pictures/" . $row["content1"];
                if ($row["content1"] != NULL && file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath)) {
                    $GLOBALS["file_exists"] = true;
                }
                if($GLOBALS["file_exists"]){echo('<script>$("#img_preview_'.$contentnum.$GLOBALS["id"].'").attr("src", "'.$imgpath.'")</script>');}
                echo '
            <input type="hidden" id="deletefile" name="deletefile" value="" />
        </div><br>
        <div id="invalidfiletype" style="display:none"><p>Nur .jpg, .jpeg, .png und .webp Dateien sind erlaubt!</p></div><br>';
        if($GLOBALS["file_exists"]){echo("<script>dropzone.children('p').hide();$('#drop_zone".$contentnum.$GLOBALS["id"]." .popupCloseButton').show();</script>");}

        // create hidden text input with file name

        if(isset($_POST["submit"])) {
            $img_id = uniqid();
            $_POST[$contentnum] = $img_id.".".pathinfo($_POST[$contentnum], PATHINFO_EXTENSION);
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/file-upload.php";
            if($_POST['deletefile'] == 'true' && $GLOBALS["file_exists"]){ //delete File if delete is true
                unlink(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath);
                $_POST[$contentnum] = NULL;
            } else {
                uploadfile($uploaddir, $accepted_files, $contentnum.'picture', $img_id, "lehrer.own");
            }
        }

    }

    // TODO: make segments editable / updatable
    // TODO: make segments movable

?>