<?php
/**
* autor:
*
* date: 2013-03-12
*
**/
ob_start(); 
require_once("../shared/common.php");
require_once("pdfPrint/dompdf/dompdf_config.inc.php");
session_cache_limiter(null);

$tab = "circulation";
$nav = "mod_member_wait";
$helpPage = "circulation";
$focus_form_name = "barcodesearch";
$focus_form_field = "searchText";

require_once("../shared/logincheck.php");
require_once("../shared/header.php");
require_once("../classes/Localize.php");
require_once("include_mods.php");

$loc = new Localize(OBIB_LOCALE,$tab);

if (isset($_REQUEST['msg'])) {
echo '<font class="error">'.H($_REQUEST['msg']).'</font>';
}
?>
<!-- NEU -->
<!-- HEADLINE: STUDENTENWARTELISTE -->
<h1><img src="../images/circ.png" border="0" width="30" height="30" align="top"> <?php echo "Studenten-Warteliste" ?></h1>
<!-- CONTENT: -->
<form method="POST" action="" name="register" style="border: hidden">
<?php

//
// Löschen von Membern
//
if(isset($_POST['del']))
{
  if(isset($_POST['create_dt']))
  {
    $sql = sprintf("DELETE FROM member_waiting WHERE register_date = '%s'",
    $_POST['create_dt']);
    db_query($sql);
  }
  else
  {
    $error_msg = true;
  }
}

//
// Eintragen des fertig bearbeiteteten Users und löschen aus der Warteliste
//
if(isset($_POST['register']) && isset($_POST['create_dt']) &&
    isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['mat']) && 
    isset($_POST['addr']) && isset($_POST['email']))
{
    $studentId = mysqli_fetch_array(db_query("SELECT code FROM mbr_classify_dm WHERE description LIKE '%Student%'"));
    $class = $studentId[0];
    $matnum = $_POST['mat'];
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $address = $_POST['addr'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    
    $sql = sprintf("
            INSERT INTO 
            member(barcode_nmbr, create_dt, last_change_dt, 
            last_change_userid, last_name, first_name, address, 
            home_phone, work_phone, email, classification, mbrshipend) 
            VALUES('%s', NOW(), NOW(), 1, '%s', '%s', '%s', '%s', NULL,
            '%s', %s, '0000-00-00')",
            $matnum, $lname, $fname, $address,$tel, $email, $class);
    db_query($sql);
    
    $sql = sprintf("DELETE FROM member_waiting WHERE register_date = '%s'",
                    $_POST['create_dt']);
    db_query($sql);
}

//register_date 	last_name 	first_name 	degree 	mat_nr 	address 	birthday 	email
$res = db_query("SELECT * FROM member_waiting");
print("<table class='table'>");
while($row = mysqli_fetch_assoc($res))
{
    if(isset($_GET['mid']) && (int)$_GET['mid'] == (int)$row['mat_nr'])
    {       
		if($row['tel_nmbr']=='0'){$ctel="";}else{ $ctel =  $row['tel_nmbr'];}
        printf("<tr><td>
                <div style='border: 1px solid black; padding: 0 25px;'><table>
                    <tr><td>Registriert am: %s</td></tr>
                    <tr><td><label>Name</label><input type='text' value='%s' name='lname'></td></tr>
                    <tr><td><label>Vorname</label><input type='text' value='%s' name='fname'></td></tr>
                    <tr><td><label>Mat.-Nr.</label><input type='text' value='%s' name='mat'></td></tr>
                    <tr><td><label>Adresse</label><br><textarea rows='5' cols='30' name='addr'>%s</textarea></td></tr>
                    <tr><td><label>Email-Adresse</label><input type='text' value='%s' name='email'></td></tr>
                    <tr><td><label>Telefonnummer</label><input type='text' value='%s' name='tel'></td></tr>
                    <tr><td>
                        <input type='hidden' name='create_dt' value='%s' />
                        <input type='submit' name='register' value='Eintragen' />
                        <input type='submit' name='del' value='L&ouml;schen' />
                        <input type='submit' name='print' value='drucken' />
                    </td></tr>
                </div></table>
                </td></tr>",
            H($row['register_date']),
            H($row['last_name']), H($row['first_name']), H($row['mat_nr']),
            H($row['address']), H($row['email']), H($ctel), H($row['register_date']));
        
    }
    
    else
    {
        printf("<tr><td>%s</td><td>%s</td><td>%s, %s</td><td><a href='?mid=%s'>anzeigen</a></td></tr>",
            H($row['register_date']), H($row['mat_nr']),
            H($row['last_name']), H($row['first_name']), H($row['mat_nr']), H($row['tel']));
    }
    
}
print("</table>");

//
// Drucken des Mitgliedes als PDF
//

if(Isset($_POST['print'])){

    $regDate = $_POST['register_date'];
    $matnum = $_POST['mat'];
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];

    ob_end_clean();

    $html =
        '<html><body>'.
        '<head><style type=\"text/css\">'.
        'h1 {
	text-align: center;
	font-family: "Verdana";
	font-size: 26;
	color: #104871;
	font-weight:bold;
   }
   h2{
	color: #104871;
	text-align: left;
	font-family: "Verdana";
	font-size: 20;
	font-weight:bold;
	font-variant: small-caps;
   }
   h3{
	font-family: "Verdana";
	font-size: 18;
	font-weight:bold;
	text-decoration: underline;
	color: black;
	font-style: italic;
   }
   th{
	color:#104871;
   }
   td{
	color: black;
	font-size: 14;
   }
   '.
        '</style>'.
        '</head>'.
        '<img align="left" src="'.H(OBIB_LIBRARY_IMAGE_URL).'" border="0"/>'.
        '<table><br><br>'.
        '<h1>Titel der Organisation</h1>'.
        '<br><br><br><br><br>'.
        '<h3>Mediothek Mitgliedschaft</h3>'.
        '<tr><td>Vorname: </td><td>'.H($fname).'</td></tr>'.
        '<tr><td>Nachname: </td><td>'.H($lname).'</td></tr>'.
        '<tr><td>Mat.-Nr. / Benutzernr.: </td><td>'.H($matnum).'</td></tr>'.
        '<tr><td>Email-Adresse: </td><td>'.H($email).'</td></tr>'.
        '<tr><td>Telefonnummer: </td><td>'.H($tel).'</td></tr></table>'.
        '<br><br><br><br><br><br><br>'.
        '<table><tr><td colspan="2">Hiermit erkläre ich, dass ich die Benutzungsordnung der Mediothek zur Kenntnis genommen'.
        ' habe und die darin bekannten Benutzungsbedingungen anerkenne. Mit der Speicherung von Daten für den Ausleihbetrieb bin ich einverstanden.</td></tr></table>'.
        '<br><br><br>'.

        '<table><tr><td>Datum: </td><td>Unterschrift</td></tr>'.
        '<tr><td>_____________</td><td>______________________</td></tr>'.


        '</table>'.
        '</body></html>';


    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();
    $dompdf->stream(H($matnum)."_".H($lname).".pdf");
}
else{
    ob_end_flush();
}


?>
</form>
<!-- ENDE NEU -->
<?php include("../shared/footer.php"); ?>
