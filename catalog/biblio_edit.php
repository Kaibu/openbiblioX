<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");

$tab = "cataloging";
$nav = "edit";

//IMPORTANT get bibid before creating nav
if (!isset($_GET['bibid']) OR !is_int((int )$_GET['bibid'])) {
    die('Wrong Parameter');
}
$bibid = (int)$_GET['bibid'];

require_once("../shared/logincheck.php");
require_once("../shared/header.php");

require_once("../functions/inputFuncs.php");
require_once("../mods/include_mods.php");

require_once("../classes/Biblio.php");
require_once("../classes/BiblioQuery.php");

require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

//Handle request Data
$bibObj = new Biblio();
$bibQ = new BiblioQuery();

if (isset($_POST['is_postback'])) {
    $bibObj->setBibid($_POST['bibid']);
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
    $bibObj->setLanFromLvl($_POST['in_from_lvl']);
    $bibObj->setLanToLvl($_POST['in_to_lvl']);

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

    $res = $bibQ->update($bibObj);
    echo '<META HTTP-EQUIV="refresh" content="0;URL=../shared/biblio_view.php?bibid='.$bibObj->getBibid().'">';
    die('Weiterleitung zu Medieninfo ...');
    //$bibObj = $bibQ->doQuery($bibid);
} else {
    $bibObj = $bibQ->doQuery($bibid);
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

?>
    <!-- Include some Javascript Functions for showing stuff when selecting -->
    <script type="text/javascript" src="../mods/lib/javascript.js"></script>

    <form name="editbiblioform" method="POST"
          action="../catalog/biblio_edit.php?bibid=<?php echo HURL($bibObj->getBibid()) ?>"
          style="border:hidden !important;">
        <input type="hidden" name="is_postback" value="1">
        <input type="hidden" name="bibid" value="<?php echo H($bibObj->getBibid()); ?>">

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Titel</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_title" value="<?php echo H($bibObj->getTitle()); ?>" autofocus>
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
            <label class="col-xs-2 col-form-label">ISBN</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_isbn" value="<?php echo H($bibObj->getIsbn()); ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Verlag</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_publisher" value="<?php echo H($bibObj->getPublisher()); ?>">
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
                <input class="form-control" name="in_pub_year" value="<?php echo H($bibObj->getPubYear()); ?>">
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
                           value="<?php echo H($bibObj->getSignature()); ?>">
                </div>

            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Sprache</label>
            <div class="col-xs-8">
                <?php echo dmSelect("nt_sprachen", "in_language", $bibObj->getLanguageId(), FALSE, $formAttr); ?>
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
                <?php
                $mat_cat = $bibQ->getMaterialCategory($bibObj);
                if ($mat_cat == 1) {
                    echo "Seitenanzahl";
                } else if ($mat_cat == 2) {
                    echo "Spieldauer";
                } else {
                    echo "Keine Länge";
                }
                ?>
            </label>
            <div class="col-xs-8">
                <label class="form-check-inline">
                    <input class="form-control" name="in_duration"
                        <?php
                        $mat_cat = $bibQ->getMaterialCategory($bibObj);
                        if ($mat_cat == 1) {
                            echo "value='" . H($bibObj->getPages()) . "''";
                        } else if ($mat_cat == 2) {
                            echo "value='" . H($bibObj->getDuration()) . "''";
                        } else {
                            echo "disabled";
                        }
                        ?> >
                </label>
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
                <button type="button" class="btn btn-outline-success" data-toggle="popover" title="Info" data-content="Gewünschtes Tag eingeben, anklicken um hinzu zu fügen. Bei manueller eingabe Tags durch ; trennen.">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8 offset-xs-2">
                <input type="button" value="Speichern" onclick="submitForm('editbiblioform');return false;" class="btn btn-primary pull-right">
                <a href="../shared/biblio_view.php?bibid=<?php echo HURL($bibObj->getBibid()) ?>"
                   class="btn btn-danger pull-left">Zurück</a>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        listSignature(document.getElementById('in_sys').getElementsByTagName('option')[document.getElementById('in_sys').selectedIndex].value, 'start_sig');

        function submitForm(form){
            $("form[name*='"+form+"']").submit();
        }

        $('#ajax_search').keypress(function(e) {
            if (e.which == 13) {//Enter key pressed
                var ref = $('#first_result').attr('href');
                if (!(ref === "")) {
                    window.location.href = ref;
                    return false;
                }
            }
        });

        function setGenericLength() {
            $('#lbl_length').text('Länge');
        }

        function showResult(str) {
            if (str.length==0) {
                document.getElementById("livesearch").innerHTML="";
                document.getElementById("livesearch").style.border="0px";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    document.getElementById("livesearch").innerHTML=this.responseText;
                    document.getElementById("livesearch").style.border="1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET","../catalog/ajax_tag_search.php?q="+str,true);
            xmlhttp.send();
        }

        function addTag(tag){
            var in_tag = $('#in_tags');
            var val = in_tag.val();
            if(val === ""){
                val += tag;
            }else{
                val += ";" + tag;
            }

            in_tag.val(val);
        }
    </script>


<?php
include("../shared/footer.php");
?>