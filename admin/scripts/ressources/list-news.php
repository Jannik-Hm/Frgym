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
                $lesscharnum = 700;
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";

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
                    echo("'<script type='text/javascript'>window.location ='".forwardwithoutquery("?page="."1"."&items=".$items)."'</script>'");
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
                    $autor = $news[$i]["autor"];
                    echo("<div onclick=\"event.stopPropagation();$('.readmorebox').show();$('#popupinhalt').html(fullinhalt".$id.".valueOf());$('#popupautor').html(autor".$id.".valueOf());$('#popuptitle').html(titel".$id.".valueOf());$('#popupzeit').html(zeit".$id.".valueOf());\" class='singlenews'>");
                    if($GLOBALS["admin"]==true){
                        echo("<div class='adminbtn'>");
                        if($GLOBALS["news.all"] == 1 || ($GLOBALS["news.own"] == 1 && $_SESSION["vorname"] . " " . $_SESSION["nachname"] == $autor)){
                            echo("
                            <a title='Bearbeiten' onclick=\"event.stopPropagation();window.location='/admin/news/edit?id=" .$id. "'\"><i style='margin-right: 30px' class='fas fa-edit'></i></a>
                            <a title='Löschen' onclick=\"event.stopPropagation();$('#confirmdelete').attr('href', '/admin/news/delete.php?id=".$id."');$('.confirm').show();document.getElementById('confirmtext').innerHTML='Möchtest du die Neuigkeit &#34;'+titel".$id."+'&#34; wirklich löschen?'\"><i class='fas fa-trash red' style='color:#F75140'></i></a>
                                ");
                            }
                        echo("</div>");
                    }
                    echo("<h1><span id='title".$id."'></span><br>
                    <h5><p><span style='display: inline-block'>Veröffentlicht von <span id='autor".$id."'></span></span><span class='time' style='display: inline-block'>am <span id='zeit".$id."'></span></span></p></h5>"."</h1>");
                    echo("<p><span id='lessinhalt".$id."'></span> <a onclick=\"event.stopPropagation();$('.readmorebox".$id."').show()\" class='readmore".$id."'>Mehr anzeigen</a></p>");
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
                        lessinhalt".$id." = nl2br(lessinhalt);
                        var fullinhalt".$id." = nl2br(inhalt);
                        var autor".$id." = ".json_encode($news[$i]["autor"]).";
                        var titel".$id." = ".json_encode($news[$i]["titel"]).";
                        var zeit".$id." = ".json_encode(date_format(date_create($news[$i]["zeit"]), "d.m.Y H:i") . " Uhr").";
                        $('#lessinhalt".$id."').html(lessinhalt".$id.".valueOf());
                        $('#title".$id."').html(titel".$id.".valueOf());
                        $('#autor".$id."').html(autor".$id.".valueOf());
                        $('#zeit".$id."').html(zeit".$id.".valueOf());
                    </script>
                    ");
                }
                echo("<div onclick=\"event.stopPropagation();$('.readmorebox').hide()\" style='left: 0;' class='readmorebox'>
                    <span class='helper'></span>
                    <div onclick=\"event.stopPropagation();\" class='scroll'>
                        <div onclick=\"event.stopPropagation();$('.readmorebox').hide()\" class='popupCloseButton'>&times;</div>
                        <div class='newspopup'>
                            <h1><span id='popuptitle'></span><br>
                                <h5><p>Veröffentlicht von <span id='popupautor'></span></p><p class='time'>am <span id='popupzeit'></span></p></h5>
                            </h1>
                            <p><span id='popupinhalt'></span></p>
                        </div>
                    </div>
                </div>");
                $article_nums = count($news);
                if ($article_nums/$items > 16) {
                    $pagwidth = 16; // TODO: Add overflow with scrollbar
                } else {
                    $pagwidth = ceil($article_nums/$items);
                }
                echo ("<div style='width: ".($pagwidth*75+150)."px;' class='pagination'>");
                if ($page > 1) {$prevpage = "href='".forwardwithoutquery("?page=".($page-1)."&items=".$items)."'";}else{$prevpage = null;}
                if ($page < $article_nums/$items) {$nextpage = "href='".forwardwithoutquery("?page=".($page+1)."&items=".$items)."'";}else{$nextpage = null;}
                echo("<a ".($prevpage)."'><i class='fas fa-chevron-left'></i></a>");
                for($j=1; $j < $article_nums/$items+1; $j++) {
                    echo("<a href='".forwardwithoutquery("?page=".$j."&items=".$items)."'>".$j."</a>");
                }
                echo("<a ".$nextpage."><i class='fas fa-chevron-right'></i></a>");
                echo("</div>");
                if($GLOBALS["admin"]==true){
                    // echo("
                    // <div style='left: 0;' class='confirm'>
                    //     <span class='helper'></span>
                    //     <div class='scroll'>
                    //         <div class='confirmation'>
                    //             <h1>Löschung bestätigen</h1><br>
                    //             <p id='confirmtext'></p><br>
                    //             <a onclick=\"$('.confirm').hide();\" class='abort'>Abbrechen</a>
                    //             <a id='confirmdelete' class='delete'>Löschen</a>
                    //         </div>
                    //     </div>
                    // </div>
                    // ");
                    deleteconfirm("Löschung bestätigen", "confirmtext", "Abbrechen", "Löschen", "confirmdelete");
                }
            ?>
        </ul>
    </div>
</section>