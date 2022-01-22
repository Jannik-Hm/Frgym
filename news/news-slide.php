<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php

    include_once "./../sites/head.html"

    ?>
    <title>News-Slide - Friedrich-Gymnasium Luckenwalde</title>
</head>

<body>
        <div class="newsslidershow">
            <?php
                $lesscharnum = 600;
                $lessrownumber = 6;
                $rowcharnum = 600;

                require "./../sites/credentials.php";
                $conn = get_connection();

                $sql = "SELECT * FROM news ORDER BY zeit DESC LIMIT 3";
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
            ?>
            <div class="slider">
                <div class="newsslides">
                    <input type="radio" name="radio-btn" id="radio1" checked>
                    <input type="radio" name="radio-btn" id="radio2">
                    <input type="radio" name="radio-btn" id="radio3">
                    <br>
                    <?php
                        for ($i=0; $i<count($news); $i++){
                            $id = $news[$i]["id"];
                            echo("<div class='newsslide". ($i+1) ."'>");
                                echo("<div onclick=\"$('.readmorebox".$i."').show()\" class='news'>");
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
                                    echo("<p>".nl2br($lessinhalt)." <a onclick=\"$('.readmorebox".$i."').show()\" class='readmore".$i."'>Mehr anzeigen</a></p>");
                                echo("</div>");
                                echo("<div style='left: 0;' class='readmorebox".$i."'>
                                    <span class='helper'></span>
                                    <div class='scroll'>
                                        <div onclick=\"$('.readmorebox".$i."').hide()\" class='popupCloseButton".$i."'>&times;</div>
                                        <div class='newspopup'>
                                            <h1>".$title."<br>
                                                <h5><p>Veröffentlicht von ".$autor."</p><p class='time'>am ".$zeit."</p></h5>
                                            </h1>
                                            <p>".nl2br($inhalt)."</p>
                                        </div>
                                    </div>
                                </div>");
                            echo("</div>");
                        }
                    ?>
                    <div class="navigation-auto">
                        <div class="auto-btn1"></div>
                        <div class="auto-btn2"></div>
                        <div class="auto-btn3"></div>
                    </div>
                </div>
                <div class="navigation-manual">
                    <label for="radio1" class="manual-btn"></label>
                    <label for="radio2" class="manual-btn"></label>
                    <label for="radio3" class="manual-btn"></label>
                </div>
            </div>
            <script type="text/javascript">
                var counter = 2;
                setInterval(function(){
                document.getElementById('radio' + counter).checked = true;
                counter++;
                if(counter > 3){
                    counter = 1;
                }
                }, 10000);
            </script>
        </div>
    </body>
</html>