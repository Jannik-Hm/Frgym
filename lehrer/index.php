<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php 

            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/head.html"

        ?>
        <title>Lehrer - Friedrich-Gymnasium Luckenwalde</title>
        <link rel="stylesheet" href="/new-css/lehrer.css">
        <script>
            function searchTable() {
                // Declare variables
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("lehrerTableSearch");
                filter = input.value.toUpperCase();
                table = document.getElementById("lehrerTable");
                tr = table.getElementsByTagName("tr");

                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[0];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="/new-css/form.css">
    </head>
    <body>
            <?php
                include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/header.html";
                if(!isset($_GET["id"])){
                    echo('<input type="text" id="lehrerTableSearch" onkeyup="searchTable();" placeholder="Suche nach Namen...">');
                    include_once ("./faecherfilter.php");
                    echo('
                        <table id="lehrerTable">
                            <tr class="tableHeader">
                                <th>Name</th>
                                <th>Fächer</th>
                            </tr>
                        </table>
                    ');
                }else{
                    echo("<section>");
                    echo("<h1 id='name'></h1>");
                    echo("<h3 id='position'></h3>");
                    $imgdir = "/files/site-ressources/lehrer-bilder/";
                    $imgpath = $imgdir . $_GET["id"].".";
                    if (file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath."jpg")) {
                        $imgpath = $imgpath."jpg";
                    }elseif (file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath."jpeg")) {
                        $imgpath = $imgpath."jpeg";
                    }elseif (file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath."png")) {
                        $imgpath = $imgpath."png";
                    }elseif (file_exists(realpath($_SERVER["DOCUMENT_ROOT"]).$imgpath."webp")) {
                        $imgpath = $imgpath."webp";
                    }else{
                        $imgpath = $imgdir."placeholder.webp";
                    }
                    echo("<img src='".$imgpath."' id=\"lehrerimg\">");
                    echo("<h2 id='faecher'></h2>");
                    echo("<a id='maillink'><button class='email-btn'><i class='fas fa-at'></i> E-Mail</button></a>");
                    echo("<p id='description'></p>");
                    echo("</section>");
                }
            ?>

            <script>
                var id = <?php echo ((isset($_GET["id"]))?$_GET["id"]:"null") ?>;
                if(id == null){
                    var ajax = $.post("https://frgym.greenygames.de/admin/api/user.php", {"action": "getall"}, function(data){console.log(ajax.status); createuserlist(JSON.parse(data)["data"]);});
                    function createuserlist(data){
                        data.forEach(function(val, key){
                            var faecher = "";
                            var faecherlist = val["faecher"].split(";");
                            faecherlist.forEach(function(val, key){
                                faecher += " "+val;
                                if(key == faecherlist.length-1){
                                    return;
                                }else if(key == faecherlist.length-2){
                                    faecher += " &";
                                }else{
                                    faecher += ",";
                                }
                            })
                            $("#lehrerTable").append("<tr onclick=\"window.location='/lehrer/?id="+val["id"]+"'\"><td>"+((val["titel"]!=null)?val["titel"]+" ":"")+((val["display_vorname"]!=null)?val["display_vorname"]:val["vorname"])+" "+val["nachname"]+"</td><td>"+faecher.trim()+"</td></tr>");
                        });
                    }
                }else{
                    function facharrays(fachlist, data){
                        var array = [[],[]];
                            Object.values(fachlist).forEach(function(val){
                            Object.values(val.faecher).forEach(function(val){
                                array[0].push(val.name.replace("<br>", " / ").replace("Gesellschafts-wissenschaften", "Gesellschaftswissenschaften"));
                                array[1].push(val.short);
                            })
                        })
                        var replacestring = "";
                        array[0].forEach(function(val,key){
                            replacestring += ".replace('"+array[1][key]+"','"+array[0][key]+"')";
                        });
                        faecher = Function("data", "return data.faecher"+replacestring+"");
                        faecherstring = "";
                        faecherlist = faecher(data).split(";");
                        faecherlist.forEach(function(val, key){
                            faecherstring += " "+val;
                            if(key == faecherlist.length-1){
                                return;
                            }else if(key == faecherlist.length-2){
                                faecherstring += " &";
                            }else{
                                faecherstring += ",";
                            }
                        })
                        $("#faecher").html(faecherstring);
                    }
                    var ajax = $.post("https://frgym.greenygames.de/admin/api/user.php", {"action": "getbyid", "id": id}, function(data){console.log(ajax.status); console.log(JSON.parse(data)["data"]);createuserprofile(JSON.parse(data)["data"]);});
                    function createuserprofile(data){
                        $("#name").html(((data.titel!=null)?data["titel"]+" ":"")+((data["display_vorname"]!=null)?data["display_vorname"]:data["vorname"])+" "+data["nachname"]);
                        $("#position").html(data.role);
                        $("#maillink").attr("href", "mailto:"+data.email);
                        $("#description").html(data.infotext);
                        fetch("/files/site-ressources/faecher-liste.json").then(response => {return response.json();}).then(jsondata => facharrays(jsondata, data));
                    }
                }
            </script>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html" ?>
    </body>
</html>