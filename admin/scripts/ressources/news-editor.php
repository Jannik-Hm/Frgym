<section>
    <link rel="stylesheet" href="/new-css/news.css">
    <link rel="stylesheet" href="/new-css/form.css">
    <script>
        $.post("/admin/api/user.php", {action:"getperms"}, function(perms) {
            var perms = JSON.parse(perms).data;
            if(perms["news.all"] || perms["news.own"]){
                <?php echo ($edit)?'$.post("/admin/api/news.php", {action:"getbyid", id: "'.$_GET["id"].'"}, function(newsdata){var newsdata = JSON.parse(newsdata).data;':'';?>
                        if(perms["news.all"] || (perms["news.own"]<?php echo '&& newsdata.autor_id == '.$_SESSION["user_id"] ?>)){
                            <?php echo ($edit)?'document.getElementById("title").value = newsdata.titel;document.getElementById("content").innerHTML = newsdata.inhalt;':'' ?>
                            document.getElementById("newsform").addEventListener("submit", function(e){
                            e.preventDefault();
                            $.post("/admin/api/news.php", {action: "<?php echo ($edit)?"update":"add" ?>", titel:document.getElementById("title").value, inhalt: document.getElementById("content").value<?php echo($edit)?(", id: ".$_GET["id"]):"" ?>}).then(function(data){
                                var data = JSON.parse(data);
                                if(data.success){
                                    $('.confirm').show();
                                }
                            }).fail(function(request){if(request.status == 401 || request.status == 403){document.getElementsByClassName("no_perm")[0].style.display = "block"}});
                        });
                    }else{
                        document.getElementById("title").disabled = true;
                        document.getElementById("content").disabled = true;
                        document.getElementById("submitbtn").disabled = true;
                    }
                <?php echo ($edit)?'});':''?>
            }
        });
    </script>

    <div class="add-input-news">
        <form method="POST" id="newsform">
            <input id="title" type="text" size="50%" placeholder="Titel*" required><br>
            <textarea id="content" rows="10" columns="50%" placeholder="Inhalt der Nachricht*" required></textarea><br>
            <input type="submit" id="submitbtn" value="Speichern">
        </form>
    </div>

    <?php
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        if($edit){
            confirmation("Änderungen erfolgreich!", "Die Neuigkeit wurde erfolgreich aktualisiert.", "Zurück zur Übersicht", "/admin/news/"); //Edit style
        }else{
            confirmation("Hinzufügen erfolgreich!", "Die Neuigkeit wurde erfolgreich hinzugefügt.", "Weitere Neuigkeit verfassen", "/admin/news/add/", "Zurück zur Übersicht", "/admin/news/");
        }
    ?>
</section>