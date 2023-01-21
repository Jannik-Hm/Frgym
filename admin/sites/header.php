<div class="bodyDiv">
    <link rel="stylesheet" href="/new-css/header.css">
    <script src="/js/index.js"></script>
    <script src="/js/jquery.min.js"></script>
    <nav class="header">
        <ul>
            <li><a href="/admin/" id="homenav">Home</a></li>
            <script>$(document).ready(function (){loadlinks()} );
                function loadlinks(){
                    $.post("https://<?php echo $_SERVER["HTTP_HOST"] ?>/admin/api/user.php", {action: "getheader"}, function(data){$(JSON.parse(data)["data"]).insertBefore("#admin-profile-dropdwn"); slide()})
                }
                function slide() {
                    $('.drop-menu ul').hide();
                    $(".drop-menu a").click(function () {
                        if ($(this).parent('.drop-menu').children("ul").css("display") == "none") { var slidedown = true; } else { var slidedown = false; }
                        $(this).parent("li.drop-menu").parent("ul").children("li.drop-menu").children("ul").slideUp("200");
                        $(this).parent("li.drop-menu").parent("ul").children("li.drop-menu").find("i.fa").attr("class", "fa fa-angle-down");
                        if (slidedown) {
                        $(this).parent(".drop-menu").children("ul").slideDown("200");
                        $(this).find("i.fa").attr("class", "fa fa-angle-up");
                        }
                    });
                }
            </script>
            <li id="admin-profile-dropdwn" class="drop-menu">
                <a href="#" style="padding-top: 0; padding-bottom:0">
                    <i class="fas fa-user-circle" style="font-size:40px;margin-top:5px;margin-bottom: 5px; margin-right: 5px"></i>
                    <!-- <img src="/files/site-ressources/lehrer-bilder/placeholder.webp" style="height:40px;margin-top:5px;border-radius:25px; margin-right: 5px"> -->
                    <i style="position: absolute;top:20px; margin-bottom:0" class="fa fa-angle-down"></i>
                </a>
                <ul style="display: none">
                    <li id="logout-btn">
                        <a style="cursor: pointer" onclick="logout()">
                            <i class="fa-solid fa-arrow-right-from-bracket" style="color: lightcoral;"></i><span>Abmelden</span>
                        </a>
                    </li>
                    <li><a href="/admin/user/edit/?id=<?php echo $_SESSION["user_id"] ?>">
                        <i class="fas fa-user"></i><span>Profil</span>
                    </a></li>
                    <li><a href="/admin/user/change_password.php">
                        <i class="fas fa-key"></i><span>Passwort ändern</span>
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
            $.post('https:\/\/<?php echo $_SERVER["HTTP_HOST"] ?>/admin/api/user.php', {action: 'logout'}).always(function (jqXHR){if(typeof jqXHR.status == 'undefined'){location.href = "/admin/login/";};});
        }
    </script>