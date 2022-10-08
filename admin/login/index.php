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
        <link rel="stylesheet" href="/new-css/login.css">
        <link rel="stylesheet" href="/new-css/form.css">
        <title>Login - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php
            include_once "$root/admin/sites/header.php";
        ?>
        <div class="loginwelcome">
            <h1>Willkommen zur Frgym&nbsp;Admin&nbsp;Seite!</h1>
        </div>
        <div class="loginform">
            <form method=POST id="loginForm">
                <input type="text" placeholder=" Loginname*" name="username" required><br>
                <input type="password" width="" placeholder=" Passwort*" name="password" required><br>

                <input type="submit" name="submit" value="Login">
            </form>
            <div id="wronginput"><p>Fehler bei der Anmeldung!</p></div>
            <script>$('#wronginput').hide()</script>
        </div>
        <?php


            if(!isset($_SESSION["user_id"])) {


                $use = $_POST["username"];
                $pwa = hash("sha256", $_POST["password"]);

                require_once "$root/sites/credentials.php";
                $conn = get_connection();

                if(isset($_POST["submit"])) {
                    $sql = $conn->prepare("SELECT * FROM users WHERE username=? AND password_hash=?;");
                    $sql->bind_param("ss", $use, $pwa);
                    $sql->execute();
                    // echo($sql);
                    // $pw = mysqli_query($conn, "SELECT * FROM users WHERE username=" . $use . " AND password_hash=" . $pwa . ";");
                    // $pw = $conn->query($sql);
                    $pw = $sql->get_result();

                    if($pw->num_rows > 0) {
                        $_SESSION["username"] = $use;
                        $_SESSION["password"] = $pwa;
                        $row = $pw->fetch_assoc();
                        $_SESSION["user_id"] = $row["id"];
                        $_SESSION["vorname"] = $row["vorname"];
                        $_SESSION["nachname"] = $row["nachname"];
                        $_SESSION["role"] = $row["role"];
                        $_SESSION["lastlogin"] = $row["lastlogin"];
                        $date = date("Y-m-d H:i");
                        $lastlogin = mysqli_query($conn, "UPDATE users SET lastlogin='{$date}' WHERE id='{$row["id"]}';");
                        echo("<script>window.location.replace('/admin/');</script>");
                    }else{
                        echo("<script>$('#wronginput').show()</script>");
                    }
                }
            } else {
                echo("<script>window.location.replace('/admin/');</script>");
            }
            // was hierunter? News-Feedeinbindung?
        ?>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>