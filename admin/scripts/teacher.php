<?php

    function teacher_editor($edit = false, $id = null){
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        verifylogin();
        getperm();
        $GLOBALS["edit"] = $edit;
        $GLOBALS["id"] = $id;
        if($edit){
            $conn = getsqlconnection();
            $sql = "SELECT * FROM users_neu WHERE id = " . $id . ";";
            $result = mysqli_query($conn,$sql);
            $myArray = array();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $GLOBALS["faecher"] = explode(";", $row["faecher"]);
                $GLOBALS["username"] = $row["username"];
                $GLOBALS["display_vorname"] = $row["display_vorname"];
                $GLOBALS["titel"] = $row["titel"];
                $GLOBALS["vorname"] = $row["vorname"];
                $GLOBALS["nachname"] = $row["nachname"];
                $GLOBALS["position"] = $row["role"];
                $GLOBALS["email"] = $row["email"];
                $GLOBALS["infotext"] = $row["beschreibung"];
            }
        }else{
            $GLOBALS["faecher"] = array("");
        }
        if($edit && $GLOBALS["lehrer.own"] == 1 && $_SESSION["vorname"] == $GLOBALS["vorname"] && $_SESSION["nachname"] == $GLOBALS["nachname"]){
            $GLOBALS["ownedit"] = true;
            $GLOBALS["disabled"] = false;
        }elseif($GLOBALS["lehrer.all"] == 0){
            echo("<script>$('.no_perm').show();</script>");
            $GLOBALS["disabled"] = true;
        }
        include realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/user-editor.php";
    }

?>