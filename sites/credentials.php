<?php 

function get_connection() {
    include(realpath($_SERVER["DOCUMENT_ROOT"])."/variables.php");
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function get_role($user_id) {
    $sql = ("SELECT role, `fachbereich-verwaltung` FROM users WHERE id=\"".$user_id."\";");
    $result = mysqli_query(get_connection(), $sql);
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fachbereich = $row["fachbereich-verwaltung"];
        $role = $row["role"];
    }
    return $role.",".$fachbereich;
}

function get_permission($user_id, $permission) {
    
    $role = explode(",", get_role($user_id))[0];
    $sql = ("SELECT \"$permission\" FROM roles WHERE name=\"$role\";");
    $perms = mysqli_query(get_connection(), $sql);
    if($perms->num_rows == 1) {
        $perms = $perms->fetch_assoc();
        return $perms[$permission];
    }
}

?>