<?php
/**
* autor:
*
* date:
*
**/
#include('pdfPrint/dompdf/dompdf_config.inc.php');

/*
 * bindet die unten aufgeführten mods ein
 */

include_once('lib/error_handler.php');



include_once('lib/db_functions.php');
include_once('lib/functions.php');
// Pdf Creator Classes //
//include_once('pdfPrint/dompdf/dompdf_config.inc.php');

// Licens Memebership  //
//include_once('pdfPrint/circ_mbr_print_certificate.php');

$cwp = getcwd();
$HEADER = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">

<head>
	<title>Opac</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/css/style.php\">
	<meta http-equiv=\"content-type\" content=\"text/html\">
    <meta charset=\"UTF-8\">
    <script src="lib/javascript.js"></script>
</head>

<body>
';

$FOOTER = '
	</body>
</html>
';

?>
