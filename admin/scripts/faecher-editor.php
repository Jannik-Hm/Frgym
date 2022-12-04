<?php

    function segment_selector($elementlistid) {
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
                        function addelement(layout) {
                            load = $.post('/admin/api/faecher.php', {action: 'createelement', fach: '".$_GET["fach"]."', contenttype: layout},function(response){
                                $('".$elementlistid."').append(response);
                                $('".$elementlistid."').dragndrop('unload'); $('".$elementlistid."').dragndrop(); save_order();
                            });
                            $('.faecher-selector-popup').hide();
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
                $.post("/admin/api/faecher.php", {action: "updateorder", fach: "'.$_GET["fach"].'", positions: JSON.stringify(positions)}, function(data){});
            }
            $("'.$tableidentifier.'").dragndrop({
                onDrop: function( element, droppedElement ) {
                    save_order();
                }
            });
        </script>';

    }

    // function save_segment() {
    //     require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    //     if(isset($_POST["submit"])) {
    //         echo '<script>save_order();</script>';
    //         if(isset($_POST["picnum"])) {
    //             require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/file-upload.php";
    //             if($_POST['deletefile'] == 'true' && $_POST["file_exists"] != NULL){ //delete File if delete is true
    //                 unlink(realpath($_SERVER["DOCUMENT_ROOT"]).$_POST["imgpath"]);
    //                 $_POST[$_POST["picnum"]] = NULL;
    //             }
    //             $extension = strtolower(pathinfo(basename($_FILES[$_POST["picnum"].'picture']["name"][0]),PATHINFO_EXTENSION));
    //             if($_POST["file_exists"] == "true"){
    //                 $img_id = $_POST["old-id"];
    //             }else{
    //                 $img_id = uniqid();
    //             }
    //             foreach($GLOBALS["accepted_files"] as $accepted_type) {
    //                 if ($extension == $accepted_type){
    //                     $_POST[$_POST["picnum"]] = $img_id.".".$extension;
    //                     break;
    //                 }
    //             }
    //             uploadfile($GLOBALS["uploaddir"], $GLOBALS["accepted_files"], $_POST["picnum"].'picture', $img_id, "lehrer.own");
    //         }
    //         $insert = mysqli_query(getsqlconnection(), "UPDATE faecher SET content1=NULLIF(\"{$_POST['content1']}\", ''), content2=NULLIF(\"{$_POST['content2']}\", ''), content3=NULLIF(\"{$_POST['content3']}\", '') WHERE id=\"{$_POST['id']}\"");
    //         if ($insert) {
    //             echo("<script>window.location.href = window.location.href;</script>");
    //         }
    //     }
    // }

    // function create_segment($segmenttype, $existingid = NULL) {
    //     $GLOBALS["viewer"] = false;
    //     if(isset($existingid)){
    //         $GLOBALS["id"] = $existingid;
    //     }else{
    //         $GLOBALS["id"] = uniqid();
    //     }
    //     echo '
    //     <li style="margin-bottom: 10px; padding: 10px; padding-bottom: 40px; border: 2px solid #fff; border-radius: 15px" title="'.$segmenttype.'" id="'.$GLOBALS["id"].'">
    //         <form method="POST" enctype="multipart/form-data" > ';
    //             include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/$segmenttype.php");
    //             echo '
    //             <input name="id" type="text" value="'.$GLOBALS["id"].'" hidden></input>
    //             <input name="edit" type="checkbox" checked hidden></input>
    //             <div style="margin: auto; margin-right: 5px; display: inline-block; float: right; margin-top: 5px;">
    //                 <btn class="button" style="cursor:pointer; display: inline-block; text-align: center; box-sizing: border-box; padding: 7px 0;" onclick="deleteelement(\''.$GLOBALS["id"].'\')" id="'.$GLOBALS["id"].'delete">Löschen</btn>
    //                 <btn class="button" style="cursor:pointer; display: inline-block; text-align: center; box-sizing: border-box; padding: 7px 0;" onclick="resetedit(); edit(\''.$GLOBALS["id"].'\');" id="'.$GLOBALS["id"].'edit">Bearbeiten</btn>
    //                 <input class="button" style="cursor: pointer; display: none" type="reset" name="" onclick="resetedit()" value="Abbrechen" id="'.$GLOBALS["id"].'abort">
    //                 <input class="button"style="cursor: pointer; display: none" type="submit" name="submit" value="Speichern" id="'.$GLOBALS["id"].'save">
    //             </div>
    //         </form>
    //     </li>
    //     <script>
    //         function deleteelement(elementid) {
    //             $.ajax({
    //                 url: "/admin/scripts/ressources/faecher-remove-element.php",
    //                 type: "post",
    //                 data : {
    //                     id : elementid
    //                 },
    //                 dataType: "json",
    //                 success: function(data)
    //                 {
    //                 }
    //             });
    //             $(\'#\'+elementid).remove()
    //         }
    //         // location.reload(); return false;
    //         function edit(id) {
    //             $(\'#\'+id+\'edit\').hide();
    //             $(\'#\'+id+\'abort\').show();
    //             $(\'#\'+id+\'save\').show();
    //             $(\'[id*="\'+id+\'"][id*="content"]\').attr(\'class\', \'edit\');
    //             $(\'[id*="\'+id+\'"][id*="content"]\').removeAttr(\'disabled\');
    //         }
    //         function resetedit() {
    //             $(\'[id*="edit"]\').show();
    //             $(\'[id*="abort"]\').hide();
    //             $(\'[id*="save"]\').hide();
    //             $(\'[id*="content"]\').attr(\'class\', \'normal\');
    //             $(\'[id*="content"]\').attr(\'disabled\', true);
    //             // TODO: reset unsaved changes
    //         }
    //     </script>';
    // }

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

    function faecher_img_dropzone($segmentid, $id, $contentnum, $accepted_files, $existingfile, $viewer, $preview) { // TODO: Update to use file API + fix dropzones + proper reset to show preivew again (refactored drop-zone itself, missing upload api call)
        $accept_string = "";
        $extension_check = "";
        foreach($accepted_files as $accepted_type) {
            $extension_check .= "extension == '".$accepted_type."' || ";
            $accept_string .= ".".$accepted_type.",";
        }
        $extension_check = trim($extension_check, "|| ");
        $GLOBALS["accepted_files"] = $accepted_files;
        $uploaddir = "/site-ressources/faecher-pictures/";
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
                <input type="file" name="content" id="'.$id.'input" accept="'.$accept_string.'" hidden disabled>
            ';
        }
        echo '
        <div id="drop_zone'.$id.'" name="content" class="normal" style="">
            <img id="img_preview_'.$id.'" src=""></img>';
        if(!$viewer) {
            echo '<div style="display: none" onclick="event.stopPropagation();segment["'.$segmentid.'"]["'.$id.'"].resetupload(\''.$id.'\');" class="popupCloseButton">&times;</div>';
            echo '<p>Datei hochladen</p>';
        }
        echo '</div>';
        if(!$preview){
            if(!$viewer){
                echo '
                <script>
                    load.success(function(){
                        segment["'.$segmentid.'"]["'.$id.'"] = {id: "'.$id.'", delete: false, file_exists: false, contentnum: "'.$contentnum.'", imgpath: "", old_id: "", uploadname: "", filename: "", dropzone: $("#drop_zone'.$id.'"), fileinput: $("#'.$id.'input")};
                        // click input file field
                        segment["'.$segmentid.'"]["'.$id.'"].dropzone.on(\'click\', function () {
                            if(segment["'.$segmentid.'"].editactive){
                                segment["'.$segmentid.'"]["'.$id.'"].fileinput.trigger("click");
                            }
                        })
    
                        // prevent default browser behavior
                        segment["'.$segmentid.'"]["'.$id.'"].dropzone.on("drag dragstart dragend dragover dragenter dragleave drop", function(e) {
                            if(segment["'.$segmentid.'"].editactive){
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        })
        
                        // add visual drag information
                        segment["'.$segmentid.'"]["'.$id.'"].dropzone.on("dragover", function() {
                                if(segment["'.$segmentid.'"].editactive){
                                    if(window.matchMedia("(prefers-color-scheme: dark)").matches){
                                        segment["'.$segmentid.'"]["'.$id.'"].dropzone.attr("style", "background-color: #676565");
                                    }else{
                                        segment["'.$segmentid.'"]["'.$id.'"].dropzone.attr("style", "background-color: rgb(174, 178, 178)");
                                    }
                                }
                            })
                            segment["'.$segmentid.'"]["'.$id.'"].dropzone.on("dragleave", function() {
                                segment["'.$segmentid.'"]["'.$id.'"].dropzone.attr("style", "background-color: ");
                            })
    
                            segment["'.$segmentid.'"]["'.$id.'"].resetupload = function(id) {
                            segment["'.$segmentid.'"]["'.$id.'"].dropzone.children(".popupCloseButton").hide();
                            segment["'.$segmentid.'"]["'.$id.'"].dropzone.children("p").html("Datei hochladen");
                            $("#"+id+"input").val("");
                            $("#"+id+"").val("");
                        }
        
                        // catch file drop and add it to input
                        segment["'.$segmentid.'"]["'.$id.'"].dropzone.on("drop", e => {
                            if(segment["'.$segmentid.'"].editactive){
                                e.preventDefault();
                                if (segment["'.$segmentid.'"]["'.$id.'"].fileinput[0].getAttribute("disabled") == null) {
                                    let files = e.originalEvent.dataTransfer.files
                                    filename = files[0].name;
                                    var extension = filename.substring(filename.lastIndexOf(".") + 1).toLowerCase();
                                    if (files.length && ('.$extension_check.')) {
                                        var newfilename = (segment["'.$segmentid.'"]["'.$id.'"].id); //(segment["'.$segmentid.'"]["'.$id.'"].file_exists) ? segment["'.$segmentid.'"]["'.$id.'"].old_id :
                                        segment["'.$segmentid.'"]["'.$id.'"].filename = newfilename;
                                        segment["'.$segmentid.'"]["'.$id.'"].uploadname = newfilename+"."+extension;
                                        segment["'.$segmentid.'"]["'.$id.'"].fileinput.prop("files", files);
                                        segment["'.$segmentid.'"]["'.$id.'"].onupload("'.$id.'");
                                    }else{
                                        segment["'.$segmentid.'"]["'.$id.'"].filename = "";
                                        segment["'.$segmentid.'"]["'.$id.'"].uploadname = "";
                                    }
                                }
                            }
                        });
    
                        // trigger file submission behavior
                        segment["'.$segmentid.'"]["'.$id.'"].fileinput.on("change", function (e) {
                        if (e.target.files.length) {
                            filename = e.target.files[0].name;
                            var extension = filename.substring(filename.lastIndexOf(".") + 1).toLowerCase();
                            var newfilename = (segment["'.$segmentid.'"]["'.$id.'"].id); //(segment["'.$segmentid.'"]["'.$id.'"].file_exists) ? segment["'.$segmentid.'"]["'.$id.'"].old_id :
                            segment["'.$segmentid.'"]["'.$id.'"].filename = newfilename;
                            segment["'.$segmentid.'"]["'.$id.'"].uploadname = newfilename+"."+extension;
                            segment["'.$segmentid.'"]["'.$id.'"].onupload("'.$id.'");
                        }
                        })
                        segment["'.$segmentid.'"]["'.$id.'"].imagePreview = function(fileInput, id) {
                            if (fileInput.files && fileInput.files[0]) {
                                var fileReader = new FileReader();
                                fileReader.onload = function (event) {
                                    var imgpreview = $("#img_preview_"+id);
                                    imgpreview.attr("src", event.target.result);
                                    imgpreview.show();
                                    segment["'.$segmentid.'"]["'.$id.'"].dropzone.children("p").hide();
                                };
                                fileReader.readAsDataURL(fileInput.files[0]);
                                var fileName = fileInput.value; //Check of Extension
                                var extension = fileName.substring(fileName.lastIndexOf(".") + 1).toLowerCase();
                                if ('.$extension_check.'){
                                    document.getElementById("invalidfiletype").style.display = "none";
                                    document.getElementById("preview").style.display = "";
                                }else{
                                    document.getElementById("invalidfiletype").style.display = "";
                                    document.getElementById("preview").style.display = "none";
                                }
                            }
                        };
                        segment["'.$segmentid.'"]["'.$id.'"].rmimage = function(id) { // TODO: Delete Picture button not removing picture from server and fix img replacing
                            segment["'.$segmentid.'"]["'.$id.'"].dropzone.children("p").html("Datei hochladen");
                            segment["'.$segmentid.'"]["'.$id.'"].delete = "true";
                            $("#img_preview_"+id).hide();
                            segment["'.$segmentid.'"]["'.$id.'"].dropzone.children("p").show();
                            document.getElementById("invalidfiletype").style.display = "none";
                        }
                        segment["'.$segmentid.'"]["'.$id.'"].dropzone.children(".popupCloseButton").click(function() {
                            segment["'.$segmentid.'"]["'.$id.'"].rmimage("'.$id.'");
                        })
                    });
                </script>';
            }

            echo '<div id="preview">';
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            $GLOBALS["file_exists"] = false;
            $imgpath = "/files".$uploaddir . $existingfile;
            if ($existingfile != NULL && file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath)) {
                echo '<script>load.success(function(){segment["'.$segmentid.'"]["'.$id.'"].file_exists = true;})</script>';
                $GLOBALS["file_exists"] = true;
            }
            if($GLOBALS["file_exists"]){echo('
                <script>
                    $("#img_preview_'.$id.'").attr("src", "'.$imgpath.'");
                    load.success(function(){segment["'.$segmentid.'"]["'.$id.'"]["old_id"] = "'.pathinfo($existingfile, PATHINFO_FILENAME).'"});
                </script>
                ');}
            if(!$viewer) {
                echo '
                <script>load.success(function(){segment["'.$segmentid.'"]["'.$id.'"].imgpath = "'.$imgpath.'"});</script>
                ';
            }
            echo '</div>';
            if(!$viewer){
                echo '<div id="invalidfiletype" style="display:none;"><p>Nur .jpg, .jpeg, .png und .webp Dateien sind erlaubt!</p></div>';
                if($GLOBALS["file_exists"]){echo("<script>load.success(function(){segment['".$segmentid."']['".$id."'].dropzone.children('p').hide();$('#drop_zone".$id." .popupCloseButton').show();});</script>");}
                echo '
                <script>
                load.success(function() {
                    segment["'.$segmentid.'"]["'.$id.'"].onupload = function(id) {
                        segment["'.$segmentid.'"]["'.$id.'"].imagePreview($("#"+id+"input")[0], id);';
                        if($GLOBALS["file_exists"] == "true"){echo 'load.success(function(){segment["'.$segmentid.'"]["'.$id.'"].delete = "true";})';}
                        echo '
                        segment["'.$segmentid.'"]["'.$id.'"].dropzone.children(".popupCloseButton").show();
                    }
                });
                </script>';
                $savestring = ['var data = new FormData(); data.append("action", "file-upload"); data.append("fach", segment["'.$segmentid.'"].fach); data.append("uploaddir", "'.$uploaddir.'"); data.append("deletefile", segment["'.$segmentid.'"]["'.$id.'"].delete); data.append("existingfilename", "'. $existingfile.'"); data.append("filenameoverride", segment["'.$segmentid.'"]["'.$id.'"].filename); data.append("files[]", $("#'.$id.'input")[0].files[0]); for (var pair of data.entries()) {console.log(pair[0]); console.log(pair[1]);}; if(!(segment["'.$segmentid.'"]["'.$id.'"].file_exists && !segment["'.$segmentid.'"]["'.$id.'"].delete)){ajaxsave["'.$id.'"] = $.ajax({url: "/admin/api/file-upload.php", data: data, type: "post", processData: false, contentType: false, complete: function(success){console.log(success)}});};', '((!(segment["'.$segmentid.'"]["'.$id.'"].file_exists && !segment["'.$segmentid.'"]["'.$id.'"].delete)) ? segment["'.$segmentid.'"]["'.$id.'"].uploadname : "'.$existingfile.'")'];
                return $savestring;
            }
        }
    }

?>