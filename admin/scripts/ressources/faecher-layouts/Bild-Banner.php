<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $dropzone_id = uniqid();
    $save1 = faecher_img_dropzone($id, $dropzone_id, "content1", array("jpg","jpeg","png", "webp"), $data[0], $viewer);
    if(!$viewer && !$preview){
        $savefunction .= $save1[0];
        $savefunction .= ajaxsave("Bild-Banner", '['.$save1[1].']', $id);
        $donefunction = 'load = $.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();});';
        $savefunction .= "$.when(ajaxsave[\"".$id."\"], ajaxsave[\"".$dropzone_id."\"]).done(function(){".$donefunction."});";
    }
    echo('<style>#drop_zone'.$dropzone_id.' {width: 100%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
?>

