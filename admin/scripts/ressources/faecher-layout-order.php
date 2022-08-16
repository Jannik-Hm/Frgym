<?php
session_name("userid_login");
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location: /admin/login/");
}
require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
$positionarray = json_decode($_POST["positions"], true);
foreach($positionarray as $i){
    $insert = mysqli_query(getsqlconnection(), "UPDATE faecher SET position=\"{$i['index']}\" WHERE id=\"{$i['id']}\"");
}
?>