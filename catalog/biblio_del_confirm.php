<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");
$tab = "cataloging";
$nav = "delete";
require_once("../shared/logincheck.php");
require_once("../classes/BiblioQuery.php");
require_once("../classes/BiblioCopyQuery.php");
require_once("../classes/BiblioHoldQuery.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$bibid = $_GET["bibid"];
$biblioQ = new BiblioQuery();

$biblio = $biblioQ->doQuery($bibid);

$title = $biblio->getTitle();


#****************************************************************************
#*  Check for copies and holds
#****************************************************************************
$copyQ = new BiblioCopyQuery();
$copyQ->execSelect($bibid);
$copyCount = $copyQ->getRowCount();

$holdQ = new BiblioHoldQuery();
$holdQ->queryByBibid($bibid);
$holdCount = $holdQ->getRowCount();

#**************************************************************************
#*  Show confirm page
#**************************************************************************
require_once("../shared/header.php");

if (($copyCount > 0) or ($holdCount > 0)) {
    ?>
    <center>
        <?php echo $loc->getText("biblioDelConfirmWarn", array("copyCount" => $copyCount, "holdCount" => $holdCount)); ?>
        <br><br>
        <a href="../shared/biblio_view.php?bibid=<?php echo HURL($bibid); ?>"><?php echo $loc->getText("biblioDelConfirmReturn"); ?></a>
    </center>

    <?php
} else {
    ?>
    <center>
        <form name="delbiblioform" method="POST" action="../shared/biblio_view.php?bibid=<?php echo HURL($bibid); ?>">
            <?php echo $loc->getText("biblioDelConfirmMsg", array("title" => $title)); ?>
            <br><br>
            <input type="button"
                   onClick="self.location='../catalog/biblio_del.php?bibid=<?php echo HURL($bibid); ?>&amp;title=<?php echo HURL($title); ?>'"
                   value="<?php echo $loc->getText("catalogDelete"); ?>" class="button">
            <input type="submit" value="<?php echo $loc->getText("catalogCancel"); ?>" class="button">
        </form>
    </center>
    <?php
}
include("../shared/footer.php");
?>
