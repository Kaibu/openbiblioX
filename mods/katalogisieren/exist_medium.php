<?php
include('../include_mods.php');
?>
<body>
	<html>
		<title>existierende Medien</title>
		<head>
			<link rel="stylesheet" type="text/css" href="../css/style.css">
			<style type="text/css">
				

						body {
						font-family: verdana, helvetica, arial;
						font-size: 14px;
						color: #104871;
						
						}






						table{
							border-collapse:collapse;
							border-style: ridge;
						}
						th {
							font-size:16px;
							border-style: solid;
							border-left-width:1;
							border-right-width:1;
							border-top-width:1;
							border-bottom-width:1;
							border-bottom-color:#000000;
							
						}
						tr {
							
							border-left-width:1;
							border-right-width:1;
						}
						tr.alt1{
							
							border-style:outset;
							border-left-color:#000000;
							border-bottom-color:#000000;	
						}
						tr.alt2{
							border-style:outset;
							border-top-color:#000000;
							border-bottom-color:#000000;
							color:#000000;
						}
						td{
							border-style:outset;
							border-left-width:1px;
							border-right-width:1px;
							border-left-color:#000000;
							border-right-color:#000000;
							border-top-color:#000000;
							border-bottom-color:#000000;
							}
						
			</style>	
		</head>
	
<?php
//echo $HEADER;
$exist_medium=db_show_biblio_value($_GET['medium_nbr']);

echo $exist_medium;
echo $FOOTER;
?>
</html>
</body>
