<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php 

            include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/head.html"

        ?>
        <title>Lehrer - Friedrich-Gymnasium Luckenwalde</title>
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
    </head>
    <body>
            <?php

                include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/header.html"

            ?>

            <!--<div style="border: 1px solid grey; border-radius: 50px; width: 150px; min-height:200px;" class="lehrer">
                <img src="" style="border:1px solid black; border-radius: 15px; width:100px; height:150px; align-self: center;">
                <h4>Vorname Nachname</h4>
                <p>Fächer(kürzel)</p>
            </div>-->



            <?php
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
                $conn = getsqlconnection();

                if(!isset($_GET["id"])) {
                //output every lehrer 
                    
                    $sql = "SELECT * FROM lehrer ORDER BY nachname ASC;";
                    $result = mysqli_query($conn,$sql); 
                    $myArray = array();
                    if ($result->num_rows > 0) {

                        echo('<input type="text" id="lehrerTableSearch" onkeyup="searchTable();" placeholder="Suche nach Namen...">');
                        include_once ("./faecherfilter.php");
                        echo('<table id="lehrerTable">');
                        echo('<tr class="tableHeader">');
                        echo('<th>Name</th>');
                        echo('<th>Fächer</th>');
                        echo('</tr>');
                        while($row = $result->fetch_assoc()) {
                            $faecher = "";
                            foreach (explode(";", $row["faecher"]) as $fach) {
                                $faecher = $faecher . " & " . $fach;
                            }
                            $faecher = substr($faecher, 3);
                            echo("<tr onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">");
                            echo("<td>" . $row["vorname"] . " " . $row["nachname"] . "</td>");
                            echo("<td>" . $faecher . "</td>");
                            echo("</a></tr>");
                        }
                    } else {
                        die("0 results.");
                    }
                } else {
                    
                    $sql = "SELECT * FROM lehrer WHERE id = " . $_GET['id'] . ";";
                    $result = mysqli_query($conn,$sql); 
                    $myArray = array();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $faecherlist = explode(";", $row["faecher"]);
                        for ($i=0; $i<count($faecherlist);$i++){
                            if($i==0){
                                $faecher = $faecherlist[$i];
                            }elseif($i<(count($faecherlist)-1)){
                                $faecher = $faecher . ", " . $faecherlist[$i];
                            }else{
                                $faecher = $faecher . " & " . $faecherlist[$i];
                            }
                        };
                        $faecher = faecherReplace($faecher);
                        if(isset($row["datum"])){
                            $date = date_diff(date_create($row["datum"]), date_create(date("Y-m-d")));
                        }
                        #$date = explode("-", $row["datum"])[2] . "." . explode("-", $row["datum"])[1] . "." . explode("-", $row["datum"])[0];
                        echo("<section>");
                        echo("<h1>" . $row["vorname"] . " " . $row["nachname"] . "</h1>");
                        echo("<h3>" . $row["position"] . "</h3>");
                        $imgdir = "/files/site-ressources/lehrer-bilder/";
                        $imgpath = $imgdir . strtolower(str_replace(" ","_",$row["vorname"])."_".str_replace(" ","_",$row["nachname"])).".";
                        if (file_exists($imgpath."jpg")) {
                            $imgpath = $imgpath."jpg";
                        }elseif (file_exists($imgpath."jpeg")) {
                            $imgpath = $imgpath."jpeg";
                        }elseif (file_exists($imgpath."png")) {
                            $imgpath = $imgpath."png";
                        }elseif (file_exists($imgpath."webp")) {
                            $imgpath = $imgpath."webp";
                        }else{
                            $imgpath = $imgdir."placeholder.webp";
                        }
                        echo("<img src='".$imgpath."' id=\"lehrerimg\">");
                        echo("<h2>" . $faecher . "</h2>");
                        if(isset($date)) {
                            echo("<h4>" . $date->format("Seit %y Jahren dabei") . "</h4>");
                        }
                        echo("<a href=\"mailto:" . $row["email"] . "\"><button class='email-btn'><i class='fas fa-at'></i> E-Mail</button></a>");
                        echo("<p>" . $row["beschreibung"] . "</p>");
                        echo("</section>");
                    } else {
                        die("0 results.");
                    }
                }
                function faecherReplace($faecher) {
                    $faecher = str_replace("DE", "Deutsch", $faecher);
                    $faecher = str_replace("MA", "Mathe", $faecher);
                    $faecher = str_replace("EN", "Englisch", $faecher);
                    $faecher = str_replace("BI", "Biologie", $faecher);
                    $faecher = str_replace("CH", "Chemie", $faecher);
                    $faecher = str_replace("DS", "Darstellendes Spiel", $faecher);
                    $faecher = str_replace("RE", "Evangelischer Religionsunterricht", $faecher);
                    $faecher = str_replace("FR", "Französisch", $faecher);
                    $faecher = str_replace("EK", "Erdkunde", $faecher);
                    $faecher = str_replace("GE", "Geschichte", $faecher);
                    $faecher = str_replace("EG", "Gesellschaftswissenschaften", $faecher);
                    $faecher = str_replace("IF", "Informatik", $faecher);
                    $faecher = str_replace("RK", "Katholischer Religionsunterricht", $faecher);
                    $faecher = str_replace("KU", "Kunst", $faecher);
                    $faecher = str_replace("LA", "Latein", $faecher);
                    $faecher = str_replace("LE", "Lebensgestaltung-Ethik-Religionskunde", $faecher);
                    $faecher = str_replace("MU", "Musik", $faecher);
                    $faecher = str_replace("NW", "Naturwissenschaften", $faecher);
                    $faecher = str_replace("PH", "Physik", $faecher);
                    $faecher = str_replace("PB", "Politische Bildung", $faecher);
                    $faecher = str_replace("PO", "Polnisch", $faecher);
                    $faecher = str_replace("RU", "Russisch", $faecher);
                    $faecher = str_replace("SN", "Spanisch", $faecher);
                    $faecher = str_replace("SP", "Sport", $faecher);
                    $faecher = str_replace("TR", "Türkisch", $faecher);
                    $faecher = str_replace("AL", "Wirtschaft-Arbeit-Technik", $faecher);
                    $faecher = str_replace("WW", "Wirtschaftswissenschaften", $faecher);
                    return $faecher;
                }

            ?>

            </table>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/footer.html" ?>
    </body>
</html>