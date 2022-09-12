<section style="display: flex">
    <?php
        echo('<style>#drop_zone'.'content2'.$GLOBALS["id"].' {width: 25%; min-height: 200px; background-size: 100%;} [id*=drop_zone] [id*=img_preview] {max-height: 400px; }</style>');
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
        if($GLOBALS["viewer"]){
            echo '<p>'.nl2br($row["content2"]).'</p>';
        }else{
            echo '
            <div class="grow-wrap" style="width: 75%">
            <textarea name="content1" id="content1'.$GLOBALS["id"].'" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate." disabled>'.$row["content1"].'</textarea>
            <input name="contenttype" type="text" value="Bild-links-Text-rechts" hidden></input>
            </div>
            ';
        }
        faecher_img_dropzone("content2", array("jpg","jpeg","png", "webp"), "site-ressources/faecher-pictures/", $GLOBALS["viewer"]);
    ?>
</section>
