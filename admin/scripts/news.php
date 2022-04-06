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
        if(isset($_GET["id"])){
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
        }
        function disable_news_editor(){
            echo("<script>$('.no_perm').show();</script>");
            $GLOBALS["disabled"] = true;
        }
        if($GLOBALS["news.own"] == 1 || $GLOBALS["news.all"] == 1){
            $GLOBAS["disabled"] = false;
            if($edit && $GLOBALS["news.all"] == 1){
                $GLOBALS["edit"] = $edit;
            }elseif($edit && $GLOBALS["news.own"] == 1){
                if($GLOBALS["autor"] == $_SESSION["vorname"] . " " . $_SESSION["nachname"]){
                    $GLOBALS["ownedit"] = $edit;
                }else{
                    disable_news_editor();
                }
            }elseif(! $edit){
            }else{
                disable_news_editor();
            }
        }else{
            disable_news_editor();
        }
        include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/news-editor.php";
    }

?>