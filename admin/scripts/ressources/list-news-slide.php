<!-- Newsslider -->
<section>
    <link rel="stylesheet" href="/new-css/news.css">
    <link rel="stylesheet" href="/new-css/news-slide.css">
    <div class="news">
        <?php
            $lesscharnum = 700;
        ?>

        <div class="newsslidershow">

            <?php
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/credentials.php";
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
                    <?php
                        for ($i=1;$i<=count($news);$i++){echo('<input type="radio" name="radio-btn" id="radio'.$i.'">');}
                        for ($i=0; $i<count($news); $i++){
                            echo("<div class='newsslide". ($i+1) ."'>");
                                echo("<div onclick=\"event.stopPropagation();$('.readmorebox').show();$('#popupinhalt').html(fullinhalt".$i.".valueOf());$('#popupautor').html(autor".$i.".valueOf());$('#popuptitle').html(titel".$i.".valueOf());$('#popupzeit').html(zeit".$i.".valueOf());\" class='news'>");
                                    echo("<h1><span id='title".$i."'></span><br>
                        <h5><p><span style='display: inline-block'>Veröffentlicht von <span id='autor".$i."'></span></span><span class='time' style='display: inline-block'>am <span id='zeit".$i."'></span></span></p></h5>"."</h1>");
                        echo("<p><span id='lessinhalt".$i."'></span><br><a onclick=\"event.stopPropagation();$('.readmorebox".$i."').show()\" class='readmore".$i."'>Mehr anzeigen</a></p>");
                        echo("</div></a>");
                        echo("</li>");
                        echo("
                        <script>
                            var inhalt = ".json_encode($news[$i]["inhalt"]).";
                            var lessinhalt = inhalt.substring(0, ".json_encode($lesscharnum).");
                            if(inhalt.length > lessinhalt.length){
                                lessinhalt = lessinhalt.trim();
                                while (lessinhalt[lessinhalt.length-1] === '.') lessinhalt = lessinhalt.slice(0,-1);
                                lessinhalt = lessinhalt + '&hellip;';
                            }
                            lessinhalt".$i." = nl2br(lessinhalt);
                            var fullinhalt".$i." = nl2br(inhalt);
                            var autor".$i." = ".json_encode($news[$i]["autor"]).";
                            var titel".$i." = ".json_encode($news[$i]["titel"]).";
                            var zeit".$i." = ".json_encode(date_format(date_create($news[$i]["zeit"]), "d.m.Y H:i") . " Uhr").";
                            $('#lessinhalt".$i."').html(lessinhalt".$i.".valueOf());
                            $('#title".$i."').html(titel".$i.".valueOf());
                            $('#autor".$i."').html(autor".$i.".valueOf());
                            $('#zeit".$i."').html(zeit".$i.".valueOf());
                        </script>
                        </div>
                        ");
                    }
                    ?>
                    <div onclick="event.stopPropagation();$('.readmorebox').hide()" style='left: 0;' class='readmorebox'>
                        <span class='helper'></span>
                        <div onclick="event.stopPropagation();" class='scroll'>
                            <div onclick="event.stopPropagation();$('.readmorebox').hide()" class='popupCloseButton'>&times;</div>
                            <div class='newspopup'>
                                <h1><span id='popuptitle'></span><br>
                                    <h5><p>Veröffentlicht von <span id='popupautor'></span></p><p class='time'>am <span id='popupzeit'></span></p></h5>
                                </h1>
                                <p><span id='popupinhalt'></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="navigation-auto">
                        <?php for($i=1;$i<=count($news);$i++){echo("<div class='auto-btn".$i."'></div>");} ?>
                    </div>
                </div>
                <div class="navigation-manual">
                    <?php for($i=1;$i<=count($news);$i++){echo('<label for="radio'.$i.'" class="manual-btn"></label>');} ?>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    <?php
                        for($i=1;$i<=count($news);$i++){
                            if($i==count($news)){
                                $nexti = 1;
                            }else{
                                $nexti = $i+1;
                            }
                            echo("
                                $('#radio".$i."').click(function () {
                                    var timer".$i." = 0;
                                    var interval".$i." = setInterval(function() {
                                        if (document.getElementById('radio".$i."').checked) {
                                            timer".$i."++;
                                            if(timer".$i." == 20){
                                                document.getElementById('radio".$nexti."').click();
                                                clearInterval(interval".$i.");
                                            }
                                        }else{
                                            clearInterval(interval".$i.");
                                        }
                                    }, 500);
                                    return;
                                });
                            ");
                        }
                    ?>
                    document.getElementById('radio1').click();
                });
            </script>
        </div>
    </div>
</section>