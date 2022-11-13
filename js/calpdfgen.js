// playground requires you to assign document definition to a variable called dd
// playgroundsite: http://pdfmake.org/playground.html

function createcal(apiinput) {
    let months = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];

    let weekdays = ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"];

    firstaweekdate = [22, 8, 2022];

    var mapObj = {
        'Beratung der Schulleitung': "SD",
        'möglicher Termin für Elternversammlung': "EV",
        Schulfahrt: "SF",
        Betriebspraktikum: "BP",
        'Projektwoche Klasse 7': "PT7",
        Schulkonferenz: "SK",
        'Schülerkonferenz': "SÜ",
        Elternkonferenz: "EK",
        Klassenkonferenz: "KK",
        Lehrerkonferenz: "LK",
        Klausur: "K"
    };

    var feriencal = "Ferien";

    var feiertag = "Feiertage in Deutschland";

    var jahrspanne = [];

    var displayedmonths = [8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6, 7];

    if (new Date().getMonth() + 1 > 7) {
        jahrspanne[0] = new Date().getFullYear();
        jahrspanne[1] = new Date().getFullYear() + 1;
    } else {
        jahrspanne[0] = new Date().getFullYear() - 1;
        jahrspanne[1] = new Date().getFullYear();
    }

    var Termine = [];

    var termincounter = 0;

    var globaltable = [];

    function generatepagetable(pageCount) {
        for (var z = 0; z < pageCount; z++) {
            var pagetable = new Array(32);
            for (var j = 0; j < pagetable.length; j++) {
                var rowtable = new Array(6);
                for (var i = 0; i < rowtable.length; i++) { rowtable[i] = {}; }
                pagetable[j] = rowtable;
            }
            globaltable.push(pagetable);
        }
    }

    generatepagetable(2);

    for (var i = 0; i < globaltable.length; i++) {
        generateTable(globaltable[i], i + 1);
    }

    function generateTable(table, page) {
        var year2;
        var jahr;
        var monthmodifier = (page - 1) * 6;
        if (page > 1) {
            year2 = true;
        }
        for (var i = 0; i < 6; i++) { // header for first table page
            table[0][i] = { text: months[displayedmonths[monthmodifier + i] - 1], bold: true, alignment: 'center' };
            if (displayedmonths[monthmodifier + i] < displayedmonths[monthmodifier + i - 1]) {
                year2 = true;
            }
            if (year2) {
                jahr = jahrspanne[1];
                table[0][i].fillColor = '#7BC3CE';
            } else {
                jahr = jahrspanne[0];
                table[0][i].fillColor = '#7BCE84';
            }
            table[0][i].text = table[0][i].text + " " + jahr;
            var monthlength = new Date(jahr, displayedmonths[monthmodifier + i], 0).getDate();
            for (var j = 1; j <= 31; j++) {
                table[j][i]["columns"] = [];
                table[j][i]["columns"][0] = {};
                if (j <= monthlength) {
                    var dayofweek = new Date(jahr, displayedmonths[monthmodifier + i] - 1, j).getDay();
                    table[j][i].columns[0]["text"] = [j, { text: ' ' + weekdays[dayofweek] }];
                    if (dayofweek === 1) {
                        table[j][i].columns[0].text[1].monthlength = monthlength;
                    }
                    if (dayofweek === 6) {
                        table[j][i].columns[0].bold = true;
                        table[j][i].fillColor = '#E8E5A0';
                    } else if (dayofweek === 0) {
                        table[j][i].columns[0].bold = true;
                        table[j][i].fillColor = '#E8D0A0';
                    } else {
                        table[j][i].columns[0].bold = false;
                    }
                } else {
                    table[j][i].columns[0].text = null;
                    table[j][i].fillColor = '#B8B8B8';
                }

            }
        }
    }

    function displayevent(table, date, month, text, color, calendar) {
        if (text == "" || text == null) {
            return;
        }
        var re = new RegExp(Object.keys(mapObj).join("|"), "gi");
        text = text.replace(re, function (matched) {
            return mapObj[matched];
        });
        var isferien = false;
        if (calendar == feiertag) {
            for (var k = 2; k < table[date][month % 6].columns[0].text.length; k++) {
                if (table[date][month % 6].columns[0].text[k].calendar == feriencal) {
                    isferien = true;
                }
                delete table[date][month % 6].columns[0].text[k];
            }
            table[date][month % 6].columns[0].text[2] = { text: ' ' + text, color: color, calendar: calendar, isferien: (isferien ? true : false) };
            return;
        }
        if (typeof table[date][month % 6].columns[0].text[2] !== 'undefined' && table[date][month % 6].columns[0].text[2].calendar == feiertag) {
            if (calendar == feriencal) {
                table[date][month % 6].columns[0].text[2].isferien = true;
            }
            return;
        }
        if (text !== "" && text !== null) {
            if (table[date][month % 6].columns[0].text.length >= 3 && calendar != feiertag) {
                table[date][month % 6].columns[0].text.push({ text: ' /', color: '' });
            }
            if (calendar == feriencal) { isferien = true; }
            table[date][month % 6].columns[0].text.push({ text: ' ' + text, color: color, calendar: calendar, isferien: (isferien ? true : false) });
        }
    }

    function displayevents(input) {
        for (var i = 0; i < input.length; i++) {
            var year = new Date(input[i].start).getFullYear();
            if (year >= jahrspanne[0]) {
                var termin = { begin: {}, end: {} };
                termin.begin.year = Number(new Date(input[i].start).getFullYear());
                termin.end.year = Number(new Date(input[i].end).getFullYear());
                termin.begin.month = displayedmonths.findIndex(x => x === new Date(input[i].start).getMonth() + 1);
                termin.end.month = displayedmonths.findIndex(x => x === new Date(input[i].end).getMonth() + 1);
                termin.begin.date = Number(new Date(input[i].start).getDate());
                termin.end.date = Number(new Date(input[i].end).getDate());
                if (!input[i].istime) {
                    termin.end.date = termin.end.date - 1;
                }
                var yeardifference = termin.end.year - termin.begin.year;
                var monthdifference;
                if (termin.begin.year == jahrspanne[0] && new Date(input[i].start).getMonth() + 1 < displayedmonths[0] && new Date(input[i].end).getMonth() + 1 < displayedmonths[0]) continue;
                if (termin.begin.year == jahrspanne[1] && new Date(input[i].start).getMonth() + 1 > displayedmonths[11]) continue;
                if (termin.begin.year > jahrspanne[1]) continue;
                if (termin.begin.year == termin.end.year && termin.begin.month == termin.end.month) {
                    monthdifference = 0;
                } else if (termin.begin.year == termin.end.year && termin.begin.month < termin.end.month) {
                    monthdifference = displayedmonths[termin.end.month] - displayedmonths[termin.begin.month];
                } else if (termin.begin.year == jahrspanne[0] && new Date(input[i].start).getMonth() + 1 < displayedmonths[0] && new Date(input[i].end).getMonth() + 1 >= displayedmonths[0]) {
                    for (var m = 0; m <= termin.end.month; m++) {
                        if (m == termin.end.month) {
                            for (var d = 1; d <= termin.end.date; d++) {
                                var table = Math.floor(m / 6);
                                if (globaltable[table][d][m % 6].text !== null) {
                                    displayevent(globaltable[table], d, m, input[i].name, input[i].color, input[i].eventtype);
                                }
                            }
                        } else {
                            for (var d = 1; d <= 31; d++) {
                                var table = Math.floor(m / 6);
                                if (globaltable[table][d][m % 6].text !== null) {
                                    displayevent(globaltable[table], d, m, input[i].name, input[i].color, input[i].eventtype);
                                }
                            }
                        }
                    }
                } else if (termin.begin.year == jahrspanne[1] && new Date(input[i].start).getMonth() + 1 <= displayedmonths[displayedmonths.length - 1] && new Date(input[i].end).getMonth() + 1 > displayedmonths[displayedmonths.length - 1]) {
                    for (var m = termin.begin.month; m <= displayedmonths.length - 1; m++) {
                        if (m == termin.begin.month) {
                            for (var d = termin.begin.date; d <= 31; d++) {
                                var table = Math.floor(m / 6);
                                if (globaltable[table][d][m % 6].text !== null) {
                                    displayevent(globaltable[table], d, m, input[i].name, input[i].color, input[i].eventtype);
                                }
                            }
                        } else {
                            for (var d = 1; d <= 31; d++) {
                                var table = Math.floor(m / 6);
                                if (globaltable[table][d][m % 6].text !== null) {
                                    displayevent(globaltable[table], d, m, input[i].name, input[i].color, input[i].eventtype);
                                }
                            }
                        }
                    }
                } else {
                    for (var y = termin.begin.year; y < termin.end.year; y++) {
                        if (y == termin.begin.year) {
                            monthdifference = 12 - displayedmonths[termin.begin.month];
                        } else {
                            monthdifference += 12;
                        }
                    }
                    monthdifference += displayedmonths[termin.end.month];
                }
                if (monthdifference > 0) {
                    for (var m = termin.begin.month; m <= termin.end.month; m++) {
                        if (m == termin.begin.month) {
                            for (var d = termin.begin.date; d <= 31; d++) {
                                var table = Math.floor(m / 6);
                                if (globaltable[0][d][m % 6].text !== null) {
                                    displayevent(globaltable[table], d, m, input[i].name, input[i].color, input[i].eventtype);
                                }
                            }
                        } else if (m == termin.end.month) {
                            for (var d = 1; d <= termin.end.date; d++) {
                                var table = Math.floor(m / 6);
                                if (globaltable[table][d][m % 6].text !== null) {
                                    displayevent(globaltable[table], d, m, input[i].name, input[i].color, input[i].eventtype);
                                }
                                // console.log("Last Month:"+d+"m:"+m);
                            }
                        } else {
                            for (var d = 1; d <= 31; d++) {
                                var table = Math.floor(m / 6);
                                if (globaltable[table][d][m % 6].text !== null) {
                                    displayevent(globaltable[table], d, m % 6, input[i].name, input[i].color, input[i].eventtype);
                                }
                            };
                        }
                    }
                } else if (monthdifference == 0 && termin.begin.date != termin.end.date) {
                    if (termin.begin.month == termin.end.month) {
                        for (var d = termin.begin.date; d <= termin.end.date; d++) {
                            var monthmodifier = (year - jahrspanne[0]) * 12;
                            monthmodifier = monthmodifier + displayedmonths[termin.begin.month] - displayedmonths[0];
                            var table = Math.floor(monthmodifier / 6);
                            displayevent(globaltable[table], d, termin.begin.month, input[i].name, input[i].color, input[i].eventtype);
                        }
                    }
                } else {
                    if (input[i].name !== "" && input[i].name !== null && input[i].name !== undefined) {
                        var monthmodifier = (year - jahrspanne[0]) * 12;
                        monthmodifier = monthmodifier + displayedmonths[termin.begin.month] - displayedmonths[0];
                        var table = Math.floor(monthmodifier / 6);
                        if (globaltable[table] !== undefined) {
                            displayevent(globaltable[table], termin.begin.date, termin.begin.month, input[i].name, input[i].color, input[i].eventtype);
                        }
                    }
                }
            }
        }
    }

    displayevents(apiinput);

    Date.prototype.getWeekNumber = function () {
        var d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
        var dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        var yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7)
    };

    console.log(globaltable);
    var aweekcounter = 0;
    for (var cal = 0; cal < globaltable.length; cal++) {
        for (var mon = 0; mon < globaltable[cal][0].length; mon++) {
            for (var day = 1; day < globaltable[cal].length; day++) {
                if (globaltable[cal][day][mon].columns[0].text != null && globaltable[cal][day][mon].columns[0].text[1].text == " " + weekdays[1]) {
                    globaltable[cal][day][mon].columns[1] = { text: [], alignment: "right", width: "auto", fontSize: 8 };
                    if (cal == 0 && mon == 0 && day <= 7) {
                        var startweeknum = new Date(jahrspanne[cal], displayedmonths[mon] - 1, day).getWeekNumber();
                    } else {
                        if (displayedmonths[mon + (6 * cal)] == 1 && day <= 7) {
                            startweeknum = 1;
                        } else {
                            startweeknum++;
                        }
                    }
                    globaltable[cal][day][mon].columns[1].text.push(startweeknum);
                    if (cal == 0 && mon < displayedmonths.findIndex(x => x === firstaweekdate[1])) {
                        continue;
                    } else if (cal == 0 && mon == displayedmonths.findIndex(x => x === firstaweekdate[1]) && day < firstaweekdate[0]) {
                        continue;
                    } else if (typeof globaltable[cal][day][mon].columns[0].text[2] != "undefined" && globaltable[cal][day][mon].columns[0].text[2].isferien) {
                        if (globaltable[cal][day][mon].columns[0].text[1].monthlength - day >= 5) {
                            friday = day + 4
                            if (typeof globaltable[cal][friday][mon].columns[0].text[2] != "undefined" && globaltable[cal][friday][mon].columns[0].text[2].isferien) {
                                continue;
                            }
                        } else {
                            friday = 5 - globaltable[cal][day][mon].columns[0].text[1].monthlength + day - 1;
                            if (mon + 1 < 6) {
                                var nextmon = mon + 1;
                                var nextcal = cal;
                            } else {
                                var nextmon = mon + 1 - 6;
                                var nextcal = cal + 1;
                            }
                            if (nextcal > 1) { continue; }
                            if (typeof globaltable[nextcal][friday][nextmon].columns[0].text[2] != "undefined" && globaltable[nextcal][friday][nextmon].columns[0].text[2].isferien) {
                                continue;
                            }
                        }
                    }
                    if (aweekcounter % 2 == 0) {
                        globaltable[cal][day][mon].columns[1].text.unshift("A ");
                    } else {
                        globaltable[cal][day][mon].columns[1].text.unshift("B ");
                    }
                    aweekcounter++;
                }
            }
        }
    }

    dd = {
        info: {
            title: 'Schulkalender ' + jahrspanne[0] + "/" + jahrspanne[1].toString().substring(jahrspanne[1].toString().length - 2),
            author: 'Friedrich-Gymnasium Luckenwalde',
        },
        // a string or { width: number, height: number }
        pageSize: 'A4',

        // by default we use portrait, you can change it to landscape if you wish
        pageOrientation: 'landscape',

        // [left, top, right, bottom] or [horizontal, vertical] or just a number for equal margins
        pageMargins: [40, 40, 40, 40],
        header: { text: 'Schulkalender ' + jahrspanne[0] + "/" + jahrspanne[1].toString().substring(jahrspanne[1].toString().length - 2), margin: [40, 21, 0, 0], fontSize: 16, bold: true },
        footer: {
            columns: [
                { text: 'SD - Beratung der Schulleitung\nEV - möglicher Termin für Elternversammlung\nSF - Schulfahrt' },
                { text: 'BP - Betriebspraktikum\nPT7 - Projektwoche Klasse 7\nSK - Schulkonferenz' },
                { text: 'SÜ - Schülerkonferenz\nEK - Elternkonferenz\nKK - Klassenkonferenz' },
                { text: 'LK - Lehrerkonferenz\nK - Klausur' }
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
                    widths: ['*', '*', '*', '*', '*', '*'],

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
                    widths: ['*', '*', '*', '*', '*', '*'],

                    body: globaltable[1]
                },
                fontSize: 9.5
            }
        ]

    };
    return dd;
}