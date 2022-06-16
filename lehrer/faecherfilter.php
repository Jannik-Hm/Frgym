<script>
    function hidebutton() {
        var item = document.getElementById('lehrerFaecherFilter');
        var icon = document.getElementById('showmorearrow');
        if(item.style.display == "none"){
            $('#lehrerFaecherFilter').slideToggle();
            icon.style.transform = "rotate(180deg)";
        }else{
            $('#lehrerFaecherFilter').slideToggle();
            icon.style.transform = "rotate(0deg)";
        }
    }
</script>
<script>
    var filteredFaecher = [];
    function addFach(fach) {
        if(document.getElementById(fach).checked){
            filteredFaecher.push(fach);
            filterFaecher(filteredFaecher);
        }else{
            filteredFaecher = filteredFaecher.filter(e => e !== fach);
            filterFaecher(filteredFaecher);
        }
    };
    function resetFilter(array) {
        for(let i = 0; i < array.length; i++){
            document.getElementById(array[i]).checked = false;
        }
        array.length = 0;
        filterFaecher(array);
    };
    function filterFaecher(faecher) {
                // Declare variables
                var input, table, tr, td, i, txtValue;
                table = document.getElementById("lehrerTable");
                tr = table.getElementsByTagName("tr");

                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1];
                    if (td) {
                        var containsfach = false;
                        txtValue = td.textContent || td.innerText;
                        for(let i = 0; i < faecher.length; i++){
                            if(txtValue.includes(faecher[i])){
                                containsfach = true;
                            }
                        }
                        if (containsfach) {
                            tr[i].style.display = "";
                        } else if (!(faecher === undefined || faecher.length == 0)) {
                            tr[i].style.display = "none";
                        } else if (faecher === undefined || faecher.length == 0) {
                            tr[i].style.display = "";
                        }
                    }
                }
            };
</script>
<button id="lehrerFaecherFilterShow" style="cursor: pointer;" onclick="hidebutton();"><i class="fas fa-filter" style="margin-right: 7px"></i>Nach Fächern Filtern<i id="showmorearrow" style="margin-left: 6px;" class="fas fa-chevron-down"></i></button>
<div id="lehrerFaecherFilter">
    <label class="heading2">Fächer</label>
    <ul>
        <ul>
            <label class="heading">Sprachwissenschaften</label>
            <li><label class="chkbx_label"><input onclick="addFach('DE')" type="checkbox" id="DE"><span class="chkbx"></span>Deutsch</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('EN')" type="checkbox" id="EN"><span class="chkbx"></span>Englisch</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('FR')" type="checkbox" id="FR"><span class="chkbx"></span>Französisch</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('PO')" type="checkbox" id="PO"><span class="chkbx"></span>Polnisch</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('RU')" type="checkbox" id="RU"><span class="chkbx"></span>Russisch</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('SN')" type="checkbox" id="SN"><span class="chkbx"></span>Spanisch</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('TR')" type="checkbox" id="TR"><span class="chkbx"></span>Türkisch</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('LA')" type="checkbox" id="LA"><span class="chkbx"></span>Latein</label></li>
        </ul>
        <ul>
            <label class="heading">Naturwissenschaften</label>
            <li><label class="chkbx_label"><input onclick="addFach('MA')" type="checkbox" name="chk_group[]" id="MA"><span class="chkbx"></span>Mathematik</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('BI')" type="checkbox" name="chk_group[]" id="BI"><span class="chkbx"></span>Biologie</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('CH')" type="checkbox" name="chk_group[]" id="CH"><span class="chkbx"></span>Chemie</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('PH')" type="checkbox" name="chk_group[]" id="PH"><span class="chkbx"></span>Physik</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('IF')" type="checkbox" name="chk_group[]" id="IF"><span class="chkbx"></span>Informatik</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('NW')" type="checkbox" name="chk_group[]" id="NW"><span class="chkbx"></span>Naturwissenschaften</label></li>
        </ul>
        <ul>
            <label class="heading">Gesellschaftswissenschaften</label>
            <li><label class="chkbx_label"><input onclick="addFach('EK')" type="checkbox" name="chk_group[]" id="EK"><span class="chkbx"></span>Erdkunde</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('GE')" type="checkbox" name="chk_group[]" id="GE"><span class="chkbx"></span>Geschichte</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('PB')" type="checkbox" name="chk_group[]" id="PB"><span class="chkbx"></span>Politische Bildung</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('EG')" type="checkbox" name="chk_group[]" id="EG"><span class="chkbx"></span>Gesellschaftswissenschaften</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('RE')" type="checkbox" name="chk_group[]" id="RE"><span class="chkbx"></span>Evangelischer Religionsunterricht</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('RK')" type="checkbox" name="chk_group[]" id="RK"><span class="chkbx"></span>Katholischer Religionsunterricht</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('LE')" type="checkbox" name="chk_group[]" id="LE"><span class="chkbx"></span>Lebensgestaltung-Ethik-Religionskunde</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('AL')" type="checkbox" name="chk_group[]" id="AL"><span class="chkbx"></span>Wirtschaft-Arbeit-Technik</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('WW')" type="checkbox" name="chk_group[]" id="WW"><span class="chkbx"></span>Wirtschaftswissenschaften</label></li>
        </ul>
        <ul>
            <label class="heading">Künstlerische Fächer</label>
            <li><label class="chkbx_label"><input onclick="addFach('DS')" type="checkbox" name="chk_group[]" id="DS"><span class="chkbx"></span>Darstellendes Spiel</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('KU')" type="checkbox" name="chk_group[]" id="KU"><span class="chkbx"></span>Kunst</label></li>
            <li><label class="chkbx_label"><input onclick="addFach('MU')" type="checkbox" name="chk_group[]" id="MU"><span class="chkbx"></span>Musik</label></li>
        </ul>
        <ul>
            <label class="heading">Sonstige</label>
            <li><label class="chkbx_label"><input onclick="addFach('SP')" type="checkbox" name="chk_group[]" id="SP"><span class="chkbx"></span>Sport</label></li>
        </ul>
        <ul><button id="resetFilter" style="cursor: pointer;" onclick="resetFilter(filteredFaecher)">Filter zurücksetzen</button></ul>
    </ul>
</div>
<script>$("#lehrerFaecherFilter").hide()</script>