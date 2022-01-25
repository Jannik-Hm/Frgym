<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    include_once "$root/sites/head.html"

    ?>
    <title>News-Slide - Friedrich-Gymnasium Luckenwalde</title>
</head>

<body>
        <div class="newsslidershow">
            <?php
                $lesscharnum = 600;
                $lessrownumber = 6;
                $rowcharnum = 600;

                require "$root/sites/credentials.php";
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
                    <input type="radio" name="radio-btn" id="radio1">
                    <input type="radio" name="radio-btn" id="radio2">
                    <input type="radio" name="radio-btn" id="radio3">
                    <br>
                    <?php
                        for ($i=0; $i<count($news); $i++){
                            $id = $news[$i]["id"];
                            echo("<div class='newsslide". ($i+1) ."'>");
                                echo("<div onclick=\"event.stopPropagation();$('.readmorebox".$i."').show()\" class='news'>");
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
                                    echo("<p>".nl2br($lessinhalt)." <a onclick=\"event.stopPropagation();$('.readmorebox".$i."').show()\" class='readmore".$i."'>Mehr anzeigen</a></p>");
                                echo("</div>");
                                echo("<div onclick=\"event.stopPropagation();$('.readmorebox".$i."').hide()\" style='left: 0;' class='readmorebox".$i."'>
                                    <span class='helper'></span>
                                    <div onclick=\"event.stopPropagation();\" class='scroll'>
                                        <div onclick=\"event.stopPropagation();$('.readmorebox".$i."').hide()\" class='popupCloseButton".$i."'>&times;</div>
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
            <script>
                $(document).ready(function () {
                    $("#radio1").click(function () {
                        var timer1 = 0;
                        var interval1 = setInterval(function() {
                            if (document.getElementById('radio1').checked) {
                                timer1++;
                                if(timer1 == 20){
                                    document.getElementById('radio2').click();
                                    clearInterval(interval1);
                                }
                            }else{
                                clearInterval(interval1);
                            }
                        }, 500);
                        return;
                    });
                    $("#radio2").click(function () {
                        var timer2 = 0;
                        var interval2 = setInterval(function() {
                            if (document.getElementById('radio2').checked) {
                                timer2++;
                                if(timer2 == 20){
                                    document.getElementById('radio3').click();
                                    clearInterval(interval2);
                                }
                            }else{
                                clearInterval(interval2);
                            }
                        }, 500);
                        return;
                    });
                    $("#radio3").click(function () {
                        var timer3 = 0;
                        var interval3 = setInterval(function() {
                            if (document.getElementById('radio3').checked) {
                                timer3++;
                                if(timer3 == 20){
                                    document.getElementById('radio1').click();
                                    clearInterval(interval3);
                                }
                            }else{
                                clearInterval(interval3);
                            }
                        }, 500);
                        return;
                    });
                    document.getElementById('radio1').click();
                });
            </script>
        </div>
    </body>
</html>