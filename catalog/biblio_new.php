<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 *
 * Seite für das Anlegen eines neuen Eintrags. Bindet größtenteils katalogisierung.php ein
 *
 */

require_once("../shared/common.php");

$tab = "cataloging";
$nav = "new";

require_once("../shared/logincheck.php");
require_once("../shared/header.php");

require_once("../functions/inputFuncs.php");
require_once("../mods/include_mods.php");

require_once("../classes/Biblio.php");
require_once("../classes/BiblioQuery.php");
require_once("../classes/BiblioCopy.php");
require_once("../classes/BiblioCopyQuery.php");
require_once("../classes/JsonImportQuery.php");


require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$bibObj = new Biblio();
$bibQ = new BiblioQuery();

//populate from import id
if (isset($_GET['import_id'])) {
    if (preg_match("([1-9]{1,6})", $_GET['import_id']) && is_int((int)$_GET['import_id'])) {
        $id = $_GET['import_id'];
    } else {
        die();
    }

    $jsQuery = new JsonImportQuery();
    $row = mysqli_fetch_assoc($jsQuery->getEntryByID($id));
    $jsQuery->markImported($id);
    //TODO create bib Obj
}

//populate from existing biblio id
if (isset($_GET['bibid'])) {
    if (preg_match("([1-9]{1,9})", $_GET['bibid']) && is_int((int)$_GET['bibid'])) {
        $id = $_GET['bibid'];
    } else {
        die('PARAMETER ERROR');
    }

    $bibObj = $bibQ->doQuery($id);
    $bibObj->setSignature("");
}


if (isset($_POST['is_postback'])) {
    $bibObj->setMaterialCd($_POST['in_material']);

    $sigArr = explode('.', $_POST['in_sys']);
    $bibObj->setCollectionCd($bibQ->getSignatureId($sigArr[0], $sigArr[1]));
    $bibObj->setSignature($_POST['in_signature']);
    $bibObj->setLanguageId($_POST['in_language']);


    if ($_POST['in_skill_hear'] == "on") {
        $bibObj->setSkillHear(true);
    }
    if ($_POST['in_skill_speak'] == "on") {
        $bibObj->setSkillSpeak(true);
    }
    if ($_POST['in_skill_write'] == "on") {
        $bibObj->setSkillWrite(true);
    }
    if ($_POST['in_skill_grammar'] == "on") {
        $bibObj->setSkillGrammar(true);
    }
    if ($_POST['in_skill_read'] == "on") {
        $bibObj->setSkillRead(true);
    }

    $bibObj->setOpacFlg($_POST['in_opac_flg']);
    $bibObj->setLocationId($_POST['in_location']);
    $bibObj->setLastChangeUserid($_SESSION["userid"]);
    if ($_POST['in_from_lvl'] == "-") {
        $bibObj->setLanFromLvl("1");
    } else {
        $bibObj->setLanFromLvl($_POST['in_from_lvl']);
    }

    if ($_POST['in_to_lvl'] == "-") {
        $bibObj->setLanToLvl("6");
    } else {
        $bibObj->setLanToLvl($_POST['in_to_lvl']);
    }

    $mat_cat = $bibQ->getMaterialCategory($bibObj);
    if ($mat_cat == 1) {
        $bibObj->setPages($_POST['in_duration']);
    } else if ($mat_cat == 2) {
        $bibObj->setDuration($_POST['in_duration']);
    }

    $bibObj->setTitle($_POST['in_title']);
    $bibObj->setSubtitle($_POST['in_subtitle']);
    $bibObj->setAuthor($_POST['in_author']);
    $bibObj->setIsbn($_POST['in_isbn']);
    $bibObj->setPublisher($_POST['in_publisher']);
    $bibObj->setPubLoc($_POST['in_pub_loc']);
    $bibObj->setPubYear($_POST['in_pub_year']);
    $bibObj->setSummary($_POST['in_summary']);

    $bibObj->setTags($_POST['in_tags']);

    $res = $bibQ->insert($bibObj);

    $copyQ = new BiblioCopyQuery();
    if ($_POST['in_copy_num'] == "") {
        $copy_num = sprintf("%0" . $nzeros . "s", $res) . $copyQ->nextCopyid($res);
    } else {
        $copy_num = $_POST['in_copy_num'];
    }

    $copy = new BiblioCopy();
    $copyQ = new BiblioCopyQuery();
    $isDuplicate = $bibQ->_dupBarcode($copy_num);

    $err_msg = "";
    $valid = $bibObj->validateData();
    if(count($valid) > 0){
        foreach ($valid as $field){
            switch ($field){
                case 1 : $err_msg .= "Sprache<br/>"; break;
                case 2 : $err_msg .= "Material<br/>"; break;
                case 3 : $err_msg .= "Systematik<br/>"; break;
                case 4 : $err_msg .= "Standort<br/>"; break;
                case 5 : $err_msg .= "ISBN<br/>"; break;
                case 6 : $err_msg .= "Titel<br/>"; break;
                case 7 : $err_msg .= "Verlag<br/>"; break;
                case 8 : $err_msg .= "Länge (Seitenzahl)<br/>"; break;
                case 9 : $err_msg .= "Länge (Spieldauer)<br/>"; break;
            }
        }
    }else{
        if ($isDuplicate) {
            //TODO add proper error handling
            die('DUPLICATE BARCODE');
        } else {
            $copy->setBarcodeNmbr($copy_num);
            $copy->setBibid($res);
            $copyQ->insert($copy);
        }
        if (!$res) {
            echo 'Ein Fehler ist aufgetreten';
        }
        echo '<META HTTP-EQUIV="refresh" content="0;URL=../shared/biblio_view.php?bibid=' . $res . '">';
        die();
    }
}

// Prepare Vars
$formAttr = array(
    "class" => "form-control",
);

$formTypeAttr = array(
    "class" => "form-control",
    "onChange" => "setGenericLength()",
);

$systematics = getSystematicNumbers();
$sysNumbers = getSpecificSystematicNumbers($bibid);
$sysNumbers = $sysNumbers[0] . "." . $sysNumbers[1];

if($err_msg != ""){
    echo "<div class='alert alert-danger' role='alert'><strong>Fehler in folgenden Feldern</strong><br/>".$err_msg."</div>";
}

?>

    <!-- Include some Javascript Functions for showing stuff when selecting -->
    <script type="text/javascript" src="../mods/lib/javascript.js"></script>

    <form name="editbiblioform" method="POST" style="border:hidden !important;">
        <input type="hidden" name="is_postback" value="1">

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">ISBN</label>
            <div class="col-xs-8">
                <div class="input-group">
                    <input class="form-control" type="text" name="in_isbn" id="in_isbn" value="<?php echo H($bibObj->getIsbn()); ?>" autofocus>
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" onclick="searchIsbn();return false;" type="button" style="height: 37.7px"><i class="fa fa-search" aria-hidden="true" ></i></button>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Titel</label>
            <div class="col-xs-8">
                <div class="input-group">
                    <input class="form-control" name="in_title" id="in_title" value="<?php echo H($bibObj->getTitle()); ?>" required>
                    <span class="input-group-btn">
                            <button class="btn btn-secondary" onclick="searchTitle();return false;" type="button" style="height: 37.7px"><i class="fa fa-search" aria-hidden="true" ></i></button>
                            <button type="button" class="btn btn-secondary" style="height: 37.7px" data-container="body" data-toggle="popover" data-placement="right" data-content="Es kann nicht nur nach Titel, sondern auch nach einer Mischung von Titel/Autor/Verlag/.. gesucht werden."><i class="fa fa-question" aria-hidden="true" ></i></button>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Untertitel</label>
            <div class="col-xs-8">
                    <input class="form-control" name="in_subtitle" value="<?php echo H($bibObj->getSubtitle()); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Autor</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_author" value="<?php echo H($bibObj->getAuthor()); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Verlag</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_publisher" value="<?php echo H($bibObj->getPublisher()); ?>"
                       required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Erscheinungsort</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_pub_loc" value="<?php echo H($bibObj->getPubLoc()); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Erscheinungsjahr</label>
            <div class="col-xs-8">
                <input class="form-control" type="number" name="in_pub_year"
                       value="<?php echo H($bibObj->getPubYear()); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Zusammenfassung</label>
            <div class="col-xs-8">
                <textarea name="in_summary" class="form-control"
                          rows="3"><?php echo H($bibObj->getSummary()); ?></textarea>
            </div>
        </div>

        <hr/>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Medienart</label>
            <div class="col-xs-8">
                <?php echo dmSelect("material_type_dm", "in_material", $bibObj->getMaterialCd(), FALSE, $formTypeAttr); ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Sprache</label>
            <div class="col-xs-8">
                <?php echo dmSelect("nt_sprachen", "in_language", $bibObj->getLanguageId(), FALSE, $formAttr,true); ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Systematik</label>
            <div class="col-xs-8">
                <?php echo listQuery($systematics, "in_sys", $sysNumbers, "class='form-control'") ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Signaturzusatz</label>
            <div class="col-xs-8">
                <div class="input-group">
                    <div class="input-group-addon"><span id="start_sig"></span></div>
                    <input type="text" name="in_signature" class="form-control"
                           value="<?php echo H($bibObj->getSignature()); ?>" required>
<!--                    <span class="input-group-btn">-->
<!--                        <button class="btn btn-secondary" onclick="getFreeSignature();return false;" type="button" style="height: 37.7px"><i class="fa fa-search" aria-hidden="true" ></i></button>-->
<!--                    </span>-->
                </div>

            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Standort</label>
            <div class="col-xs-8">
                <?php echo dmSelect("locations", "in_location", $bibObj->getLocationId(), FALSE, $formAttr); ?>
            </div>
        </div>

        <hr/>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Skills</label>
            <div class="col-xs-8">
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox"
                           name="in_skill_hear" <?php if ($bibObj->isSkillHear()) {
                        echo "checked";
                    } ?>> Hören
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox"
                           name="in_skill_speak" <?php if ($bibObj->isSkillSpeak()) {
                        echo "checked";
                    } ?>> Sprechen
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox"
                           name="in_skill_write" <?php if ($bibObj->isSkillWrite()) {
                        echo "checked";
                    } ?>> Schreiben
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox"
                           name="in_skill_grammar" <?php if ($bibObj->isSkillGrammar()) {
                        echo "checked";
                    } ?>> Grammatik
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox"
                           name="in_skill_read" <?php if ($bibObj->isSkillRead()) {
                        echo "checked";
                    } ?>> Lesen
                </label>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">OPAC</label>
            <div class="col-xs-8">
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox" name="in_opac_flg" <?php if ($bibObj->isOpacFlg()) {
                        echo "checked";
                    } ?>> Im OPAC anzeigen
                </label>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Niveau</label>
            <div class="col-xs-3">
                <label class="form-check-inline">
                    <?php echo db_niveau_sel("in_from_lvl", $bibObj->getLanFromLvl(), "class=form-control"); ?>
                </label>
            </div>
            <div class="col-xs-2">
                <label class="form-check-inline">
                    bis
                </label>
            </div>
            <div class="col-xs-3">
                <label class="form-check-inline">
                    <?php echo db_niveau_sel("in_to_lvl", $bibObj->getLanToLvl(), "class=form-control"); ?>
                </label>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label" id="lbl_length">
                Länge

            </label>
            <div class="col-xs-8">
                <label class="form-check-inline">
                    <?php
                    if ($bibObj->getPages() != "" AND $bibObj->getPages() != 0) {
                        $dur = $bibObj->getPages();
                    } else {
                        $dur = $bibObj->getDuration();
                    }
                    ?>
                    <input class="form-control" name="in_duration" value="<?php echo H($dur) ?>">
                </label>
                <button type="button" class="btn btn-outline-success" data-toggle="popover" title="Info"
                        data-content="Seitenzahl/Spieldauer wird bestimmt aus Medienart">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <hr/>
        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Schlagwörter</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_tags" id="in_tags" value="<?php echo H($bibObj->getTags()) ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Schlagwort Suche</label>
            <div class="col-xs-6">
                <input type="text" size="30" onkeyup="showResult(this.value)" class="form-control" id="ajax_search">
                <div id="livesearch"></div>
            </div>
            <div class="col-xs-2">
                <button type="button" class="btn btn-outline-success" data-toggle="popover" title="Info"
                        data-content="Gewünschtes Tag eingeben, anklicken um hinzu zu fügen. Bei manueller eingabe Tags durch ; trennen.">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <hr/>
        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Mediennummer</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_copy_num" placeholder="Leer lassen für automatische Nummer">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8 offset-xs-2">
                <input type="button" onclick="submitForm('editbiblioform')" class="btn btn-primary pull-right" value="Speichern">
            </div>
        </div>

    </form>

    <script type="text/javascript">
        listSignature(document.getElementById('in_sys').getElementsByTagName('option')[document.getElementById('in_sys').selectedIndex].value, 'start_sig');

        function submitForm(form){
            $("form[name*='"+form+"']").submit();
        }

        $('#ajax_search').keypress(function(e){
            if(e.which == 13){//Enter key pressed
                var ref = $('#first_result').attr('href');
                if(!(ref === "")){
                    window.location.href = ref;
                    return false;
                }
            }
        });

        function setGenericLength() {
            $('#lbl_length').text('Länge');
        }
        $(document).ready(function () {
            $(function () {
                $('[data-toggle="popover"]').popover()
            });
        });

        function showResult(str) {
            if (str.length == 0) {
                document.getElementById("livesearch").innerHTML = "";
                document.getElementById("livesearch").style.border = "0px";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("livesearch").innerHTML = this.responseText;
                    document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET", "../catalog/ajax_tag_search.php?q=" + str, true);
            xmlhttp.send();
        }

        function addTag(tag) {
            var in_tag = $('#in_tags');
            var val = in_tag.val();
            if (val === "") {
                val += tag;
            } else {
                val += ";" + tag;
            }

            in_tag.val(val);
        }

        function searchTitle(){
            var title = $('#in_title').val();
            title = title.replace('-','');
            $.getJSON( ("../catalog/ajax_gbv_search.php?isbn=" + title), function( data ) {
                $("input[name*='in_title']").val(data.title);
                $("input[name*='in_subtitle']").val(data.sub_title);
                $("input[name*='in_author']").val(data.author);
                $("input[name*='in_publisher']").val(data.publisher);
                $("input[name*='in_pub_loc']").val(data.pub_place);
                $("input[name*='in_pub_year']").val(data.pub_year);

            });
        }

        function searchIsbn(){
            var isbn = $('#in_isbn').val();
            isbn = isbn.replace('-','');
            $.getJSON( ("../catalog/ajax_gbv_search.php?isbn=" + isbn), function( data ) {
                $("input[name*='in_title']").val(data.title);
                $("input[name*='in_subtitle']").val(data.sub_title);
                $("input[name*='in_author']").val(data.author);
                $("input[name*='in_publisher']").val(data.publisher);
                $("input[name*='in_pub_loc']").val(data.pub_place);
                $("input[name*='in_pub_year']").val(data.pub_year);

            });

        }

        $(function () {
            $('[data-toggle="popover"]').popover()
        })

        function getFreeSignature(){
            var sys = $("select[name*='in_sys']").val();
            var lan = $("select[name*='in_language']").val();
            if(sys == 0){
                alert("Bitte erst eine Systematik auswählen!");
                return;
            }
            if(lan == -1){
                alert("Bitte erst eine Sprache auswählen!");
                return;
            }
            var split = sys.split('.');

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    $("input[name*='in_signature']").val(this.responseText);
                }
            };
            xmlhttp.open("GET", "../catalog/ajax_sig_search.php?s=" + split[0] + "&sc=" + split[1] + "&l=" + lan, true);
            xmlhttp.send();
        }

    </script>

<?php
include("../shared/footer.php");
?>