<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $username = $_POST["username"];
    $password = $_POST["password_hash"];

    function uploadfile($dir, $accepted_files, $inputname, $filenameoverride = null){
        $target_dir = $_SERVER["DOCUMENT_ROOT"]."/files/".$dir;
        $max_filesize = 10000000;

        if($filenameoverride == ""){
            $filenameoverride = null;
        }

        if($filenameoverride == null){
            $fileCount = count($_FILES[$inputname]['name']);
        }else{
            $fileCount = 1;
        }
        if(!($_FILES[$inputname]["error"] == 4)) {
            if(substr($dir, -1) != "/"){$target_dir=$target_dir."/";}
            for($i = 0; $i < $fileCount; $i++){
                $extension = strtolower(pathinfo($_FILES[$inputname]["name"][$i], PATHINFO_EXTENSION));
                $targetfilename = pathinfo($_FILES[$inputname]["name"][$i], PATHINFO_FILENAME);
                if($filenameoverride != null){
                    $targetfilename = $filenameoverride;
                }
                $target_file = $target_dir."/".$targetfilename.".".$extension;
                $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $uploadOk = 1;

                // Check file size
                if ($_FILES[$inputname]["size"][$i] > $max_filesize) {
                    // echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                foreach($accepted_files as $accepted_type) {
                    if (strtolower($FileType) != strtolower($accepted_type)){
                        $uploadOk = 0;
                    }else{
                        $uploadOk = 1;
                        break;
                    }
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    return false;
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES[$inputname]["tmp_name"][$i], $target_file)) {
                        if($i == $fileCount - 1){
                            return true;
                        }
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    if($_POST["action"] == "file-upload"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            if(!is_null($user["id"])){
                $response["performed"] = "uploadfile";
                $fileinputname = "files";
                $accepted_files = array("jpg","jpeg","png", "webp", "pdf", "docx", "doc");
    
                if(!is_null($_POST["existingfilename"])){
                    $existingfilename = $_POST["existingfilename"];
                }else{
                    $existingfilename = basename($_FILES[$fileinputname]["name"][$i]);
                }
                $filepath = realpath($_SERVER["DOCUMENT_ROOT"])."/files/".$_POST["uploaddir"]."/".$existingfilename;
    
                if(filter_var($_POST['deletefile'], FILTER_VALIDATE_BOOLEAN) && file_exists($filepath)){ //delete File if delete is true
                    unlink($filepath);
                }
                if(!is_null($_FILES[$fileinputname])){
                    uploadfile($_POST["uploaddir"], $accepted_files, $fileinputname, $_POST["filenameoverride"]);
                }
            }else{
                $response["error"][] = "Missing authentication";
            }
        }
    }

    echo json_encode($response);
?>