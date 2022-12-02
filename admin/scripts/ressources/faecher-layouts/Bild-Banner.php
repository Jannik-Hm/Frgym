<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $dropzone_id = uniqid();
    faecher_img_dropzone($id, $dropzone_id, "content1", array("jpg","jpeg","png", "webp"), $data[0], $viewer);
    if(!$viewer){
        echo '
            <input name="contenttype" type="text" value="Bild-Banner" hidden></input>
        ';
    }
    echo('<style>#drop_zone'.$dropzone_id.' {width: 100%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
?>

