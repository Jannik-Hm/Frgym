<?php
    session_name("userid_login");
    session_start();
    if(isset($_SESSION["user_id"])) {
        header("Location: /admin/");
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js" integrity="sha512-szJ5FSo9hEmXXe7b5AUVtn/WnL8a5VofnFeYC2i2z03uS2LhAch7ewNLbl5flsEmTTimMN0enBZg/3sQ+YOSzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                <input type="text" placeholder=" Loginname*" id="username" required><br>
                <input type="password" width="" placeholder=" Passwort*" id="password" required><br>
                <input type="button" onclick="$.post('/admin/api/user.php', {action: 'login', username: $('#username').val(), password_hash: sha256($('#password').val())}, function(data){handleresponse(JSON.parse(data));}).always(function (jqXHR){if(typeof jqXHR.status !== 'undefined'){$('#wronginput').show()};});" value="Login">
            </form>
            <div id="wronginput" style="display: none"><p>Fehler bei der Anmeldung!</p></div>
        </div>
        <script>
            function handleresponse(data){
                if(typeof data["error"] == 'undefined'){
                    window.location.replace("/admin/");
                }else{
                    $('#wronginput').show()
                }
            }
        </script>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>