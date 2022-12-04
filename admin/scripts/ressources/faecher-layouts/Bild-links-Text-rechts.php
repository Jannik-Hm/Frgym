<section style="display: flex">
    <?php
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        $dropzone_id = uniqid();
        echo('<style>#drop_zone'.$dropzone_id.' {width: 25%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
        $save1 = faecher_img_dropzone($id, $dropzone_id, "content1", array("jpg","jpeg","png", "webp"), $data[0], $viewer, $preview);
        if($viewer){
            echo '<p>'.nl2br($data[1]).'</p>';
        }else{
            echo '
            <div class="grow-wrap" style="width: 75%">
                <textarea name="content" id="content2'.$id.'" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate." disabled>'.$data[1].'</textarea>
            </div>
            ';
        }
        if(!$preview && !$viewer){
            $savefunction .= $save1[0];
            $savefunction .= ajaxsave("Bild-links-Text-rechts", '['.$save1[1].', $("#content2'.$id.'").val()]', $id);
            $donefunction = 'load = $.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();});';
            $savefunction .= "$.when(ajaxsave[\"".$id."\"], ajaxsave[\"".$dropzone_id."\"]).done(function(){".$donefunction."});";
        }
    ?>
</section>
