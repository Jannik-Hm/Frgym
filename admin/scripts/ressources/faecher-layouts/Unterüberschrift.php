<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    if($viewer){
        echo '<span style="font-size: 30px; height: 30px;">'.nl2br($data[0]).'</span>';
    }else{
        echo '
        <div class="grow-wrap">
            <textarea name="content" id="content1'.$id.'" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" style="font-size: 30px; height: 30px; box-sizing: content-box;" placeholder="Lorem Ipsum Dolor" disabled>'.$data[0].'</textarea>
        </div>
        ';
        if(!$preview){
            $savefunction .= ajaxsave("Unterüberschrift", '[$("#content1'.$id.'").val()]', $id);
            $donefunction = 'load = $.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();$("textarea").each(function() {autosizetext(this)}).on("input", function() {autosizetext(this)});});';
            $savefunction .= "$.when(ajaxsave[\"".$id."\"]).done(function(){".$donefunction."});";
        }
    }
?>