<?php $GLOBALS["id"] = uniqid(); ?>
<li style="margin-bottom: 40px;" id="<?php echo $GLOBALS["id"] ?>">
    <form method="POST">
        <?php $GLOBALS["edit"] = true; ?>
        <?php include(realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/ressources/faecher-layouts/text.php"); ?>
        <input name="id" type="text" value="<?php echo $GLOBALS["id"] ?>" hidden></input>
        <input name="update" type="checkbox" hidden></input>
        <input name="edit" type="checkbox" checked hidden></input>
        <div style="margin: auto; margin-right: 5px; display: inline-block; float: right; margin-top: 5px;">
            <btn style="cursor:pointer; border: 1px solid #000; width: 80px; display: inline-block; text-align: center;" onclick="resetedit();edit('<?php echo $GLOBALS['id'] ?>')" id="<?php echo $GLOBALS["id"] ?>edit">Bearbeiten</btn>
            <btn style="cursor:pointer; border: 1px solid #000; width: 80px; display: inline-block; text-align: center; display: none" onclick="resetedit()" id="<?php echo $GLOBALS["id"] ?>abort">Abbrechen</btn>
            <input style="cursor: pointer; display: none" type="submit" name="submit" value="Speichern" id="<?php echo $GLOBALS["id"] ?>save">
        </div>
    </form>
</li>
<script>
    // location.reload(); return false;
    function edit(id) {
        $('#'+id+'edit').hide();
        $('#'+id+'abort').show();
        $('#'+id+'save').show();
        $('[id*="'+id+'"][id*="content"]').attr('class', 'edit');
        $('[id*="'+id+'"][id*="content"]').removeAttr('disabled');
    }
    function resetedit() {
        $('[id*="edit"]').show();
        $('[id*="abort"]').hide();
        $('[id*="save"]').hide();
        $('[id*="content"]').attr('class', 'normal');
        $('[id*="content"]').attr('disabled');
    }
</script>