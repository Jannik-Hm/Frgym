<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $username = $_POST["username"];
    $password = $_POST["password_hash"];
    $app = $_POST["action"];

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
    function delete_directory($dirname) {
        if (is_dir($dirname)){
        $dir_handle = opendir($dirname);
        }
        if (!$dir_handle){
        return false;
        }
        while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
        if (!is_dir($dirname."/".$file))
        unlink($dirname."/".$file);
        else
        delete_directory($dirname.'/'.$file);
        }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }



    if($app == "file-upload"){
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
    }elseif($app == "delete-file"){
        $user = verifyapi($username, $password);
        if(!is_array($user)){
            $response["error"] = $user;
        }else{
            $path = $_SERVER["DOCUMENT_ROOT"]."/files/".$_POST["path"];
            if(is_dir($path)) {
                delete_directory($path);
            } else if (is_file($path)){
                if(unlink($path)){
                    $response["success"] = true;
                }else{
                    $response["success"] = false;
                }
            }else{
                $response["success"] = false;
            }
        }
    }elseif($app == "rename-file"){
        $filelocation = $_SERVER["DOCUMENT_ROOT"]."/files/";
        $newname = trim($_POST["newfilename"]);
        if(pathinfo($_POST["filepath"], PATHINFO_EXTENSION) != null){
            $newname = $newname.".".pathinfo($_POST["filepath"], PATHINFO_EXTENSION);
        }
        $newpath = $filelocation.pathinfo($_POST["filepath"], PATHINFO_DIRNAME)."/";
        if(rename($filelocation.$_POST["filepath"], $newpath.$newname)) {
            $response["success"] = true;
        }else{
            $response["success"] = false;
        }
    }elseif($app == "move-file"){
        if(rename($_SERVER["DOCUMENT_ROOT"]."/files/".$_POST["filepath"], $_SERVER["DOCUMENT_ROOT"]."/files/".$_POST["newpath"])){
            $response["success"] = true;
        }else{
            $response["success"] = false;
        }
    }else{
        http_response_code(404);
        $response["error"] = "Application unknown";
    }

    echo json_encode($response);
?>