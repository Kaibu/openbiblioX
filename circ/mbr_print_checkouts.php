<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

/*
  * Zum Drucken der ausgeliehenen Medien. Durch hermfuckeln mit UTF 8 decode konnte ich die Probleme mit den Umlauten
  * entfernen. Interessanterweise musste UTF 8 decode an anderer Stelle entfernt werden, um solche Probleme zu lösen...
  */

require_once("../shared/common.php");
$tab = "circulation";
$nav = "view";
$focus_form_name = "barcodesearch";
$focus_form_field = "barcodeNmbr";

require_once("../functions/inputFuncs.php");
require_once("../shared/logincheck.php");
require_once("../classes/Member.php");
require_once("../classes/MemberQuery.php");
require_once("../classes/BiblioSearchQuery.php");
require_once("../classes/DmQuery.php");
require_once("../shared/get_form_vars.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

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
$materialTypeDm = $dmQ->getAssoc("material_type_dm");
$materialImageFiles = $dmQ->getAssoc("material_type_dm", "image_file");

#****************************************************************************
#*  Search database for member
#****************************************************************************
$mbrQ = new MemberQuery();
$mbr = $mbrQ->get($mbrid);

#**************************************************************************
#*  Show member checkouts
#**************************************************************************
?>
<html>
<head>
    <style type="text/css">
        <?php include("../css/style.php");?>
    </style>
    <meta name="description" content="OpenBiblio Library Automation System">
    <title>Checkouts for <?php echo utf8_decode(H($mbr->getFirstLastName())); ?></title>

</head>
<body bgcolor="<?php echo H(OBIB_PRIMARY_BG); ?>" topmargin="5" bottommargin="5" leftmargin="5" rightmargin="5" marginheight="5" marginwidth="5" onLoad="self.focus();self.print();">

<font class="primary">
    <table class="primary" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="100%" class="noborder" valign="top">
                <h1><?php echo utf8_decode($loc->getText("mbrPrintCheckoutsTitle", array("mbrName" => $mbr->getFirstLastName()))); ?></h1>
            </td>
            <td class="noborder" valign="top" nowrap="yes"><font class="small"><a href="javascript:window.close()"><?php echo utf8_decode($loc->getText("mbrPrintCloseWindow")); ?></a></font>&nbsp;&nbsp;
</font></td>
</tr>
</table>
<br>
<table class="primary" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="noborder" valign="top"><?php echo utf8_decode($loc->getText("mbrPrintCheckoutsHdr1")); ?></td>
        <td width="100%" class="noborder" valign="top"><?php echo H(date("F j, Y, g:i a")); ?></td>
    </tr>
    <tr>
        <td class="noborder" valign="top" nowrap><?php echo utf8_decode($loc->getText("mbrPrintCheckoutsHdr2")); ?></td>
        <td class="noborder" valign="top"><?php echo utf8_decode(H($mbr->getFirstLastName())); ?></td>
    </tr>
    <tr>
        <td class="noborder" valign="top" nowrap><?php echo utf8_decode($loc->getText("mbrPrintCheckoutsHdr3")); ?></td>
        <td class="noborder" valign="top"><?php echo H($mbr->getBarcodeNmbr()); ?></td>
    </tr>
    <tr>
        <td class="noborder" valign="top" nowrap><?php echo utf8_decode($loc->getText("mbrPrintCheckoutsHdr4")); ?></td>
        <td class="noborder" valign="top"><?php echo H($mbrClassifyDm[$mbr->getClassification()]); ?></td>
    </tr>
</table>
<br>
<table class="primary">
    <tr>
        <td class="primary" valign="top" nowrap="yes" align="left">
            <?php echo utf8_decode($loc->getText("mbrViewOutHdr1")); ?>
        </th>
        <td class="primary" valign="top" nowrap="yes" align="left">
            <?php echo utf8_decode($loc->getText("mbrViewOutHdr2")); ?>
        </th>
        <td class="primary" valign="top" nowrap="yes" align="left">
            <?php echo utf8_decode($loc->getText("mbrViewOutHdr4")); ?>
        </th>

        <td class="primary" valign="top" nowrap="yes" align="left"> <!-- Medinenummer-->
            <?php echo utf8_decode($loc->getText("mbrViewOutHdr3")); ?>
        </th>

        <td class="primary" valign="top" nowrap="yes" align="left">
            <?php echo utf8_decode($loc->getText("mbrViewOutHdr5")); ?>
        </th>
        <td class="primary" valign="top" nowrap="yes" align="left">
            <?php echo utf8_decode($loc->getText("mbrViewOutHdr6")); ?>
        </th>
        <td class="primary" valign="top" align="left">
            <?php echo utf8_decode($loc->getText("mbrViewOutHdr7")); ?>
        </th>
    </tr>

    <?php
    #****************************************************************************
    #*  Search database for BiblioStatus data
    #****************************************************************************
    $biblioQ = new BiblioSearchQuery();

    $res = $biblioQ->getMemberBiblio($mbrid);
    if (!$res) {
        displayErrorPage($biblioQ);
    }
    if ($res->num_rows == 0) {
        ?>
        <tr>
            <td class="primary" align="center" colspan="6">
                <?php echo $loc->getText("mbrViewNoCheckouts"); ?>
            </td>
        </tr>
        <?php

    } else {
        while ($biblio = $biblioQ->fetchRowQ($res)) {
            ?>
            <tr>
                <td class="primary" valign="top" nowrap>
                    <?php echo H($biblio['status_begin_dt']); ?>
                </td>
                <td class="primary" valign="top">
                    <img src="../images/<?php echo HURL($materialImageFiles[$biblio['material_cd']]); ?>" width="20"height="20" border="0" align="middle"
                         alt="<?php echo H($materialTypeDm[$biblio['material_cd']]); ?>">
                    <?php echo H($materialTypeDm[$biblio['material_cd']]); ?>
                </td>
                <td class="primary" valign="top">
                    <?php echo H($biblio['title']); ?>
                </td>
                <td class="primary" valign="top">
                    <?php echo H($biblio['barcode_nmbr']); ?>
                </td>
                <td class="primary" valign="top">
                    <?php echo H($biblio['author']); ?>
                </td>
                <td class="primary" valign="top" nowrap="yes">
                    <?php echo H($biblio['due_back_dt']); ?>
                </td>
                <td class="primary" valign="top">
                    <?php echo H($biblio['days_late']); ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>

</table>
</body>

