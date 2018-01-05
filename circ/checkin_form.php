<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");
$tab = "circulation";
$nav = "checkin";
$helpPage = "checkin";
$focus_form_name = "barcodesearch";
$focus_form_field = "barcodeNmbr";

require_once("../functions/inputFuncs.php");
require_once("../functions/formatFuncs.php");
require_once("../shared/logincheck.php");
require_once("../classes/BiblioSearchQuery.php");
require_once("../classes/MemberAccountQuery.php");
require_once("../classes/MemberQuery.php");
require_once("../shared/get_form_vars.php");
require_once("../shared/header.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

?>


<?php
if (isset($_GET['barcode'])) {
    if (isset($_GET['mbrid']) and $_GET['mbrid']) {
        $memberQ = new MemberQuery;
        $mbr = $memberQ->get($_GET['mbrid']);
        echo '<p>';
        echo $loc->getText("Checked in %barcode% for ", array(
            'barcode' => H($_GET['barcode']),
        ));
        echo '<a href="../circ/mbr_view.php?mbrid=' . HURL($mbr->getMbrid()) . '&amp;reset=Y">';
        echo $loc->getText("%fname% %lname%", array(
            'fname' => $mbr->getFirstName(),
            'lname' => $mbr->getLastName(),
        ));
        echo '</a>.';
        echo '</p>';
        if (isset($_GET['late']) and $_GET['late']) {
            echo '<p><font class="error">' . $loc->getText("mbrViewOutHdr7") . ': ' . H($_GET['late']) . '</font></p>';
        }
        $acctQ = new MemberAccountQuery();
        $balance = $acctQ->getBalance($mbr->getMbrid());
        $balMsg = "";
        if ($balance > 0) {
            $balText = moneyFormat($balance, 2);
            $balMsg = "<font class=\"error\">" . $loc->getText("mbrViewBalMsg", array("bal" => $balText)) . "</font><br><br>";
        }
        echo $balMsg;
    } else {
        echo '<p>' . $loc->getText("Checked in %barcode%.", array('barcode' => H($_GET['barcode']))) . '</p>';
    }
}
?>

<form name="barcodesearch" method="POST" action="../circ/shelving_cart.php" style="border: hidden">

        <h2><?php echo $loc->getText("checkinFormHdr1"); ?></h2>
        <div class="input-group">
            <?php printInputText("barcodeNmbr", 18, 18, $postVars, $pageErrors); ?>
            <span class="input-group-addon" ><a href="javascript:popSecondaryLarge('../opac/index.php?lookup=Y')"><i class="fa fa-search" aria-hidden="true"></i></a></span>
            <span class="input-group-btn" ><button class="btn btn-secondary" style="height: 37.7px" type="submit"><i class="fa fa-check" aria-hidden="true"></i></button></span>
        </div>

</form>

<div class="alert alert-success" role="alert">
    <h5>INFO</h5>
    Die Eingangsablage wurde entfernt, wenn ihr ein Medium zurück gebt, wird dieses nun direkt wieder verfügbar!
</div>

<?php
if (isset($_GET["msg"])) {
    echo "<font class=\"error\">";
    echo H($_GET["msg"]) . "</font>";
}
?>

<script>
    $(document).ready(function () {
        $("input[name*='barcodeNmbr']").focus();
    });
</script>

<?php require_once("../shared/footer.php"); ?>
