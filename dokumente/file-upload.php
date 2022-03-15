<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            include_once "$root/sites/head.html"

        ?>
        <title>Dokumente - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/sites/header.html";
            if(isset($_GET["dir"])){
                $dir = "/".$_GET["dir"];
            }else{
                $dir = "";
            }
            $pathworoot = "/files/document-page".$dir;

        ?>
        <style>
            form {display: flex; justify-content: center; margin: auto; width: 90%; flex-wrap: nowrap; align-items: center;}
            #drop_zone {cursor: pointer; border: none; width: 90%;  padding: 15px 0;  margin: 15px auto;  border-radius: 15px;background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='15' ry='15' stroke='%23333' stroke-width='5' stroke-dasharray='6%2c 14' stroke-dashoffset='14' stroke-linecap='square'/%3e%3c/svg%3e");}
        </style>
        <section>
        <iframe name="formreloadblock" style="display:none;"></iframe>
        <form id="file_upload" enctype="multipart/form-data" method="POST">
            <input type="file" name="file-input" id="file-input" multiple>
            <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" style="">
                <p>Datei hochladen</p>
            </div>
            <script>
                const dropzone = $('#drop_zone')
                    // click input file field
                    dropzone.on('click', function () {
                    $("#file-input").trigger("click");
                    })

                    // prevent default browser behavior
                    dropzone.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    })

                    // add visual drag information
                    // dropzone.on('dragover dragenter', function() {
                    //     $('#fieldWrapper').addClass('active');
                    // })
                    // dropzone.on('dragleave dragend', function() {
                    // $('#fieldWrapper').removeClass('active');
                    // })

                    function onupload() {
                        var filenames = {};
                        for (var i = 0; i<$("#file-input")[0].files.length; ++i) {
                            filenames[i] = $("#file-input")[0].files[i].name;
                        }
                        // function transferfiles(value, index) {filenames[index] = value;}
                        // $("#file-input")[0].files.forEach (transferfiles(value, index));
                        // var filename = $("#file-input")[0].files[0].name;
                        // var filetype = $("#file-input")[0].files[0].name.substr($("#file-input")[0].files[0].name.lastIndexOf(".")+1);
                        var filenames_string = "";
                        for (const element in filenames) {
                            if (element != 0) {filenames_string += " & ";}
                            filenames_string += filenames[element].replace("."+filenames[element].substr(filenames[element].lastIndexOf(".")+1), "");
                        }
                        $("#drop_zone p").html(filenames_string);
                        // TODO: change dropzone background to signalise files were added and add icons
                    }

                    // catch file drop and add it to input
                    dropzone.on("drop", e => {
                    e.preventDefault();
                    let files = e.originalEvent.dataTransfer.files

                    if (files.length) {
                        $("#file-input").prop("files", files);
                        onupload();
                        // document.getElementById("file_upload").submit();
                    }
                    });

                    // trigger file submission behavior
                    $("#file-input").on('change', function (e) {
                    if (e.target.files.length) {
                        onupload();
                        // document.getElementById("file_upload").submit();
                    }
                    })

                    function dragOverHandler(ev) {
                        console.log('File(s) in drop zone');

                        // Prevent default behavior (Prevent file from being opened)
                        ev.preventDefault();
                    }
            </script>
            <input type="submit" name="submit" style="width: 200px; margin-left: 20px; height: auto" value="Datei freigeben">
        </form>
        <?php
            if(isset($_POST["submit"])){ echo($_POST["test"]); echo(basename($_FILES["file-input"]["name"])); }
        ?>
        <div id="testoutput"></div>
        </section>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>