<?php

require_once('../mods/include_mods.php');

/*********************Dozentenformular********************************/
  $error_last  ="";
  $error_first ="";
  $error_mail  ="";
  $error_mail  ="";
  $error_locate="";
  $eintragen = $_POST['eintragen'];
  
  
  
  if(isset($_POST['location_2'])){$location_2 = $_POST['location_2'] ;}else {$location_2="";}
  if(isset($_POST['lastName_2']) && ($eintragen !="")){$lastName_2 = $_POST['lastName_2']; $error_last="";}
  if(($eintragen !="") && ($_POST['lastName_2'] =="")){ $lastName_2 ="";$error_last = "<span class=\"error\">Bitte Nachnamen eintragen</span>";}
  
  if(isset($_POST['firstName_2'])){$firstName_2 = $_POST['firstName_2']; $error_first="";} 
  if(($eintragen !="") && ($_POST['firstName_2'] =="")){ $firstName_2=""; $error_first = "<span class=\"error\">Bitte Vornamen eintragen</span>";}
  
  if(isset($_POST['email_2'])){$email_2 = $_POST['email_2'] ;} 
  if(($eintragen !="") && ($_POST['email_2']=="")){ $firstName_2="";$error_mail = "<span class=\"error\">Bitte Email eintragen</span>";}
  
  if(isset($_POST['classifik_2'])){$classifik_2 = $_POST['classifik_2']; }
  if($_POST['address_2'] !=""){$address_2 = $_POST['address_2'];}else{$address_2 = "NULL";}
  
  /*echo "<br>location_2 =".$location_2;
  echo "<br>last Name_2 =".$lastName_2;
  echo "<br>firstName_2 =".$firstName_2;
  echo "<br>email_2 =".$email_2;
  echo "<br>classifik_2 =".$classifik_2;
  echo "<br>address =".$address_2;*/
  
 /*********************create BarcodeNmbr********************************/
 if($_POST['location_2'] != "0"){
	 $sql="SELECT barcode_nmbr FROM member WHERE barcode_nmbr LIKE '%$location_2%'";
	 $new=db_query($sql);
	 $i = 0;
	 while($barcode=mysqli_fetch_assoc($new)){
		 
		$barcode_dozent[$i] = substr($barcode['barcode_nmbr'], 1);
		$i = $i + 1;
	 }
	 $barNmbr = max($barcode_dozent)+1;
	 $barcode_2 = $location_2.$barNmbr;
	 $error_locate="";
	 
 }
 else{
	$error_locate = "<span class=\"error\">Bitte Standort eintragen</span>";
 }
 
 /**********************INsert Dozent into members******************************/
 
 
  If ($lastName_2 && $firstName_2 && $email_2 && $barcode_2){
	    $create_dt = date('YmdHis', time());
		$change_dt = date('YmdHis', time());
		$Session_id = $_SESSION['userid'];
		$sql="INSERT INTO member (barcode_nmbr,create_dt,last_change_dt,last_change_userid,last_name,first_name, address,email,classification) 
			  VALUES ('$barcode_2','$create_dt','$change_dt','$Session_id','$lastName_2','$firstName_2','$address_2','$email_2','$classifik_2')";
		$new=db_query( $sql);
		echo "<br><span class=\"ok\">Benutzer erfolgreich angelegt</span>";		
		echo "<br><span class=\"ok\">neue Benutzernummer = ".$barcode_2."</span>";
  }





?>
<form name=new_dozentform method="POST" action="../circ/mbr_new_form.php">
<table class="primary">
 <tr>
   <td  nowrap="true" class="primary" valign="top">Klassifikation:</td>
   <td  nowrap="true" class="primary" valign="top"><select name="classifik_2">
    <option value="1" >DozentIn</option>
    </select>
   </td>
 </tr>
 
 
 <tr>
   <td  nowrap="true" class="primary" valign="top">Standort: *<?php echo $error_locate ;?></td>
   <td  valign="top" class="primary">
     <select id="location"  name="location_2" >
	   <option value="0">--bitte ausw&auml;hlen--</option>
       <option value="N">Standort 1</option>
       <option value="G">Standort 2</option>
     </select>
   </td>  
 </tr>
 <tr>
   <td  nowrap="true" class="primary" valign="top">Nachname: *<?php echo $error_last; ?></td>
   <td  valign="top" class="primary"><input id="last_name" type="text" name="lastName_2" value=""></input></td>  
 </tr>
 <tr>
   <td  nowrap="true" class="primary" valign="top">Vorname: *<?php echo $error_first; ?></td>
   <td  valign="top" class="primary"> <input id="first_name" type="text" name="firstName_2" value=""></input></td>  
 </tr>
 <tr>
   <td  nowrap="true" class="primary" valign="top">Email: *<?php echo $error_mail; ?></td>
   <td  valign="top" class="primary"> <input id="email" type="text" name="email_2" value=""></input></td>  
 </tr>
 <tr>
   <td  nowrap="true" class="primary" valign="top">Postanschrift:</td>
   <td  valign="top" class="primary"> <textarea id="address" type="textarea" name="address_2" value=""></textarea></td>  
 </tr>
 <tr>
    <td align="center" colspan="2" class="primary">
      <input type="submit" value="Eintragen" name="eintragen" class="button">
      <input type="reset" value="Abbrechen" class="button">
    </td>
  </tr>
</table>
</form>
