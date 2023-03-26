<section id="previewiframe" style="display: none">
    <link rel="stylesheet" href="/new-css/documents.css">
    <div onclick="event.stopPropagation();$('.filepreview').hide()" style='left: 0;' class='filepreview'>
        <span class='helper'></span>
        <div>
            <iframe title="Dateivorschau" src="" onclick="event.stopPropagation();" id="filepreviewiframe" style="margin:auto; display:block"></iframe>
            <div onclick="event.stopPropagation();$('.filepreview').hide();" class='popupCloseButton'>&times;</div>
        </div>
    </div>
</section>
<section id="files">
    <table id="fileTable">
    <tr style="display: none">
        <td></td>
        <td></td>
    </tr>

        <?php
            if(isset($_GET["dir"])){
                $dir = "/".$_GET["dir"];
            }else{
                $dir = "";
            }
            $scriptpath = "https://".$_SERVER['SERVER_NAME'].str_replace("?".$_SERVER['QUERY_STRING'],'', $_SERVER['REQUEST_URI']);
            function changedir($url) {
                echo("window.history.pushState({}, null, \"".$url."\")");
            }
            function listfiles($dir, $scriptpath) {
                $root = realpath($_SERVER["DOCUMENT_ROOT"]);
                if($_SERVER['QUERY_STRING'] != ""){
                    $dirpath = "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."/";
                }else{
                    $dirpath = "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?dir=";
                }
                // $pathworoot = "/files/document-page".$dir;
                $pathworoot = $GLOBALS["rootdir"].$dir;
                $path = $root.$pathworoot;
                $files = array_diff(scandir($path), array('.', '..'));
                // echo("<ul class='docs-list' style='list-style-type: none'>");
                if($dir != ""){
                    if(substr(pathinfo($dir, PATHINFO_DIRNAME), 0, 1) == "/"){ $dir = (ltrim(pathinfo($dir, PATHINFO_DIRNAME), "/")); }
                    if($dir != ""){$dir="?dir=".$dir;}
                    echo("<tr onclick='window.location=\"".$scriptpath.$dir."\";' class='dirup' title='Zurück'>
                        <td colspan='2'>
                        <p><i class='fas fa-chevron-left' style='margin-right: 5px;'></i>Zurück</p>
                        </td>
                        </tr>"); // window.history.pushState({}, null, \"".$scriptpath.$dir."\")
                }else{$hidefirstline = "<style>span.line:first-child { display: none; } </style>";}
                foreach($files as $i){
                    // echo('<span class="line"></span>');
                    if (is_dir($path."/".$i)) { // Check if object is a directory
                        echo("<tr onclick='window.location.href=\"".$dirpath.$i."\";' class='folder' title='Ordner öffnen'>");
                        if($GLOBALS["admin"] == true) {
                            echo("<td>");
                        } else {
                            echo("<td colspan='2'>");
                        }
                        echo("
                            <p>
                            <i class='far fa-folder'></i>
                            <span class='file_name_span'>".$i."</span>
                            </p>
                            </td>");
                        if ($GLOBALS["admin"] == true){
                        echo("  <td class='floatright' nowrap='nowrap'>
                                <p>
                                <a onclick='event.stopPropagation();' href='/admin/dokumente/rename.php?path=".str_replace("/files/","", $pathworoot)."/".$i."' title='Ordnername bearbeiten'><i class='fas fa-edit' style='cursor: pointer; margin-right: 5px;'></i></a>
                                <a onclick=\"event.stopPropagation();$('.confirm').show();$('#confirmdelete').attr('href','/admin/scripts/delete-file.php?path=".str_replace("/files/","", $pathworoot)."/".$i."');$('#confirmtext').html('Möchtest du den Ordner &#34;".$i."&#34; wirklich löschen?')\" title='Ordner löschen'><i class='fas fa-trash red' style='color:#F75140; margin-right: 5px;'></i></a>
                                </p>
                            </td>
                            </tr>");
                        }
                    }elseif (is_file($path."/".$i)){ // Check if object is a file
                        $extension = pathinfo($i, PATHINFO_EXTENSION);
                        if ($extension=="jpg" || $extension=="jpeg" || $extension=="png" || $extension=="webp"){
                            $icon = "far fa-file-image";
                            $is_image = true;
                            $previewaction = 'document.getElementById("imgpreviewsrc").src="'.$pathworoot."/".$i.'";$(".img").show();';
                            $title = "Bild öffnen";
                        } else if ($extension == "pdf") {
                            $icon = "far fa-file-pdf";
                            $is_image = false;
                            $previewaction = '$("#filepreviewiframe").attr("src", "/pdfviewer/?file='.$pathworoot."/".$i.'"); $("#filepreviewiframe").attr("title", "'.$i.'"); $("#previewiframe").show();$(".filepreview").show()';
                            $title = "PDF öffnen";
                        } else {
                            $icon = "far fa-file-alt";
                            $is_image = false;
                            $previewaction = 'window.location.href = "'.$pathworoot."/".$i.'"';
                            $previewaction = '$("#filepreviewiframe").attr("src", "https://drive.google.com/viewerng/viewer?embedded=true&url="+encodeURIComponent("https://'.$_SERVER['SERVER_NAME'].$pathworoot."/".$i.'")); $("#filepreviewiframe").attr("title", "'.$i.'"); $("#previewiframe").show();$(".filepreview").show()';
                            $style = "cursor: pointer;";
                            $title = null;
                        }
                        echo("<tr onclick='".$previewaction."' class='file' style='".$style."' title='".$title."'>
                            <td class='filename'>
                                <p><i class='".$icon."'></i>
                                <span class='file_name_span'>".pathinfo($i, PATHINFO_FILENAME)."</span></p>
                            </td>
                            <td class='floatright' nowrap='nowrap'>
                                <p>
                                    <a class='downloadlink' href='".$pathworoot."/".$i."' onclick=\"event.stopPropagation();\" download><i class='far fa-save download' title='Herunterladen'></i></a>");
                                if ($GLOBALS["admin"] == true) {
                                    echo("<a onclick='event.stopPropagation();' href='/admin/dokumente/rename.php?path=".str_replace("/files/","", $pathworoot)."/".$i."' title='Dateiname bearbeiten'><i class='fas fa-edit' style='cursor: pointer; margin-right: 5px;'></i></a>");
                                    echo("<a onclick=\"event.stopPropagation();$('.confirm').show();$('#confirmdeletefirst').attr('href','/admin/scripts/delete-file.php?path=".str_replace("/files/","", $pathworoot)."/".$i."');$('#confirmdelete').attr('href','/admin/scripts/delete-file.php?path=".str_replace("/files/","", $pathworoot)."/".$i."');$('#confirmtext').html('Möchtest du die Datei &#34;".substr($i,0,20).(strlen($i)>20 ? "&hellip;":"")."&#34; wirklich löschen?')\" title='Datei löschen'><i class='fas fa-trash red' style='color:#F75140; margin-right: 5px;'></i></a>");
                                }
                        echo("      <span class='editdate'>Hochgeladen am: ".date("d.m.Y H:i:s", filemtime($path."/".$i))."</span>
                                </p>
                            </td>
                        </tr>");
                    }else {
                        // echo("unknown type");
                    }
                }
                echo("<div onclick=\"event.stopPropagation();$('.img').hide()\" style='left: 0;' class='img'>
                    <span class='helper'></span>
                    <div>
                        <img onclick=\"event.stopPropagation();\" id='imgpreviewsrc' style='margin: auto'>
                        <div onclick=\"event.stopPropagation();$('.img').hide()\" class='popupCloseButton'>&times;</div>
                    </div>
                </div>");
                echo($hidefirstline);
                echo("</ul>");
            }
            listfiles($dir, $scriptpath);
            if($GLOBALS["admin"] == true) {
                require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
                deleteconfirm("Löschung bestätigen", "confirmtext", "Abbrechen", "Löschen", "confirmdelete");
            }
        ?>

        <script>
            onresizefunc += 'if ($(".filename").width()>=7/8*$(".file_name_span").width() && $(window).width() > 500) { $(".editdate").show(); } else { $(".editdate").hide(); };'
            // if (!$(".editdate").is("hidden")) {
            //     if ($(".filename").outerWidth()+$(".floatright").outerWidth() <= 0.9 * $(window).width()) {
            //         $(".editdate").hide();
            //     }
            // }
            // if (1/2*$(window).width()>$(".file_name_span").width()) { $(".editdate").show(); } else { $(".editdate").hide(); } // TODO: implement this
            // if ( $(".filename").outerWidth()+30+320 > $("#fileTable").width() ) { $(".editdate").hide(); } else { $(".editdate").show(); }
        </script>
    </table>
</section>