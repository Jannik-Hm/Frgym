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
            <li><label class="chkbx_label"><span class="chkbx"></span><input onclick="addFach('DE')" type="checkbox" id="DE">Deutsch</label></li>
            <li><label><input onclick="addFach('EN')" type="checkbox" id="EN">Englisch</label></li>
            <li><label><input onclick="addFach('FR')" type="checkbox" id="FR">Französisch</label></li>
            <li><label><input onclick="addFach('PO')" type="checkbox" id="PO">Polnisch</label></li>
            <li><label><input onclick="addFach('RU')" type="checkbox" id="RU">Russisch</label></li>
            <li><label><input onclick="addFach('SN')" type="checkbox" id="SN">Spanisch</label></li>
            <li><label><input onclick="addFach('TR')" type="checkbox" id="TR">Türkisch</label></li>
            <li><label><input onclick="addFach('LA')" type="checkbox" id="LA">Latein</label></li>
        </ul>
        <ul>
            <label class="heading">Naturwissenschaften</label>
            <li><label><input onclick="addFach('MA')" type="checkbox" name="chk_group[]" id="MA">Mathematik</label></li>
            <li><label><input onclick="addFach('BI')" type="checkbox" name="chk_group[]" id="BI">Biologie</label></li>
            <li><label><input onclick="addFach('CH')" type="checkbox" name="chk_group[]" id="CH">Chemie</label></li>
            <li><label><input onclick="addFach('PH')" type="checkbox" name="chk_group[]" id="PH">Physik</label></li>
            <li><label><input onclick="addFach('IF')" type="checkbox" name="chk_group[]" id="IF">Informatik</label></li>
            <li><label><input onclick="addFach('NW')" type="checkbox" name="chk_group[]" id="NW">Naturwissenschaften</label></li>
        </ul>
        <ul>
            <label class="heading">Gesellschaftswissenschaften</label>
            <li><label><input onclick="addFach('EK')" type="checkbox" name="chk_group[]" id="EK">Erdkunde</label></li>
            <li><label><input onclick="addFach('GE')" type="checkbox" name="chk_group[]" id="GE">Geschichte</label></li>
            <li><label><input onclick="addFach('PB')" type="checkbox" name="chk_group[]" id="PB">Politische Bildung</label></li>
            <li><label><input onclick="addFach('EG')" type="checkbox" name="chk_group[]" id="EG">Gesellschaftswissenschaften</label></li>
            <li><label><input onclick="addFach('RE')" type="checkbox" name="chk_group[]" id="RE">Evangelischer Religionsunterricht</label></li>
            <li><label><input onclick="addFach('RK')" type="checkbox" name="chk_group[]" id="RK">Katholischer Religionsunterricht</label></li>
            <li><label><input onclick="addFach('LE')" type="checkbox" name="chk_group[]" id="LE">Lebensgestaltung-Ethik-Religionskunde</label></li>
            <li><label><input onclick="addFach('AL')" type="checkbox" name="chk_group[]" id="AL">Wirtschaft-Arbeit-Technik</label></li>
            <li><label><input onclick="addFach('WW')" type="checkbox" name="chk_group[]" id="WW">Wirtschaftswissenschaften</label></li>
        </ul>
        <ul>
            <label class="heading">Künstlerische Fächer</label>
            <li><label><input onclick="addFach('DS')" type="checkbox" name="chk_group[]" id="DS">Darstellendes Spiel</label></li>
            <li><label><input onclick="addFach('KU')" type="checkbox" name="chk_group[]" id="KU">Kunst</label></li>
            <li><label><input onclick="addFach('MU')" type="checkbox" name="chk_group[]" id="MU">Musik</label></li>
        </ul>
        <ul>
            <label class="heading">Sonstige</label>
            <li><label><input onclick="addFach('SP')" type="checkbox" name="chk_group[]" id="SP">Sport</label></li>
        </ul>
        <ul><button id="resetFilter" style="cursor: pointer;" onclick="resetFilter(filteredFaecher)">Filter zurücksetzen</button></ul>
    </ul>
</div>
<script>$("#lehrerFaecherFilter").hide()</script>