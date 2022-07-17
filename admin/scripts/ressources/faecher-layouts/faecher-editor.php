<section>
    <form method="POST">
        <?php $GLOBALS["edit"] = true; ?>
        <?php $GLOBALS["id"] = uniqid(); ?>
        <?php include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/text.php"); ?>
        <input name="id" type="text" value="<?php echo $GLOBALS["id"] ?>"></input>
        <input name="update" type="checkbox" hidden></input>
        <input name="edit" type="checkbox" checked hidden></input>
        <div style="margin: auto; margin-right: 5px; display: inline-block;position: absolute;right: 15px;margin-top: 5px;">
            <btn style="cursor:pointer; border: 1px solid #000; width: 80px; display: inline-block; text-align: center" onclick="location.reload(); return false;">Abbrechen</btn>
            <input style="cursor: pointer" type="submit" name="submit" <?php if(!($GLOBALS["edit"])){echo "hidden";} ?> value="Speichern">
        </div>
    </form>
</section>