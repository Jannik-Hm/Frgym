<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/credentials.php";
$id = $_POST["id"];
$numofcontentrows = 3;
for ($i = 1; $i <= $numofcontentrows; $i++){
    $mysqlstringselector = $mysqlstringselector."content".$i;
    if($i != $numofcontentrows){
        $mysqlstringselector = $mysqlstringselector.", ";
    }
}
$result = mysqli_query(get_connection(), "SELECT ".$mysqlstringselector." FROM faecher WHERE id='{$id}'");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $contenttable = array();
    for ($i = 1; $i <= $numofcontentrows; $i++){
        $contenttable[$i] = $row["content".$i];
    }
}
foreach ($contenttable as $content){
    if(is_file(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-pictures/".$content)){
        unlink(realpath($_SERVER["DOCUMENT_ROOT"])."/files/site-ressources/faecher-pictures/".$content);
    }
}
$insert = mysqli_query(get_connection(), "DELETE FROM faecher WHERE id='{$id}'");
?>