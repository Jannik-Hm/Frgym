<section style="display: flex">
    <?php
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        $dropzone_id = uniqid();
        echo('<style>#drop_zone'.$dropzone_id.' {width: 25%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
        if($viewer){
            echo '<span style="width: 75%;text-align: left;padding: 0 10px 0 0; margin: 0 auto;">'.nl2br($data[0]).'</span>';
        }else{
            echo '
            <div class="grow-wrap" style="width: 75%">
            <textarea name="content" id="content1'.$id.'" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate." disabled>'.$data[0].'</textarea>
            </div>
            ';
        }
        $save1 = faecher_img_dropzone($id, $dropzone_id, "content2", array("jpg","jpeg","png", "webp"), $data[1], $viewer, $preview);
        if(!$preview && !$viewer){
            $savefunction .= $save1[0];
            $savefunction .= ajaxsave("Text-links-Bild-rechts", '[$("#content1'.$id.'").val(), '.$save1[1].']', $id);
            $donefunction = 'load = $.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();$("textarea").each(function() {autosizetext(this)}).on("input", function() {autosizetext(this)});});';
            $savefunction .= "$.when(ajaxsave[\"".$id."\"], ajaxsave[\"".$dropzone_id."\"]).done(function(){".$donefunction."});";
        }
    ?>
</section>
