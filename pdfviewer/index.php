<!DOCTYPE html>
<html>

<head>
    <?php include_once realpath($_SERVER["DOCUMENT_ROOT"])."/sites/head.html" ?>
    <title>PDF Viewer PDF.js</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="/new-css/pdfviewer.css"/>
    <link href="/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="/fontawesome/css/solid.min.css" rel="stylesheet">
    <link href="/fontawesome/css/regular.min.css" rel="stylesheet">
</head>

<body style="min-width: fit-content">
    <div id="app" style="min-width:fit-content">
        <div role="toolbar" id="toolbar">
            <div style="margin-right: 15px; font-size: 25px">
                <a onclick='$("#viewport").css("width", ((($("#viewport").css("width") != "none") ? Number($("#viewport").css("width").replace("px", "")) : windowwidth)*1.1)+"px"); zoom+=0.1'
                    style="cursor:pointer; margin-right: 5px" title="Zoom-In">+</a>
                <a onclick='$("#viewport").css("width", ((($("#viewport").css("width") != "none") ? Number($("#viewport").css("width").replace("px", "")) : windowwidth)/1.1)+"px"); zoom-=0.1'
                    style="cursor:pointer; margin-right: 5px" title="Zoom-Out">-</a>
                <a onclick='$("#viewport").css("width", windowwidth+"px")' style="cursor:pointer" title="Zoom-Reset"><i
                        class="fa-solid fa-power-off" style="font-size: 20px; margin-right: 10px"></i></a>
                <span>|</span>
            </div>
            <div style="margin-left: 0">
                <a id="downloadbtn" download title="Speichern"
                    style="color: white;margin-right: 10px;text-decoration: none;">
                    <i class="fas fa-save download"></i>
                </a>
                <a id="printbtn" onclick='print();'
                    title="Drucken" style="cursor: pointer">
                    <i class="fas fa-print"></i>
                </a>
            </div>
        </div>
        <div id="viewport-container">
            <div role="main" id="viewport"></div>
        </div>
    </div>
    <iframe id="downloadprev" height="0px" width="0px"></iframe>
    <!-- <script src="https://unpkg.com/pdfjs-dist@2.0.489/build/pdf.min.js"></script> -->
    <script src="/js/pdf.js/build/pdf.js"></script> <!-- TODO: update pdf.js version -->
    <!-- <script src="/js/pdf.js/pdf.worker.js"></script> -->
    <script src="/js/pdfviewer.js"></script>
    <script src="/js/jquery.min.js"></script>
    <script>
        var src = "<?php echo $_GET["file"] ?>";
        function print(){ //TODO: Fix Print on Safari
            // var wnd = window.open(src);
            // wnd.print();
            // wnd.close();
            document.getElementById("downloadprev").contentWindow.print();
        }
        // var standardzoom = ($("#viewport").css("max-width") != "none") ? Number($("#viewport").css("max-width").replace("px", "")) : Number($("#viewport").css("width").replace("px", "")) + "px";
        function getzoom() {
            return (($("#viewport").css("width") != "none") ? Number($("#viewport").css("width").replace("px", "")) : windowwidth);
        }
        var standardzoom = getzoom();
        function setzoom(width) { //TODO: Fix Zoom + Implement it
            $("#viewport, .textLayer").css("width", width);
            $("canvas").width(width);
            for (var i = 0; i < $("canvas").length; i++) {
                textLayer = $(".textLayer")[i];
                canvas = $("canvas")[i];
                textLayer.style.width = canvas.style.width + 'px';
                textLayer.style.height = canvas.style.height + 'px';
                textLayer.style.left = canvas.offsetLeft + 'px';
                textLayer.style.top = canvas.offsetTop + 'px';
            }
            $("canvas").siblings(".textLayer").css("width", $("canvas").css("width")).css("height", $("canvas").css("height"));
            console.log(width);
            // console.log(standardzoom);
            // $(".textLayer").css("scale", (width/Number((standardzoom).replace("px", ""))).toString());
            $(".textLayer").css("scale", (width/standardzoom).toString());
        }
        initPDFViewer(src);
        $("#downloadbtn").attr("href", src);
        $("#downloadprev").attr("src", src);
        var zoom = 1.0;
        var windowwidth;
        function onresizefunc() {
            $("#viewport").hide();
            windowwidth = window.innerWidth;
            console.log(windowwidth);
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
        // onresizefunc();
    </script>
</body>
<script defer>
    function adjustheader() {
        // var sonstiges_width = $(".header ul li:last-child").not(".header ul li ul li").width();
        // var sonstiges_width = 240; // Slide out menu buttons width
        // var window_width = $(window).width();
        // var menu_width = sonstiges_width;
        // $("#Sonstiges-Button").show();
        // $("#mobile").hide();
        // $("#Sonstiges li").not("#Sonstiges li ul li").each(function () {
        //     $(this).insertBefore("#Sonstiges-Button");
        // })
        // $("#mobile-list li").not("#mobile-list li ul li").each(function () {
        //     $(this).insertBefore("#Sonstiges-Button");
        // })
        // if (window_width <= 460) {
        //     $("#Sonstiges-Button").hide();
        //     $("#mobile").show();
        //     $(".header ul li").not(".header ul li ul li").not(".header ul li:last-child").each(function () {
        //         $(this).appendTo("#mobile-list");
        //     })
        // } else {
        //     $(".header ul li").not(".header ul li ul li").not(".header ul li:last-child").each(function (index) { // Iterate every head element excluding "Sonstiges"
        //         var isLastElement = index == $(".header ul li").not(".header ul li ul li").not(".header ul li:last-child").length - 1;
        //         menu_width += $(this).width();
        //         if (menu_width < window_width) {
        //             if (isLastElement) { $("#Sonstiges-Button").hide(); }
        //         } else if (isLastElement && menu_width - sonstiges_width <= window_width) {
        //             $("#Sonstiges-Button").hide();
        //         } else {
        //             $(this).appendTo("#Sonstiges");
        //         }
        //     })
        // }
        headerheight();
    }
    function headerheight() {
        var interval1 = setInterval(function () {
            if (typeof onresizefunc == "function") { onresizefunc() };
            // if (document.getElementById('page-beginning').style.height != $('.header').outerHeight() + "px") {
            //     document.getElementById('page-beginning').style.height = $('.header').outerHeight() + "px";
            // } else {
            clearInterval(interval1);
            // }
        }, 50);
    }
    adjustheader();
    window.onresize = function (e) {
        adjustheader();
    }

</script>

</html>