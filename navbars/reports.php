<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../classes/Localize.php");
$navLoc = new Localize(OBIB_LOCALE, "navbars");

if (isset($rpt)) {
    $rptType = U($rpt->type());
} else {
    $rptType = "";
}

$navArray = array(
    new Navobject("reportlist", "../reports/index.php", "Report List"),
    //new Navobject("mods_time", "../time/track.php", "modTimeTracking"),
    //new Navobject("mods_time_edit", "", "modTimeTrackingEdit",2),
    new Navobject("changelog", "../mods/changelog.php", "Changelog"),

    new Navobject("results", "../reports/run_report.php?type=previous", "Report Results", 1),
    new Navobject("results/list", "../shared/layout.php?rpt=Report&name=list", "Print list", 1),
    new Navobject("reportcriteria", '../reports/report_criteria.php?type=' . $rptType, "Report Criteria", 1),

    new Navobject("mods_tag_cleanup", "../mods/tag_cleanup.php", "modTagCleanup"),

    new Navobject("mods_feedback", "../mods/reports_feedback.php", "opacFeedback"),
    new Navobject("mods_feedback_archiv", "../mods/reports_feedback_archiv.php", "opacFeedbackArchive"),

);


if (isset($_SESSION["userid"])) {
    $sess_userid = $_SESSION["userid"];
} else {
    $sess_userid = "";
}
if ($sess_userid == "") { ?>
    <input type="button"
           onClick="self.location='../shared/loginform.php?RET=../reports/index.php'"
           value="<?php echo $navLoc->getText("login"); ?>" class="btn btn-outline-primary">
<?php } else { ?>
    <input type="button" onClick="self.location='../shared/logout.php'"
           value="<?php echo $navLoc->getText("logout"); ?>" class="btn btn-outline-primary">
<?php } ?>
<br/><br/>

<?php Navobject::printNav($navArray, $nav, $navLoc); ?>

<?php

$helpurl = "javascript:popSecondary('../shared/help.php";
if (isset($helpPage)) {
    $helpurl .= "?page=" . $helpPage;
}
$helpurl .= "')";
Nav::node('help', $navLoc->getText("help"), $helpurl);

Nav::display("$nav");
?>
