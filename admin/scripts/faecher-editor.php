<?php

    function segment_selector() {
        echo("
        <link rel='stylesheet' href='/new-css/faecher.css'>
        <section id='faecher-selector'>
            <btn onclick='$(\".faecher-selector-popup\").show()' title='Segment hinzufügen' style=''>+</btn>
        </section>
        <div style='left: 0;' onclick=\"event.stopPropagation();$('.faecher-selector-popup').hide()\" class='faecher-selector-popup'>
        <span class='helper'></span>
            <div onclick=\"event.stopPropagation();\" class='scroll'>
                <div onclick=\"event.stopPropagation();$('.faecher-selector-popup').hide()\" class='popupCloseButton'>&times;</div>
                <div class='faecher-selector-popup-list'>
                    <ul>
                        <li>Test</li>
                    </ul>
                </div>
            </div>
        </div>
        ");
        // TODO: add popup Selector listing all possible layouts
    }

    // Question: live save or save btn?

    // TODO: Dropzone for pictures with "placeholder" img as background

    function save_segment() {
        // TODO: save segment to individual DB entry
        // for every segment save to DB

        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        if(isset($_POST["submit"])) {
            if(isset($_POST["update"])){
                echo("update query");
                // TODO: add edit feature
                $insert = mysqli_query(getsqlconnection(), "UPDATE faecher SET content1=NULLIF(\"{$_POST['content1']}\", '') content2=NULLIF(\"{$_POST['content2']}\", '') content3=NULLIF(\"{$_POST['content3']}\", '') WHERE id=\"{$_POST['id']}\"");
            }else{
                $insert = mysqli_query(getsqlconnection(), "INSERT INTO faecher (id, fach, position, contenttype, content1, content2, content3) VALUES (\"{$_POST['id']}\", \"{$_GET['fach']}\", \"\", \"{$_POST['contenttype']}\", NULLIF(\"{$_POST['content1']}\", ''), NULLIF(\"{$_POST['content2']}\", ''), NULLIF(\"{$_POST['content3']}\", ''))");
            }
            if ($insert) {
            // confirm action
            }
        }
    }

    // TODO: make segments editable / updatable
    // TODO: make segments movable

?>