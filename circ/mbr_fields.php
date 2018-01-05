<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

  require_once("../functions/inputFuncs.php");
  require_once('../classes/DmQuery.php');
  $dmQ = new DmQuery();
  $dmQ->connect();
  $mbrClassifyDm = $dmQ->getAssoc('mbr_classify_dm');
  $customFields = $dmQ->getAssoc('member_fields_dm');
  $dmQ->close();
  //echo $mbr->getClassification();
  //echo $mbr->getClassification();
  
  /********************* abschneiden des mbrClassifyDm -> nur noch Student anzeigen erstezt inn $fields durch $output *********************/
  //$output[1]="DozentIn";
  $output[2]="StudentIn";
  //echo $mbr->getClassification();
  /***************************************************************************************************/
  $fields = array(
    "mbrFldsClassify" => inputField('select', 'classification', $mbr->getClassification(), NULL, $output),
  
    "mbrFldsCardNmbr" => inputField('text', "barcodeNmbr", $mbr->getBarcodeNmbr()),
    "mbrFldsLastName" => inputField('text', "lastName", $mbr->getLastName()),
    "mbrFldsFirstName" => inputField('text', "firstName", $mbr->getFirstName()),
    //"mbrFldsBirthDate" => input Field('text', "birthday", $mbr->getBirthDate()), /*Funktion muss noch geschrieben werden*/
    "mbrFldsEmail" => inputField('text', "email", $mbr->getEmail()),
    "Mailing Address:" => inputField('textarea', "address", $mbr->getAddress()),
    "mbrFldsHomePhone" => inputField('text', "homePhone", $mbr->getHomePhone()),  
    //"mbrFldsWorkPhone" => inputField('text', "workPhone", $mbr->getWorkPhone()),
    //"mbrFldsMbrShip" => inputField('text', "membershipEnd", $mbr->getMembershipEnd()),
  );
  
  foreach ($customFields as $name => $title) {
    $fields[$title.':'] = inputField('text', 'custom_'.$name, $mbr->getCustom($name));
  }
?>

<table class="primary">
  <tr>
    <th colspan="2" valign="top" nowrap="yes" align="left">
      <?php echo H($headerWording);?> <?php echo $loc->getText("mbrFldsHeader"); ?>
    </td>
  </tr>
<?php
  foreach ($fields as $title => $html) {
?>
  <tr>
    <td nowrap="true" class="primary" valign="top">
      <?php echo $loc->getText($title); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo $html; ?>
    </td>
  </tr>
<?php
  }
?>
  <tr>
    <td align="center" colspan="2" class="primary">
      <input type="submit" value="<?php echo $loc->getText("mbrFldsSubmit"); ?>" class="button">
      <input type="button" onClick="self.location='<?php echo H(addslashes($cancelLocation));?>'" value="<?php echo $loc->getText("mbrFldsCancel"); ?>" class="button">
    </td>
  </tr>

</table>
