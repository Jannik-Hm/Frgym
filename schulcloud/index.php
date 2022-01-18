<html>
    <head>
        <?php 

            include_once "./../sites/head.html"

        ?>
        <title>SchulCloud - Friedrich-Gymnasium Luckenwalde</title>
        <script>
            function detectBrowser() { 
                var isSafari = navigator.userAgent.match(/(iPhone|iPod|iPad|blackberry|android|Kindle|htc|lg|midp|mmp|mobile|nokia|opera mini|palm|pocket|psp|sgh|smartphone|symbian|treo mini|Playstation Portable|SonyEricsson|Samsung|MobileExplorer|PalmSource|Benq|Windows Phone|Windows Mobile|IEMobile|Windows CE|Nintendo Wii)/i);
                if(isSafari) {
                    window.location.replace("https://brandenburg.cloud/dashboard");
                }
            }
        </script>
    </head>
    <body onload=detectBrowser();>
        <?php 

            include_once "./../sites/header.html"

        ?>
        <iframe src="https://brandenburg.cloud/dashboard" id="untisframe"></iframe>
        <?php

    include_once "./../sites/footer.html"

        ?>
    </body>
</html>