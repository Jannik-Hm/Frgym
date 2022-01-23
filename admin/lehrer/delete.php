<?php

    session_name("userid_login");
    session_start();

    if(!isset($_SESSION["user_id"])) {
        header("Location: /admin/login/");
    }

?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            include_once "$root/admin/sites/head.html";

        ?>
        <title>Lehrer löschen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/header.html";

            include_once "$root/admin/sites/permissions.php";

            include_once "$root/admin/no-permission.html";

            if($lehrer_all == 0){
                echo("<script>$('.no_perm').show();</script>");
                $disabled = true;
            };

        ?>

        <?php

            $id = $_GET["id"];
            require_once "$root/sites/credentials.php";
            $conn = get_connection();
            if ($disabled==false){$insert = mysqli_query($conn, "DELETE FROM lehrer WHERE id='{$id}'");}
            if ($insert) {
                echo '<script type="text/javascript">window.location = "/admin/lehrer/"</script>';
            }
        ?>
    </body>
</html>