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
            function deleteuser(id){
                $.post("https://frgym.greenygames.de/admin/api/user.php", {action: "delete", id: id, username: "<?php echo $_SESSION["username"] ?>", password_hash: "<?php echo $_SESSION["password"] ?>"}, function() {window.location.reload();});
            }
        </script>
    </head>
    <body>
        <?php

            include_once "$root/admin/sites/header.php";

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
            //output every user
                
                $sql = "SELECT * FROM users ORDER BY nachname ASC";
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
                    // echo('<th class="date">An der Schule seit</th>');
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
                        echo("<td class='position' onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">" . $row["role"] . "</td>");
                        echo("<td class='faecher' onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">" . $faecher . "</td>");
                        // echo("<td class='date' onclick=\"window.location='/lehrer/?id=" . $row["id"] . "'\">" . $date . "</td>");
                        if( !( $disabledall ) || ($lehrer_own == 1 && $_SESSION["vorname"] == $row["vorname"] && $_SESSION["nachname"] == $row["nachname"])){
                            echo("<td title='Bearbeiten' class='editrow' onclick=\"window.location='/admin/user/edit?id=" .$row["id"] . "'\"><i class='fas fa-edit'></i></td>");
                            if( ! ($disabledall)){
                                echo("<td title='Löschen' class='deleterow' onclick=\"$('#confirmdelete, #confirmdeletefirst').attr('onclick', 'deleteuser(".$row['id'].")');$('#confirmtext').html('Möchtest du den Lehrer &#34;".$row["vorname"]." ".$row["nachname"]."&#34; wirklich löschen?');$('.confirm').show();\"><i class='fas fa-trash red' style='color:#F75140'></i></td>");
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
            // $faecherlist = json_decode(file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-liste.json"), true);
            // $shortfacharray = array();
            // $longfacharray = array();
            // foreach($faecherlist as $fachbereich){
            //     foreach($fachbereich["faecher"] as $fach){
            //         array_push($shortfacharray, $fach["short"]);
            //         array_push($longfacharray, str_replace(["Gesellschafts-wissenschaften", "<br>"],["Gesellschaftswissenschaften", " / "],$fach["name"]));
            //     }
            // }
            // $faecher = str_replace($shortfacharray, $longfacharray, $faecher);
        ?>
        
        </table>
        <?php
            require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
            deleteconfirm("Löschung bestätigen", "confirmtext", "Abbrechen", "Löschen", "confirmdelete");
        ?>
        <?php include_once "$root/sites/footer.html" ?>
    </body>
</html>
