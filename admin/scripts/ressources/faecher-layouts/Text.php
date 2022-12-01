<?php
    // require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    // $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    if($viewer){
        echo '<p>'.nl2br($data[0]).'</p>';
    }else{
        echo '
        <div class="grow-wrap">
            <textarea name="content1" id="content1'.$id.'" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate." disabled>'.$data[0].'</textarea>
            <input name="contenttype" type="text" value="Text" hidden></input>
        </div>
        ';
        if(!$preview){
            $savefunction .= ajaxsave("Text", '[$("#content1'.$id.'").val()]');
        }
    }
?>
