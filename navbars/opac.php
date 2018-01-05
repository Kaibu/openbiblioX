<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../classes/Localize.php");
$navLoc = new Localize(OBIB_LOCALE, "navbars");

$navArray = array(
    new Navobject("home", "../opac/index.php", "catalogSearch1"),
    new Navobject("search", "", "catalogResults"),
    new Navobject("view", "", "catalogBibInfo"),
    new Navobject("create", "../mods/opac_create_user.php", "opacRequestAccount"),
    new Navobject("feedback", "../mods/opac_feedback.php", "opacFeedback"),
);

?>

<?php Navobject::printNav($navArray,$nav,$navLoc); ?>

<a class="alt1"
   href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=" . H(addslashes(U($helpPage))); ?>')"><?php echo $navLoc->getText("Help"); ?></a>
