<?php header("Cache-Control: max-age=30"); ?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);

    include_once "$root/sites/head.html"

    ?>
    <title>Kurswahlen - Friedrich-Gymnasium Luckenwalde</title>
</head>

<body>
    <style>
        input {
            padding: 5px 10px;
            margin-bottom: 10px;
            border: 2px solid cornflowerblue;
            border-radius: 15px;
            font-size: small;
            background-color: silver;
            transition: all 1s;
        }

        input:hover {
            transform: scale(1.1);
            box-shadow: 5px 5px 5px darkgray;
        }

        .kWa:nth-of-type(2) {
            background-color: #363636;
        }
    </style>
    <?php

    include_once "$root/sites/header.html"

    ?>
    <section>
        <div>
            <?php
            $directory = "$root/files/site-ressources/kurswahlen/";
            $files = scandir($directory);
            $i = 1;
            foreach ($files as $fi) {
                if ($fi != '.' && $fi != '..') {
                    echo ('<div class="kWa" style="width: 100%; height: fit-content; padding: 10px 0px; display: flex; flex-direction: column; align-content: center; justify-content: space-evenly; align-items: center;">');
                    echo ('<h1 style="padding: 0px; margin: 0px">' . str_replace(".pdf", "", $fi) . '</h1>');
                    echo ('<input type="button" id="btnSlideToggle' . $i . '" value="Zeigen/Verstecken" />');
                    echo ('<div id="divSlideToggle' . $i . '"style="width: 100%; display: none">');
                    echo ('<div style="margin-left: auto; margin-right: auto; width: 90%">');
                    if ($i == 1 || $i == 2) {
                        echo ('<h3>Kurswahl in der Sekundarstufe I</h3>
                            <p>In den Jahrgangsstufen 9 und 10 werden jeweils
                            Schwerpunktfächer zur Profilbildung als Wahlpflichtfächer
                            angeboten. Wir möchten mit der Befragung die Kurseinteilung
                            vornehmen. Für die endgültige Errichtung eines Kurses ist unter
                            anderem das Wahlverhalten der Schülerinnen und Schüler
                            hinsichtlich der Anzahl der Teilnehmerinnen und Teilnehmer
                            maßgebend.</p>
                            <p>Die inhaltliche Gestaltung der Wahlpflichtfächer umfasst neue
                            Fächer, die auch in der gymnasialen Oberstufe belegt werden
                            können, sowie Lernbereiche, die eine fachliche Vertiefung der
                            Interessen der Schülerinnen und Schüler in Hinblick auf die
                            Kurswahlen in der gymnasialen Oberstufe ermöglichen.</p>
                            <p>Zu den neuen Fächern gehören eine weitere Fremdsprache,
                            Darstellendes Spiel, Informatik und Wirtschaftswissenschaft.</p>
                            <p>Für die Belegung in der gymnasialen Oberstufe müssen diese
                            Fächer spätestens ab der Jahrgangsstufe 10 mit zwei
                            Wochenstunden belegt werden.</p>
                            <p>Die dritte Fremdsprache kann am Friedrich-Gymnasium nur in der
                            Jahrgangsstufe 9 angewählt werden, die dann mit drei
                            Wochenstunden belegt werden muss.</p>
                        ');
                    } elseif ($i == 3) {
                        echo ('<h3>Kurswahl in der Sekundarstufe II</h3>
                            <p>• Der Unterricht in der Jahrgangsstufe 11 findet in Kursen statt.<br><br>
                            • Tutor/-<wbr>in (Klassenlehrkraft) wird ein Leistungskurslehrer/-<wbr>in.<br><br>
                            • Die Fächer werden in drei Aufgabenfelder eingeteilt.</p>
                            <p>1. sprachliches–künstlerisches Aufgabenfeld: &nbsp;<br>
                            Deutsch, Fremdsprachen, Kunst, Musik und Darstellendes Spiel<br><br>
                            2. gesellschaftliches Aufgabenfeld:<br>
                            Pol. Bildung, Geschichte, Geografie und Wirtschaftswissenschaft<br><br>
                            3. mathematisch-<wbr>naturwissenschaftlich-<wbr>technisches Aufgabenfeld:<br>
                            Mathematik, Physik, Chemie, Biologie und Informatik<br><br>
                            Sport gehört zu keinem Aufgabenfeld</p>
                            <p>• Überlegungen zu den vier Abiturfächern sollten in die
                            Kurswahl einfließen, denn aus jedem Aufgabenfeld muss ein
                            Abiturprüfungsfach gewählt werden und zwei der Fächer Deutsch,
                            Mathematik, Fremdsprache müssen unter den vier
                            Abiturprüfungsfächern sein.<br><br>
                            • Alle Schüler/-<wbr>innen wählen zwei Leistungskurse, sieben
                            Grundkurse und<br>
                            einen Seminarkurs. Ein zusätzlicher Kurs ist möglich.<br><br>
                            Grund-<wbr>und Leistungskurse können nur aus den in der
                            Jahrgansstufe 10 belegten Fächern gewählt werden.<br><br>
                            Im Seminarkurs wird projektbezogen 4 Halbjahre lang gearbeitet.&nbsp;<br><br>
                            Das 3. Halbjahr schließt mit einer Seminararbeit ab, die im 4.
                            Halbjahr mündlich verteidigt wird.<br><br>
                            • Die Einrichtung der Kurse hängt von der Anzahl der
                            Schüler/-<wbr>innen ab.<br><br>
                            • Alle Schüler/-<wbr>innen wählen sieben Klausurfächer, unter
                            denen müssen<br>
                            Deutsch, Fremdsprache, Mathematik, eine Naturwissenschaft und
                            eine Gesellschaftswissenschaft sein (Leistungskurse sind
                            Pflichtklausuren).</p>
                        ');
                    }
                    echo ('</div>');
                    echo ('<iframe src="/files/site-ressources/kurswahlen/' . $fi . '#toolbar=0" style="position: relative; margin: 10px auto; width: 90%; border: 1px solid #fff; border-radius: 15px; height: 50vh"></iframe>');
                    echo ('</div>');
                    echo ('</div><span class="line" style="height: 0px"></span>');
                    $i++;
                }
            }
            ?>
            <!-- <div style="width: 100%; height: fit-content; padding: 10px 0px; background-color: #363636; display: flex; flex-direction: column; align-content: center; justify-content: space-evenly; align-items: center;">
                <h1 style="padding: 0px; margin: 0px">Hinweise zu Kurswahlen</h1>
                <button onclick="showFile(0)">Zeigen/Verstecken</button>
                <div id="0"style="width: 100%; display: none">
                </div>
            </div> -->
            <script>
                $("#btnSlideToggle1").click(function() {
                    $("#divSlideToggle1").slideToggle();
                });
                $("#btnSlideToggle2").click(function() {
                    $("#divSlideToggle2").slideToggle();
                });
                $("#btnSlideToggle3").click(function() {
                    $("#divSlideToggle3").slideToggle();
                });
            </script>
        </div>
    </section>

    <?php

    include_once "$root/sites/footer.html"

    ?>
</body>

</html>