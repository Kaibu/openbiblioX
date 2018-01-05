<?php

error_reporting(E_ALL); // Fehler anzeigen
    //error_reporting(0); // keine Fehler anzeigen 
    ini_set("display_errors", "on");
    ini_set("display_startip_errors", "on");


include("../../include_mods.php");
require_once("dompdf_config.inc.php");

$html =
  '<html><body>'.
  '<p>Put your html here, or generate it with your favourite '.
  'templating system.</p>'.
  '</body></html>';

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("sample.pdf");
// PDF speichern
//$pdf = $dompdf->output();
//file_put_contents("file.pdf", $pdf);






?>
