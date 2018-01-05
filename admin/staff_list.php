<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");
$tab = "admin";
$nav = "staff";

require_once("../classes/Staff.php");
require_once("../classes/StaffQuery.php");
require_once("../functions/errorFuncs.php");

require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

require_once("../shared/logincheck.php");

require_once("../shared/header.php");

$staffQ = new StaffQuery();
$res = $staffQ->execSelect();

?>
<a href="../admin/staff_new_form.php?reset=Y"><?php echo $loc->getText("adminStaff_list_formHeader"); ?></a><br><br>
<h1><?php echo $loc->getText("adminStaff_list_Columnheader"); ?></h1>
<table class="table table-hover table-bordered">
    <tr>

        <th rowspan="2" valign="top" nowrap="yes">
            <?php echo $loc->getText("adminStaff_edit_formLastname"); ?>
        </th>
        <th rowspan="2" valign="top" nowrap="yes">
            <?php echo $loc->getText("adminStaff_edit_formFirstname"); ?>
        </th>
        <th rowspan="2" valign="top">
            <?php echo $loc->getText("adminStaff_edit_formLogin"); ?>
        </th>
        <th colspan="5">
            <?php echo $loc->getText("adminStaff_edit_formAuth"); ?>
        </th>
        <th rowspan="2" valign="top">
            <?php echo $loc->getText("adminStaff_edit_formSuspended"); ?>
        </th>
        <th colspan="3" rowspan="2" valign="top">
            <?php echo $loc->getText("adminStaff_list_Function"); ?>
        </th>
    </tr>
    <tr>
        <th>
            <?php echo $loc->getText("adminStaff_edit_formCirc"); ?>
        </th>
        <th>
            <?php echo $loc->getText("adminStaff_edit_formUpdatemember"); ?>
        </th>
        <th>
            <?php echo $loc->getText("adminStaff_edit_formCatalog"); ?>
        </th>
        <th>
            <?php echo $loc->getText("adminStaff_edit_formAdmin"); ?>
        </th>
        <th>
            <?php echo $loc->getText("adminStaff_edit_formReports"); ?>
        </th>
    </tr>
    <?php
    $row_class = "";
    while ($staff = $staffQ->fetchStaff($res)) {
        ?>
        <tr>

            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php echo "<strong>".H($staff->getLastName())."</strong>"; ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php echo "<strong>".H($staff->getFirstName())."</strong>"; ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php echo H($staff->getUsername()); ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php if ($staff->hasCircAuth()) {
                    echo $loc->getText("adminStaff_Yes");
                } else {
                    echo $loc->getText("adminStaff_No");
                } ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php if ($staff->hasCircMbrAuth()) {
                    echo $loc->getText("adminStaff_Yes");
                } else {
                    echo $loc->getText("adminStaff_No");
                } ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php if ($staff->hasCatalogAuth()) {
                    echo $loc->getText("adminStaff_Yes");
                } else {
                    echo $loc->getText("adminStaff_No");
                } ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php if ($staff->hasAdminAuth()) {
                    echo $loc->getText("adminStaff_Yes");
                } else {
                    echo $loc->getText("adminStaff_No");
                } ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php if ($staff->hasReportsAuth()) {
                    echo $loc->getText("adminStaff_Yes");
                } else {
                    echo $loc->getText("adminStaff_No");
                } ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <?php if ($staff->isSuspended()) {
                    echo $loc->getText("adminStaff_Yes");
                } else {
                    echo $loc->getText("adminStaff_No");
                } ?>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <a href="../admin/staff_edit_form.php?UID=<?php echo HURL($staff->getUserid()); ?>"
                   class="<?php echo H($row_class); ?>"><?php echo $loc->getText("adminStaff_list_Edit"); ?></a>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <a href="../admin/staff_pwd_reset_form.php?UID=<?php echo HURL($staff->getUserid()); ?>"
                   class="<?php echo H($row_class); ?>"><?php echo $loc->getText("adminStaff_list_Pwd"); ?></a>
            </td>
            <td valign="top" class="<?php echo H($row_class); ?>">
                <a href="../admin/staff_del_confirm.php?UID=<?php echo HURL($staff->getUserid()); ?>&amp;LAST=<?php echo HURL($staff->getLastName()); ?>&amp;FIRST=<?php echo HURL($staff->getFirstName()); ?>"
                   class="<?php echo H($row_class); ?>"><?php echo $loc->getText("adminStaff_list_Del"); ?></a>
            </td>
        </tr>
    <?php } ?>
</table>
<?php include("../shared/footer.php"); ?>
