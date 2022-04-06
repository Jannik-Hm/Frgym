<section>
    <link rel="stylesheet" href="/admin/css/news.css">
    <?php

    $titel = $GLOBALS["titel"];
    $inhalt = $GLOBALS["inhalt"];
    $autor = $GLOBALS["autor"];

    if( ! $GLOBALS["edit"] && ! $GLOBALS["ownedit"]){
        $list = true;
    }else{
        $list = false;
    }

    $ownedit = $GLOBALS["ownedit"];
    $disabled = $GLOBALS["disabled"];

    ?>

    <div class="add-input-news">
        <form method="POST">
            <input type="text" size="50%" placeholder="Titel*" name="titel" <?php echo 'value="'.$titel.'"'; ?> <?php if($disabled){echo "disabled";} ?> required><br>
            <textarea rows="10" columns="50%" placeholder="Inhalt der Nachricht*" name="inhalt" <?php if($disabled){echo "disabled";} ?> required><?php echo $inhalt; ?></textarea><br>
            <input type="submit" name="submit" <?php if($disabled){echo "disabled";} ?> value="Senden">
            <div class="page-ending"></div>
        </form>
    </div>

    <?php

    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    if($list){
        confirmation("Hinzufügen erfolgreich!", "Die Neuigkeit wurde erfolgreich hinzugefügt.", "Weitere Neuigkeit verfassen", "/admin/news/add/", "Zurück zur Übersicht", "/admin/news/");
    }else{
        confirmation("Änderungen erfolgreich!", "Die Neuigkeit wurde erfolgreich aktualisiert.", "Zurück zur Übersicht", "/admin/news/"); //Edit style
    }

    $autor = $_SESSION["vorname"] . " " . $_SESSION["nachname"];
    $titel = $_POST["titel"];
    $inhalt = $_POST["inhalt"];
    $date = date("Y-m-d H:i");
    $conn = get_connection();
    if($list){
        $query="INSERT INTO news (titel, inhalt, autor, zeit) VALUES ('{$titel}', '{$inhalt}', '{$autor}', '{$date}')";
    }else{
        $query="UPDATE news SET titel='{$titel}', inhalt='{$inhalt}', autor='{$autor}', zeit='{$date}' WHERE id='{$id}'";
    }
        if(isset($_POST["submit"]) && !$disabled && !$list) {
            $insert = mysqli_query($conn, $query);
            if ($insert) {
                echo("<script>$('.confirm').show();</script>");
            }
        }
    ?>
</section>