<?php $GLOBALS["id"] = uniqid(); ?>
<li style="margin-bottom: 25px;" id="<?php echo $GLOBALS["id"] ?>">
    <form method="POST">
        <?php $GLOBALS["edit"] = true; ?>
        <?php include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/text.php"); ?>
        <input name="id" type="text" value="<?php echo $GLOBALS["id"] ?>" hidden></input>
        <input name="update" type="checkbox" hidden></input>
        <input name="edit" type="checkbox" checked hidden></input>
        <div style="margin: auto; margin-right: 5px; display: inline-block; float: right; margin-top: 5px;">
            <btn style="cursor:pointer; border: 1px solid #000; width: 80px; display: inline-block; text-align: center;" onclick="$('#<?php echo $GLOBALS['id'] ?>edit').hide();$('#<?php echo $GLOBALS['id'] ?>abort').show();$('#<?php echo $GLOBALS['id'] ?>save').show(); $('#<?php echo $GLOBALS['id'] ?>').attr('style', ''); $('#content1<?php echo $GLOBALS['id'] ?>').removeAttr('style disabled')" id="<?php echo $GLOBALS["id"] ?>edit">Bearbeiten</btn>
            <btn style="cursor:pointer; border: 1px solid #000; width: 80px; display: inline-block; text-align: center; display: none" onclick="location.reload(); return false;" id="<?php echo $GLOBALS["id"] ?>abort">Abbrechen</btn>
            <input style="cursor: pointer; display: none" type="submit" name="submit" value="Speichern" id="<?php echo $GLOBALS["id"] ?>save">
        </div>
    </form>
</li>