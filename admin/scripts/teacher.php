<?php

    function teacher_editor($edit = false, $id = null){
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        verifylogin();
        getperm();
        $GLOBALS["edit"] = $edit;
        $GLOBALS["id"] = $id;
        if($edit){
            $conn = getsqlconnection();
            $sql = "SELECT * FROM users WHERE id = " . $id . ";";
            $result = mysqli_query($conn,$sql);
            $myArray = array();
            if ($result->num_rows > 0) {
                foreach($result->fetch_assoc() as $key => $value)
                $GLOBALS["userdb"][$key] = $value;
            }
        }else{
            $GLOBALS["faecher"] = array("");
        }
        if($edit && $GLOBALS["lehrer.own"] == 1 && $_SESSION["vorname"] == $GLOBALS["userdb"]["vorname"] && $_SESSION["nachname"] == $GLOBALS["userdb"]["nachname"]){
            $GLOBALS["ownedit"] = true;
            $GLOBALS["disabled"] = false;
        }elseif($GLOBALS["lehrer.all"] == 0){
            echo("<script>$('.no_perm').show();</script>");
            $GLOBALS["disabled"] = true;
        }
        include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/user-editor.php";
    }

?>