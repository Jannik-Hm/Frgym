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
            <section style="text-align: left; margin-top: 25px">
                <link rel="stylesheet" href="/new-css/lehrer.css">
                <link rel="stylesheet" href="/new-css/form.css">
                <script src="/js/easepick_bundle@1.2.1_dist_index.umd.min.js"></script>
                <section style="text-align: left; width: clamp(360px, 95%, 1000px);">
                    <div class="add-input">
                        <form id="calform">
                            <div class="custom-select" style="width:200px;">
                                <select id="calselector" required>
                                    <option selected disabled>Kalendar auswählen:</option>
                                </select>
                            </div>
                            <style>
                                /* The container must be positioned relative: */
                                .custom-select {
                                position: relative;
                                font-family: Arial;
                                }

                                .custom-select select {
                                display: none; /*hide original SELECT element: */
                                }

                                .select-selected {
                                background-color: var(--inputbackground);
                                border-radius: 10px;
                                }

                                /* Style the arrow inside the select element: */
                                .select-selected:after {
                                position: absolute;
                                content: "";
                                top: 14px;
                                right: 10px;
                                width: 0;
                                height: 0;
                                border: 6px solid transparent;
                                border-color: #fff transparent transparent transparent;
                                }

                                /* Point the arrow upwards when the select box is open (active): */
                                .select-selected.select-arrow-active:after {
                                border-color: transparent transparent #fff transparent;
                                top: 7px;
                                }

                                /* style the items (options), including the selected item: */
                                .select-items div,.select-selected {
                                color: #ffffff;
                                padding: 8px 16px;
                                border: 1px solid transparent;
                                border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
                                cursor: pointer;
                                }

                                /* Style items (options): */
                                .select-items {
                                position: absolute;
                                background-color: var(--inputbackground);
                                border-radius: 10px;
                                top: 100%;
                                left: 0;
                                right: 0;
                                z-index: 99;
                                }

                                div.select-items div:first-child{
                                    border-top-right-radius: 10px;
                                    border-top-left-radius: 10px;
                                }

                                div.select-items div:last-child {
                                    border-bottom-right-radius: 10px;
                                    border-bottom-left-radius: 10px;
                                }

                                /* Hide the items when the select box is closed: */
                                .select-hide {
                                display: none;
                                }

                                .select-items div:hover, .same-as-selected {
                                background-color: rgba(0, 0, 0, 0.1);
                                }
                            </style>
                            <script>
                                var x, i, j, l, ll, selElmnt, a, b, c;
                                /* Look for any elements with the class "custom-select": */
                                x = document.getElementsByClassName("custom-select");
                                l = x.length;
                                for (i = 0; i < l; i++) {
                                selElmnt = x[i].getElementsByTagName("select")[0];
                                /* For each element, create a new DIV that will act as the selected item: */
                                a = document.createElement("DIV");
                                a.setAttribute("class", "select-selected");
                                a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
                                x[i].appendChild(a);
                                /* For each element, create a new DIV that will contain the option list: */
                                b = document.createElement("DIV");
                                b.setAttribute("class", "select-items select-hide");
                                function addtocssselector(){
                                    for (j = 1; j < selElmnt.length; j++) {
                                        /* For each option in the original select element,
                                        create a new DIV that will act as an option item: */
                                        c = document.createElement("DIV");
                                        c.innerHTML = selElmnt.options[j].innerHTML;
                                        c.addEventListener("click", function(e) {
                                            /* When an item is clicked, update the original select box,
                                            and the selected item: */
                                            var y, i, k, s, h, sl, yl;
                                            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                                            sl = s.length;
                                            h = this.parentNode.previousSibling;
                                            for (i = 0; i < sl; i++) {
                                            if (s.options[i].innerHTML == this.innerHTML) {
                                                s.selectedIndex = i;
                                                h.innerHTML = this.innerHTML;
                                                y = this.parentNode.getElementsByClassName("same-as-selected");
                                                yl = y.length;
                                                for (k = 0; k < yl; k++) {
                                                y[k].removeAttribute("class");
                                                }
                                                this.setAttribute("class", "same-as-selected");
                                                break;
                                            }
                                            }
                                            h.click();
                                        });
                                        b.appendChild(c);
                                    }
                                }
                                x[i].appendChild(b);
                                a.addEventListener("click", function(e) {
                                    /* When the select box is clicked, close any other select boxes,
                                    and open/close the current select box: */
                                    e.stopPropagation();
                                    closeAllSelect(this);
                                    this.nextSibling.classList.toggle("select-hide");
                                    this.classList.toggle("select-arrow-active");
                                });
                                }

                                function closeAllSelect(elmnt) {
                                /* A function that will close all select boxes in the document,
                                except the current select box: */
                                var x, y, i, xl, yl, arrNo = [];
                                x = document.getElementsByClassName("select-items");
                                y = document.getElementsByClassName("select-selected");
                                xl = x.length;
                                yl = y.length;
                                for (i = 0; i < yl; i++) {
                                    if (elmnt == y[i]) {
                                    arrNo.push(i)
                                    } else {
                                    y[i].classList.remove("select-arrow-active");
                                    }
                                }
                                for (i = 0; i < xl; i++) {
                                    if (arrNo.indexOf(i)) {
                                    x[i].classList.add("select-hide");
                                    }
                                }
                                }

                                /* If the user clicks anywhere outside the select box,
                                then close all select boxes: */
                                document.addEventListener("click", closeAllSelect);
                            </script>
                            <br>
                            <input type="text" placeholder="Terminname*" id="eventname" required>
                            <br>
                            <input type="text" placeholder="Terminbeschreibung" id="eventdescr">
                            <br>
                            <input type="text" placeholder="Ort" id="eventloc">
                            <br>
                            <div style="width: fit-content; width: -moz-fit-content; margin: auto">
                                <label class="chkbx_label"><input type="checkbox" id="dayeventcheck" onchange='daycheckchange(this)'><span class="chkbx" style="top: 5px;"></span>Ganztägig</label>
                            </div>
                            <div style="display: flex; width: 100%; margin-bottom: 35px">
                                <div style="width: 50%; text-align: center">
                                    <span  style="margin-right: 10px;">Start</span>
                                    <input id="startdatetimepicker" style="text-align: center; cursor:pointer"/>
                                    <input id="startdatepicker" style="text-align: center; cursor:pointer; display: none"/>
                                    <script>
                                        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                                            darkmode = true;
                                        }else{
                                            darkmode = false;
                                        }
                                        // Datetime Event
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
                                        // Fulldayevent
                                        const startdate = new easepick.create({
                                            element: "#startdatepicker",
                                            css: [
                                                "/new-css/easepick.css"
                                            ],
                                            zIndex: 10,
                                            lang: "de-DE",
                                            format: "DD. MMM YYYY",
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
                                                "AmpPlugin"
                                            ]
                                        });
                                        startdate.setDate(new Date());
                                    </script>
                                </div>
                                <div style="width: 50%; text-align: center">
                                    <span style="margin-right: 10px;">Ende</span>
                                    <input id="enddatetimepicker" style="text-align: center; cursor:pointer"/>
                                    <input id="enddatepicker" style="text-align: center; cursor:pointer; display: none"/>
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
                                        // Fulldayevent
                                        const enddate = new easepick.create({
                                            element: "#enddatepicker",
                                            css: [
                                                "/new-css/easepick.css"
                                            ],
                                            zIndex: 10,
                                            lang: "de-DE",
                                            format: "DD. MMM YYYY",
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
                                                "AmpPlugin"
                                            ]
                                        });
                                        enddate.setDate(new Date());
                                    </script>
                                </div>
                            </div>
                            <input type="button" style="cursor: pointer" value="Speichern" onclick="addevent()"/>
                        </form>
                    </div>
                    <script>
                        function addcalselector(callist){
                            callist.forEach(function(cal){
                                option = document.createElement("option");
                                option.value = cal.id;
                                option.innerHTML = cal.summary;
                                document.getElementById("calselector").append(option);
                            });
                            addtocssselector();
                            geteditdata();
                        }
                        $.post("/admin/api/calendar.php", {"action": "getcallist"}, function (data) { addcalselector(JSON.parse(data).data) });
                        function addevent() {
                            if(document.getElementById("calform").reportValidity()){
                                var start;
                                var end;
                                var isdayevent = false;
                                if(document.getElementById("dayeventcheck").checked){
                                    startd = startdate.getDate();
                                    start = startd.getFullYear() + "-" + ("0" + (startd.getMonth() + 1)).slice(-2) + "-" + ("0" + startd.getDate()).slice(-2);
                                    endd = new Date(enddate.getDate().getTime() + 86400000);
                                    end = endd.getFullYear() + "-" + ("0" + (endd.getMonth() + 1)).slice(-2) + "-" + ("0" + endd.getDate()).slice(-2);
                                    isdayevent = true;
                                }else{
                                    start = "@"+startdatetime.getDate().getTime()/1000;
                                    end = "@"+enddatetime.getDate().getTime()/1000;
                                }
                                uri = new URLSearchParams(window.location.search);
                                if(uri.has("edit") && uri.get("edit") == "true"){
                                    $.post("/admin/api/calendar.php", {"action": "update_event", "eventid":uri.get("eventid"), "calid":document.getElementById("calselector").value, "title":document.getElementById("eventname").value, "description":document.getElementById("eventdescr").value, "location":document.getElementById("eventloc").value, "start":start, "end":end, "isdayevent":isdayevent}, function(data) {console.log(data)});
                                }else{
                                    $.post("/admin/api/calendar.php", {"action": "create_event", "calid":document.getElementById("calselector").value, "title":document.getElementById("eventname").value, "description":document.getElementById("eventdescr").value, "location":document.getElementById("eventloc").value, "start":start, "end":end, "isdayevent":isdayevent}, function(data) {console.log(data)});
                                }
                            }
                        }
                        function daycheckchange(checkbox){
                            if(checkbox.checked == true){
                                document.getElementById("startdatepicker").style.display = "";
                                document.getElementById("enddatepicker").style.display = "";
                                document.getElementById("startdatetimepicker").style.display = "none";
                                document.getElementById("enddatetimepicker").style.display = "none";
                            }else{
                                document.getElementById("startdatepicker").style.display = "none";
                                document.getElementById("enddatepicker").style.display = "none";
                                document.getElementById("startdatetimepicker").style.display = "";
                                document.getElementById("enddatetimepicker").style.display = "";
                            }
                        }
                        function geteditdata() {
                            uri = new URLSearchParams(window.location.search);
                            if(uri.has("edit") && uri.get("edit") == "true"){
                                document.getElementsByClassName("custom-select")[0].hidden = true;
                                var eventid = uri.get("eventid");
                                var calid = uri.get("calid");
                                $.post("/admin/api/calendar.php", {"action": "get_eventdata", "calid": calid, "eventid": eventid}, function(data) {
                                    eventdata = JSON.parse(data).data;
                                    calname = document.querySelectorAll("option[value='"+calid+"']")[0].innerHTML;
                                    document.querySelectorAll("div.select-items div").forEach(function(calbutton){
                                        if(calbutton.innerHTML == calname){
                                            calbutton.click();
                                    }});
                                    document.getElementById("eventname").value = (typeof eventdata.summary !== "undefined") ? eventdata.summary : "";
                                    document.getElementById("eventdescr").value = (typeof eventdata.description !== "undefined") ? eventdata.description : "";
                                    document.getElementById("eventloc").value = (typeof eventdata.location !== "undefined") ? eventdata.location : "";
                                    if (typeof eventdata.start.date !== 'undefined') {
                                        document.getElementById("dayeventcheck").click();
                                        start = new Date(eventdata.start.date);
                                        end = new Date(eventdata.end.date);
                                        startdate.setDate(start);
                                        enddate.setDate(end);
                                        startdatetime.setDate(start);
                                        startdatetime.setTime("00:00");
                                        enddatetime.setDate(end);
                                        enddatetime.setTime("01:00");
                                    } else {
                                        document.getElementById("dayeventcheck");
                                        start = new Date(eventdata.start.dateTime);
                                        end = new Date(eventdata.end.dateTime);
                                        startdate.setDate(start);
                                        enddate.setDate(end);
                                        startdatetime.setDate(start);
                                        startdatetime.setTime(start.getHours() + ":" + start.getMinutes());
                                        enddatetime.setDate(end);
                                        enddatetime.setTime(end.getHours() + ":" + end.getMinutes());
                                    }
                                });
                            }
                        }
                    </script>
                </section>
            </section>
        </div>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>