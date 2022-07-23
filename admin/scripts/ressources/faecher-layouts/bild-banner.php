<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
    faecher_img_dropzone("content1", array("jpg","jpeg","png", "webp"), "site-ressources/faecher-pictures/");
?>

<style>[id*="drop_zone"] {width: 100%; min-height: 200px; background-size: 100%; margin-top: 50px;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>
<input name="contenttype" type="text" value="bild-banner" hidden></input>