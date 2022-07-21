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
                        <li>Test</li>
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

    function create_segment($segmenttype) {
        $GLOBALS["id"] = uniqid();
        $GLOBALS["edit"] = true;
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
            }
        </script>';
    }

    function faecher_img_dropzone($contentnum, $accepted_files, $uploaddir) {
        $accept_string = "";
        foreach($accepted_files as $accepted_type) {
            $accept_string = $accept_string.".".$accepted_type.",";
        }
        echo '
        <style>
            #drop_zone {cursor: pointer; text-align: center; border: none; width: 100%;  padding: 15px 0;  margin: 15px auto;  border-radius: 15px; background-color: #514f4f ;background-image: url("data:image/svg+xml,%3csvg width=\'100%25\' height=\'100%25\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100%25\' height=\'100%25\' fill=\'none\' rx=\'15\' ry=\'15\' stroke=\'%23333\' stroke-width=\'5\' stroke-dasharray=\'6%2c 14\' stroke-dashoffset=\'14\' stroke-linecap=\'square\'/%3e%3c/svg%3e"); position: relative}
            #drop_zone:hover {background-color: #676565}
            #drop_zone .popupCloseButton {position: absolute; right: -15px; top: -15px; display: inline-block; font-weight: bold; font-size: 25px; line-height: 30px; width: 30px; height: 30px; text-align: center; background-color: rgb(122, 133, 131); border-radius: 50px; border: 3px solid #999; color: #414141;}
            #drop_zone .popupCloseButton:hover {background-color: #fff;}
            @media (prefers-color-scheme: light){
                #drop_zone {background-color: rgb(205, 211, 210)}
                #drop_zone:hover {background-color: rgb(174, 178, 178)}
            }
        </style>
        <input type="file" name="'.$contentnum.'picture[]" id="'.$contentnum.'picture" accept="'.$accept_string.'" hidden>
        <input type="text" name="'.$contentnum.'" id="'.$contentnum.'">
        <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" style="">';
        echo '<div style="display: none" onclick="event.stopPropagation();resetupload();" class="popupCloseButton">&times;</div>';
        echo '<p>Datei hochladen</p>
        </div>
        <!-- <div id="submitbtn" onclick=\'$("#file_upload").submit()\' style="display:none">
            <p>
            </p>
        </div> -->
        <script>
            const dropzone = $("#drop_zone")
                // click input file field
                dropzone.on(\'click\', function () {
                $("#'.$contentnum.'picture").trigger("click");
                })

                // prevent default browser behavior
                dropzone.on("drag dragstart dragend dragover dragenter dragleave drop", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                })

                // add visual drag information
                    dropzone.on("dragover", function() {
                        if(window.matchMedia("(prefers-color-scheme: dark)").matches){
                            $("#drop_zone").attr("style", "background-color: #676565");
                        }else{
                            $("#drop_zone").attr("style", "background-color: rgb(174, 178, 178)");
                        }
                    })
                    dropzone.on("dragleave", function() {
                        $("#drop_zone").attr("style", "background-color: ");
                    })

                function resetupload() {
                    $("#submitbtn").hide();
                    $("#drop_zone .popupCloseButton").hide();
                    $("#drop_zone p").html("Datei hochladen");
                    $("#'.$contentnum.'picture").val("");
                    $("#'.$contentnum.'").val("");
                }

                function onupload() {
                    var filenames = {};
                    for (var i = 0; i<$("#'.$contentnum.'picture")[0].files.length; ++i) {
                        filenames[i] = $("#'.$contentnum.'picture")[0].files[i].name;
                    }
                    var filenames_string = "";
                    for (const element in filenames) {
                        if (element != 0) {filenames_string += " & ";}
                        filenames_string += filenames[element].replace("."+filenames[element].substr(filenames[element].lastIndexOf(".")+1), "");
                    }
                    $("#drop_zone p").html(filenames_string);
                    $("#'.$contentnum.'").val(filenames[0]);
                    // TODO: change dropzone background to signalise files were added and add icons
                    $("#submitbtn").attr("value",$("#'.$contentnum.'picture")[0].files.length+" Datei/en freigeben");
                    $("#submitbtn").show();
                    $("#drop_zone .popupCloseButton").show();
                }

                // catch file drop and add it to input
                dropzone.on("drop", e => {
                e.preventDefault();
                let files = e.originalEvent.dataTransfer.files

                if (files.length) {
                    $("#'.$contentnum.'picture").prop("files", files);
                    onupload();
                    // document.getElementById("file_upload").submit();
                }
                });

                // trigger file submission behavior
                $("#'.$contentnum.'picture").on("change", function (e) {
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
        </script>
        <!-- <input type="submit" name="submit" style="width: 200px; margin-left: 20px; height: auto" value="Datei freigeben"> -->';

        // create hidden text input with file name

        if(isset($_POST["submit"])) {
            $_POST[$contentnum] = uniqid().".".pathinfo($_POST[$contentnum], PATHINFO_EXTENSION);
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/file-upload.php";
            uploadfile($uploaddir, $accepted_files, $contentnum.'picture[]', $_POST[$contentnum]);
        }

    }

    // TODO: make segments editable / updatable
    // TODO: make segments movable

?>