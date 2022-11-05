<?php

    function segment_selector() {
        $layouts = scandir(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/");
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
                <iframe class='preview-frame' id='layout-preview' style='height: 40vh;width: 25vw;position: absolute;left: -5vw;opacity: 0.95;border-radius: 15px;' scrolling='no' src=''></iframe>
                    <ul style='list-style-type: none'>");
                    foreach ($layouts as $layout) {
                        if(is_file(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/".$layout) && $layout!="test.php"){
                            echo("
                            <li class='layout-select-btn' style='text-align: left' onclick='addelement(\"".pathinfo($layout, PATHINFO_FILENAME)."\")' onmouseover='$(\"#layout-preview\").attr(\"src\", \"/admin/scripts/ressources/faecher-layout-preview.php?layout=".pathinfo($layout, PATHINFO_FILENAME)."\");$(\"#layout-preview\").show()' onmouseout='$(\"#layout-preview\").hide()'>
                                ".pathinfo($layout, PATHINFO_FILENAME)."
                            </li>
                            ");
                        }
                    }
                    echo("
                    <script>
                        function reloaddragndrop() {
                            $('.test').dragndrop('reload');
                        }

                        function addelement(layout) {
                            $.get('/admin/scripts/ressources/faecher-layout-add.php?fach=".$_GET["fach"]."&layout='+layout,function(response){
                                $('.test').append(response);
                            });
                            $('.faecher-selector-popup').hide();
                            setTimeout(() => reloaddragndrop(), 500);
                            setTimeout(() => save_order(), 500);
                        }
                    </script>
                    </ul>
                </div>
            </div>
        </div>
        ");
    }

    function dragndrop($tableidentifier) {
        echo '
        <script src="/js/jquery.dragndrop.js"></script>
        <script>
            function save_order() {
                console.log("test");
                var positions = {};
                var i = 0;
                $("'.$tableidentifier.' li").each(function () {
                    positions[i] = {};
                    positions[i]["id"] = this.id;
                    positions[i]["index"] = $(this).index();
                    i++;
                })
                $.ajax({
                    url: "/admin/scripts/ressources/faecher-layout-order.php",
                    type: "post",
                    data : {
                        positions: JSON.stringify(positions)
                    },
                    dataType: "json",
                    success: function(data)
                    {
                    }
                });
            }

            $("'.$tableidentifier.'").dragndrop({
                onDrop: function( element, droppedElement ) {
                    save_order();
                }
            });
        </script>';

    }

    function makevisible() {
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        $result = mysqli_query(getsqlconnection(), "SELECT id, content1 FROM faecher WHERE fach=\"{$_GET["fach"]}\" AND contenttype='visibility'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row["id"];
            $content1 = $row["content1"];
        }else{
            $id = uniqid();
            mysqli_query(getsqlconnection(), "INSERT INTO faecher (id, fach, position, contenttype) VALUES (\"{$id}\", \"{$_GET['fach']}\", \"\", \"visibility\")");
        }
        if ($content1 == "visible") $checked="checked";
        echo '
        <style>
            .chkbx_label,
            .customradio {
                display: block;
                position: relative;
                padding-left: 20px!important;
                margin-bottom: 7px!important;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
            
            .chkbx_label input[type="checkbox"] {
                display: none;
            }
            
            .chkbx_label .chkbx,
            .customradio .radiochk {
                position: absolute;
                left: 2px;
                height: 15px;
                width: 15px;
                background-color: #eee;
            }
            
            .chkbx_label .chkbx {
                border-radius: 3px;
                top: 1px;
            }
            
            .customradio .radiochk {
                border-radius: 50%;
                top: 5px;
            }
            
            /* On mouse-over, add a grey background color */
            .chkbx_label:hover input[type="checkbox"] ~ .chkbx,
            .customradio:hover input[type="radio"] ~ .radiochk {
                background-color: #ccc;
            }

            /* When the checkbox is checked, add a blue background */
            .chkbx_label input[type="checkbox"]:checked ~ .chkbx,
            .customradio input[type="radio"]:checked ~ .radiochk {
                background-color: #4ba5c2;
            }
            
            /* Create the checkmark/indicator (hidden when not checked) */
            .chkbx_label .chkbx:after,
            .customradio .radiochk:after {
                content: "";
                position: absolute;
                display: none;
            }
            
            /* Show the checkmark when checked */
            .chkbx_label input[type="checkbox"]:checked ~ .chkbx:after,
            .customradio input[type="radio"]:checked ~ .radiochk:after {
                display: block;
            }

            /* Style the checkmark/indicator */
            .chkbx_label .chkbx:after {
                left: 4.25px;
                top: 2px;
                width: 3px;
                height: 6px;
                border: solid white;
                border-width: 0 3px 3px 0;
                -webkit-transform: rotate(45deg);
                -ms-transform: rotate(45deg);
                transform: rotate(45deg);
            }
        </style>
        <section style="padding: 10px; margin: auto; margin-top: 25px; margin-bottom: 25px; border-radius: 15px; width: clamp(300px, 25vw, 600px);background-color: var(--inputbackground);">
            <form method="POST" enctype="multipart/form-data" style="display: inline-flex">
                <label class="chkbx_label" style="margin: auto; width: fit-content; margin-right: 15px"><input type="checkbox" name="content1" value="visible"'.$checked.'><span class="chkbx"></span>Öffentlich sichtbar</label>
                <input name="id" type="hidden" value="'.$id.'"></input>
                <input name="contenttype" type="hidden" value="visibility"></input>
                <input class="button"style="cursor: pointer; margin-left: 15px" type="submit" name="submit" value="Speichern">
            </form>
        </section>
        ';
    }

    function save_segment() {
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        if(isset($_POST["submit"])) {
            echo '<script>save_order();</script>';
            if(isset($_POST["picnum"])) {
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/file-upload.php";
                if($_POST['deletefile'] == 'true' && $_POST["file_exists"] != NULL){ //delete File if delete is true
                    unlink(realpath($_SERVER["DOCUMENT_ROOT"]).$_POST["imgpath"]);
                    $_POST[$_POST["picnum"]] = NULL;
                }
                $extension = strtolower(pathinfo(basename($_FILES[$_POST["picnum"].'picture']["name"][0]),PATHINFO_EXTENSION));
                if($_POST["file_exists"] == "true"){
                    $img_id = $_POST["old-id"];
                }else{
                    $img_id = uniqid();
                }
                foreach($GLOBALS["accepted_files"] as $accepted_type) {
                    if ($extension == $accepted_type){
                        $_POST[$_POST["picnum"]] = $img_id.".".$extension;
                        break;
                    }
                }
                uploadfile($GLOBALS["uploaddir"], $GLOBALS["accepted_files"], $_POST["picnum"].'picture', $img_id, "lehrer.own");
            }
            $insert = mysqli_query(getsqlconnection(), "UPDATE faecher SET content1=NULLIF(\"{$_POST['content1']}\", ''), content2=NULLIF(\"{$_POST['content2']}\", ''), content3=NULLIF(\"{$_POST['content3']}\", '') WHERE id=\"{$_POST['id']}\"");
            if ($insert) {
                echo("<script>window.location.href = window.location.href;</script>");
            }
        }
    }

    function create_segment($segmenttype, $existingid = NULL) {
        $GLOBALS["viewer"] = false;
        if(isset($existingid)){
            $GLOBALS["id"] = $existingid;
        }else{
            $GLOBALS["id"] = uniqid();
        }
        echo '
        <li style="margin-bottom: 10px; padding: 10px; padding-bottom: 40px; border: 2px solid #fff; border-radius: 15px" title="'.$segmenttype.'" id="'.$GLOBALS["id"].'">
            <form method="POST" enctype="multipart/form-data" > ';
                include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/$segmenttype.php");
                echo '
                <input name="id" type="text" value="'.$GLOBALS["id"].'" hidden></input>
                <input name="edit" type="checkbox" checked hidden></input>
                <div style="margin: auto; margin-right: 5px; display: inline-block; float: right; margin-top: 5px;">
                    <btn class="button" style="cursor:pointer; display: inline-block; text-align: center; box-sizing: border-box; padding: 7px 0;" onclick="deleteelement(\''.$GLOBALS["id"].'\')" id="'.$GLOBALS["id"].'delete">Löschen</btn>
                    <btn class="button" style="cursor:pointer; display: inline-block; text-align: center; box-sizing: border-box; padding: 7px 0;" onclick="resetedit(); edit(\''.$GLOBALS["id"].'\');" id="'.$GLOBALS["id"].'edit">Bearbeiten</btn>
                    <input class="button" style="cursor: pointer; display: none" type="reset" name="" onclick="resetedit()" value="Abbrechen" id="'.$GLOBALS["id"].'abort">
                    <input class="button"style="cursor: pointer; display: none" type="submit" name="submit" value="Speichern" id="'.$GLOBALS["id"].'save">
                </div>
            </form>
        </li>
        <script>
            function deleteelement(elementid) {
                $.ajax({
                    url: "/admin/scripts/ressources/faecher-remove-element.php",
                    type: "post",
                    data : {
                        id : elementid
                    },
                    dataType: "json",
                    success: function(data)
                    {
                    }
                });
                $(\'#\'+elementid).remove()
            }
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

    function show_segment($segmenttype, $existingid) {
        $GLOBALS["id"] = $existingid;
        echo '
        <style>
            textarea {height: 1em}
        </style>
        <li style="padding: 10px;" title="'.$segmenttype.'" id="'.$GLOBALS["id"].'"
            <form>';
            $GLOBALS["viewer"] = true;
            include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/$segmenttype.php");
            echo '
            </form>
        </li>';
    }

    function faecher_img_dropzone($contentnum, $accepted_files, $uploaddir, $viewer) {
        $accept_string = "";
        foreach($accepted_files as $accepted_type) {
            $accept_string = $accept_string.".".$accepted_type.",";
        }
        $GLOBALS["accepted_files"] = $accepted_files;
        $GLOBALS["uploaddir"] = $uploaddir;
        echo '
        <style>
            [id*=drop_zone] {text-align: center; border: none; width: 100%;  padding: 0;  border-radius: 15px; background-color: none ; position: relative}
            [id*=drop_zone].edit {cursor: pointer;}
            [id*=drop_zone].edit:hover {background-color: #676565}
            [id*=drop_zone] p {padding: 15px 0}
            [id*=drop_zone] .popupCloseButton {position: absolute; right: -15px; top: -15px; display: inline-block; font-weight: bold; font-size: 25px; line-height: 30px; width: 30px; height: 30px; text-align: center; background-color: rgb(122, 133, 131); border-radius: 50px; border: 3px solid #999; color: #414141;}
            [id*=drop_zone] .popupCloseButton:hover {background-color: #fff;}
            [id*=drop_zone] .popupCloseButton {visibility: hidden}
            [id*=drop_zone].edit .popupCloseButton {visibility: visible}
            [id*=drop_zone] [id*=img_preview] {width: 100%; height: auto; border-radius: 15px; object-fit: cover;}
            #invalidfiletype p {margin-bottom: 0}
            @media (prefers-color-scheme: light){
                [id*=drop_zone] {background-color: rgb(205, 211, 210)}
                [id*=drop_zone].edit:hover {background-color: rgb(174, 178, 178)}
            }
        </style>';
        if(!$viewer) {
            echo '
                <style>[id*=drop_zone] {background-image: url("data:image/svg+xml,%3csvg width=\'100%25\' height=\'100%25\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100%25\' height=\'100%25\' fill=\'none\' rx=\'15\' ry=\'15\' stroke=\'%23fff\' stroke-width=\'5\' stroke-dasharray=\'6%2c 14\' stroke-dashoffset=\'14\' stroke-linecap=\'square\'/%3e%3c/svg%3e");}</style>
                <input type="file" name="'.$contentnum.'picture[]" id="'.$contentnum.$GLOBALS["id"].'picture" accept="'.$accept_string.'" hidden disabled>
                <input type="text" name="picnum" value="'.$contentnum.'" hidden></input>
                <input type="text" name="file_exists" id="'.$contentnum.$GLOBALS["id"].'file_exists" value="'.$contentnum.'" hidden></input>
            ';
        }
        echo '
        <div id="drop_zone'.$contentnum.$GLOBALS["id"].'" class="normal" ondragover="dragOverHandler(event);" style="">
        <img id="img_preview_'.$contentnum.$GLOBALS["id"].'" src=""></img>';
        if(!$viewer) {
            echo '<div style="display: none" onclick="event.stopPropagation();resetupload(\''.$contentnum.$GLOBALS["id"].'\');" class="popupCloseButton">&times;</div>';
            echo '<p>Datei hochladen</p>';
        }
        echo '</div>';
        if($viewer){
            echo '
            <!-- <div id="submitbtn" onclick=\'$("#file_upload").submit()\' style="display:none">
                <p>
                </p>
            </div> -->';
    
            echo '
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
    
                    function resetupload(id) {
                        dropzone.children(".popupCloseButton").hide();
                        dropzone.children("p").html("Datei hochladen");
                        $("#"+id+"picture").val("");
                        $("#"+id+"").val("");
                    }
    
                    // catch file drop and add it to input
                    dropzone.on("drop", e => {
                        e.preventDefault();
                        if ($("#'.$contentnum.$GLOBALS["id"].'picture")[0].getAttribute("disabled") == null) {
                            let files = e.originalEvent.dataTransfer.files
                            if (files.length) {
                                $("#'.$contentnum.$GLOBALS["id"].'picture").prop("files", files);
                                onupload("'.$contentnum.$GLOBALS["id"].'");
                            }
                        }
                    });
    
                    // trigger file submission behavior
                    $("#'.$contentnum.$GLOBALS["id"].'picture").on("change", function (e) {
                    if (e.target.files.length) {
                        onupload("'.$contentnum.$GLOBALS["id"].'");
                        // document.getElementById("file_upload").submit();
                    }
                    })
    
                    function dragOverHandler(ev) {
                        // console.log("File(s) in drop zone");
    
                        // Prevent default behavior (Prevent file from being opened)
                        ev.preventDefault();
                    }
                    function imagePreview(fileInput, id) {
                        if (fileInput.files && fileInput.files[0]) {
                            var fileReader = new FileReader();
                            fileReader.onload = function (event) {
                                $("#img_preview_"+id).attr("src", event.target.result);
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
                    function rmimage(id) { // TODO: Delete Picture button not removing picture from server and fix img replacing
                        dropzone.children("p").html("Datei hochladen");
                        document.getElementById(id+"deletefile").value = "true";
                        $("#img_preview_"+id).attr("src", "");
                        dropzone.children("p").show();
                        document.getElementById("invalidfiletype").style.display = "none";
                    }
                    dropzone.children(".popupCloseButton").click(function() {
                        rmimage("'.$contentnum.$GLOBALS["id"].'");
                    })
            </script>';
        }

        echo '<div id="preview">';
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
        echo '<script>$("#'.$contentnum.$GLOBALS["id"].'file_exists").val("false")</script>';
        $GLOBALS["file_exists"] = false;
        $imgpath = "/files/site-ressources/faecher-pictures/" . $row[$contentnum];
        if ($row[$contentnum] != NULL && file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath)) {
            echo '<script>$("#'.$contentnum.$GLOBALS["id"].'file_exists").val("true")</script>';
            $GLOBALS["file_exists"] = true;
        }
        if($GLOBALS["file_exists"]){echo('
            <script>$("#img_preview_'.$contentnum.$GLOBALS["id"].'").attr("src", "'.$imgpath.'")</script>
            <input type="hidden" name="old-id" value='.str_replace(".".strtolower(pathinfo(basename($row[$contentnum]),PATHINFO_EXTENSION)), "", $row[$contentnum]).'></input>
            ');}
        if(!$viewer) {
            echo '
                <input type="hidden" id="'.$contentnum.$GLOBALS["id"].'deletefile" name="deletefile" value="" />
                <input type="hidden" name="imgpath" value="'.$imgpath.'" />
            ';
        }
        echo '</div>';
        if(!$viewer){
            echo '<div id="invalidfiletype" style="display:none;"><p>Nur .jpg, .jpeg, .png und .webp Dateien sind erlaubt!</p></div>';
            if($GLOBALS["file_exists"]){echo("<script>dropzone.children('p').hide();$('#drop_zone".$contentnum.$GLOBALS["id"]." .popupCloseButton').show();</script>");}
            echo '
            <script>
                function onupload(id) {
                    imagePreview($("#"+id+"picture")[0], id);';
                    if($GLOBALS["file_exists"] == "true"){echo 'document.getElementById(id+"deletefile").value = "true";';}
                    echo '
                    dropzone.children(".popupCloseButton").show();
                }
            </script>';
        }


    }

?>