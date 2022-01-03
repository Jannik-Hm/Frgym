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
                    $lesscharnum = 600;
                    $lessrownumber = 6;
                    $rowcharnum = 600;

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
                        $id = $news[$i]["id"];
                        echo("<a class='divrm".$id."'><div class='singlenews'>");
                        $title = $news[$i]["titel"];
                        $lessinhalt = substr($news[$i]["inhalt"],0,$lesscharnum);
                        $inhalt = $news[$i]["inhalt"];
                        $autor = $news[$i]["autor"];
                        $zeitor1 = explode("-", $news[$i]["zeit"]);
                        $zeitor2 = explode(" ", $zeitor1[2]);
                        $zeitor3 = explode(":", $zeitor2[1]);
                        $zeit = $zeitor2[0] . "." . $zeitor1[1] . "." . $zeitor1[0] . " " . $zeitor3[0] . ":" . $zeitor3[1];
                        echo("<h1>".$title."<br>
                            <h5>Veröffentlicht von ".$autor." am ".$zeit."</h5>"."</h1>");
                        echo("<p>".$lessinhalt."... <a class='readmore".$id."'>Mehr anzeigen</a></p>");
                        echo("</div></a>");
                        echo("</li>");
                        echo("<div style='left: 0;' class='readmorebox".$id."'>
                                <span class='helper'></span>
                                <div>
                                    <div class='popupCloseButton".$id."'>&times;</div>
                                    <div class='newspopup'>
                                        <h1>".$title."<br>
                                            <h5>Veröffentlicht von ".$autor." am ".$zeit."</h5>
                                        </h1>
                                        <p>".$inhalt."</p>
                                    </div>
                                </div>
                            </div>");
                        echo("<script>$(window).load(function () {
                            $('.readmore".$id."').click(function(){
                            $('.readmorebox".$id."').show();
                            });
                            $('.divrm".$id."').click(function(){
                                $('.readmorebox".$id."').show();
                                });
                            // $('.readmorebox".$id."').click(function(e){
                            //     ('.readmorebox".$id."').hide();
                            // });
                            $('.popupCloseButton".$id."').click(function(){
                                $('.readmorebox".$id."').hide();
                            });
                        });</script>");
                    }
                ?>
            </ul>
            <div class="page-ending"></div>
        </div>
    </body>
</html>