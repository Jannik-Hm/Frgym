<?php
    if(isset($_SESSION["user_id"])) {
        header("Location: /admin/");
    } else {
        session_name("userid_login");
        session_start();
    }
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
?>
<html>
    <head>
        <?php
            include_once "$root/admin/sites/head.html";
        ?>
        <title>Login - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>

        <?php
            include_once "$root/admin/sites/header.html";
        ?>
        <form method=POST id="loginForm">
            <input type="text" placeholder=" Loginname*" name="username" required><br>
            <input type="password" width="" placeholder=" Passwort*" name="password" required><br>
                
            <input type="submit" name="submit" value="Login">
        </form>
        <?php


            if(!isset($_SESSION["user_id"])) {


                $use = $_POST["username"];
                $pwa = hash("sha256", $_POST["password"]);

                require_once "$root/sites/credentials.php";
                $conn = get_connection();

                if(isset($_POST["submit"])) {
                    $sql = str_replace("--", "_", ("SELECT * FROM users WHERE username=\"" . $use . "\" AND password_hash=\"" . $pwa . "\";"));
                    // $pw = mysqli_query($conn, "SELECT * FROM users WHERE username="" . $use . "" AND password_hash="" . $pwa . "";");
                    $pw = mysqli_query($conn, $sql);
                    
                    if($pw->num_rows > 0) {
                        $_SESSION["username"] = $use;
                        $_SESSION["password"] = $pwa;
                        $row = $pw->fetch_assoc();
                        $_SESSION["user_id"] = $row["id"];
                        $_SESSION["vorname"] = $row["vorname"];
                        $_SESSION["nachname"] = $row["nachname"];
                        $_SESSION["role"] = $row["role"];
                        echo("<script>window.location.replace('/admin/');</script>");
                    }
                }
            } else {
                echo("<script>window.location.replace('/admin/');</script>");
            }
            // was hierunter? News-Feedeinbindung?
            include_once "$root/sites/footer.html"
        ?> 
    </body>
</html>