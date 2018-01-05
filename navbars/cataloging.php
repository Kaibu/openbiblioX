<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../classes/Localize.php");
$navLoc = new Localize(OBIB_LOCALE, "navbars");

$navArray = array(
    new Navobject("searchform", "../catalog/index.php", "catalogSearch1"),
    new Navobject("search", "", "catalogResults"),

    new Navobject("view", "../shared/biblio_view.php?bibid=" . HURL($bibid), "catalogBibInfo", 1),
    new Navobject("newcopy", "../catalog/biblio_copy_new_form.php?bibid=" . HURL($bibid), "catalogCopyNew", 1),
    new Navobject("editcopy", "", "catalogCopyEdit", 1),
    new Navobject("edit", "../catalog/biblio_edit.php?bibid=" . HURL($bibid), "catalogBibEdit", 1),
    new Navobject("newlike", "../catalog/biblio_new.php?bibid=" . HURL($bibid), "catalogBibNewLike", 1),
    new Navobject("holds", "../catalog/biblio_hold_list.php?bibid=" . HURL($bibid), "catalogHolds", 1),
    new Navobject("history", "../catalog/biblio_history.php?bibid=" . HURL($bibid), "History", 1),
    new Navobject("delete", "../catalog/biblio_del_confirm.php?bibid=" . HURL($bibid), "catalogDelete", 1),

    new Navobject("new", "../catalog/biblio_new.php", "catalogBibNew"),
    //new Navobject("jsonUpload", "../mods/import.php", "media_import"),
    //new Navobject("mod_keywordliste", "../mods/catalog_keywordlist.php", "key_words"),
);
?>
<input type="button" onClick="self.location='../shared/logout.php'" value="<?php echo $navLoc->getText("logout"); ?>"
       class="btn btn-outline-primary"><br/>
<br/>

<?php Navobject::printNav($navArray, $nav, $navLoc); ?>

<a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=" . H(addslashes(U($helpPage))); ?>')"><?php echo $navLoc->getText("help"); ?></a>
