<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    include_once "$root/sites/head.html"
    ?>
    <link rel="stylesheet" href="/new-css/termine.css">
    <title>Rundgang - Friedrich-Gymnasium Luckenwalde</title>
</head>

    <body>
        <?php
            include_once "$root/sites/header.html";
            $data = json_decode(file_get_contents($root."/files/site-ressources/rundgang.json"), true);
            if(!isset($_GET["bereich"])){
                echo '
                <section>
                    <h1 style="margin-bottom: 25px">Rundgang</h1>
                    <img style="margin-bottom: 25px; border-radius: 15px; height: auto; max-height: 400px; max-width: 90vw" src="'.$data["filelocation"].$data["default-pic"].'"></img>
                    <div class="bereiche">
                ';
                    foreach(array_keys($data["ressources"]) as $bereich){
                        echo "<a href='".explode("?", $_SERVER['REQUEST_URI'])[0]."?bereich=".$bereich."'>".$bereich."</a>";
                    }
                echo '
                    </div>
                </section>
                ';
            }else{
                $picnum = (isset($_GET["num"])) ? $_GET["num"] : 0;
                echo '
                <section style="margin-top: 25px">
                    <h1 style="margin-bottom: 25px">'.$_GET["bereich"].'</h1>
                    <div style="margin: auto;width: fit-content;position: relative;margin-bottom: 25px;">
                        <img class="foto" src="'.$data["filelocation"].$data["ressources"][$_GET["bereich"]][$picnum]["filename"].'"></img>
                        <a class="navbtn" id="prev" style="left: -38px;border-radius: 15px 0 0 15px;" href="'.explode("?", $_SERVER['REQUEST_URI'])[0].(($picnum > 0)?"?bereich=".$_GET["bereich"]."&num=".($picnum-1):"").'"><i class="fa-solid fa-arrow-left"></i></a>
                        <a class="navbtn" id="next" style="right: -38px;border-radius: 0 15px 15px 0;" href="'.explode("?", $_SERVER['REQUEST_URI'])[0].(($picnum < count($data["ressources"][$_GET["bereich"]])-1)?"?bereich=".$_GET["bereich"]."&num=".($picnum+1):"").'"><i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <p>'.$data["ressources"][$_GET["bereich"]][$picnum]["description"].'</p>
                </section>
                <script>
                    $(document).on("keydown", function (e) { // if pressedkey is left arrow then go to previous
                        if(e.keyCode == 37){
                            $("#prev")[0].click();
                        }else if(e.keyCode == 39 || e.keyCode == 32){ // if pressedkey is right arrow or space bar then go to next
                            $("#next")[0].click();
                        }
                    });
                </script>
                ';
            }
        ?>
            <style>
                div.bereiche{
                    display: flex;
                    flex-direction: column;
                    padding: 0;
                    list-style-type: none;
                    width: fit-content;
                    margin: auto;
                }
                div.bereiche a {
                    border-radius: 15px;
                    margin-bottom: 15px;
                    padding: 10px 30px;
                    background-color: var(--inputbackground);
                }
                div.bereiche a:hover{
                    box-shadow: 0 3px 6px grey;
                }
                a.navbtn {
                    background-color: var(--inputbackground);
                    padding: 10px 12px;
                    position: absolute;
                    transform: translate(0,-50%);
                    top: 50%;
                }
                div img.foto{
                    border-radius: 15px;
                    height: auto;
                    max-height: 70vh;
                    min-width: calc(300px - 70px);
                    max-width: calc(90vw - 70px);
                    z-index: 1;
                    position: relative;
                }
            </style>
        <?php include_once "$root/sites/footer.html" ?>
    </body>

</html>