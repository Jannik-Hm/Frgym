<?php
        session_name("userid_login");
        session_start();
        ?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            include_once "$root/admin/sites/head.html";
            require_once "$root/admin/scripts/admin-scripts.php";
            verifylogin();

        ?>
        <title>Dokumente - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/header.html";
            $oldname = $_GET["path"];

        ?>

    <section>
        <form method="POST">
            <input name="newfilename" type="text" value="<?php echo pathinfo($oldname, PATHINFO_FILENAME); ?>" required><br>
            <input type="submit" name="submit" style="cursor: pointer" value="Speichern"><br>
        </form>
    </section>

<?php

    if(isset($_POST["submit"])){
        $filelocation = "$root/files/";
        $newname = trim($_POST["newfilename"]);
        if(pathinfo($oldname, PATHINFO_EXTENSION) != null){
            $newname = $newname.".".pathinfo($oldname, PATHINFO_EXTENSION);
        }
        $newpath = $filelocation.pathinfo($oldname, PATHINFO_DIRNAME)."/";
        rename($filelocation.$oldname, $newpath.$newname);
        // TODO: Confirmation
        echo("<script>window.location.href='/admin/dokumente/'</script>");
    }

?>
<?php include_once "$root/sites/footer.html" ?>
    </body>
</html>
