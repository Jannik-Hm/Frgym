<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    faecher_img_dropzone("content1", array("jpg","jpeg","png", "webp"), "site-ressources/faecher-pictures/", $GLOBALS["viewer"]);
    if(!$GLOBALS["viewer"]){
        echo '
            <input name="contenttype" type="text" value="Bild-Banner" hidden></input>
        ';
    }
    echo('<style>#drop_zone'.'content1'.$GLOBALS["id"].' {width: 100%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
?>

