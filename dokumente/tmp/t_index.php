<?php header("Cache-Control: max-age=30"); ?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);

    include_once "./../sites/head.html"

    ?>
    <title>Dokumente - Friedrich-Gymnasium Luckenwalde</title>
</head>
<body>
<?php
include_once "./../sites/header.html"
?>
<!-- <iframe src="/sites/tinyfilesmanager.php" id="untisframe"> -->
<?php 
define('FM_EMBED', true);
define('FM_SELF_URL', $_SERVER['PHP_SELF']);
include_once './../sites/tinyfilesmanager.php';
?>
<?php
include_once "./../sites/footer.html"
?>
</body>

</html>
