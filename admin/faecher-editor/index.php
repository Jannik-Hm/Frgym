<?php
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    setsession();
    verifylogin();
?>

<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
            <?php
                include_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/sites/head.html"
            ?>
            <title>Fachseite bearbeiten - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
            <link rel="stylesheet" href="/new-css/faecher.css">
        </head>
        <body>
            <?php
            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/sites/header.php";
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            getperm();
            if($GLOBALS["fachbereich"] == "admin"){$GLOBALS["fachbereich"] = $_GET["fach"];}
            // if($GLOBALS["fachbereich"] == $_GET["fach"] && $GLOBALS["fachbereich"] != NULL && $_GET["fach"] != NULL){
                echo '
                <section style="height: 25px"></section>
                <ul class="test" style="list-style: none; padding: 25px; margin-left: 50px; margin-right: 50px; background-color: var(--inputbackground); border-radius: 15px; color: var(--inputcolor);">';
                echo '<script>var ajaxsave = {}; var segment = {};load = $.post("/admin/api/faecher.php", {"action": "getfachelements", "fach": "'.$_GET["fach"].'", "editor": true}, function(data){$(".test").append(data);$(".test").dragndrop("reload");$("textarea").each(function() {autosizetext(this)}).on("input", function() {autosizetext(this)});$("btn[id*=edit]").click(function() {$(".test").dragndrop();$(".test").dragndrop("unload")});$("input[id*=abort]").click(function() {$(".test").dragndrop()})})</script>';
                echo '</ul>';
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
                dragndrop(".test");
                segment_selector(".test");
                include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/stylemenu.html";
                ?>
                <style>
                    .chkbx_label,
                    .customradio {
                        display: block;
                        position: relative;
                        padding-left: 20px!important;
                        margin-bottom: 7px!important;
                        cursor: pointer;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        user-select: none;
                    }

                    .chkbx_label input[type="checkbox"] {
                        display: none;
                    }

                    .chkbx_label .chkbx,
                    .customradio .radiochk {
                        position: absolute;
                        left: 2px;
                        height: 15px;
                        width: 15px;
                        background-color: #eee;
                    }

                    .chkbx_label .chkbx {
                        border-radius: 3px;
                        top: 1px;
                    }

                    .customradio .radiochk {
                        border-radius: 50%;
                        top: 5px;
                    }
                    
                    /* On mouse-over, add a grey background color */
                    .chkbx_label:hover input[type="checkbox"] ~ .chkbx,
                    .customradio:hover input[type="radio"] ~ .radiochk {
                        background-color: #ccc;
                    }

                    /* When the checkbox is checked, add a blue background */
                    .chkbx_label input[type="checkbox"]:checked ~ .chkbx,
                    .customradio input[type="radio"]:checked ~ .radiochk {
                        background-color: #4ba5c2;
                    }

                    /* Create the checkmark/indicator (hidden when not checked) */
                    .chkbx_label .chkbx:after,
                    .customradio .radiochk:after {
                        content: "";
                        position: absolute;
                        display: none;
                    }

                    /* Show the checkmark when checked */
                    .chkbx_label input[type="checkbox"]:checked ~ .chkbx:after,
                    .customradio input[type="radio"]:checked ~ .radiochk:after {
                        display: block;
                    }

                    /* Style the checkmark/indicator */
                    .chkbx_label .chkbx:after {
                        left: 4.25px;
                        top: 2px;
                        width: 3px;
                        height: 6px;
                        border: solid white;
                        border-width: 0 3px 3px 0;
                        -webkit-transform: rotate(45deg);
                        -ms-transform: rotate(45deg);
                        transform: rotate(45deg);
                    }
                </style>
                <section style="padding: 10px; margin: auto; margin-top: 25px; margin-bottom: 25px; border-radius: 15px; width: clamp(300px, 25vw, 600px);background-color: var(--inputbackground);">
                    <form method="POST" enctype="multipart/form-data" style="display: inline-flex">
                        <label class="chkbx_label" style="margin: auto; width: fit-content; margin-right: 15px"><input type="checkbox" value="visible"><span class="chkbx"></span>Öffentlich sichtbar</label>
                        <input name="contenttype" type="hidden" value="visibility"></input>
                        <input class="button" style="cursor: pointer; margin-left: 15px" type="button" id="setvisibility" value="Speichern">
                    </form>
                </section>
                <script>
                    function autosizetext(item){
                        item.style.height = 0;
                        item.style.height = ( item.scrollHeight ) + "px";
                    }
                    $.post("/admin/api/faecher.php", {action: "getvisibility", fach: "<?php echo $_GET["fach"] ?>"}, getvisibility);
                    function getvisibility(data){
                        data=JSON.parse(data);
                        selector = $("input[value='visible']");
                        selector.attr("id", data.data.id);
                        if(data.data.content=="visible"){
                            selector.prop("checked", true);
                        }
                        $("#setvisibility").click(function(){$.post("/admin/api/faecher.php", {action: "setvisibility", id: data.data.id, fach: "<?php echo $_GET["fach"] ?>", visibility: (($("#"+data.data.id).is(":checked")) ? "visible":"")}, function(data){console.log(data)})});
                    }
                </script>
            </div>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html" ?>
    </body>
</html>
