<?php
    if(isset($_SESSION["user_id"])) {
        header("Location: /admin/");
    } else {
        session_name("userid_login");
        session_start();
    }
?>
<html>
    <head>
        <?php
            include_once "./../sites/head.html";
        ?>
        <title>Login - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>

        <?php
            include_once "./../sites/header.html";
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

                $servername = "sql150.your-server.de";
                $username = "c0921922321";
                $password = "AHWNiBfs2u14AAZg"; //master
                $dbname = "friedrich_gym";
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if(isset($_POST["submit"])) {
                    $sql = str_replace("--", "_", ("SELECT * FROM users WHERE username=\"" . $use . "\" AND password_hash=\"" . $pwa . "\";"));
                    // $pw = mysqli_query($conn, "SELECT * FROM users WHERE username="" . $use . "" AND password_hash="" . $pwa . "";");
                    $pw = mysqli_query($conn, $sql);
                    
                    if($pw->num_rows > 0) {
                        $_SESSION["user_id"] = $use;
                        $_SESSION["password"] = $pwa;
                        $row = $pw->fetch_assoc();
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
        ?> 
    </body>
</html>