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

    function save_segments() {
        // TODO: save segment to individual DB entry
        // for every segment save to DB
    }

    // TODO: make segments editable / updatable
    // TODO: make segments movable

?>