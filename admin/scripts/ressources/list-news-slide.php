<!-- Newsslider -->
<section>
    <link rel="stylesheet" href="/new-css/news.css">
    <div class="news">

        <div class="newsslidershow">
            <script>
                const lesscharnum = 700;
                const numofnews = 3;
                var timer = {};
                var interval = {};
            </script>
            <div class="slider">
                <div class="newsslides">
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
                    <div class="navigation-auto">
                    </div>
                </div>
                <div class="navigation-manual">
                </div>
            </div>
            <script>
                var newsdata = {};
                var perms = {};

                function openpopup(id){
                    var newsitem = document.getElementById(id);
                    document.getElementsByClassName("readmorebox")[0].style.display = "block";
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
                    document.getElementById("confirmdelete").href = '/admin/news/delete.php?id='+newsdata[id].id;
                    document.getElementById("confirmdeletefirst").href = '/admin/news/delete.php?id='+newsdata[id].id;
                    document.getElementsByClassName("confirm")[0].style.display = "inherit";
                    document.getElementById('confirmtext').innerHTML='Möchtest du die Neuigkeit &#34;'+newsdata[id].title+'&#34; wirklich löschen?';
                }

                function generatenewsitem(id){
                    if (typeof newsdata[id] !== 'undefined') {
                        var newslist = document.getElementsByClassName("newsslides")[0];
                        const radio = document.createElement("input");
                        radio.type = "radio";
                        radio.id = "radio"+(id+1);
                        radio.value = (id+1);
                        radio.name = "radio-btn";
                        radio.addEventListener("click", function(e){
                            var i = e.target.value;
                            timer[i] = 0;
                            interval[i] = setInterval(function() {
                                if (document.getElementById('radio'+i).checked) {
                                    timer[i]++;
                                    if(timer[i] == 20){
                                        document.getElementById('radio'+((i==numofnews.toString)?1:(Number(i)+1))).click();
                                        clearInterval(interval[i]);
                                    }
                                }else{
                                    clearInterval(interval[i]);
                                }
                            }, 500);
                            return;
                        });
                        fir_newsentry = document.getElementById("newsslide1");
                        if(fir_newsentry == null){
                            newslist.insertBefore(radio, document.getElementsByClassName("readmorebox")[0]);
                        }else{
                            newslist.insertBefore(radio, fir_newsentry);
                        }

                        var autonav = document.getElementsByClassName("navigation-auto")[0];
                        var autobtn = document.createElement("div");
                        autobtn.className = "auto-btn"+(id+1);
                        autonav.appendChild(autobtn);
                        var mannav = document.getElementsByClassName("navigation-manual")[0];
                        var manbtn = document.createElement("label");
                        manbtn.className = "manual-btn";
                        manbtn.htmlFor = "radio"+(id+1);
                        mannav.appendChild(manbtn);
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
                        const node = document.createElement("div");
                        node.className = "newsslide"+(id+1);
                        node.id = "newsslide"+(id+1);
                        var divbeginn = "<div onclick=\"event.stopPropagation();openpopup('"+id+"');\" class='news'>";
                        var toptext = "<h1><span>"+title+"</span></h1><h5><p><span style='display: inline-block'>Veröffentlicht von <span class='autor'>"+author+"</span></span><span class='time' style='display: inline-block'>am <span>"+time+" Uhr</span></span></p></h5>";
                        var contenttext = "<p><span>"+lessinhalt+"</span><br><a class='readmore'>Mehr anzeigen</a></p>";
                        node.innerHTML = divbeginn+toptext+contenttext+"</div>";
                        newslist.insertBefore(node, document.getElementsByClassName("readmorebox")[0]);
                    }
                }

                $.post("/admin/api/news.php", {"action": "getall", "limit": numofnews}, function(data){
                    var counter = 0;
                    JSON.parse(data).data.forEach(function(cur){
                        newsdata[counter] = {};
                        newsdata[counter].id = cur.id;
                        newsdata[counter].title = cur.titel;
                        var d = new Date(cur.zeit);
                        newsdata[counter].time = ("0" + d.getDate()).slice(-2) + "." + ("0" + (d.getMonth() + 1)).slice(-2) + "." + d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
                        newsdata[counter].author = cur.autor;
                        newsdata[counter].author_id = cur.autor_id;
                        newsdata[counter].content = cur.inhalt;
                        generatenewsitem(counter);
                        counter++;
                    });
                    document.getElementById('radio1').click();
                })
            </script>
        </div>
    </div>
</section>