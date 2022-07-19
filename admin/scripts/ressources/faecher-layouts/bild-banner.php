<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/file-upload.php";
    dropzone("pictureUpload", array("jpg","jpeg","png", "webp"), "site-ressources/faecher-pictures/", null, false, true, true);
?>
<div id="preview">
    <?php
        $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"62d6d68733b83\"");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
        $file_exists = false;
        $imgpath = "/files/site-ressources/faecher-pictures/" . $row["content1"];
        if ($row["content1"] != NULL && file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath)) {
                $file_exists = true;
        }
        if($file_exists){echo('<img src="'.$imgpath.'" width="300" height="auto"/>');}
    ?>
    <input type="hidden" id="deletefile" name="deletefile" value="" />
</div><br>
<div id="invalidfiletype" style="display:none"><p>Nur .jpg, .jpeg, .png und .webp Dateien sind erlaubt!</p></div><br>
<?php if($file_exists){echo("<script>$('#drop_zone .popupCloseButton').show();</script>");} ?>

<script>
    function imagePreview(fileInput) {
        if (fileInput.files && fileInput.files[0]) {
            var fileReader = new FileReader();
            fileReader.onload = function (event) {
                // $('#preview').html('<img src="'+event.target.result+'" width="300" height="auto"/>');
                $("#drop_zone").css("background-image", "url("+event.target.result+")");
                $("#drop_zone p").hide();
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
        $("#drop_zone").css("background-image", "url(\"data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='15' ry='15' stroke='%23333' stroke-width='5' stroke-dasharray='6%2c 14' stroke-dashoffset='14' stroke-linecap='square'/%3e%3c/svg%3e\")");
        $("#drop_zone p").show();
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
<style>#drop_zone{width: 100%; height: 200px; background-size: 100%; margin-top: 50px;}</style>

<?php
    if ($insert) {
        echo("<script>$('.confirm').show();</script>");
        if($_POST['deletefile'] == 'true' && $file_exists){ //delete File if delete is true
            unlink(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath);
        } else {
            uploadfile("site-ressources/lehrer-bilder/", array("jpg","jpeg","png", "webp"), "pictureUpload", strtolower(str_replace(" ","_",$vorname)."_".str_replace(" ","_",$nachname)), "lehrer.own");
        }
    }
?>