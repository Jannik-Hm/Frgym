<?php
require_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/credentials.php";
$id = $_POST["id"];
$insert = mysqli_query(get_connection(), "DELETE FROM faecher WHERE id='{$id}'");
?>