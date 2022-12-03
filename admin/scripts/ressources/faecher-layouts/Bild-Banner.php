<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    // if($data[0] != NULL && $data[0] != ""){
        // $dropzone_id = pathinfo($data[0], PATHINFO_FILENAME);
    // }else{
        $dropzone_id = uniqid();
    // }
    $save1 = faecher_img_dropzone($id, $dropzone_id, "content1", array("jpg","jpeg","png", "webp"), $data[0], $viewer);
    if(!$viewer){
        echo '
            <input name="contenttype" type="text" value="Bild-Banner" hidden></input>
        ';
        if(!$preview){
            // $donefunction = 'load = $.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();});';
            // $savefunction = "$.when(function(){".$save1."}, function(){".ajaxsave("Bild-Banner", '[segment["'.$id.'"]["'.$dropzone_id.'"].uploadname]')."}).done()";
            $savefunction .= $save1;
            $savefunction .= ajaxsave("Bild-Banner", '[segment["'.$id.'"]["'.$dropzone_id.'"].uploadname]', $id);
            $donefunction = 'load = $.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();});';
            $savefunction .= "$.when(ajaxsave[\"".$id."\"], ajaxsave[\"".$dropzone_id."\"]).done(function(){".$donefunction."});";
        }
    }
    echo('<style>#drop_zone'.$dropzone_id.' {width: 100%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
?>

