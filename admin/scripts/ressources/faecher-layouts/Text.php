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
            <textarea name="content" id="content1'.$id.'" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" placeholder="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate." disabled>'.$data[0].'</textarea>
        </div>
        ';
        if(!$preview){
            $savefunction .= ajaxsave("Text", '[$("#content1'.$id.'").val()]', $id);
            $donefunction = 'load = $.post("/admin/api/faecher.php", {action: "getfachelementbyid", fach: fach, editor: true, id: id}, function(data){$("#"+id).replaceWith(data);$("#"+id).parent().dragndrop("unload");$("#"+id).parent().dragndrop();});';
            $savefunction .= "$.when(ajaxsave[\"".$id."\"]).done(function(){".$donefunction."});";
        }
    }
?>
