<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    if($GLOBALS["viewer"]){
        echo '<p style="font-size: 40px; height: 40px;">'.nl2br($row["content1"]).'</p>';
    }else{
        echo '
        <div class="grow-wrap">
            <textarea name="content1" id="content1'.$GLOBALS["id"].'" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" style="font-size: 40px; height: 40px; box-sizing: content-box;" placeholder="Lorem Ipsum Dolor" disabled>'.$row["content1"].'</textarea>
            <input name="contenttype" type="text" value="Titel" hidden></input>
        </div>
        ';
    }
?>