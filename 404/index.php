<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/head.html" ?>
        <script>
        var i = 0;
        var txt = 'Error 404 - Something went wrong';
        var txt2 = 'The site wasn\'t found or isn\'t implemented yet';
        var speed = 100;
        var call2 = false;

        function typeWriter() {
            if (i < txt.length) {
                document.getElementById("tW").innerHTML += txt.charAt(i);
                i++;
                setTimeout(typeWriter, speed);
            }
            if (i == txt.length) typeWriter2();
        }
        var x = 0;

        function typeWriter2() {
            if (x < txt2.length) {
                document.getElementById("tW2").innerHTML += txt2.charAt(x);
                x++;
                setTimeout(typeWriter, speed);
            }
        }
    </script>
    <title>404 Error - Friedrich Gymnasium Luckenwalde</title>
    </head>
    <body onload="typeWriter();">
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/header.html" ?>
        <section>
            <div style="margin-top: 40vh;">
                <h1 id="tW" style="font-size: 3rem;"></h1>
                <h2 id="tW2" style="font-size: 1rem;"></h2>
            </div>
        </section>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html" ?>
    </body>
</html>