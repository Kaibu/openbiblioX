<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../classes/Localize.php");
$navLoc = new Localize(OBIB_LOCALE, "navbars");

$navArray = array(
    new Navobject("summary", "../admin/index.php", "adminSummary"),
    new Navobject("staff", "../admin/staff_list.php", "adminStaff"),
    //new Navobject("time_report", "../time/report.php", "modTimeTracking"),
    new Navobject("settings", "../admin/settings_edit_form.php?reset=Y", "adminSettings"),
    new Navobject("classifications", "../admin/mbr_classify_list.php", "Member Types"),
    new Navobject("materials", "../admin/materials_list.php", "adminMaterialTypes"),
    new Navobject("collections", "../admin/collections_list.php", "adminCollections"),
    new Navobject("checkout_privs", "../admin/checkout_privs_list.php", "Checkout Privs"),
    new Navobject("themes", "../admin/theme_list.php", "adminThemes"),
);

?>
<input type="button" onClick="self.location='../shared/logout.php'" value="<?php echo $navLoc->getText("logout"); ?>" class="btn btn-outline-primary">
<br/>
<br/>

<?php Navobject::printNav($navArray,$nav,$navLoc); ?>

<a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=" . H(addslashes(U($helpPage))); ?>')"><?php echo $navLoc->getText("help"); ?></a>