function getcaldata(username, password, edit=false) {
    var ajax = $.post("/admin/api/calendar.php", { "action": "geteventlist", "username": username, "password_hash": password }, function (data) { console.log(ajax.status); processcaldata(JSON.parse(data)["data"], edit); }).fail(function(response){if(typeof wrongpassword == "function" && response.status==401){wrongpassword()};});
}

function deleteevent(calid, eventid){
    $.post("/admin/api/calendar.php", {"action":"delete_event", "calid":calid, "eventid":eventid}, function(){$(".terminelistdiv, #pdfbtn").hide();$(".loadingscreen").show();$(".terminelistdiv").html("");getcaldata(null, null, true)});
}

function geticsuri(id, datestamp, dayevent, start, end, summary, description = null, location = null) {
    var ics = [];
    ics.push("BEGIN:VCALENDAR");
    ics.push("VERSION:2.0");
    ics.push("PRODID:-// https://frgym.de// Schultermin //DE");
    ics.push("METHOD:PUBLISH");
    ics.push("BEGIN:VEVENT");
    ics.push("DTSTAMP:" + datestamp);
    start = new Date(Number(start));
    end = new Date(Number(end));
    if (dayevent) {
        ics.push("DTSTART;VALUE=DATE:" + start.getFullYear() + ("0" + (start.getMonth() + 1)).slice(-2) + ("0" + start.getDate()).slice(-2));
        ics.push("DTEND;VALUE=DATE:" + end.getFullYear() + ("0" + (end.getMonth() + 1)).slice(-2) + ("0" + end.getDate()).slice(-2));
    } else {
        ics.push("DTSTART;TZID=Europe/Berlin:" + start.getFullYear() + ("0" + (start.getMonth() + 1)).slice(-2) + ("0" + start.getDate()).slice(-2) + "T" + ("0" + start.getHours()).slice(-2) + ("0" + start.getMinutes()).slice(-2) + ("0" + start.getSeconds()).slice(-2));
        ics.push("DTEND;TZID=Europe/Berlin:" + end.getFullYear() + ("0" + (end.getMonth() + 1)).slice(-2) + ("0" + end.getDate()).slice(-2) + "T" + ("0" + end.getHours()).slice(-2) + ("0" + end.getMinutes()).slice(-2) + ("0" + end.getSeconds()).slice(-2));
    }
    ics.push("SUMMARY;CHARSET=UTF-8:" + unescape(encodeURI(summary)));
    if (description != null && description != "" && description != "null") {
        ics.push("DESCRIPTION;CHARSET=UTF-8:" + unescape(encodeURI(description)));
    }
    if (location != null && location != "" && location != "null") {
        ics.push("LOCATION;CHARSET=UTF-8:" + unescape(encodeURI(location)));
    }
    ics.push("END:VEVENT");
    ics.push("END:VCALENDAR");
    var icsstring = ics.join("\r\n");
    return "data:text/calendar;charset=utf8," + escape(icsstring);
};
Date.prototype.getFullDate = function () {
    return ("0" + this.getDate()).slice(-2) + "." + ("0" + (this.getMonth() + 1)).slice(-2) + "." + this.getFullYear();
};
Date.prototype.getClock = function () {
    return ("0" + this.getHours()).slice(-2) + ":" + ("0" + this.getMinutes()).slice(-2);
};
function processcaldata(data, edit = false) {
    var current = new Date();
    var currentdate = new Date(current.getFullYear() + "-" + ("0" + (current.getMonth() + 1)).slice(-2) + "-" + ("0" + current.getDate()).slice(-2)).getTime();
    console.log(data);
    var dd = createcal(data);
    eventlist = [];
    data.forEach(function (val, key) {
        if (val["name"] == "" || val["name"] == null) return;
        if (val["end"] < new Date().getTime()) return;
        var startdate = new Date(val["start"]);
        var startdatetime = new Date(startdate.getFullYear() + "-" + ("0" + (startdate.getMonth() + 1)).slice(-2) + "-" + ("0" + startdate.getDate()).slice(-2)).getTime();
        var daysleft = Math.ceil((startdatetime - currentdate) / (86400000));
        if (daysleft > 365) return;
        var popuptime = new Date(val["start"]).getFullDate();
        if (new Date(val["start"]).getFullDate() != new Date(val["end"] - 86400000).getFullDate()) {
            if (val["istime"]) {
                if (new Date(val["start"]).getFullDate() == new Date(val["end"]).getFullDate()) {
                    popuptime += " " + new Date(val["start"]).getClock() + " - " + new Date(val["end"]).getClock();
                } else {
                    popuptime += " " + new Date(val["start"]).getClock() + " - " + new Date(val["end"]).getFullDate() + " - " + new Date(val["end"]).getClock();
                }
            } else {
                popuptime += " - " + new Date(val["end"] - 86400000).getFullDate();
            }
        }
        var daysleftspan = "";
        if (daysleft < 1) {
            daysleftspan = "<span style='color: var(--newstextcolor)'>heute</span> ";
        } else if (daysleft == 1) {
            daysleftspan = "<span style='color: var(--newstextcolor)'>morgen</span> ";
        } else {
            daysleftspan = "<span style='color: var(--newstextcolor)'> in " + daysleft + " Tagen</span> ";
        }
        var event = document.createElement("a");
        event.setAttribute("style", "cursor:pointer");
        event.setAttribute("onclick", "$(\".readmorebox\").show();$(\"#termintest\").html(\"" + val["name"] + "\");$(\"#termintest1\").html(\"" + val["eventtype"] + "\");$(\"#popuptime\").html(\"" + popuptime + "\");$(\"#ics #icslink\").attr(\"href\", geticsuri(\"bla@frgym.de\", Date().now, " + !val["istime"] + ", " + val["start"] + ", " + val["end"] + ", \"" + (val["name"]) + "\", \"" + val["description"] + "\", \"" + val["location"] + "\"));$(\"#ics #icslink\").attr(\"download\", \"" + val["name"] + ".ics\");"
        + ((val["description"] != null) ? "$(\"#popupdesc\").html(\"" + val["description"] + "\");$(\"#description\").show();" : "$(\"#description\").hide();") +
        ((val["location"] != null) ? "$(\"#popuploc\").html(\"" + val["location"] + "\");$(\"#location\").show();" : "$(\"#location\").hide();") +
        "$(\"#popuptermincolordiv\").css(\"background-color\",\"" + val["color"] + "\");");
        event.innerHTML = "<div class='termindiv'><i class='termincolori'><div style='background-color:" + val["color"] + ";><div style='background-color:" + val["color"] + ";'></div></i><span>" + val["name"] + " " + daysleftspan + "</span>"+(edit ? ("<span style='float:right; margin-left: 10px'><a  title=\"Bearbeiten\" onclick=\"event.stopPropagation()\"; href=\"/admin/calendar/entry?edit=true&eventid="+encodeURI(val.eventid)+"&calid="+encodeURI(val.calid)+"\"><i class=\"fas fa-edit\"></i></a><i class=\"fas fa-trash\" onclick=\"event.stopPropagation();deleteevent('"+val.calid+"','"+val.eventid+"')\" title=\"Löschen\" style=\"color:#F75140;margin-left: 5px\"></i></span>") : "")+"</div>";
        element = document.getElementsByClassName("terminelistdiv")[0].appendChild(event);
        eventlist.push({"element":element, "eventid":val.eventid, "calid":val.calid});
    });
    $(".terminelistdiv, #pdfbtn").show();
    $(".loadingscreen").hide();
}