<div class="bodyDiv">
    <link rel="stylesheet" href="/new-css/header.css">
    <nav class="header">
        <ul>
            <li><a href="/admin/" id="homenav">Home</a></li>
            <?php
            if(basename(dirname($_SERVER['PHP_SELF']))!="login"){
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
                getperm();
            }
            if($GLOBALS["user.administration"]){
                echo('
                <li class="drop-menu">
                    <a href="#"> Benutzerverwaltung <i
                            class="fa fa-angle-down"></i></a>
                    <ul>
                        <li><a href="/admin/lehrer/">Übersicht</a></li>
                        <li><a href="/admin/lehrer/add/">Hinzufügen</a></li>
                    </ul>
                </li>
                ');
            }
            if($GLOBALS["news.all"] || $GLOBALS["news.own"]){
                echo('
                <li class="drop-menu">
                    <a href="#"> News <i class="fa fa-angle-down"></i></a>
                    <ul>
                        <li><a href="/admin/news/">News-Blog</a></li>
                        <li><a href="/admin/news/add/">Hinzufügen</a></li>
                    </ul>
                </li>
                ');
            }
            if($GLOBALS["docs"]){
                echo('
                <li><a href="/admin/dokumente/">Dokumente</a></li>
                ');
            }
            ?>
            <li><a href="/admin/faecher-editor/faecher-liste.php">Fächer</a></li>
            <li id="admin-profile-dropdwn" class="drop-menu" style="width: 150px;display: inline; float: right; margin-right: 0px">
                <a href="#" style="padding-top: 0; padding-bottom:0">
                    <i class="fas fa-user-circle" style="font-size:40px;margin-top:5px;margin-bottom: 5px; margin-right: 5px"></i>
                    <!-- <img src="/files/site-ressources/lehrer-bilder/placeholder.webp" style="height:40px;margin-top:5px;border-radius:25px; margin-right: 5px"> -->
                    <i style="position: absolute;top:20px; margin-bottom:0" class="fa fa-angle-down"></i>
                </a>
                <ul>
                    <li id="logout-btn">
                        <a style="cursor: pointer" onclick="logout()">
                            Abmelden<i class="fa-solid fa-arrow-right-from-bracket" style="margin-left: 5px;color: lightcoral;"></i>
                        </a>
                    </li>
                    <li><a href="/admin/lehrer/edit/?id=<?php echo $_SESSION["user_id"] ?>">
                        Profil<i class="fas fa-user" style="margin-left: 8px;"></i>
                    </a></li>
                    <li><a href="">
                        Einstellungen<i class="fas fa-cog" style="margin-left: 5px;"></i>
                    </a></li>
                </ul>
            </li>
            <li class="drop-menu" id="Sonstiges-Button">
                <a href="#"> Sonstiges <i class="fa fa-angle-down"></i></a>
                <ul id="Sonstiges">
                </ul>
            </li>
        </ul>
        <ul id="mobile" style="display: none;">
            <li class="drop-menu" style="width: 100%">
                <a href="#" style="width: 100%; text-align: left"><i class="fas fa-bars" style="margin-right: 15px"></i>Navigator</a>
                <ul id="mobile-list">
                </ul>
            </li>
        </ul>
    </nav>
    <script src="/js/index.js"></script>
    <script src="/js/jquery.min.js"></script>
    <div id="page-beginning"></div>
    <script>
        document.getElementById('page-beginning').style.height = $('.adminheader').outerHeight() + "px";

        console.log(document.cookie);
        if (location.href.match("/admin/login/")) {
            $(".header ul li a").hide();
            $("#homenav").show();
        }
    </script>
    <script>
        function logout() {
            if (document.cookie.includes("userid_login=")) {
                document.cookie = "userid_login=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                location.href = "/admin/login/";
            }
        }
    </script>
    <!-- <footer>
    <picture>
        <img src="/img/favicon_dark.ico">
    </picture>
    <p>Friedrich-Gymnasium Luckenwalde © Adrean K., Jannik H., Florian P. </p>

    <a href="/impressum/">Impressum</a>
    <a href="/datenschutz/">Datenschutz</a>
</footer> -->