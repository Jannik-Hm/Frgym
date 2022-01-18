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

                ?>

                    <form method='POST'>
                        <div class='newssettings'>
                        <label>Beiträge pro Seite: <select class='itemsnum' name='itemsnum'>
                            <option value='8' <?php if ($items==8) {echo("selected"); } ?>>8</option>
                            <option value='16' <?php if ($items==16) {echo("selected"); } ?>>16</option>
                            <option value='32' <?php if ($items==32) {echo("selected"); } ?>>32</option>
                            <option value='64' <?php if ($items==64) {echo("selected"); } ?>>64</option>
                        </select></label>
                        <input type='submit' name='submit' value='Anwenden'></input>
                    </div>
                </form>

                <?php
                if(isset($_POST['submit'])) {
                    $items = $_POST["itemsnum"];
                    echo("'<script type='text/javascript'>window.location ='/news/?page="."1"."&items=".$items."'</script>'");
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

                    for ($i=count($news)-1-($items*($page-1)); $i >= count($news)-($items*($page-1))-$items && $i>=0; $i--) {
                        echo("<li>");
                        $id = $news[$i]["id"];
                        echo("<a class='divrm".$id."'><div class='singlenews'>");
                        $title = $news[$i]["titel"];
                        $inhalt = $news[$i]["inhalt"];
                        $lessinhalt = substr($news[$i]["inhalt"],0,$lesscharnum);
                        if (strlen($inhalt) > strlen($lessinhalt)) {
                            $lessinhalt = $lessinhalt . "...";
                        }
                        $autor = $news[$i]["autor"];
                        $zeitor1 = explode("-", $news[$i]["zeit"]);
                        $zeitor2 = explode(" ", $zeitor1[2]);
                        $zeitor3 = explode(":", $zeitor2[1]);
                        $zeit = $zeitor2[0] . "." . $zeitor1[1] . "." . $zeitor1[0] . " " . $zeitor3[0] . ":" . $zeitor3[1] . " Uhr";
                        echo("<h1>".$title."<br>
                            <h5><p>Veröffentlicht von ".$autor."</p><p class='time'>am ".$zeit."</p></h5>"."</h1>");
                        echo("<p>".nl2br($lessinhalt)." <a class='readmore".$id."'>Mehr anzeigen</a></p>");
                        echo("</div></a>");
                        echo("</li>");
                        echo("<div style='left: 0;' class='readmorebox".$id."'>
                                <span class='helper'></span>
                                <div class='scroll'>
                                    <div class='popupCloseButton".$id."'>&times;</div>
                                    <div class='newspopup'>
                                        <h1>".$title."<br>
                                            <h5><p>Veröffentlicht von ".$autor."</p><p class='time'>am ".$zeit."</p></h5>
                                        </h1>
                                        <p>".nl2br($inhalt)."</p>
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
                    $article_nums = count($news);
                    if ($article_nums/$items > 16) {
                        $pagwidth = 16; // TODO: Add overflow with scrollbar
                    } else {
                        $pagwidth = ceil($article_nums/$items);
                    }
                    echo ("<div style='width: ".($pagwidth*75+150)."px;' class='pagination'>");
                    if ($page > 1) {$prevpage = "href='https://frgym.greenygames.de/news/?page=".($page-1)."&items=".$items."'";}else{$prevpage = null;}
                    if ($page < $article_nums/$items) {$nextpage = "href='https://frgym.greenygames.de/news/?page=".($page+1)."&items=".$items."'";}else{$nextpage = null;}
                    echo("<a ".($prevpage)."'><i class='fas fa-chevron-left'></i></a>");
                    for($j=1; $j < $article_nums/$items+1; $j++) {
                        echo("<a href='https://frgym.greenygames.de/news/?page=".$j."&items=".$items."'>".$j."</a>");
                    }
                    echo("<a ".$nextpage."><i class='fas fa-chevron-right'></i></a>");
                    echo("</div>");
                ?>
            </ul>
            <div class="page-ending"></div>
        </div>
        <?php include_once "./../sites/footer.html" ?>
    </body>
</html>