<html>
    <head>
        <?php
            include_once "./sites/head.html";
        ?>
        <title>Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>

        <?php
            include_once "./sites/header.html";
        ?>

        <?php 


            if(!isset($_SESSION["user_id"])) {

                echo("
                <form method=\"POST\">
                    <input type=\"text\" placeholder=\"Loginname*\" name=\"username\" required><br>
                    <input type=\"password\" width=\"\" placeholder=\"Passwort*\" name=\"password\" required><br>
                
                    <input type=\"submit\" name=\"submit\" value=\"Login\">
                </form>
                ");


                $username = $_POST["username"];
                $nachname = hash("sha256", $_POST["password"]);

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
                    $pw = mysqli_query($conn, "SELECT 'password' FROM users WHERE username='" . $username . "';");
                    if($pw == $password) {
                        session_start();
                        $_SESSION["user_id"] = $username;
                        $_SESSION["password"] = $password;
                    }
                }
            } else {
                echo("<h1>Willkommen " . $_SESSION["user_id"] . "</h1>");
            }
            // was hierunter? News-Feedeinbindung?
        ?> 
    </body>
</html>