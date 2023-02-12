<section>
    <link rel="stylesheet" href="/new-css/news.css">
    <div class="news">

            <?php
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            ?>

            <script>
                const urlparams = new URLSearchParams(window.location.search);
                var page = urlparams.get("page");
                if(page == undefined){
                    page = 1;
                }
                var items = urlparams.get("items");
                if(items == undefined){
                    items = 16;
                }
                const lesscharnum = 700;
            </script>

                <form method='POST' style="margin-top: 15px">
                    <div class='newssettings'>
                    <label>Beiträge pro Seite: <select id='itemsnum' name='itemsnum'>
                        <option value='8'>8</option>
                        <option value='16'>16</option>
                        <option value='32'>32</option>
                        <option value='64'>64</option>
                    </select></label>
                    <input type="button" id='itemssubmit' value="Anwenden" onclick='gotopage(1, Object.keys(newsdata).length/Number(document.getElementById("itemsnum").value));'></input>
                </div>
            </form>
            <ul style="list-style-type: none;" id="newslist">
            </ul>
            <script>
                const itemoptions = document.querySelectorAll("option[value='"+items+"']");
                for(i=0; i<itemoptions.length; i++){
                    if(itemoptions[i].innerHTML == items){
                        itemoptions[i].selected = true;
                        break;
                    }
                }

                var newsdata = {};
                var perms = {};

                function openpopup(id){
                    var newsitem = document.getElementById(id);
                    document.getElementsByClassName("readmorebox")[0].style.display = "inherit";
                    document.getElementById("popupinhalt").innerHTML = nl2br(newsdata[id].content);
                    document.getElementById("popupautor").innerHTML = newsdata[id].author;
                    document.getElementById("popuptitle").innerHTML = newsdata[id].title;
                    document.getElementById("popupzeit").innerHTML = newsdata[id].time;
                }

                function editnews(id){
                    event.stopPropagation();
                    window.location='/admin/news/edit?id='+newsdata[id].id;
                }

                function deletenews(id){
                    event.stopPropagation();
                    document.getElementById("confirmdelete").setAttribute('onclick', '$.post("/admin/api/news.php", {action: "delete", id:"'+newsdata[id].id+'"}, function(data){if(JSON.parse(data).success){document.getElementsByClassName("confirm")[0].style.display = "none";getdata();}})');
                    document.getElementById("confirmdeletefirst").setAttribute('onclick', '$.post("/admin/api/news.php", {action: "delete", id:"'+newsdata[id].id+'"}, function(data){if(JSON.parse(data).success){document.getElementsByClassName("confirm")[0].style.display = "none";getdata();}})');
                    document.getElementsByClassName("confirm")[0].style.display = "inherit";
                    document.getElementById('confirmtext').innerHTML='Möchtest du die Neuigkeit &#34;'+newsdata[id].title+'&#34; wirklich löschen?';
                }

                function generatenewsitem(id){
                    if (typeof newsdata[id] !== 'undefined') {
                        var dbid = newsdata[id].id;
                        var title = newsdata[id].title;
                        var time = newsdata[id].time;
                        var author = newsdata[id].author;
                        var content = newsdata[id].content;
                        var lessinhalt = content.substring(0, lesscharnum);
                        if(content.length > lessinhalt.length){
                            lessinhalt = lessinhalt.trim();
                            while (lessinhalt[lessinhalt.length-1] === '.') lessinhalt = lessinhalt.slice(0,-1);
                            lessinhalt = lessinhalt + '&hellip;';
                        }
                        lessinhalt = nl2br(lessinhalt);
    
                        var newslist = document.getElementById("newslist");
                        const node = document.createElement("li");
                        node.id = id;
                        var divbeginn = "<div onclick=\"event.stopPropagation();openpopup('"+id+"');\" class='singlenews'>";
                        var toptext = "<h1><span>"+title+"</span></h1><h5><p><span style='display: inline-block'>Veröffentlicht von <span class='autor'>"+author+"</span></span><span class='time' style='display: inline-block'>am <span>"+time+" Uhr</span></span></p></h5>";
                        <?php if($GLOBALS["admin"]){
                            echo 'if(perms["news.all"] || (perms["news.own"] && newsdata[id].author_id == '.$_SESSION["user_id"].')){adminbtn = "<div class=\'adminbtn\'><a title=\'Bearbeiten\' onclick=\"editnews(\'"+id+"\');\"><i style=\'margin-right: 30px\' class=\'fas fa-edit\'></i></a><a title=\'Löschen\' onclick=\"deletenews(\'"+id+"\');\"><i class=\'fas fa-trash red\' style=\'color:#F75140\'></i></a></div>";}else{adminbtn =""}';
                        }else{
                            echo 'adminbtn = "";';
                        } ?>
                        var contenttext = "<p><span>"+lessinhalt+"</span> <a class='readmore'>Mehr anzeigen</a></p>";
                        node.innerHTML = divbeginn+adminbtn+toptext+contenttext+"</div>";
                        newslist.appendChild(node);
                    }
                }

                function getdata(){
                    document.getElementById("newslist").innerHTML = "";
                    document.getElementById("pageselector").innerHTML = "";
                    $.post("/admin/api/news.php", {"action": "getall"}, function(data){
                        var itemsperpage = Number(document.getElementById("itemsnum").value);
                        var startitem = (page-1)*itemsperpage;
                        var counter = 0;
                        <?php if($GLOBALS["admin"]) { echo '$.post("/admin/api/user.php", {"action": "getperms"}, function(permdata){perms = JSON.parse(permdata).data;';} ?>
                            JSON.parse(data).data.forEach(function(cur){
                                newsdata[counter] = {};
                                newsdata[counter].id = cur.id;
                                newsdata[counter].title = cur.titel;
                                var d = new Date(cur.zeit);
                                newsdata[counter].time = ("0" + d.getDate()).slice(-2) + "." + ("0" + (d.getMonth() + 1)).slice(-2) + "." + d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
                                newsdata[counter].author = cur.autor;
                                newsdata[counter].author_id = cur.autor_id;
                                newsdata[counter].content = cur.inhalt;
                                if((counter >= startitem) && (counter < (itemsperpage+startitem))){
                                    generatenewsitem(counter);
                                };
                                counter++;
                            });
                            createpagebuttons();
                        <?php if($GLOBALS["admin"]) { echo '});';} ?>
                    });
                }
                window.addEventListener('load', function() {getdata();});
            </script>
            <div onclick="event.stopPropagation();document.getElementsByClassName('readmorebox')[0].style.display = 'none'" style='left: 0;' class='readmorebox'>
                <span class='helper'></span>
                <div onclick="event.stopPropagation();" class='scroll'>
                    <div onclick="event.stopPropagation();document.getElementsByClassName('readmorebox')[0].style.display = 'none'" class='popupCloseButton'>&times;</div>
                    <div class='newspopup'>
                        <h1><span id='popuptitle'></span><br>
                            <h5><p>Veröffentlicht von <span id='popupautor'></span></p><p class='time'>am <span id='popupzeit'></span> Uhr</p></h5>
                        </h1>
                        <p><span id='popupinhalt'></span></p>
                    </div>
                </div>
            </div>
            <style>
                #pageselector a {
                    cursor: pointer;
                }
            </style>
            <div id="pageselector" class='pagination'>
            </div>
            <script>
                function gotopage(newpage, pagenums){
                    if(newpage <= pagenums && newpage > 0){
                        history.pushState(null, null, location.pathname+"?page="+newpage+"&items="+Number(document.getElementById("itemsnum").value));
                        page = newpage;
                        var newslist = document.getElementById("newslist");
                        newslist.innerHTML = "";
                        var itemsperpage = Number(document.getElementById("itemsnum").value);
                        for(i=((newpage-1)*itemsperpage); i<(newpage*itemsperpage); i++){
                            generatenewsitem(i);
                        }
                        document.getElementById("pageselector").innerHTML = null;
                        createpagebuttons();
                    }
                }
                function createpagebuttons(){
                    var paginationelem = document.getElementById("pageselector");
                    var articlenums = Object.keys(newsdata).length;
                    var pagwidth;
                    var newsitems = Number(document.getElementById("itemsnum").value);
                    var pagenums = articlenums/newsitems
                    if(pagenums > 16){
                        pagwidth = 16;
                    }else{
                        pagwidth = Math.ceil(pagenums);
                    }
                    paginationelem.style.width = (pagwidth*75+150)+"px";
                    prevbtn = document.createElement("a");
                    prevbtn.innerHTML = "<i class='fas fa-chevron-left'></i>";
                    prevbtn.addEventListener("click", function(e){gotopage(page-1, pagenums+1)})
                    paginationelem.appendChild(prevbtn);
                    for(i=0; i<pagwidth; i++){
                        var btn = document.createElement("a");
                        btn.innerHTML = i+1;
                        btn.addEventListener("click", function(e){gotopage(Number(e.srcElement.innerHTML), pagenums+1)});
                        paginationelem.appendChild(btn);
                    }
                    nextbtn = document.createElement("a");
                    nextbtn.innerHTML = "<i class='fas fa-chevron-right'></i>";
                    nextbtn.addEventListener("click", function(e){gotopage(page+1, pagenums+1)});
                    paginationelem.appendChild(nextbtn);
                }
            </script>
            <?php
                if($GLOBALS["admin"]==true){
                    deleteconfirm("Löschung bestätigen", "confirmtext", "Abbrechen", "Löschen", "confirmdelete");
                }
            ?>
    </div>
</section>