<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 * 
 * Für die Benutzer bearbeitung extra, damit ein wechsel von Student nach Dozent möglich ist
 * 
 */

  require_once("../functions/inputFuncs.php");
  require_once('../classes/DmQuery.php');
  $dmQ = new DmQuery();
  $dmQ->connect();
  $mbrClassifyDm = $dmQ->getAssoc('mbr_classify_dm');
  $customFields = $dmQ->getAssoc('member_fields_dm');
  $dmQ->close();
    
  
  #********************* abschneiden des mbrClassifyDm -> nur noch Student,l Dozent anzeigen erstezt inn $fields durch $output *********************/
  $output[1]="DozentIn";
  $output[2]="StudentIn";
  #echo $mbr->getClassification();
  
  #******************* Onchange event für wechsel Student zu Dozent*******deprecated******************
  
  $val = 'changeClassifikation(this.value,'.$mbr->getClassification().')';
  $onChange = array("onchange" => $val);
  
  if($barcodeNumber == NULL){$barcodeNumber =  $mbr->getBarcodeNmbr();}
  if(isset($_GET['barcodeNmbr'])){
	  echo "<span class=\"error\">Beim Wechsel wurde die Benutzernummer ge&auml;ndert</span>";
	  $barcodeNumber = $_GET['barcodeNmbr'];
  }
  
  
  
  #***************************************************************************************************
  $fields = array(
    "mbrFldsClassify" => inputField('select', 'classification', $mbr->getClassification(), $onChange, $output),
  
    "mbrFldsCardNmbr" => inputField('text', "barcodeNmbr",$barcodeNumber),
    "mbrFldsLastName" => inputField('text', "lastName", $mbr->getLastName()),
    "mbrFldsFirstName" => inputField('text', "firstName", $mbr->getFirstName()),
    #"mbrFldsBirthDate" => input Field('text', "birthday", $mbr->getBirthDate()), /*Funktion muss noch geschrieben werden*/
    "mbrFldsEmail" => inputField('text', "email", $mbr->getEmail()),
    "Mailing Address:" => inputField('textarea', "address", $mbr->getAddress()),
    "mbrFldsHomePhone" => inputField('text', "homePhone", $mbr->getHomePhone()),  
    #"mbrFldsWorkPhone" => inputField('text', "workPhone", $mbr->getWorkPhone()),
    #"mbrFldsMbrShip" => inputField('text', "membershipEnd", $mbr->getMembershipEnd()),
  );

#********************************************************************************************************
#*               ----------------------->Maintanace<---------------------------------
#********************************************************************************************************
#$zeichenkette = '/^([G]{1}|[N]{1})([^0-9][^A-Za-z])*$/';
#$suchmuster = "/^([N]{1}|[G]{1})[0-9]{3}/";
#$matrikel= "/^[0-9]{1,}[^A-Za-z]/";
		
#echo "<br>regexp suchmuster:=> ".$hit = preg_match($suchmuster, $barcodeNumber, $treffer,PREG_OFFSET_CAPTURE);
#echo "<br>regexp matrikel=> ".$hit2 = preg_match($matrikel, $barcodeNumber, $treffer,PREG_OFFSET_CAPTURE);
#echo "<br> G || N=> ".$hit2 = preg_match($zeichenkette, $barcodeNumber, $treffer,PREG_OFFSET_CAPTURE);
  
  
  foreach ($customFields as $name => $title) {
    $fields[$title.':'] = inputField('text', 'custom_'.$name, $mbr->getCustom($name));
  }
?>
<script type="text/javascript">
   var sel = document.getElementById('classification');
   //sel.onchange = function() {
     // var show = document.getElementById('show');
      //show.innerHTML = this.value;
      alert(sel.value);
  // }
</script>
<div id ="show"></div>

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
  <tr>
    <td>
    </td>
     <td>
	  <input type="hidden" id="barcode" name="barcode" value="<?php $_POST['barcode'] ?>">
    </td>
  </tr>

</table>
</form>
