<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php

            include_once "./../sites/head.html"

        ?>
        <title>News - Friedrich-Gymnasium Luckenwalde</title>
    </head>
    <body>
        <?php

            include_once "./../sites/header.html"

        ?>

        <br>
        <br>
        <br>
        <div class="news">
            <ul style="list-style-type: none;">

                <?php
                    if (isset($_GET["items"]) && !(isset($items))) { // Nachrichten pro Seite
                        $items = $_GET["items"];
                    } elseif (!(isset($items))) {
                        $items = 16;
                    }
                    if (isset($_GET["page"]) && !(isset($page))) { // Seite
                        $page = $_GET["page"];
                    } elseif (!(isset($page))) {
                        $page = 1;
                    }

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

                    $sql = "SELECT * FROM news";
                    $result = mysqli_query($conn,$sql);
                    if ($result->num_rows > 0) {
                        $i = 0;
                        while($row = $result->fetch_assoc()) {
                            $news[$i]["id"] = $row["id"];
                            $news[$i]["titel"] = $row["titel"];
                            $news[$i]["inhalt"] = $row["inhalt"];
                            $news[$i]["autor"] = $row["autor"];
                            $news[$i]["zeit"] = $row["zeit"];
                            $i++;
                        }
                    }

                    for ($i=count($news)-1-($items*($page-1)); $i >= 0; $i--) {
                        echo("<li>");
                        echo("<div class='singlenews'>");
                        $title = $news[$i]["titel"];
                        $inhalt = $news[$i]["inhalt"];
                        $autor = $news[$i]["autor"];
                        $zeit = $news[$i]["zeit"];
                        echo("<p>Titel: ".$title.", Inhalt: ".$inhalt.", Autor: ".$autor.", Letzte Bearbeitung: ".$zeit."</p>");
                        echo("</div>");
                        echo("</li>");
                    }
                ?>
            </ul>
        </div>
    </body>
</html>