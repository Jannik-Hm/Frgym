<html>
    <head>
        <?php 

            include_once "./../sites/head.html"

        ?>
        <title>Untis - Friedrich-Gymnasium Luckenwalde</title>
        <script>
            function detectBrowser() { 
                var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
                if(isSafari) {
                    window.location.replace("https://nessa.webuntis.com/WebUntis/?school=Friedrich-Gym.");
                }
            }
        </script>
    </head>
    <body onload=detectBrowser();>
        <?php 

            include_once "./../sites/header.html"

        ?>
        <iframe src="https://nessa.webuntis.com/WebUntis/?school=Friedrich-Gym." id="untisframe"></iframe>
    </body>
</html>