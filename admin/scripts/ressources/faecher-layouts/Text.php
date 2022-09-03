<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    if($GLOBALS["viewer"]){
        echo '<p>'.nl2br($row["content1"]).'</p>';
    }else{
        echo '
        <div class="grow-wrap">
            <textarea name="content1" id="content1'.$GLOBALS["id"].'" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate." disabled>'.$row["content1"].'</textarea>
            <input name="contenttype" type="text" value="Text" hidden></input>
        </div>
        ';
    }
?>
