<?php

    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/admin/scripts/admin-scripts.php";
    verifylogin();

    function dropzone($inputname) {
        echo '<form id="file_upload" enctype="multipart/form-data" method="POST">
        <input type="file" name="'.$inputname.'" id="'.$inputname.'" multiple hidden>
        <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" style="">
            <div style="display: none" onclick="event.stopPropagation();resetupload();" class="popupCloseButton">&times;</div>
            <p>Datei hochladen</p>
        </div>
        <!-- <div id="submitbtn" onclick=\'$("#file_upload").submit()\' style="display:none">
            <p>
            </p>
        </div> -->
        <input id="submitbtn" type="submit" name="submit" style="display:none">
        <script>
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
                        $("#drop_zone").attr("style", "background-color: #676565");
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
        <!-- TODO: add upload button with "onclick=\'$("#file_upload").submit()\'" -->
    </form>';
    }

    function uploadfile($dir, $accepted_files, $inputname) {
        $target_dir = "/usr/www/users/greenyr/frgym/new/files/".$dir;
        $max_filesize = 10000000;

        if(!($_FILES[$inputname]["error"] == 4)) {
            if($dir != ""){$target_dir=$target_dir."/";}
            $extension = strtolower(pathinfo(basename($_FILES[$inputname]["name"]),PATHINFO_EXTENSION));
            $targetfilename = basename($_FILES[$inputname]["name"]);
            $target_file = $target_dir . $targetfilename;
            $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $uploadOk = 1;

            // Check file size
            if ($_FILES[$inputname]["size"] > $max_filesize) {
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
                if (move_uploaded_file($_FILES[$inputname]["tmp_name"], $target_file)) {
                    return true;
                } else {
                    return false;
                }
            }
        }

    }

