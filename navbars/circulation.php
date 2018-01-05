<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../classes/Localize.php");
$navloc = new Localize(OBIB_LOCALE, "navbars");

$navArray = array(
    new Navobject("searchform", "../circ/index.php", "memberSearch"),
    new Navobject("search", "", "catalogResults"),

    new Navobject("checkin", "../circ/checkin_form.php?reset=Y", "checkIn"),

    new Navobject("view", "../circ/mbr_view.php?mbrid=" . HURL($mbrid), "memberInfo", 1),
    new Navobject("edit", "../circ/mbr_edit_form.php?mbrid=" . HURL($mbrid), "editInfo", 1),
    new Navobject("delete", "../circ/mbr_del_confirm.php?mbrid=" . HURL($mbrid), "catalogDelete", 1),
    new Navobject("account", "../circ/mbr_account.php?mbrid=" . HURL($mbrid), "account", 1),
    new Navobject("hist", "../circ/mbr_history.php?mbrid=" . HURL($mbrid), "checkoutHistory", 1),

    new Navobject("new", "../circ/mbr_new_form.php?reset=Y", "newMember"),
    new Navobject("mod_member_wait", "../mods/circ_mbr_waitlist.php", "circWaitList"),

);

?>
<input type="button" onClick="self.location='../shared/logout.php'" value="<?php echo $navloc->getText("Logout"); ?>"
       class="btn btn-outline-primary"><br/>
<br/>

<?php Navobject::printNav($navArray,$nav,$navloc); ?>

<a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=" . H(addslashes(U($helpPage))); ?>')"><?php echo $navloc->getText("help"); ?></a>
