<?php

    session_name("userid_login");
    session_start();

    if(!isset($_SESSION["user_id"])) {
        header("Location: /admin/login/");
    }

    $root = realpath($_SERVER["DOCUMENT_ROOT"]);

?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <?php 

            include_once "$root/admin/sites/head.html";

        ?>
        <link rel="stylesheet" href="/new-css/lehrer.css">
        <link rel="stylesheet" href="/new-css/form.css">
        <title>Lehrerliste - Friedrich-Gymnasium Luckenwalde</title>
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

            include_once "$root/admin/sites/header.html";

            include_once "$root/admin/sites/permissions.php";
            if($lehrer_all == 0){
                $disabledall = true;
            };

        ?>


        <!--<div style="border: 1px solid grey; border-radius: 50px; width: 150px; min-height:200px;" class="lehrer">
            <img src="" style="border:1px solid black; border-radius: 15px; width:100px; height:150px; align-self: center;">
            <h4>Vorname Nachname</h4>
            <p>Fächer(kürzel)</p>
        </div>-->

        <?php

            require_once "$root/sites/credentials.php";
            $conn = get_connection();

            if(!isset($_GET["id"])) {
            //output every lehrer 
                
                $sql = "SELECT * FROM lehrer ORDER BY nachname ASC;";
                $result = mysqli_query($conn,$sql); 
                $myArray = array();
                if ($result->num_rows > 0) {

                    echo('<input type="text" id="lehrerTableSearch" onkeyup="searchTable();" placeholder="Suche nach Namen...">');
                    echo('<table id="lehrerTable">');
                    echo('<tr class="tableHeader">');
                    echo('<th class="name">Name</th>');
                    echo('<th class="email">Email</th>');
                    echo('<th class="position">Position</th>');
                    echo('<th class="faecher">Fächer</th>');
                    echo('<th class="date">An der Schule seit</th>');
                    echo('<th class="editrow"></th>');
                    if( ! ($disabledall)){ echo('<th class="deleterow"></th>'); }
                    echo('</tr>');
                    while($row = $result->fetch_assoc()) {
                        $faecher = "";
                        foreach (explode(";", $row["faecher"]) as $fach) {
                            $faecher = $faecher . " & " . $fach;
                        }
                        $faecher = substr($faecher, 3);
                        if(isset($row["datum"])){
                            $datear = explode("-", $row["datum"]);
                            $date = $datear[2].".".$datear[1].".".$datear[0];
                        }else{$date = "";}
                        echo("<tr>");
                        echo("<td class='name' onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">" . $row["vorname"] . " " . $row["nachname"] . "</td>");
                        echo("<td class='email' onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">" . $row["email"] . "</td>");
                        echo("<td class='position' onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">" . $row["position"] . "</td>");
                        echo("<td class='faecher' onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">" . $faecher . "</td>");
                        echo("<td class='date' onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">" . $date . "</td>");
                        if( !( $disabledall ) || ($lehrer_own == 1 && $_SESSION["vorname"] == $row["vorname"] && $_SESSION["nachname"] == $row["nachname"])){
                            echo("<td title='Bearbeiten' class='editrow' onclick=\"window.location='/admin/lehrer/edit?id=" .$row["id"] . "'\"><i class='fas fa-edit'></i></td>");
                            if( ! ($disabledall)){
                                echo("<td title='Löschen' class='deleterow' onclick=\"$('#confirmdelete').attr('href', '/admin/lehrer/delete.php?id=".$row['id']."');$('.confirm').show();document.getElementById('confirmtext').innerHTML='Möchtest du den Lehrer &#34;".$row["vorname"]." ".$row["nachname"]."&#34; wirklich löschen?'\"><i class='fas fa-trash red' style='color:#F75140'></i></td>");
                            }
                            // else{
                                // echo("<td><i class='fas fa-trash red' style='color:#F75140;color:transparent'></i></td>");
                            // }
                        }else{
                            echo("<td></td>");
                        }
                        echo("</a></tr>");
                    }
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
                $faecher = str_replace("FR", "Franzöisch", $faecher);
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
        <?php
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            deleteconfirm("Löschung bestätigen", "confirmtext", "Abbrechen", "Löschen", "confirmdelete");
        ?>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>
