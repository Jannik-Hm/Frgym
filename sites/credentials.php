<?php 

function get_connection() {
    $servername = "sql150.your-server.de";
    $username = "c0921922321";
    $password = "AHWNiBfs2u14AAZg"; //master
    $dbname = "friedrich_gym";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
            

?>