<!DOCTYPE html>
<html>
    <head>
        <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/head.html" ?>
        <title><?php echo basename($_GET["file"]) ?></title>
        <link rel="stylesheet" href="/new-css/pdfviewer.css"/>
    </head>

    <body style="min-width: fit-content">
        <div id="app" style="min-width:fit-content">
            <div role="toolbar" id="toolbar">
                <div style="margin-right: 15px; font-size: 25px">
                    <a onclick='zoom+=0.1;setzoom(standardzoom*zoom);'
                        style="cursor:pointer; margin-right: 5px" title="Zoom-In">+</a>
                    <a onclick='zoom-=0.1;setzoom(standardzoom*zoom);'
                        style="cursor:pointer; margin-right: 5px" title="Zoom-Out">-</a>
                    <a onclick='zoom=1;setzoom(standardzoom);' style="cursor:pointer" title="Zoom-Reset"><i
                            class="fa-solid fa-power-off" style="font-size: 20px; margin-right: 10px"></i></a>
                    <span>|</span>
                </div>
                <div style="margin-left: 0">
                    <a id="downloadbtn" download title="Speichern"
                        style="color: white;margin-right: 10px;text-decoration: none;">
                        <i class="fas fa-save download"></i>
                    </a>
                    <!-- <a id="printbtn" onclick='print();'
                        title="Drucken" style="cursor: pointer">
                        <i class="fas fa-print"></i>
                    </a> -->
                </div>
            </div>
            <div id="viewport-container">
                <div role="main" id="viewport"></div>
            </div>
        </div>
        <script src="/js/pdf.js/build/pdf.js"></script>
        <script src="/js/pdfviewer.js"></script>
        <script src="/js/jquery.min.js"></script>
        <script>
            onresizefunc = "";
            var src = "<?php echo $_GET["file"] ?>";
            function getzoom() {
                return (($("#viewport").css("width") != "none") ? Number($("#viewport").css("width").replace("px", "")) : windowwidth);
            }
            function setzoom(width) {
                $("#viewport").css("width", width+"px");
                for(let page = 0; page<textcontentarray.length; page++){
                    renderText(page, zoom);
                }
            }
            initPDFViewer(src);
            $("#downloadbtn").attr("href", src);
            var zoom = 1.0;
            var windowwidth;
            function resizepdfupdate() {
                if(typeof window.issafari == "undefined"){
                    $("#viewport").hide();
                    windowwidth = window.innerWidth;
                    if (windowwidth > (1000 * 0.9)) {
                        windowwidth = 1000;
                    } else if (windowwidth < (300 / 0.9)) {
                        windowwidth = 300;
                    } else {
                        windowwidth = windowwidth * 0.9;
                    }
                    $("#viewport").show();
                    setzoom(windowwidth);
                }
            }
            onresizefunc += 'resizepdfupdate();';
        </script>
    </body>
    <script defer>
        if(window.onresize == null){
            if (typeof onresizefunc == "string") { Function(onresizefunc)() };
            window.onresize = function (e) {
                if (typeof onresizefunc == "string") { Function(onresizefunc)() };
            }
        }
    </script>
</html>