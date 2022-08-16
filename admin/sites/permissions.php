<?php

    // session_name("userid_login");
    // session_start();

    if(!isset($_SESSION["user_id"])) {
        header("Location: /admin/login/");
    }

    $user_id = $_SESSION["user_id"];

    $root = realpath($_SERVER["DOCUMENT_ROOT"]);

    require "$root/sites/credentials.php";

    $role = explode(",", get_role($_SESSION["user_id"]))[0];

    $sqlperm = ("SELECT * FROM roles WHERE name='".$role."';");
    $perms = mysqli_query(get_connection(), $sqlperm);
    if($perms->num_rows > 0) {
        $row = $perms->fetch_assoc();
        $docs = $row["docs"];
        $news_own = $row["news.own"];
        $news_all = $row["news.all"];
        $lehrer_own = $row["lehrer.own"];
        $lehrer_all = $row["lehrer.all"];
    }

?>