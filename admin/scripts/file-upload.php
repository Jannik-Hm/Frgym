<?php

    function dropzone($inputname, $accepted_files, $uploaddir, $filenameoverride = null, $multiplebool = true, $ownform = true, $lehreredit = false) {
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        verifylogin();
        if(!$lehreredit){checkperm("docs");};
        if(($GLOBALS["disabled"])){
            $disabled="disabled";
        }else{
            $disabled="";
        }
        $accept_string = "";
        foreach($accepted_files as $accepted_type) {
            $accept_string = $accept_string.".".$accepted_type.",";
        }
        if($filenameoverride == null && $multiplebool){$multiple = "multiple";$multiplearray="[]";}else{$multiple="";$multiplearray="[]";}
        if($ownform){
            echo("<section>");
            echo '<form id="file_upload" enctype="multipart/form-data" method="POST">';
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
        <input type="file" name="'.$inputname.$multiplearray.'" id="'.$inputname.'" accept="'.$accept_string.'" '.$multiple.' '.$disabled.' hidden>
        <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" style="">';
        echo '<div style="display: none" onclick="event.stopPropagation();resetupload();" class="popupCloseButton">&times;</div>';
        echo '<p>Datei hochladen</p>
        </div>
        <!-- <div id="submitbtn" onclick=\'$("#file_upload").submit()\' style="display:none">
            <p>
            </p>
        </div> -->';
        if($ownform){echo '<input id="submitbtn" type="submit" name="submitdrop" style="display:none">';}
        echo '<script>
            const dropzone = $("#drop_zone")
                // click input file field
                dropzone.on(\'click\', function () {
                $("#'.$inputname.'").trigger("click");
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
                    $("#'.$inputname.'").val("");
                }

                function onupload() {
                    var filenames = {};
                    for (var i = 0; i<$("#'.$inputname.'")[0].files.length; ++i) {
                        filenames[i] = $("#'.$inputname.'")[0].files[i].name;
                    }
                    // function transferfiles(value, index) {filenames[index] = value;}
                    // $("#'.$inputname.'")[0].files.forEach (transferfiles(value, index));
                    // var filename = $("#'.$inputname.'")[0].files[0].name;
                    // var filetype = $("#'.$inputname.'")[0].files[0].name.substr($("#'.$inputname.'")[0].files[0].name.lastIndexOf(".")+1);
                    var filenames_string = "";
                    for (const element in filenames) {
                        if (element != 0) {filenames_string += " & ";}
                        filenames_string += filenames[element].replace("."+filenames[element].substr(filenames[element].lastIndexOf(".")+1), "");
                    }
                    $("#drop_zone p").html(filenames_string);
                    // TODO: change dropzone background to signalise files were added and add icons
                    $("#submitbtn").attr("value",$("#'.$inputname.'")[0].files.length+" Datei/en freigeben");
                    $("#submitbtn").show();
                    $("#drop_zone .popupCloseButton").show();
                }

                // catch file drop and add it to input
                dropzone.on("drop", e => {
                e.preventDefault();
                let files = e.originalEvent.dataTransfer.files

                if (files.length) {
                    $("#'.$inputname.'").prop("files", files);
                    onupload();
                    // document.getElementById("file_upload").submit();
                }
                });

                // trigger file submission behavior
                $("#'.$inputname.'").on("change", function (e) {
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
        <!-- <input type="submit" name="submit" style="width: 200px; margin-left: 20px; height: auto" value="Datei freigeben"> -->
        <!-- TODO: add upload button with "onclick=\'$("#file_upload").submit()\'" -->';
    if($ownform){
        echo '</form>';
        if(isset($_POST["submitdrop"])){
            uploadfile($uploaddir, $accepted_files, $inputname, $filenameoverride);
        }
        echo("</section>");
    };
    }

    function createdir($dir) {
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        verifylogin();
        checkperm("docs");
        echo("<section style=''>");
        echo('<link rel="stylesheet" href="/new-css/form.css">');
        echo("<div class='showform' onclick=\"$('#createfolder').show();\"><p>Ordner erstellen</p></div>");
        // TODO: form with dir name
        if(($GLOBALS["disabled"])){
            $disabled="disabled";
        }else{
            $disabled="";
        }
        echo("
        <form id='createfolder' method='POST' style='display:none; width: 90%'>
            <input type='text' id='dirname' name='dirname' ".$disabled.">
            <input style='margin-left: 50px; width: 250px' type='submit' name='submitdir' value='Ordner erstellen' ".$disabled.">
        </form>
        ");
        if(isset($_POST["submitdir"]) && !($GLOBALS["disabled"])){
            $dirname = $_POST["dirname"];
            // echo(realpath($_SERVER["DOCUMENT_ROOT"]).$dir."/".$dirname);
            mkdir(realpath($_SERVER["DOCUMENT_ROOT"]).$dir."/".$dirname);
            echo("<script>$('#createfolder').hide();</script>");
            // echo("<script>window.location.reload();</script>");
            echo("<script>window.location.href = '".$_SERVER['REQUEST_URI']."';</script>");
        }
        echo("</section>");
    }

    function uploadfile($dir, $accepted_files, $inputname, $filenameoverride = null, $permneeded = "docs") {
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        verifylogin();
        checkperm($permneeded);
        if(!($GLOBALS["disabled"])){
            $target_dir = $_SERVER["DOCUMENT_ROOT"]."/files/".$dir;
            // echo $_FILES[$inputname]["name"]."<br>";
            $max_filesize = 10000000;
    
            if($filenameoverride == null){
                $fileCount = count($_FILES[$inputname]['name']);
            }else{
                $fileCount = 1;
            }
            if(!($_FILES[$inputname]["error"] == 4)) {
                if(substr($dir, -1) != "/"){$target_dir=$target_dir."/";}
                for($i = 0; $i < $fileCount; $i++){
                    $extension = strtolower(pathinfo(basename($_FILES[$inputname]["name"][$i]),PATHINFO_EXTENSION));
                    $targetfilename = basename($_FILES[$inputname]["name"][$i]);
                    if($filenameoverride != null){
                        $targetfilename = $filenameoverride.".".$extension;
                    }
                    $target_file = $target_dir . $targetfilename;
                    $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    $uploadOk = 1;
    
                    // Check file size
                    if ($_FILES[$inputname]["size"][$i] > $max_filesize) {
                        // echo "Sorry, your file is too large.";
                        $uploadOk = 0;
                    }
    
                    // Allow certain file formats
                    foreach($accepted_files as $accepted_type) {
                        if ($FileType != $accepted_type){
                            $uploadOk = 0;
                        }else{
                            $uploadOk = 1;
                            break;
                        }
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        return false;
                    // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES[$inputname]["tmp_name"][$i], $target_file)) {
                            if($i == $fileCount - 1){
                                if($permneeded != "lehrer.own" && $permneeded != "lehrer.all"){echo("<script>window.location.href = '".$_SERVER['REQUEST_URI']."';</script>");}
                                return true;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }
        }

    }

    function list_files($rootdir, $admin = false){
        if($admin){
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            verifylogin();
            checkperm("docs");
        }
        if(!($GLOBALS["disabled"])){
            $GLOBALS["admin"] = $admin;
        }
        $GLOBALS["rootdir"] = $rootdir;
        // if($admin){$GLOBALS["admin"]= true;}
        include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/list-files.php";
    }

?>