<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

//var_dump($pageErrors[$name]);

require_once("../shared/common.php");
$tab = "circulation";
$nav = "view";
$helpPage = "memberView";
$focus_form_name = "barcodesearch";
$focus_form_field = "barcodeNmbr";

require_once("../functions/inputFuncs.php");
require_once("../functions/formatFuncs.php");
require_once("../shared/logincheck.php");
require_once("../classes/Member.php");
require_once("../classes/MemberQuery.php");
require_once("../classes/BiblioSearchQuery.php");
require_once("../classes/BiblioHold.php");
require_once("../classes/BiblioHoldQuery.php");
require_once("../classes/MemberAccountQuery.php");
require_once("../classes/DmQuery.php");
require_once("../shared/get_form_vars.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

#****************************************************************************
#*  Checking for get vars.  Go back to form if none found.
#****************************************************************************
if (count($_GET) == 0) {
    header("Location: ../circ/index.php");
    exit();
}

#****************************************************************************
#*  Retrieving get var
#****************************************************************************
$mbrid = $_GET["mbrid"];
if (isset($_GET["msg"])) {
    $msg = "<font class=\"error\">" . H($_GET["msg"]) . "</font><br><br>";
} else {
    $msg = "";
}

#****************************************************************************
#*  Loading a few domain tables into associative arrays
#****************************************************************************
$dmQ = new DmQuery();
$mbrClassifyDm = $dmQ->getAssoc("mbr_classify_dm");
$mbrMaxFines = $dmQ->getAssoc("mbr_classify_dm", "max_fines");
$biblioStatusDm = $dmQ->getAssoc("biblio_status_dm");
$materialTypeDm = $dmQ->getAssoc("material_type_dm");
$materialImageFiles = $dmQ->getAssoc("material_type_dm", "image_file");
$memberFieldsDm = $dmQ->getAssoc("member_fields_dm");

#****************************************************************************
#*  Search database for member
#****************************************************************************
$mbrQ = new MemberQuery();
$mbr = $mbrQ->get($mbrid);

#****************************************************************************
#*  Check for outstanding balance due
#****************************************************************************
$acctQ = new MemberAccountQuery();
$balance = $acctQ->getBalance($mbrid);
$balMsg = "";
if ($balance > 0 && $balance >= $mbrMaxFines[$mbr->getClassification()]) {
    $balText = moneyFormat($balance, 2);
    $balMsg = "<font class=\"error\">" . $loc->getText("mbrViewBalMsg", array("bal" => $balText)) . "</font><br><br>";
}

#****************************************************************************
#*  Make sure member does not have expired membership
#****************************************************************************
$overMsg = "";
if ($mbr->getMembershipEnd() != "0000-00-00") {
    if (strtotime($mbr->getMembershipEnd()) <= strtotime("now")) {
        $overMsg = "<font class=\"error\">" . $loc->getText("checkoutEndErr") . "</font><br><br>";
    }
}
#**************************************************************************
#*  Show member information
#**************************************************************************
require_once("../shared/header.php");
//var_dump($_SESSION);
?>

<?php echo $balMsg ?>
<?php echo $overMsg ?>
<?php echo $msg ?>

<div class="row">
    <div class="col-md-4">
        <h3><?php echo $loc->getText("mbrViewHead1"); ?></h3>
        <div class="row">
            <table class="table table-bordered table-success table-hover">
                <tr>
                    <td>
                        <?php echo $loc->getText("mbrViewName"); ?>
                    </td>
                    <td>
                        <?php echo H($mbr->getLastName()); ?>, <?php echo H($mbr->getFirstName()); ?>
                        <span class="pull-right">
                            <?php echo '<a href=../mods/circ_mbr_print_certificate.php?mbrid=' . $mbr->getMbrid() . '><i class="fa fa-print" aria-hidden="true"></i></a>'; ?>

                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $loc->getText("mbrViewAddr"); ?>
                    </td>
                    <td>
                        <?php
                        echo str_replace("\n", "<br />", H($mbr->getAddress()));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $loc->getText("mbrViewCardNmbr"); ?>
                    </td>
                    <td>
                        <?php echo H($mbr->getBarcodeNmbr()); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $loc->getText("mbrViewClassify"); ?>
                    </td>
                    <td>
                        <?php echo H($mbrClassifyDm[$mbr->getClassification()]); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $loc->getText("mbrViewPhone"); ?>
                    </td>
                    <td>
                        <?php
                        if ($mbr->getHomePhone() != "") {
                            echo $loc->getText("mbrViewPhoneHome") . $mbr->getHomePhone() . " ";
                        }
                        if ($mbr->getWorkPhone() != "") {
                            echo $loc->getText("mbrViewPhoneWork") . $mbr->getWorkPhone();
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $loc->getText("mbrViewEmail"); ?>
                    </td>
                    <td>
                        <?php echo H($mbr->getEmail()); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php print $loc->getText("mbrViewMbrShipEnd"); ?>
                    </td>
                    <td>
                        <?php
                        if ($mbr->getMembershipEnd() == "0000-00-00") print $loc->getText("mbrViewMbrShipNoEnd");
                        else echo $mbr->getMembershipEnd();
                        ?>
                    </td>
                </tr>
            </table>
        </div>

    </div>
    <div class="col-md-8">
        <?php
        $dmQ = new DmQuery();
        $dms = $dmQ->getCheckoutStats($mbr->getMbrid());
        ?>
        <h3>Ausleih-Status
            <small> Anzahl aktuell ausgeliehener Medien nach Typ</small>
        </h3>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>
                    <?php echo $loc->getText("mbrViewStatColHdr1"); ?>
                </th>
                <th>
                    <?php echo $loc->getText("mbrViewStatColHdr2"); ?>
                </th>
                <th>
                    Ausleihe-Limit
                </th>
                <th>
                    <?php echo $loc->getText("mbrViewStatColHdr5"); ?>
                </th>
            </tr>
            </thead>
            <?php
            foreach ($dms as $dm) {
                if ($dm->getCount() > 0) {
                    ?>
                    <tr>
                        <td>
                            <?php echo H($dm->getDescription()); ?>
                        </td>
                        <td>
                            <?php echo H($dm->getCount()); ?>
                        </td>
                        <td>
                            <?php echo H($dm->getCheckoutLimit()); ?>
                        </td>
                        <td>
                            <?php echo H($dm->getRenewalLimit()); ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>
</div>

<br><br>

<h3><?php echo $loc->getText("mbrViewHead3"); ?></h3>
<form name="barcodesearch" method="POST" action="../circ/checkout.php" style="border: hidden">
    <input type="hidden" name="mbrid" value="<?php echo H($mbrid); ?>">
    <input type="hidden" name="date_from" id="date_from" value="default"/>

    <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <?php printInputText("barcodeNmbr", 30, 30, $postVars, $pageErrors, '', $postVars['barcodeNmbr'], $flag = "Y"); ?>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-secondary" style="height: 37.7px;" onclick="popSecondaryLarge('../opac/index.php?lookup=Y')"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <input placeholder="Tage" type="number" class="form-control" name="checkout_days" value="1" required>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-secondary" style="height: 37.7px;" data-container="body" data-toggle="popover" data-placement="top" data-content="Anzahl der Tage die das Medium ausgeliehen werden soll. Bei Dozenten Irrelevant">
                      <i class="fa fa-question" aria-hidden="true"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-md-1">
            <input type="submit" value="<?php echo $loc->getText("mbrViewCheckOut"); ?>" class="btn btn-primary">
        </div>
    </div>
</form>


<br><br>

<h3><?php echo $loc->getText("mbrViewHead4"); ?>
    <small>
        <a href="javascript:popSecondary('../circ/mbr_print_checkouts.php?mbrid=<?php echo H(addslashes(U($mbrid))); ?>')">[<?php echo $loc->getText("mbrPrintCheckouts"); ?>
            ]</a>
        <a href="../circ/mbr_renew_all.php?mbrid=<?php echo HURL($mbrid); ?>">[<?php echo $loc->getText("Renew All"); ?>
            ]</a>
    </small>
</h3>
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>
                <?php echo $loc->getText("mbrViewOutHdr1"); ?>
            </th>
            <th>
                <?php echo $loc->getText("mbrViewOutHdr2"); ?>
            </th>
            <th>
                <?php echo $loc->getText("mbrViewOutHdr3"); ?>
            </th>
            <th>
                <?php echo $loc->getText("mbrViewOutHdr4"); ?>
            </th>
            <th>
                <?php echo $loc->getText("mbrViewOutHdr5"); ?>
            </th>
            <th>
                <?php echo $loc->getText("mbrViewOutHdr6"); ?>
            </th>
            <th>
                <?php echo $loc->getText("mbrViewOutHdr8"); ?>
            </th>
            <th>
                <?php echo $loc->getText("mbrViewOutHdr7"); ?>
            </th>
        </tr>
    </thead>
    <?php
    $biblioQ = new BiblioSearchQuery();

    $res = $biblioQ->getMemberBiblio($mbrid);
    if (!$res) {
        displayErrorPage($biblioQ);
    }
    if ($res->num_rows == 0) {
        ?>
        <tr>
            <td>
                <?php echo $loc->getText("mbrViewNoCheckouts"); ?>
            </td>
        </tr>
        <?php
    } else {
        while ($biblio = $biblioQ->fetchRowQ($res)) {
            ?>
            <tr>
                <td>
                    <?php echo H($biblio['status_begin_dt']); ?>
                </td>
                <td>
                    <img src="../images/<?php echo HURL($materialImageFiles[$biblio['material_cd']]); ?>" width="20"
                         height="20" border="0" align="middle"
                         alt="<?php echo H($materialTypeDm[$biblio['material_cd']]); ?>">
                    <?php echo H($materialTypeDm[$biblio['material_cd']]); ?>
                </td>
                <td>
                    <?php echo H($biblio['barcode_nmbr']); ?>
                </td>
                <td>
                    <a href="../shared/biblio_view.php?bibid=<?php echo HURL($biblio['bibid']); ?>"><?php echo H($biblio['title']); ?></a>
                </td>
                <td>
                    <?php echo H($biblio['author']); ?>
                </td>
                <td>
                    <?php echo H($biblio['due_back_dt']); ?>
                </td>
                <td>
                    <a href="../circ/checkout.php?barcodeNmbr=<?php echo HURL($biblio['barcode_nmbr']); ?>&amp;mbrid=<?php echo HURL($mbrid); ?>&amp;renewal"><?php echo $loc->getText("Renew item"); ?></A>
                    <?php
                    if ($biblio['renewal_count'] > 0) { ?>
                        </br>
                        (<?php echo H($biblio['renewal_count']); ?><?php echo $loc->getText("mbrViewOutHdr9"); ?>)
                        <?php
                    } ?>
                </td>
                <td>
                    <?php echo H($biblio['days_late']); ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>

</table>

<br>
<!--****************************************************************************
    *  Hold form
    **************************************************************************** -->
<form name="holdForm" method="POST" action="../circ/place_hold.php">
    <table class="table">
        <tr>
            <th valign="top" nowrap="yes" align="left">
                <?php echo $loc->getText("mbrViewHead5"); ?>
            </th>
        </tr>
        <tr>
            <td nowrap="true" class="primary">
                <?php echo $loc->getText("mbrViewBarcode"); ?>
                <?php printInputText("holdBarcodeNmbr", 18, 18, $postVars, $pageErrors); ?>
                <a href="javascript:popSecondaryLarge('../opac/index.php?lookup=Y')"><?php echo $loc->getText("indexSearch"); ?></a>
                <input type="hidden" name="mbrid" value="<?php echo H($mbrid); ?>">
                <input type="hidden" name="classification" value="<?php echo H($mbr->getClassification()); ?>">
                <input type="submit" value="<?php echo $loc->getText("mbrViewPlaceHold"); ?>" class="button">
            </td>
        </tr>
    </table>
</form>

<h1><?php echo $loc->getText("mbrViewHead6"); ?></h1>
<table class="primary">
    <tr>
        <th valign="top" nowrap="yes" align="left">
            <?php echo $loc->getText("mbrViewHoldHdr1"); ?>
        </th>
        <th valign="top" nowrap="yes" align="left">
            <?php echo $loc->getText("mbrViewHoldHdr2"); ?>
        </th>
        <th valign="top" nowrap="yes" align="left">
            <?php echo $loc->getText("mbrViewHoldHdr3"); ?>
        </th>
        <th valign="top" nowrap="yes" align="left">
            <?php echo $loc->getText("mbrViewHoldHdr4"); ?>
        </th>
        <th valign="top" nowrap="yes" align="left">
            <?php echo $loc->getText("mbrViewHoldHdr5"); ?>
        </th>
        <th valign="top" nowrap="yes" align="left">
            <?php echo $loc->getText("mbrViewHoldHdr6"); ?>
        </th>
        <th valign="top" align="left">
            <?php echo $loc->getText("mbrViewHoldHdr7"); ?>
        </th>
        <th valign="top" align="left">
            <?php echo $loc->getText("mbrViewHoldHdr8"); ?>
        </th>
    </tr>
    <?php
    #****************************************************************************
    #*  Search database for BiblioHold data
    #****************************************************************************
    $holdQ = new BiblioHoldQuery();
    $holdRes = $holdQ->queryByMbrid($mbrid);
    if (!$holdRes) {
        displayErrorPage($holdQ);
    }
    if ($holdQ->getRowCount() == 0) {
        ?>
        <tr>
            <td class="primary" align="center" colspan="8">
                <?php echo $loc->getText("mbrViewNoHolds"); ?>
            </td>
        </tr>
        <?php
    } else {
        while ($hold = $holdQ->fetchRow($holdRes)) {
            ?>
            <tr>
                <td class="primary" valign="top" nowrap="yes">
                    <a href="../shared/hold_del_confirm.php?bibid=<?php echo HURL($hold->getBibid()); ?>&amp;copyid=<?php echo HURL($hold->getCopyid()); ?>&amp;holdid=<?php echo HURL($hold->getHoldid()); ?>&amp;mbrid=<?php echo HURL($mbrid); ?>"><?php echo $loc->getText("mbrViewDel"); ?></a>
                </td>
                <td class="primary" valign="top" nowrap="yes">
                    <?php echo H($hold->getHoldBeginDt()); ?>
                </td>
                <td class="primary" valign="top">
                    <img src="../images/<?php echo HURL($materialImageFiles[$hold->getMaterialCd()]); ?>" width="20"
                         height="20" border="0" align="middle"
                         alt="<?php echo H($materialTypeDm[$hold->getMaterialCd()]); ?>">
                    <?php echo H($materialTypeDm[$hold->getMaterialCd()]); ?>
                </td>
                <td class="primary" valign="top">
                    <?php echo H($hold->getBarcodeNmbr()); ?>
                </td>
                <td class="primary" valign="top">
                    <a href="../shared/biblio_view.php?bibid=<?php echo HURL($hold->getBibid()); ?>"><?php echo H($hold->getTitle()); ?></a>
                </td>
                <td class="primary" valign="top">
                    <?php echo H($hold->getAuthor()); ?>
                </td>
                <td class="primary" valign="top">
                    <?php echo H($biblioStatusDm[$hold->getStatusCd()]); ?>
                </td>
                <td class="primary" valign="top">
                    <?php echo H($hold->getDueBackDt()); ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</table>

<script>
    $(document).ready(function () {
        var input = $("input[name*='barcodeNmbr']");
        input.attr('placeholder','Barcode');
        input.focus();
    });

    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>

<?php require_once("../shared/footer.php"); ?>
