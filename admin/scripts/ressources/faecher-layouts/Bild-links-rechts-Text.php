<section style="display: flex">
    <?php
        echo('<style>#drop_zone'.'content1'.$GLOBALS["id"].',#drop_zone'.'content3'.$GLOBALS["id"].' {width: 25%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
        faecher_img_dropzone("content1", array("jpg","jpeg","png", "webp"), "site-ressources/faecher-pictures/", $GLOBALS["viewer"]);
        if($GLOBALS["viewer"]){
            echo '<p>'.nl2br($row["content2"]).'</p>';
        }else{
            echo '
            <div class="grow-wrap" style="width: 50%">
                <textarea name="content2" id="content2'.$GLOBALS["id"].'" style="text-align: justify" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate." disabled>'.$row["content1"].'</textarea>
                <input name="contenttype" type="text" value="Bild-links-Text-rechts" hidden></input>
            </div>
            ';
        }
        faecher_img_dropzone("content3", array("jpg","jpeg","png", "webp"), "site-ressources/faecher-pictures/", $GLOBALS["viewer"]);
    ?>
</section>
