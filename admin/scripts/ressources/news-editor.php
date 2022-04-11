<section>
    <link rel="stylesheet" href="/new-css/news.css">
    <?php

    $titel = $GLOBALS["titel"];
    $inhalt = $GLOBALS["inhalt"];
    $autor = $GLOBALS["autor"];

    $edit = $GLOBALS["edit"];
    $disabled = $GLOBALS["disabled"];

    ?>

    <div class="add-input-news">
        <form method="POST">
            <input type="text" size="50%" placeholder="Titel*" name="titel" <?php if($edit)echo 'value="'.$titel.'"'; ?> <?php if($disabled){echo "disabled";} ?> required><br>
            <textarea rows="10" columns="50%" placeholder="Inhalt der Nachricht*" name="inhalt" <?php if($disabled){echo "disabled";} ?> required><?php if($edit)echo $inhalt; ?></textarea><br>
            <input type="submit" name="submit" <?php if($disabled){echo "disabled";} ?> value="Senden">
        </form>
    </div>

    <?php

    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    if($edit){
        confirmation("Änderungen erfolgreich!", "Die Neuigkeit wurde erfolgreich aktualisiert.", "Zurück zur Übersicht", "/admin/news/"); //Edit style
    }else{
        confirmation("Hinzufügen erfolgreich!", "Die Neuigkeit wurde erfolgreich hinzugefügt.", "Weitere Neuigkeit verfassen", "/admin/news/add/", "Zurück zur Übersicht", "/admin/news/");
    }

    $autor = $_SESSION["vorname"] . " " . $_SESSION["nachname"];
    $titel = $_POST["titel"];
    $inhalt = $_POST["inhalt"];
    $date = date("Y-m-d H:i");
    $conn = get_connection();
    if($edit){
        $query="UPDATE news SET titel='{$titel}', inhalt='{$inhalt}', autor='{$autor}', zeit='{$date}' WHERE id='{$id}'";
    }else{
        $query="INSERT INTO news (titel, inhalt, autor, zeit) VALUES ('{$titel}', '{$inhalt}', '{$autor}', '{$date}')";
    }
        if(isset($_POST["submit"]) && !$disabled) {
            $insert = mysqli_query($conn, $query);
            if ($insert) {
                echo("<script>$('.confirm').show();</script>");
            }
        }
    ?>
</section>