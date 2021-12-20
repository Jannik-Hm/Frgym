var currentSheet = document.getElementById("stylesheet"),
            switcher = document.getElementById("styleswitcher");

            function loadStyle () {
                currentSheet.href = stylez;
            }

            switcher.addEventListener("click", function (ev) {
                var b = ev.target; // button

                if (b && b.hasAttribute("data-stylesheet")) {
                     stylez = b.getAttribute("data-stylesheet");
                } 

                loadStyle();
            });