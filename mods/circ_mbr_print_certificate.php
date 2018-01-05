<?php
/*
 * Zum drucken der Endbenutzervereinbarung. Momentan funktioniert die Generierung, auch mit Umlauten.
 * War zwischendurch allerdings auch plötzlich kaputt, häufig keine Ahnung, wieso. Ich vermute, dass es am Zusammenspiel
 * mit der Datenbank liegt(encoding, collation blabla)
 */

ob_start();
  require_once("../shared/common.php");

  include_once('pdfPrint/dompdf/dompdf_config.inc.php');
  require_once("../mods/include_mods.php");


  $tab = "circulation";
  $nav = "mod_member_wait";
  $helpPage = "circulation";
  $focus_form_name = "barcodesearch";
  $focus_form_field = "searchText";

  require_once("../shared/logincheck.php");

/************************************************************************/
/*********** print PDF Licences Membership  *****************************/
/************************************************************************/
// define variables //
  $member_Query="";
  $member="";
  $regDate="";
  $matnum="";
  $lname="";
  $fname="";
  $address="";
  $email="";
  $tel="";
  $mbrid="";

echo "<br>memberid = ".$_GET['mbrid'];

/********** if $_GET['mbrid'] set => fill pdf *******************/
if(isset($_GET['mbrid'])){$mbrid=$_GET['mbrid'];}
if($mbrid){

  $member_Query = getMemberWaiting($mbrid);

  $member = mysqli_fetch_assoc($member_Query);


    $regDate = $member['register_date'];
    $matnum = $member['mat_nr'];

    $lname = $member['last_name'];
    $fname = $member['first_name'];
    $address = $member['address'];
    $email = $member['email'];
    $tel = $member['tel_nmbr'];

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
    '<img align="left" src="../images/logo.png" border="0"/>'.
    '<table><br><br>'.
      '<h1>Titel der Organisation</h1>'.
      '<br><br><br><br><br>'.
      '<h3>Mediothek Mitgliedschaft</h3>'.
      '<tr><td>Vorname: </td><td>'.$fname.'</td></tr>'.
      '<tr><td>Nachname: </td><td>'.$lname.'</td></tr>'.
      '<tr><td>Mat.-Nr. / Benutzernr.: </td><td>'.$matnum.'</td></tr>'.
          '<tr><td>Email-Adresse: </td><td>'.$email.'</td></tr>'.
          '<tr><td>Telefonnummer: </td><td>'.$tel.'</td></tr></table>'.
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
        $dompdf->stream($matnum."_".$lname.".pdf");
}
else{
        ob_end_flush();
}





?>
