<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../classes/Localize.php");
$navLoc = new Localize(OBIB_LOCALE,"navbars");

$navArray = array(
    new Navobject("home", "../home/index.php", "homeHomeLink"),
    new Navobject("license", "../home/license.php", "homeLicenseLink"),
);

if (isset($_SESSION["userid"])) {
    $sess_userid = $_SESSION["userid"];
} else {
    $sess_userid = "";
}
if ($sess_userid == "") { ?>
    <input type="button" onClick="self.location='../shared/loginform.php?RET=../home/index.php'" value="<?php echo $navLoc->getText("login");?>" class="btn btn-outline-primary">
<?php } else { ?>
    <input type="button" onClick="self.location='../shared/logout.php'" value="<?php echo $navLoc->getText("logout");?>" class="btn btn-outline-primary">
<?php } ?>
<br /><br />


<?php Navobject::printNav($navArray, $nav, $navLoc); ?>


<a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=".H(addslashes(U($helpPage))); ?>')"><?php echo $navLoc->getText("help");?></a>