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
        <title>Neuigkeit löschen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/permissions.php";

            include_once "$root/admin/no-permission.html";

            $id = $_GET["id"];
            require_once "$root/sites/credentials.php";
            $conn = get_connection();

            $sql = "SELECT autor FROM news WHERE id = " . $id . ";";
            $result = mysqli_query($conn,$sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $autor = $row["autor"];
            }

            if($news_own == 1 && $autor == $_SESSION["vorname"] . " " . $_SESSION["nachname"]){
                $disabled = false;
            }elseif($news_all == 0){
                echo("<script>$('.no_perm').show();</script>");
                $disabled = true;
            };

        ?>

        <?php

            $conn = get_connection();
            if ($disabled==false){$insert = mysqli_query($conn, "DELETE FROM news WHERE id='{$id}'");}
            if ($insert) {
                echo '<script type="text/javascript">window.location = "/admin/news/"</script>';
            }
        ?>
    </body>
</html