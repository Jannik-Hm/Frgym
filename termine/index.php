<?php
session_name("userid_login");
session_start();
?>
<!DOCTYPE html>
<html lang="de-DE" prefix="og: https://ogp.me/ns#" xmlns:og="http://opengraphprotocol.org/schema/">

<head>
    <?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    include_once "$root/sites/head.html"
    ?>
    <link rel="stylesheet" href="/new-css/termine.css">
    <title>Termine - Friedrich-Gymnasium Luckenwalde</title>
</head>

    <body>
        <?php include_once "$root/sites/header.html"; ?>
        <br>
        <section>
            <?php
                if(isset($_GET["schueler"])){
                    echo("
                    <div style='left: 0;display: block' class='no_perm'>
                        <link rel=\"stylesheet\" href=\"/new-css/no-perm.css\">
                        <style>
                            @media (prefers-color-scheme: light) {
                                #supportmaillink {
                                    color: rgb(0, 0, 0);
                                }
                            }
                        </style>
                        <div>
                            <span class='helper'></span>
                            <div class='no_permission'>
                                <h1>Passwort</h1><br>
                                <p id='wrongpass' style='display: none;color: #F75140'>Falsches Passwort</p>
                                <input type='password' id='schuelerpasswordinput' style='background-color: var(--terminbackground);color: var(--inputcolor);border: none;border-radius: 10px;font-size: 24px;padding: 7px;margin-bottom: 20px;'></input>
                                <br>
                                <a onclick=\"getcaldata('schueler', document.getElementById('schuelerpasswordinput').value);$('.no_perm').hide();\" class='back'>OK</a>
                            </div>
                        </div>
                    </div>
                    <script>
                        function wrongpassword() {
                            $('.no_perm').show();
                            document.getElementById('wrongpass').style.display = 'block';
                        }
                    </script>
                    ");
                }
            ?>
            <div class="terminelistdiv" style="display: none">
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.js"></script>
                <script src="/js/calpdfgen.js"></script>
                <script src="/js/getcaldata.js"></script>
                <?php
                    if(!isset($_GET["schueler"])){
                        echo("
                        <script>
                            getcaldata(null, null);
                            </script>
                        ");
                    }
                ?>
            </div>
            </section>
                <button href="" onclick="pdfMake.createPdf(dd).open();" id="pdfbtn" style="display: none">PDF generieren</button>
                <div onclick="event.stopPropagation();$('.readmorebox').hide()" style='left: 0;' class='readmorebox'>
                    <span class='helper'></span>
                    <div onclick="event.stopPropagation();" class='scroll'>
                        <div onclick="event.stopPropagation();$('.readmorebox').hide()" class='popupCloseButton'>&times;</div>
                        <div class='terminpopup'>
                            <div>
                                <i class='termincolori' id='popuptermincolori'><div id='popuptermincolordiv'></div></i>
                                <span style="font-size: 35px; font-weight: bold; width: fit-content; margin: auto" id="termintest"></span>
                                <br>
                                <p style="text-align: center; color: var(--newstextcolor); margin-top: 2px; margin-bottom: 0; font-weight: bold;"><i class="far fa-calendar"></i><span id="termintest1" style="margin-left: 8px"></span></p>
                            </div>
                            <div style="margin-top: 10px">
                                <p>
                                    <i class="far fa-clock"></i>
                                    <span id="popuptime" style="margin-left: 6px;"></span>
                                </p>
                            </div>
                            <div style="margin-top: 10px" id="location">
                                <p>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="popuploc" style="margin-left: 6px;"></span>
                                </p>
                            </div>
                            <div style="margin-top: 10px; font-size: 16px" id="description">
                                <p>
                                    <i class="fas fa-align-justify"></i>
                                    <span id="popupdesc" style="margin-left: 6px;"></span>
                                </p>
                            </div>
                            <div style="margin-top: 10px; font-size: 16px" id="ics">
                                <p>
                                    <i class="far fa-calendar-plus"></i>
                                    <a id="icslink" href="" download="" target="_BLANK"><span style="margin-left: 6px;">In Kalender importieren</span></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <?php include_once "$root/sites/footer.html" ?>
                <style>@keyframes spin {0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); }}</style>
                <section class="loadingscreen" style="position: absolute;background-color: rgba(0,0,0,0.4);top: 0;bottom: 0;display: flex;"><div class="loading" style="border: 16px solid #f3f3f3; /* Light grey */border-top: 16px solid #3498db; /* Blue */border-radius: 50%;width: 120px;height: 120px;animation: spin 2s linear infinite;webkit-animation: spin 2s linear infinite;position: relative;margin: auto;"></div>
            </section>
        </section>
    </body>

</html>
