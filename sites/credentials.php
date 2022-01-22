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

function get_role($user_id) {
    $sql = ("SELECT \"role\" FROM users WHERE id=\"".$user_id."\";");
    $role = mysqli_query(get_connection(), $sql);
    if($role->num_rows == 1) {
        $role = $role->fetch_assoc();
        $role = $role["role"];
    }
    return $role;
}

function get_permission($user_id, $permission) {
    
    $role = get_role($user_id);
    $sql = ("SELECT \"$permission\" FROM roles WHERE name=\"$role\";");
    $perms = mysqli_query(get_connection(), $sql);
    if($perms->num_rows == 1) {
        $perms = $perms->fetch_assoc();
        return $perms[$permission];
    }
}

?>