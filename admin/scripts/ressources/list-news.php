<section>
    <link rel="stylesheet" href="/css/news.css">
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
                    echo("'<script type='text/javascript'>window.location ='".str_replace("??","?",str_replace($_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"])."?page="."1"."&items=".$items)."'</script>'");
                }

                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/credentials.php";
                $conn = get_connection();

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
                    echo("<div onclick=\"event.stopPropagation();$('.readmorebox".$id."').show()\" class='singlenews'>");
                    $title = $news[$i]["titel"];
                    $inhalt = $news[$i]["inhalt"];
                    $lessinhalt = substr($news[$i]["inhalt"],0,$lesscharnum);
                    if (strlen($inhalt) > strlen($lessinhalt)) {
                        $lessinhalt = $lessinhalt . "...";
                    }
                    $autor = $news[$i]["autor"];
                    $zeit = date_format(date_create($news[$i]["zeit"]), "d.m.Y H:i") . " Uhr";
                    if($GLOBALS["admin"]==true){
                        echo("<div class='adminbtn'>");
                        if($GLOBALS["news.all"] == 1 || ($GLOBALS["news.own"] == 1 && $_SESSION["vorname"] . " " . $_SESSION["nachname"] == $autor)){
                            echo("
                                <a title='Bearbeiten' onclick=\"event.stopPropagation();window.location='/admin/news/edit?id=" .$id. "'\"><i style='margin-right: 30px' class='fas fa-edit'></i></a>
                                <a title='Löschen' onclick=\"event.stopPropagation();$('#confirmdelete').attr('href', '/admin/news/delete.php?id=".$id."');$('.confirm').show();document.getElementById('confirmtext').innerHTML='Möchtest du die Neuigkeit &#34;".$title."&#34; wirklich löschen?'\"><i class='fas fa-trash red' style='color:#F75140'></i></a>
                                ");
                            }
                        echo("</div>");
                    }
                    echo("<h1>".$title."<br>
                        <h5><p><span style='display: inline-block'>Veröffentlicht von ".$autor."</span><span class='time' style='display: inline-block'>am ".$zeit."</span></p></h5>"."</h1>");
                    echo("<p>".nl2br($lessinhalt)." <a onclick=\"event.stopPropagation();$('.readmorebox".$id."').show()\" class='readmore".$id."'>Mehr anzeigen</a></p>");
                    echo("</div></a>");
                    echo("</li>");
                    echo("<div onclick=\"event.stopPropagation();$('.readmorebox".$id."').hide()\" style='left: 0;' class='readmorebox".$id."'>
                            <span class='helper'></span>
                            <div onclick=\"event.stopPropagation();\" class='scroll'>
                                <div onclick=\"event.stopPropagation();$('.readmorebox".$id."').hide()\" class='popupCloseButton".$id."'>&times;</div>
                                <div class='newspopup'>
                                    <h1>".$title."<br>
                                        <h5><p>Veröffentlicht von ".$autor."</p><p class='time'>am ".$zeit."</p></h5>
                                    </h1>
                                    <p>".nl2br($inhalt)."</p>
                                </div>
                            </div>
                        </div>");
                }
                $article_nums = count($news);
                if ($article_nums/$items > 16) {
                    $pagwidth = 16; // TODO: Add overflow with scrollbar
                } else {
                    $pagwidth = ceil($article_nums/$items);
                }
                echo ("<div style='width: ".($pagwidth*75+150)."px;' class='pagination'>");
                if ($page > 1) {$prevpage = "href='https://".$_SERVER["HTTP_HOST"] . str_replace("??","?",str_replace($_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"])."?page=".($page-1)."&items=".$items)."'";}else{$prevpage = null;}
                if ($page < $article_nums/$items) {$nextpage = "href='https://".$_SERVER["HTTP_HOST"] . str_replace("??","?",str_replace($_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"])."?page=".($page+1)."&items=".$items)."'";}else{$nextpage = null;}
                echo("<a ".($prevpage)."'><i class='fas fa-chevron-left'></i></a>");
                for($j=1; $j < $article_nums/$items+1; $j++) {
                    echo("<a href='https://".$_SERVER["HTTP_HOST"] . str_replace("??","?",str_replace($_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"])."?page=".$j."&items=".$items)."'>".$j."</a>");
                }
                echo("<a ".$nextpage."><i class='fas fa-chevron-right'></i></a>");
                echo("</div>");
                if($GLOBALS["admin"]==true){
                    echo("
                    <div style='left: 0;' class='confirm'>
                    <span class='helper'></span>
                    <div class='scroll'>
                        <div class='confirmation'>
                            <h1>Löschung bestätigen</h1><br>
                            <p id='confirmtext'></p><br>
                            <a onclick=\"$('.confirm').hide();\" class='abort'>Abbrechen</a>
                            <a id='confirmdelete' class='delete'>Löschen</a>
                            </div>
                        </div>
                    </div>
                    ");
                }
            ?>
        </ul>
    </div>
</section>