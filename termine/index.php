<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    include_once "$root/sites/head.html"
    ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.js"></script>
    <link rel="stylesheet" href="/new-css/termine.css">
    <title>Termine - Friedrich-Gymnasium Luckenwalde</title>
</head>

    <body>
      <?php include_once "$root/sites/header.html"; ?>
          <?php
            $username = "";
            $password_hash = "";
            $curl = curl_init();
            curl_setopt_array($curl, [
              CURLOPT_URL => "https://frgym.greenygames.de/admin/api/calendar.php",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"action\"\r\n\r\ngeteventlist\r\n-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"username\"\r\n\r\n".$username."\r\n-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"password_hash\"\r\n\r\n".$password_hash."\r\n-----011000010111000001101001--\r\n",
              CURLOPT_COOKIE => "userid_login=7e41d17fb0044b4586ea2772cb7057db",
              CURLOPT_HTTPHEADER => [
                "Content-Type: multipart/form-data; boundary=---011000010111000001101001"
              ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $event_array = json_decode($response, true)["data"];
            $event_string = "[";
            foreach($event_array as $entry){
              if($entry["name"] == "" || $entry["name"] == NULL) continue;
              $event_string = $event_string."['".$entry["name"]."', new Date(".json_encode(date('Y/m/d H:i:s', $entry["start"]))."), new Date(".json_encode(date('Y/m/d H:i:s', $entry["end"]))."), '".$entry["color"]."', '".$entry["eventtype"]."', ".$entry["istime"]."],";
            }
            $event_string = $event_string."]";
          ?>
  <br>
  <section>
    <div class="terminelistdiv">
    <?php
      foreach($event_array as $entry){
        if($entry["name"] == "" || $entry["name"] == NULL) continue;
        if($entry["end"] < time()) continue;
        $daysleft = ceil((mktime(0,0,0,date("m", $entry["start"]), date("d", $entry["start"]), date("Y", $entry["start"]))-mktime(0,0,0,date("m", time()), date("d", time()), date("Y", time())))/86400);
        if($daysleft > 365) continue;
        if($entry["istime"] || date('d.m.Y', $entry["end"]-86400) != date('d.m.Y', $entry["start"])) {
          if($entry["istime"]){
            if(date('d.m.Y', $entry["end"]) == date('d.m.Y', $entry["start"])){
              $endtext = " - ".date('H:i', $entry["end"]);
            }else{
              $endtext = " - ".date('d.m.Y H:i', $entry["end"]);
            }
          }else{
            $endtext = " - ".date('d.m.Y', $entry["end"]-86400);
          }
        }else{
          $endtext = "";
        }
        echo("
        <a style='cursor: pointer' onclick='
          $(\".readmorebox\").show();
          $(\"#termintest\").html(\"".$entry["name"]."\");
          $(\"#termintest1\").html(\"".$entry["eventtype"]."\");
          $(\"#popuptime\").html(\"".(($entry["istime"]) ? date('d.m.Y H:i', $entry["start"]) : date('d.m.Y', $entry["start"])).$endtext."\");
          $(\"#ics #icslink\").attr(\"href\", geticsuri(\"bla@frgym.de\", Date().now, \"".!$entry["istime"]."\", \"".$entry["start"]."\", \"".$entry["end"]."\", \"".($entry["name"])."\", \"".(($entry["eventtype"] == "Feiertage in Deutschland") ? NULL : $entry["description"])."\", \"".$entry["location"]."\"));
          $(\"#ics #icslink\").attr(\"download\", \"".$entry["name"].".ics\");");
          if(isset($entry["description"]) && $entry["eventtype"] != "Feiertage in Deutschland"){
            echo("$(\"#popupdesc\").html(\"".$entry["description"]."\");$(\"#description\").show();");
          }else{
            echo("$(\"#description\").hide();");
          }
          if(isset($entry["location"])){
            echo("$(\"#popuploc\").html(\"".$entry["location"]."\");$(\"#location\").show();");
          }else{
            echo("$(\"#location\").hide();");
          }
          echo("
          $(\"#popuptermincolordiv\").css(\"background-color\",\"".$entry["color"]."\");
          '>
          <div class='termindiv'><i class='termincolori'><div style='background-color:".$entry["color"].";'></div></i><span>");
        echo ($entry["name"]. " ");
        if($daysleft < 1){
          echo ("<span style='color: var(--newstextcolor)'>heute</span> ");
        }elseif($daysleft == 1) {
          echo ("<span style='color: var(--newstextcolor)'>morgen</span> ");
        }else{
          echo ("<span style='color: var(--newstextcolor)'> in ".$daysleft." Tag(en)</span> ");
        }
        echo("</span></div></a>");
      }
    ?>
    <script>
      function geticsuri(id, datestamp, dayevent, start, end, summary, description=null, location=null){
          var ics = [];
          ics.push("BEGIN:VCALENDAR");
          ics.push("VERSION:2.0");
          ics.push("PRODID:-// https://frgym.de// Schultermin //DE");
          ics.push("METHOD:PUBLISH");
          ics.push("BEGIN:VEVENT");
          ics.push("DTSTAMP:"+datestamp);
          var start = new Date(Number(start*1000));
          var end = new Date(Number(end*1000));
          if(dayevent){
            ics.push("DTSTART;VALUE=DATE:"+start.getFullYear()+("0" + (start.getMonth()+1)).slice(-2)+("0" + start.getDate()).slice(-2));
            ics.push("DTEND;VALUE=DATE:"+end.getFullYear()+("0" + (end.getMonth()+1)).slice(-2)+("0" + end.getDate()).slice(-2));
          }else{
            ics.push("DTSTART;TZID=Europe/Berlin:"+start.getFullYear()+("0" + (start.getMonth()+1)).slice(-2)+("0" + start.getDate()).slice(-2)+"T"+("0" + start.getHours()).slice(-2)+("0" + start.getMinutes()).slice(-2)+("0" + start.getSeconds()).slice(-2));
            ics.push("DTEND;TZID=Europe/Berlin:"+end.getFullYear()+("0" + (end.getMonth()+1)).slice(-2)+("0" + end.getDate()).slice(-2)+"T"+("0" + end.getHours()).slice(-2)+("0" + end.getMinutes()).slice(-2)+("0" + end.getSeconds()).slice(-2));
          }
          ics.push("SUMMARY;CHARSET=UTF-8:"+unescape(encodeURI(summary)));
          if(description != null && description != ""){
            ics.push("DESCRIPTION;CHARSET=UTF-8:"+unescape(encodeURI(description)));
          }
          if(location != null && location != ""){
            ics.push("LOCATION;CHARSET=UTF-8:"+unescape(encodeURI(location)));
          }
          ics.push("END:VEVENT");
          ics.push("END:VCALENDAR");
          var icsstring = ics.join("\r\n");
          return "data:text/calendar;charset=utf8," + escape(icsstring);
      }
    </script>
    </div>
  </section>
        <script>
            // playground requires you to assign document definition to a variable called dd
            // playgroundsite: http://pdfmake.org/playground.html

            let months = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];

            let weekdays = ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"];

            firstaweekdate = [22, 8, 2022];

            var mapObj = {
              'Beratung der Schulleitung': "SD",
              'möglicher Termin für Elternversammlung': "EV",
              Schulfahrt: "SF",
              Betriebspraktikum: "BP",
              'Projektwoche Klasse 7': "PT7",
              Schulkonferenz:"SK",
              'Schülerkonferenz':"SÜ",
              Elternkonferenz:"EK",
              Klassenkonferenz:"KK",
              Lehrerkonferenz:"LK",
              Klausur:"K"
            };

            var feriencal = "Ferien";

            var feiertag = "Feiertage in Deutschland";

            var jahrspanne = [];

            var displayedmonths = [8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6, 7];

            if(new Date().getMonth() + 1 > 7){
              jahrspanne[0] = new Date().getFullYear();
              jahrspanne[1] = new Date().getFullYear()+1;
            }else{
              jahrspanne[0] = new Date().getFullYear()-1;
              jahrspanne[1] = new Date().getFullYear();
            }

            var apiinput = <?php echo($event_string) ?>;

            var Termine = [];

            var termincounter = 0;

            var globaltable = [];

              function generatepagetable(pageCount){
                  for(var z=0; z<pageCount; z++){
                    var pagetable = new Array(32);
                    for(var j=0; j<pagetable.length; j++){
                        var rowtable = new Array(6);
                        for(var i=0; i<rowtable.length; i++){rowtable[i]={};}
                        pagetable[j]=rowtable;
                    }
                    globaltable.push(pagetable);
                  }
              }

              generatepagetable(2);

              for(var i = 0; i<globaltable.length; i++){
                  generateTable(globaltable[i], i+1);
              }

              function generateTable(table, page) {
                  var year2;
                  var jahr;
                  var monthmodifier = (page-1)*6;
                  if(page>1){
                      year2 = true;
                  }
              for(var i = 0; i < 6; i++){ // header for first table page
                table[0][i] = {text: months[displayedmonths[monthmodifier+i]-1], bold: true, alignment: 'center'};
                if(displayedmonths[monthmodifier+i]<displayedmonths[monthmodifier+i-1]){
                    year2 = true;
                }
                if(year2){
                    jahr = jahrspanne[1];
                    table[0][i].fillColor = '#7BC3CE';
                }else{
                    jahr = jahrspanne[0];
                    table[0][i].fillColor = '#7BCE84';
                }
                table[0][i].text = table[0][i].text + " "+ jahr;
                var monthlength = new Date(jahr, displayedmonths[monthmodifier+i], 0).getDate();
                for(var j = 1; j <= 31; j++){
                    table[j][i]["columns"] = [];
                    table[j][i]["columns"][0] = {};
                    if (j<=monthlength){
                        var dayofweek = new Date(jahr, displayedmonths[monthmodifier+i]-1, j).getDay();
                        table[j][i].columns[0]["text"] = [j, {text: ' '+weekdays[dayofweek]}];
                        if(dayofweek === 1){
                            table[j][i].columns[0].text[1].monthlength = monthlength;
                        }
                        if(dayofweek === 6){
                            table[j][i].columns[0].bold = true;
                            table[j][i].fillColor = '#E8E5A0';
                        }else if(dayofweek === 0){
                            table[j][i].columns[0].bold = true;
                            table[j][i].fillColor = '#E8D0A0';
                        }else{
                            table[j][i].columns[0].bold = false;
                        }
                    }else{
                        table[j][i].columns[0].text = null;
                        table[j][i].fillColor = '#B8B8B8';
                    }

                }
            }
              }

            function displayevent(table, date, month, text, color, calendar){
              if(text == "" || text == null){
                return;
              }
              var re = new RegExp(Object.keys(mapObj).join("|"),"gi");
              text = text.replace(re, function(matched){
                return mapObj[matched];
              });
              var isferien = false;
              if(calendar == feiertag){
                  for(var k=2; k<table[date][month%6].columns[0].text.length; k++){
                      if(table[date][month%6].columns[0].text[k].calendar == feriencal){
                        isferien = true;
                      }
                      delete table[date][month%6].columns[0].text[k];
                  }
                  table[date][month % 6].columns[0].text[2] = {text: ' '+text, color: color, calendar: calendar, isferien: (isferien ? true : false)};
                  return;
              }
              if(typeof table[date][month%6].columns[0].text[2] !== 'undefined' && table[date][month%6].columns[0].text[2].calendar == feiertag){
                  if(calendar == feriencal){
                    table[date][month % 6].columns[0].text[2].isferien = true;
                  }
                  return;
              }
              if(text !== "" && text !== null){
                if(table[date][month % 6].columns[0].text.length >= 3 && calendar != feiertag){
                    table[date][month % 6].columns[0].text.push({text: ' /', color: ''});
                }
                if(calendar == feriencal){isferien = true;}
                table[date][month % 6].columns[0].text.push({text: ' '+text, color: color, calendar: calendar, isferien: (isferien ? true : false)});
              }
            }

            function displayevents(input){
                for(var i=0; i<input.length; i++){
                    var year = input[i][1].getFullYear();
                    if(year >= jahrspanne[0]){
                        var termin = {begin:{}, end:{}};
                        termin.begin.year = Number(input[i][1].getFullYear());
                        termin.end.year = Number(input[i][2].getFullYear());
                        termin.begin.month = displayedmonths.findIndex(x => x === input[i][1].getMonth()+1);
                        termin.end.month = displayedmonths.findIndex(x => x === input[i][2].getMonth()+1);
                        termin.begin.date = Number(input[i][1].getDate());
                        termin.end.date = Number(input[i][2].getDate());
                        if(input[i][5] != 1){
                          termin.end.date = termin.end.date - 1;
                        }
                        var yeardifference = termin.end.year - termin.begin.year;
                        var monthdifference;
                        if(termin.begin.year == jahrspanne[0] && input[i][1].getMonth()+1 < displayedmonths[0] && input[i][2].getMonth()+1 < displayedmonths[0]) continue;
                        if(termin.begin.year == jahrspanne[1] && input[i][1].getMonth()+1 > displayedmonths[11]) continue;
                        if(termin.begin.year>jahrspanne[1]) continue;
                        if(termin.begin.year == termin.end.year && termin.begin.month == termin.end.month){
                            monthdifference = 0;
                        }else if(termin.begin.year == termin.end.year && termin.begin.month<termin.end.month){
                            monthdifference = displayedmonths[termin.end.month]-displayedmonths[termin.begin.month];
                        }else if(termin.begin.year == jahrspanne[0] && input[i][1].getMonth()+1<displayedmonths[0] && input[i][2].getMonth()+1 >= displayedmonths[0]){
                            for(var m=0;m<=termin.end.month;m++){
                              if(m == termin.end.month){
                                for(var d=1; d<=termin.end.date; d++){
                                  var table = Math.floor(m/6);
                                  if(globaltable[table][d][m % 6].text!==null){
                                      displayevent(globaltable[table], d, m, input[i][0], input[i][3], input[i][4]);
                                  }
                                }
                              }else{
                                for(var d=1; d<=31; d++){
                                  var table = Math.floor(m/6);
                                  if(globaltable[table][d][m % 6].text!==null){
                                      displayevent(globaltable[table], d, m, input[i][0], input[i][3], input[i][4]);
                                  }
                                }
                              }
                            }
                        }else if(termin.begin.year == jahrspanne[1] && input[i][1].getMonth()+1<=displayedmonths[displayedmonths.length-1] && input[i][2].getMonth()+1 > displayedmonths[displayedmonths.length-1]){
                            for(var m=termin.begin.month;m<=displayedmonths.length-1;m++){
                              if(m == termin.begin.month){
                                for(var d=termin.begin.date; d<=31; d++){
                                  var table = Math.floor(m/6);
                                  if(globaltable[table][d][m % 6].text!==null){
                                      displayevent(globaltable[table], d, m, input[i][0], input[i][3], input[i][4]);
                                  }
                                }
                              }else{
                                for(var d=1; d<=31; d++){
                                  var table = Math.floor(m/6);
                                  if(globaltable[table][d][m % 6].text!==null){
                                      displayevent(globaltable[table], d, m, input[i][0], input[i][3], input[i][4]);
                                  }
                                }
                              }
                            }
                        }else{
                            for(var y=termin.begin.year; y<termin.end.year; y++){
                                if(y==termin.begin.year){
                                    monthdifference = 12 - displayedmonths[termin.begin.month];
                                }else{
                                    monthdifference += 12;
                                }
                            }
                            monthdifference += displayedmonths[termin.end.month];
                        }
                        if(monthdifference>0){
                            for(var m = termin.begin.month; m<=termin.end.month; m++){
                                if(m==termin.begin.month){
                                    for(var d = termin.begin.date; d<=31; d++){
                                        var table = Math.floor(m/6);
                                        if(globaltable[0][d][m % 6].text!==null){
                                            displayevent(globaltable[table], d, m, input[i][0], input[i][3], input[i][4]);
                                        }
                                    }
                                }else if(m==termin.end.month){
                                    for(var d = 1; d<=termin.end.date; d++){
                                        var table = Math.floor(m/6);
                                        if(globaltable[table][d][m % 6].text!==null){
                                            displayevent(globaltable[table], d, m, input[i][0], input[i][3], input[i][4]);
                                        }
                                        // console.log("Last Month:"+d+"m:"+m);
                                    }
                                }else{
                                    for(var d = 1; d<=31; d++){
                                        var table = Math.floor(m/6);
                                        if(globaltable[table][d][m%6].text!==null){
                                            displayevent(globaltable[table], d, m%6, input[i][0], input[i][3], input[i][4]);
                                        }
                                    };
                                }
                            }
                        }else if(monthdifference == 0 && termin.begin.date != termin.end.date){
                          if(termin.begin.month == termin.end.month){
                            for(var d=termin.begin.date; d<=termin.end.date; d++){
                              var monthmodifier = (year-jahrspanne[0])*12;
                              monthmodifier = monthmodifier + displayedmonths[termin.begin.month] - displayedmonths[0];
                              var table = Math.floor(monthmodifier/6);
                              displayevent(globaltable[table], d, termin.begin.month, input[i][0], input[i][3], input[i][4]);
                            }
                          }
                        }else{
                            if(input[i][0] !== "" && input[i][0] !== null && input[i][0] !== undefined){
                              var monthmodifier = (year-jahrspanne[0])*12;
                              monthmodifier = monthmodifier + displayedmonths[termin.begin.month] - displayedmonths[0];
                              var table = Math.floor(monthmodifier/6);
                              if(globaltable[table] !== undefined){
                                displayevent(globaltable[table], termin.begin.date, termin.begin.month, input[i][0], input[i][3], input[i][4]);
                              }
                            }
                        }
                    }
                }
            }

            displayevents(apiinput);

            Date.prototype.getWeekNumber = function(){
              var d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
              var dayNum = d.getUTCDay() || 7;
              d.setUTCDate(d.getUTCDate() + 4 - dayNum);
              var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
              return Math.ceil((((d - yearStart) / 86400000) + 1)/7)
            };

            console.log(globaltable);
            var aweekcounter = 0;
            for(var cal=0; cal<globaltable.length; cal++){
              for(var mon=0; mon<globaltable[cal][0].length; mon++){
                for(var day=1; day<globaltable[cal].length; day++){
                  if(globaltable[cal][day][mon].columns[0].text != null && globaltable[cal][day][mon].columns[0].text[1].text == " "+weekdays[1]){
                    globaltable[cal][day][mon].columns[1] = {text: [], alignment: "right", width: "auto", fontSize: 8};
                    if(cal == 0 && mon == 0 && day<= 7){
                      var startweeknum = new Date(jahrspanne[cal], displayedmonths[mon]-1, day).getWeekNumber();
                    }else{
                      if(displayedmonths[mon+(6*cal)] == 1 && day <= 7){
                        startweeknum = 1;
                      }else{
                        startweeknum++;
                      }
                    }
                    globaltable[cal][day][mon].columns[1].text.push(startweeknum);
                    if(cal == 0 && mon < displayedmonths.findIndex(x => x === firstaweekdate[1])){
                      continue;
                    }else if(cal == 0 && mon == displayedmonths.findIndex(x => x === firstaweekdate[1]) && day < firstaweekdate[0]){
                      continue;
                    }else if(typeof globaltable[cal][day][mon].columns[0].text[2] != "undefined" && globaltable[cal][day][mon].columns[0].text[2].isferien){
                      if(globaltable[cal][day][mon].columns[0].text[1].monthlength - day >= 5){
                        friday = day+4
                        if(typeof globaltable[cal][friday][mon].columns[0].text[2] != "undefined" && globaltable[cal][friday][mon].columns[0].text[2].isferien){
                          continue;
                        }
                      }else{
                        friday = 5 - globaltable[cal][day][mon].columns[0].text[1].monthlength + day - 1;
                        if(mon+1 < 6){
                          var nextmon = mon + 1;
                          var nextcal = cal;
                        }else{
                          var nextmon = mon + 1 - 6;
                          var nextcal = cal+1;
                        }
                        if(nextcal > 1){continue;}
                        if(typeof globaltable[nextcal][friday][nextmon].columns[0].text[2] != "undefined" && globaltable[nextcal][friday][nextmon].columns[0].text[2].isferien){
                          continue;
                        }
                      }
                    }
                    if(aweekcounter % 2 == 0){
                      globaltable[cal][day][mon].columns[1].text.unshift("A ");
                    }else{
                      globaltable[cal][day][mon].columns[1].text.unshift("B ");
                    }
                    aweekcounter++;
                  }
                }
              }
            }

            var dd = {
              info: {
                  title: 'Schulkalender '+jahrspanne[0]+"/"+jahrspanne[1].toString().substring(jahrspanne[1].toString().length-2),
                  author: 'Friedrich-Gymnasium Luckenwalde',
              },
                // a string or { width: number, height: number }
              pageSize: 'A4',

              // by default we use portrait, you can change it to landscape if you wish
              pageOrientation: 'landscape',

              // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
              pageMargins: [ 40, 40, 40, 40 ],
              header: {text: 'Schulkalender '+jahrspanne[0]+"/"+jahrspanne[1].toString().substring(jahrspanne[1].toString().length-2), margin: [40, 21, 0, 0], fontSize: 16, bold: true},
              footer: {
                  columns: [
                      {text: 'SD - Beratung der Schulleitung\nEV - möglicher Termin für Elternversammlung\nSF - Schulfahrt'},
                      {text: 'BP - Betriebspraktikum\nPT7 - Projektwoche Klasse 7\nSK - Schulkonferenz'},
                      {text: 'SÜ - Schülerkonferenz\nEK - Elternkonferenz\nKK - Klassenkonferenz'},
                      {text: 'LK - Lehrerkonferenz\nK - Klausur'}
                  ],
                  margin: [50, -10, 0, 0],
                  fontSize: 9
              },
              content: [
                {
                  layout: {
                    hLineWidth: function (i, node) {
                      return (i === 0 || i === node.table.body.length) ? 0.6 : 0.3;
                    },
                    vLineWidth: function (i, node) {
                      return (i === 0 || i === node.table.widths.length) ? 0.6 : 0.3;
                    },
                  },
                  table: {
                    // headers are automatically repeated if the table spans over multiple pages
                    // you can declare how many rows should be treated as headers
                    headerRows: 1,
                    widths: [ '*', '*', '*', '*', '*', '*' ],

                    body: globaltable[0]
                  },
                  pageBreak: "after",
                  fontSize: 9.5
                },
                {
                  layout: {
                    hLineWidth: function (i, node) {
                      return (i === 0 || i === node.table.body.length) ? 0.6 : 0.3;
                    },
                    vLineWidth: function (i, node) {
                      return (i === 0 || i === node.table.widths.length) ? 0.6 : 0.3;
                    },
                  },
                  table: {
                    // headers are automatically repeated if the table spans over multiple pages
                    // you can declare how many rows should be treated as headers
                    headerRows: 1,
                    widths: [ '*', '*', '*', '*', '*', '*' ],

                    body: globaltable[1]
                  },
                  fontSize: 9.5
                }
              ]

            };
        </script>
        <button href="" onclick="pdfMake.createPdf(dd).open();" id="pdfbtn">PDF generieren</button>
        <div onclick="event.stopPropagation();$('.readmorebox').hide()" style='left: 0;' class='readmorebox'>
                <span class='helper'></span>
                <div onclick="event.stopPropagation();" class='scroll'>
                    <div onclick="event.stopPropagation();$('.readmorebox').hide()" class='popupCloseButton'>&times;</div>
                    <div class='terminpopup'>
                        <div>
                            <i class='termincolori' id='popuptermincolori'><div id='popuptermincolordiv'></div></i>
                            <span style="font-size: 35px; font-weight: bold; width: fit-content; margin: auto" id="termintest"></span>
                            <br>
                            <p style="text-align: center; color: var(--newstextcolor); margin-top: 2px; margin-bottom: 0; font-weight: bold;"><i class="far fa-calendar"></i><span id="termintest1" style="margin-left: 8px"></span></p>
                        </div>
                        <div style="margin-top: 10px">
                            <p>
                                <i class="far fa-clock"></i>
                                <span id="popuptime" style="margin-left: 6px;"></span>
                            </p>
                        </div>
                        <div style="margin-top: 10px" id="location">
                            <p>
                                <i class="fas fa-map-marker-alt"></i>
                                <span id="popuploc" style="margin-left: 6px;"></span>
                            </p>
                        </div>
                        <div style="margin-top: 10px; font-size: 16px" id="description">
                            <p>
                                <i class="fas fa-align-justify"></i>
                                <span id="popupdesc" style="margin-left: 6px;"></span>
                            </p>
                        </div>
                        <div style="margin-top: 10px; font-size: 16px" id="ics">
                            <p>
                                <i class="far fa-calendar-plus"></i>
                                <a id="icslink" href="" download="" target="_BLANK"><span style="margin-left: 6px;">In Kalender importieren</span></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <br>
        <?php include_once "$root/sites/footer.html" ?>
    </body>

</html>
