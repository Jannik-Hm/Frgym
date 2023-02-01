<?php

    function list_news($admin = false) {
        $GLOBALS["admin"] = $admin;
        if($GLOBALS["admin"]==true){
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            getperm();
            verifylogin();
        }
        include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/list-news.php";
    }

    function news_editor($edit = false){
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        verifylogin();
        getperm();
        if($edit && isset($_GET["id"])){
            $conn = getsqlconnection();
            $id = $_GET['id'];
            $sql = "SELECT * FROM news WHERE id = " . $id . ";";
            $result = mysqli_query($conn,$sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $GLOBALS["titel"] = $row["titel"];
                $GLOBALS["inhalt"] = $row["inhalt"];
                $GLOBALS["autor"] = $row["autor"];
            }
        }elseif($edit && ! isset($id)){
            $edit = false;
        }
        function disable_news_editor(){
            echo("<script>$('.no_perm').show();</script>");
            $GLOBALS["disabled"] = true;
        }
        if($GLOBALS["news.own"] == 1 || $GLOBALS["news.all"] == 1){
            $GLOBAS["disabled"] = false;
            if($edit && ($GLOBALS["news.all"] == 1 || ($GLOBALS["news.own"] == 1 && $GLOBALS["autor"] == $_SESSION["user_id"]))){
                $GLOBALS["edit"] = true;
            }elseif(! $edit){
                $GLOBALS["edit"] = false;
            }else{
                disable_news_editor();
            }
        }else{
            disable_news_editor();
        }
        include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/news-editor.php";
    }

    function delete_news($id){
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        verifylogin();
        getperm();
        $conn = getsqlconnection();
        $sql = "SELECT autor FROM news WHERE id = " . $id . ";";
        $result = mysqli_query($conn,$sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $autor = $row["autor"];
        }
        if($GLOBALS["news.all"] == 1 || ($GLOBALS["news.own"] == 1 && $autor == $_SESSION["user_id"])){
            $insert = mysqli_query($conn, "DELETE FROM news WHERE id='{$id}'");
            if ($insert) {echo '<script type="text/javascript">window.location = "/admin/news/"</script>';}
        }else{
            echo("<script>$('.no_perm').show();</script>");
        }
    }

?>