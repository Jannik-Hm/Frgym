<?php
session_name("userid_login");
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location: /admin/login/");
}
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/faecher-editor.php";
    create_segment($_GET["layout"]);
    require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
    $insert = mysqli_query(getsqlconnection(), "INSERT INTO faecher (id, fach, position, contenttype) VALUES (\"{$GLOBALS['id']}\", \"{$_GET['fach']}\", \"\", \"{$_GET["layout"]}\")");
?>