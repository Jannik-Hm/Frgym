<?php
    session_name("userid_login");
    session_start();
    if(!isset($_SESSION["user_id"])) {
        header("Location: /admin/login/");
    }
?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
            <?php
                $root = realpath($_SERVER["DOCUMENT_ROOT"]);
                include_once "$root/admin/sites/head.html"
            ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js" integrity="sha512-szJ5FSo9hEmXXe7b5AUVtn/WnL8a5VofnFeYC2i2z03uS2LhAch7ewNLbl5flsEmTTimMN0enBZg/3sQ+YOSzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <title>Event hinzufügen - Admin Panel - Friedrich-Gymnasium Luckenwalde</title>
        </head>
        <body>
            <div class="bodyDiv">
            <?php
                include_once "$root/admin/sites/header.php";
            ?>
            <section style="text-align: left">
                <link rel="stylesheet" href="/new-css/lehrer.css">
                <link rel="stylesheet" href="/new-css/form.css">
                <script src="/js/easepick_bundle@1.2.1_dist_index.umd.min.js"></script>
                <section style="text-align: left; width: clamp(360px, 95%, 1000px);">
                    <div class="add-input">
                        <form id="calform">
                            <select id="calselector" required>
                                <option selected disabled>Kalendar auswählen:</option>
                            </select>
                            <br>
                            <input type="text" placeholder="Terminname*" id="eventname" required>
                            <br>
                            <input type="text" placeholder="Terminbeschreibung" id="eventdescr">
                            <br>
                            <input type="text" placeholder="Ort" id="eventloc">
                            <br>
                            <div style="display: flex; width: 100%; margin-bottom: 35px">
                                <div style="width: 50%; text-align: center">
                                    <span  style="margin-right: 10px;">Start</span>
                                    <input id="startdatetimepicker" style="text-align: center; cursor:pointer"/>
                                    <script>
                                        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                                            darkmode = true;
                                        }else{
                                            darkmode = false;
                                        }
                                        const startdatetime = new easepick.create({
                                            element: "#startdatetimepicker",
                                            css: [
                                                "/new-css/easepick.css"
                                            ],
                                            zIndex: 10,
                                            lang: "de-DE",
                                            format: "DD. MMM YYYY, HH:mm",
                                            autoApply: false,
                                            locale: {
                                                cancel: "Abbrechen",
                                                apply: "Bestätigen"
                                            },
                                            AmpPlugin: {
                                                dropdown: {
                                                    months: true
                                                },
                                                darkMode: darkmode
                                            },
                                            plugins: [
                                                "AmpPlugin",
                                                "TimePlugin"
                                            ]
                                        })
                                        startdatetime.setDate(new Date());
                                        startdatetime.setTime("00:00");
                                    </script>
                                </div>
                                <div style="width: 50%; text-align: center">
                                    <span style="margin-right: 10px;">Ende</span>
                                    <input id="enddatetimepicker" style="text-align: center; cursor:pointer"/>
                                    <script>
                                        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                                            darkmode = true;
                                        }else{
                                            darkmode = false;
                                        }
                                        const enddatetime = new easepick.create({
                                            element: "#enddatetimepicker",
                                            css: [
                                                "/new-css/easepick.css"
                                            ],
                                            zIndex: 10,
                                            lang: "de-DE",
                                            format: "DD. MMM YYYY, HH:mm",
                                            autoApply: false,
                                            locale: {
                                                cancel: "Abbrechen",
                                                apply: "Bestätigen"
                                            },
                                            AmpPlugin: {
                                                dropdown: {
                                                    months: true
                                                },
                                                darkMode: darkmode
                                            },
                                            plugins: [
                                                "AmpPlugin",
                                                "TimePlugin"
                                            ]
                                        })
                                        enddatetime.setDate(new Date());
                                        enddatetime.setTime("01:00");
                                    </script>
                                </div>
                            </div>
                            <input type="button" style="cursor: pointer" value="Speichern" onclick="addevent()"/>
                        </form>
                    </div>
                    <script>
                        function addcalselector(cal){
                            option = document.createElement("option");
                            option.value = cal.id;
                            option.innerHTML = cal.summary;
                            document.getElementById("calselector").append(option);
                        }
                        $.post("/admin/api/calendar.php", {"action": "getcallist"}, function (data) { JSON.parse(data).data.forEach(addcalselector) });
                        function addevent() {
                            if(document.getElementById("calform").reportValidity()){
                                start = "@"+startdatetime.getDate().getTime()/1000;
                                end = "@"+enddatetime.getDate().getTime()/1000;
                                $.post("/admin/api/calendar.php", {"action": "create_event", "calid":document.getElementById("calselector").value, "title":document.getElementById("eventname").value, "description":document.getElementById("eventdescr").value, "location":document.getElementById("eventloc").value, "start":start, "end":end}, function(data) {console.log(data)});
                            }
                        }
                    </script>
                </section>
            </section>
        </div>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>