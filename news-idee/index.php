<?php
    if (isset($_GET["items"]) && !(isset($items))) { // Nachrichten pro Seite
        $items = $_GET["items"];
    } elseif (!(isset($items))) {
        $items = 16;
    }
    if (isset($_GET["page"]) && !(isset($page))) { // Seite
        $page = $_GET["page"];
    } elseif (!(isset($page))) {
        $page = 1;
    }

    $news = array([$test["title"] = "Title"], $test["author"] = "Autor");
    for ($i=count($news)-1-($items*($page-1)); $i >= 0; $i--) {
        $title = $news[$i]["title"];
    }
?>