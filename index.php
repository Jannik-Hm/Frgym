<?php header("Cache-Control: max-age=30"); ?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);

    include_once "$root/sites/head.html"

    ?>
    <title>Startseite - Friedrich-Gymnasium Luckenwalde</title>
</head>

<body>
        <?php

        include_once "$root/sites/header.html"

        ?>
        <section id="mainTop" class="parallax">
            <div>
                <h1>Friedrich-Gymnasium</h1>
                <h2>Luckenwalde</h2>
            </div>
            <i class="far fa-arrow-alt-circle-down" style="font-size: 250%; color: #ffffffa6; position:sticky; top: 85%; background-color: rgb(60, 60, 60, 0.6); padding: 5px; border-radius: 50px; margin-bottom: 100px" id="upndown"></i>
        </section>
        <section id="mainSecond">
            <div>
                <div>
                    <img src="https://via.placeholder.com/200?text=Placeholder">
                </div>
                <div>
                    <p>Adresse</p>
                    <p>Parkstraße 59<br>14943 Luckenwalde<br><br>Telefon: <a href="tel:+493371632569">03371-632569</a><br>Fax: <a href="tel:+493371641060">03371-641060</a><br><a href="mailto:s120534@schulen.brandenburg.de">E-Mail</a> </p>
                </div>
                <div>
                    <img src="https://via.placeholder.com/200?text=Placeholder">
                </div>
            </div>
            <span class="line"></span>
        </section>
        <section id="mainThird">
            <div>
                <p>Über uns</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore nulla rem iste quaerat iure tenetur repudiandae minima, aliquid in optio eaque soluta voluptatum corrupti ullam. Id, fugit! Assumenda, vel quas. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequatur perspiciatis illo illum expedita iure deleniti sapiente magni necessitatibus quod, maiores, temporibus dolor ipsa, quibusdam corporis culpa impedit aliquam incidunt porro! Lorem ipsum dolor sit amet consectetur, adipisicing elit. Incidunt, doloremque corrupti dolorum placeat iste vitae debitis magnam nostrum deserunt, ipsam ratione quae excepturi dolores ducimus officia consequuntur itaque quasi enim. Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse, optio? Doloremque nihil odio labore. Minima at ratione itaque cum numquam hic. Facilis unde neque velit inventore harum beatae natus repellat?</p>
            </div>
            <span class="line"></span>
        </section>
        <section id="mainFourth">
            <div>
                <p>Was uns auszeichnet</p>
            </div>
            <div>
                <div>
                    <p>DELF</p>
                    <img src="/img/delf-logo.webp" style="height: 100px; max-height: 200px; width: auto;">
                </div>
                <div>
                    <p>English</p>
                    <img src="/img/label-english.webp" style="height: 100px; max-height: 200px; width: auto;">
                </div>
                <div>
                    <p>Schule ohne Rassismus, mit Courage </p>
                    <img src="/img/schule_or_mc.webp" style="min-height: 100px; max-height: 200px; width: auto;">
                </div>
            </div>
            <span class="line"></span>
        </section>
        <section style="height: fit-content; width: 100%; background-color: #282828; display: block; position: relative; margin: 0; padding: 0; padding-bottom: 50px;">
            <div>
                <p>Aktuelles</p>
                <?php
                    include "$root/news/news-slide.php";
                ?>
            </div>
        </section>

    <?php

    include_once "$root/sites/footer.html"

    ?>
</body>

</html>