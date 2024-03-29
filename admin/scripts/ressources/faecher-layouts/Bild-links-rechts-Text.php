<section style="display: flex">
    <?php
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        $dropzone_id1 = uniqid();
        $dropzone_id2 = uniqid();
        echo('<style>#drop_zone'.$dropzone_id1.',#drop_zone'.$dropzone_id2.' {width: 25%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
        $save1 = faecher_img_dropzone($id, $dropzone_id1, "content1", array("jpg","jpeg","png", "webp"), $data[0], $viewer, $preview);
        if($viewer){
            echo('
            <script>
                [[document.getElementById("img_preview_'.$dropzone_id1.'"), document.getElementById("drop_zone'.$dropzone_id1.'")], [document.getElementById("img_preview_'.$dropzone_id2.'"), document.getElementById("drop_zone'.$dropzone_id2.'")]].forEach(function(cur){
                    var img = cur[0];
                    var dropzone = cur[1];
                    imgheight = img.naturalHeight;
                    imgwidth = img.naturalWidth
                    if(imgheight > imgwidth){
                        dropzone.style.height = "200px";
                        img.style.height = "200px";
                    }else{
                        dropzone.style.width = "clamp(150px, 25vw, 400px)";
                        img.style.width = "clamp(150px, 25vw, 400px)";
                    }
                })
            </script>');
            echo '<span style="margin: 0 auto;padding: 0 10px">'.nl2br($data[1]).'</span>';
        }else{
            echo '
            <div class="grow-wrap" style="width: 50%">
                <textarea name="content" id="content2'.$id.'" style="text-align: justify" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate." disabled>'.$data[1].'</textarea>
            </div>
            ';
        }
        $save2 = faecher_img_dropzone($id, $dropzone_id2, "content3", array("jpg","jpeg","png", "webp"), $data[2], $viewer, $preview);
        if(!$preview && !$viewer){
            $savefunction .= $save1[0];
            $savefunction .= $save2[0];
            $savefunction .= ajaxsave("Bild-links-rechts-Text", '['.$save1[1].', $("#content2'.$id.'").val(), '.$save2[1].']', $id);
            $donefunction = 'load = $.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();$("textarea").each(function() {autosizetext(this)}).on("input", function() {autosizetext(this)});});';
            $savefunction .= "$.when(ajaxsave[\"".$id."\"], ajaxsave[\"".$dropzone_id1."\"], ajaxsave[\"".$dropzone_id2."\"]).done(function(){".$donefunction."});";
        }
    ?>
</section>
