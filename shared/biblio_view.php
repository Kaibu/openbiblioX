<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");

#****************************************************************************
#*  Checking for get vars.  Go back to form if none found.
#****************************************************************************
if (count($_GET) == 0) {
    header("Location: ../catalog/index.php");
    exit();
}

#****************************************************************************
#*  Checking for tab name to show OPAC look and feel if searching from OPAC
#****************************************************************************
if (isset($_GET["tab"])) {
    $tab = $_GET["tab"];
} else {
    $tab = "cataloging";
}

$nav = "view";
if ($tab != "opac") {
    require_once("../shared/logincheck.php");
}

require_once("../classes/Biblio.php");
require_once("../classes/Logger.php");
require_once("../classes/BiblioQuery.php");
require_once("../classes/BiblioCopy.php");
require_once("../classes/BiblioCopyQuery.php");
require_once("../classes/DmQuery.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, "shared");

require_once("../mods/include_mods.php");


#****************************************************************************
#*  Retrieving get var
#****************************************************************************
$bibid = $_GET["bibid"];
if (isset($_GET["msg"])) {
    $msg = "<font class=\"error\">" . H($_GET["msg"]) . "</font><br><br>";
} else {
    $msg = "";
}

#****************************************************************************
#*  Loading a few domain tables into associative arrays
#****************************************************************************
$dmQ = new DmQuery();
$collectionDm = $dmQ->getAssoc("collection_dm");
$materialTypeDm = $dmQ->getAssoc("material_type_dm");
$biblioStatusDm = $dmQ->getAssoc("biblio_status_dm");


#****************************************************************************
#*  Search database
#****************************************************************************
$biblioQ = new BiblioQuery();

if (!$biblio = $biblioQ->doQuery($bibid)) {
    displayErrorPage($biblioQ);
}

$logger = new Logger();
$logger->logBibView($bibid);

#**************************************************************************
#*  Show bibliography info.
#**************************************************************************
if ($tab == "opac") {
    require_once("../shared/header_opac.php");
    if (!$biblio->isOpacFlg()) {
        echo '<META HTTP-EQUIV="refresh" content="0;URL=../index.php">';
        die();
    }
} else {
    require_once("../shared/header.php");
}

?>

<?php echo $msg ?>

<h4>Medieninformationen</h4>
<div class="row">
    <div class="col-md-6">
        <dl class="row">
            <dt class="col-sm-3">Titel</dt>
            <dd class="col-sm-9"><?php echo $biblio->getTitle() ?></dd>

            <dt class="col-sm-3">Untertitel</dt>
            <dd class="col-sm-9"><?php echo $biblio->getSubtitle() ?></dd>

            <dt class="col-sm-3">Autor</dt>
            <dd class="col-sm-9"><?php echo $biblio->getAuthor() ?></dd>

            <dt class="col-sm-3">Medienart</dt>
            <dd class="col-sm-9"><?php echo $biblioQ->getMaterialString($biblio) ?></dd>

            <dt class="col-sm-3">Im OPAC?</dt>
            <dd class="col-sm-9"><?php if ($biblio->isOpacFlg()) {
                    echo $loc->getText("biblioViewYes");
                } else {
                    echo $loc->getText("biblioViewNo");
                } ?>
            </dd>
        </dl>
    </div>
    <div class="col-md-6">
        <dl class="row">
            <dt class="col-sm-4">Standort</dt>
            <dd class="col-sm-8"><?php
                if($biblio->getLocationId() == 1){
                    echo "<span style='color: #319d01'><i class=\"fa fa-fort-awesome\" aria-hidden=\"true\"></i> ".$biblioQ->getLocationString($biblio)."</span>";
                }else if($biblio->getLocationId() == 2){
                    echo "<span style='color: #3e57b2'><i class=\"fa fa-building\" aria-hidden=\"true\"></i> ".$biblioQ->getLocationString($biblio)."</span>";
                }else{
                    echo $biblioQ->getLocationString($biblio);
                }
                ?></dd>

            <dt class="col-sm-4">Sprache</dt>
            <dd class="col-sm-8"><?php echo H($biblioQ->getLanguageString($biblio)); ?></dd>

            <dt class="col-sm-4">Signatur</dt>
            <dd class="col-sm-8"><b><?php echo H($biblioQ->getFullSystematic($biblio)); ?></b></dd>

            <dt class="col-sm-4">Hauptkategorie</dt>
            <dd class="col-sm-8"><?php echo H($biblioQ->getMainCategory($biblio)); ?></dd>

            <dt class="col-sm-4">Unterkategorie</dt>
            <dd class="col-sm-8"><?php echo H($biblioQ->getSubCategory($biblio)); ?></dd>
        </dl>
    </div>
</div>

<br/>

<!-- TODO understand and clean this mess -->
<?php

#****************************************************************************
#*  Show copy information
#****************************************************************************
if ($tab == "cataloging") { ?>
    <a href="../catalog/biblio_copy_new_form.php?bibid=<?php echo HURL($bibid); ?>&reset=Y">
        <?php echo $loc->getText("biblioViewNewCopy"); ?></a><br/>
    <?php
    $copyCols = 7;
} else {
    $copyCols = 5;
}

$copyQ = new BiblioCopyQuery();
if (!$res = $copyQ->execSelect($bibid)) {
    displayErrorPage($copyQ);
}
?>

<h4><?php echo $loc->getText("biblioViewTble2Hdr"); ?>:</h4>
<table class="table table-bordered table-hover">
    <tr>
        <?php if ($tab == "cataloging") { ?>
            <th colspan="2" nowrap="yes">
                <?php echo $loc->getText("biblioViewTble2ColFunc"); ?>
            </th>
        <?php } ?>
        <th align="left" nowrap="yes">
            <?php echo $loc->getText("biblioViewTble2Col1"); ?> <!-- Mediennummer header -->
        </th>
        <th align="left" nowrap="yes">
            <?php echo $loc->getText("biblioViewTble2Col2"); ?> <!--Beschreibung header-->
        </th>
        <th align="left" nowrap="yes">
            <?php echo $loc->getText("biblioViewTble2Col3"); ?> <!-- Status header -->
        </th>
        <th align="left" nowrap="yes">
            <?php echo "Datum Ausleihe"/*$loc->getText("biblioViewTble2Col4")*/
            ; ?><!-- ausgeliehen am -->
        </th>
        <th align="left" nowrap="yes">
            <?php echo $loc->getText("biblioViewTble2Col5"); ?><!-- Rückgabe-->
        </th>
    </tr>
    <?php

    if ($copyQ->getRowCount() == 0) { ?>
        <tr>
            <td valign="top" colspan="<?php echo H($copyCols); ?>" colspan="2">
                <?php echo $loc->getText("biblioViewNoCopies"); ?>
            </td>
        </tr>
    <?php } else {
        $row_class = "primary";
        while ($copy = $copyQ->fetchCopy($res)) {
            ?>
            <tr><?php //echo $tab; ?>
                <?php if ($tab == "cataloging") { ?>
                    <td valign="top" >
                        <a href="../catalog/biblio_copy_edit_form.php?bibid=<?php echo HURL($copy->getBibid()); ?>&amp;copyid=<?php echo H($copy->getCopyid()); ?>"
                           class="<?php echo H($row_class); ?>"><?php echo $loc->getText("biblioViewTble2Coledit"); ?></a>
                    </td>
                    <td valign="top" >
                        <a href="../catalog/biblio_copy_del_confirm.php?bibid=<?php echo HURL($copy->getBibid()); ?>&amp;copyid=<?php echo HURL($copy->getCopyid()); ?>"
                           class="<?php echo H($row_class); ?>"><?php echo $loc->getText("biblioViewTble2Coldel"); ?></a>
                    </td>
                <?php } ?>
                <td valign="top">
                    <?php echo H($copy->getBarcodeNmbr()); ?>
                </td>
                <td valign="top">
                    <?php echo H($copy->getCopyDesc()); ?> <!--Beschreibung -- vieleicht standort angabe für buch -->
                </td>
                <td valign="top" >
                    <?php //echo H($biblioStatusDm[$copy->getStatusCd()]);

                    /*****NEU************************************ bei ausgeliehen Medien Link auf ausleihe historie zeigen **************************************************************/
                    $lended = listLendedStatus($bibid, H($copy->getCopyid()));
                    //$lended+="";
                    //echo $lended;
                    //Falls nicht eingeloggt, wird jedes exemplar, das nicht verfuegbar ist als ausgeliehen angezeigt
                    if ($lended == "in") {
                        echo "<span class=\"ok\">" . H($biblioStatusDm[$lended]) . "</span>";
                    } elseif ($lended == "out") {
                        $mbrid = lendedExemplares($bibid, H($copy->getCopyid()));
                        $link_to_medium = "<a class=\"error\" href=\"../circ/mbr_view.php?mbrid=$mbrid\" target=\"blank\" >" . H($biblioStatusDm[$lended]) . "</a>";
                        echo $link_to_medium;
                    } elseif ($lended != "in" or $lended != "out") {
                        if ($_SESSION["hasCircAuth"] or $_SESSION["hasCircMbrAuth"]) {
                            echo "<span class=\"attention\">" . H($biblioStatusDm[$lended]) . "</span>";
                        } else {
                            echo "<span class=\"error\">" . H($biblioStatusDm['out']) . "</span>";
                        }
                    } else {
                        echo "<span class=\"error\">Fehler</span>";

                    }
                    /************************************************************************************************************************************************************************/
                    ?>
                </td>
                <td valign="top" >
                    <?php echo H($copy->getStatusBeginDt()); ?>
                </td>
                <td valign="top" >
                    <?php echo H($copy->getDueBackDt()); ?>
                </td>
            </tr>
            <?php

        }
    } ?>
</table>
<!-- TODO understand and clean this mess END ^^ -->


<?php
$tags = explode(";", $biblio->getTags());
?>

<dl class="row">
    <div class="col-md-6">
        <dl class="row">
            <dt class="col-sm-4">ISBN</dt>
            <dd class="col-sm-8"><?php echo H($biblio->getIsbn()) ?></dd>

            <dt class="col-sm-4">Schlagwörter</dt>
            <dd class="col-sm-8">
                <?php
                $str = "";
                foreach ($tags as $tag){
                    $str .= $tag.",";
                }
                if(count($tags) > 0){
                    echo rtrim($str, ",");
                }else{
                    echo "Keine Schlagwörter";
                }
                ?>
            </dd>

            <dt class="col-sm-4">Fertigkeiten</dt>
            <dd class="col-sm-8">
                <?php
                    if($biblio->isSkillGrammar()){ echo " Grammatik"; }
                    if($biblio->isSkillHear()){ echo " Hören"; }
                    if($biblio->isSkillRead()){ echo " Lesen"; }
                    if($biblio->isSkillSpeak()){ echo " Sprechen"; }
                    if($biblio->isSkillWrite()){ echo " Schreiben"; }
                ?>
            </dd>

            <dt class="col-sm-4">Niveau</dt>
            <?php if($biblio->getLanLvlDesc($biblio->getLanToLvl()) != ""){$toLvl = "-".$biblio->getLanLvlDesc($biblio->getLanToLvl());} ?>
            <dd class="col-sm-8"><?php echo H($biblio->getLanLvlDesc($biblio->getLanFromLvl())).H($toLvl); ?></dd>
        </dl>

    </div>
    <div class="col-md-6">
        <dl class="row">
            <dt class="col-sm-4">Verlaginformation</dt>
            <dd class="col-sm-8"><?php echo H($biblio->getPublisher()); ?></dd>

            <dt class="col-sm-4">Erscheinungsort</dt>
            <dd class="col-sm-8"><?php echo H($biblio->getPubLoc()); ?></dd>

            <dt class="col-sm-4">Erscheinungsjahr</dt>
            <dd class="col-sm-8"><?php echo H($biblio->getPubYear()); ?></dd>

            <?php if($biblio->getSummary() != ""){
                echo "<dt class=\"col-sm-4\">Zusammenfassung</dt><dd class=\"col-sm-8\">".H($biblio->getSummary())."</dd>";
            }?>

            <?php
            $type = $biblioQ->getMaterialCategory($biblio);
            if($type == 1){
                echo "<dt class=\"col-sm-4\">Seitenanzahl</dt><dd class=\"col-sm-8\">".H($biblio->getPages())."</dd>";
            }else if($type == 2){
                echo "<dt class=\"col-sm-4\">Spieldauer</dt><dd class=\"col-sm-8\">".H($biblio->getDuration())."</dd>";
            } ?>

        </dl>

    </div>


    <?php require_once("../shared/footer.php"); ?>
