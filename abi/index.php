<?php header("Cache-Control: max-age=30"); ?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);

    include_once "$root/sites/head.html"

    ?>
    <title>Abi-Jahrgänge - Friedrich-Gymnasium Luckenwalde</title>
</head>

<body>
    <?php

    include_once "$root/sites/header.html"

    ?>
    <section>
        <div style="position: relative; height: fit-content; width: fit-content; display: inline-flex; flex-direction: row; flex-wrap: wrap; align-content: center; justify-content: center; align-items: center;">
            <?php

            $directory = "$root/img/jahrgangsbilder/";
            $files = scandir($directory, SCANDIR_SORT_DESCENDING);

            foreach ($files as $fi) {
                if ($fi != '.' && $fi != '..') {
                    echo ('<div style="padding: 2vh; border: 1px solid #fff; border-radius: 20px; margin: 2vh 2vh;">');
                    echo ('<img src="/img/jahrgangsbilder/' . $fi . '" style="border: 1px solid #fff; border-radius: 10px;">');
                    echo ('<h2 style="font-size: 2vh; font-weight: lighter; margin-top: 1vh; margin-bottom: 0px;">Jahrgang</h2>');
                    echo ('<h1 style="font-size: 7vh; font-weight: bolder; margin-top: 1vh; margin-bottom: 1vh;">' . str_split($fi, 4)[0] . '</h1>');
                    echo ('</div>');
                }
            }


            ?>
        </div>
    </section>

    <?php

    include_once "$root/sites/footer.html"

    ?>
</body>

</html>