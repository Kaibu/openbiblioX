<?php

include('../include_mods.php');
/*verbindung aufbauen zu db openbiblio */
$DBopenbiblio = connect_to_database();
# Style f�r Tabelle
echo '<link rel="stylesheet" type="text/css" href="../errorHandling/style.css">';
# Suche Fehlerhafte Exemplare


#$languageArray = Array(
#$languageTable= 
#$languageTable . = 
#

#*************************************************************#
#finde in als Mediennummer                                    #
#*************************************************************#
$sql ='
	SELECT
		op_biblio.call_nmbr1 as location,
		op_copy.copy_desc,
		op_biblio.call_nmbr2 as signature,
		op_biblio.topic3 as language,
		op_biblio.title,
		op_copy.bibid,
		op_copy.copyid,
		op_copy.create_dt as create_dt
	from
		 openbiblio.biblio_copy as op_copy ,
		 openbiblio.biblio as op_biblio

	where
		op_copy.bibid = op_biblio.bibid
	and
		op_copy.barcode_nmbr ="in"
	order by
		op_biblio.call_nmbr1,
		op_biblio.topic3,
		op_biblio.call_nmbr2,
		op_copy.bibid,
		op_copy.copyid
';

$query = db_query($sql);
$table = "<br>anzahl beeintraechtigeer Datensaetze mit \"in\" als Mediennummer ".mysqli_num_rows($query)."<br>";


#*************************************************************#
#finde leere und                      #
#*************************************************************#
$sql2='SELECT
		op_biblio.call_nmbr1 as location,
		op_copy.copy_desc,
		op_biblio.call_nmbr2 as signature,
		op_biblio.topic3 as language,
		op_biblio.title,
		op_copy.bibid,
		op_copy.copyid,
		op_copy.create_dt as create_dt
	from
		 openbiblio.biblio_copy as op_copy ,
		 openbiblio.biblio as op_biblio

	where
		op_copy.bibid = op_biblio.bibid
	and
		op_copy.barcode_nmbr ="in"
	order by
		op_biblio.call_nmbr1,
		op_biblio.topic3,
		op_biblio.call_nmbr2,
		op_copy.bibid,
		op_copy.copyid
	';
$query2 = db_query($sql2);
$table .= "<br>anzahl beeintraechtigeer Datensaetze mit leerer Mediennummer ".mysqli_num_rows($query2)."<br>";

#*******************************************************************#
#finde dopellte mediennummern                                       #
#*******************************************************************#

	$sql3="
		SELECT 
				op_biblio.call_nmbr1 as location,
				op_copy.copy_desc,
				op_biblio.call_nmbr2 as signature,
				op_biblio.topic3 as language,
				op_biblio.title,
				op_copy.bibid,
				op_copy.copyid,
				op_copy.create_dt as create_dt
		FROM 
			show_double_barcode_nmbr_in_biblio_copy AS error, 
			biblio AS op_biblio, 
			biblio_copy AS op_copy
		WHERE 
			error.occurance > '1'
		AND 
			error.barcode_nmbr <> 'in'
		AND 
			error.barcode_nmbr <> ' '
		AND 
			error.bibid = op_biblio.bibid
		AND 
			error.bibid = op_copy.bibid
		order by
			op_biblio.call_nmbr1,
			op_biblio.topic3,
			op_biblio.call_nmbr2,
			op_copy.bibid,
			op_copy.copyid
	";
	$query3 = db_query($sql3);
	 $amount = mysqli_affected_rows();
	
$table .= "<br>anzahl beeintraechtigeer Datensaetze mit dopellter Mediennummer ".$amount."<br>";



#******************************************************************#
#erste abfrage in tabelle umwandeln                                #
#******************************************************************#


$languageAmount[] =array("location" => "", "language" => "", "amount" => "");
$table .="<table><th>Standort</th><th>Sprache</th><th>Titel</th><th>Signatur</th><th>Beschreibung</th><th>Eexemplarnummer</th>
	  <th>Erstelldatum</th>";
while ($run = mysqli_fetch_assoc($query)){
	$location = getLocationDescription($run['location']);
	$language = getLanguageDescription($run['language']);
	$signature = $run['signature'];
	$copy_desc = $run['copy_desc'];
	$bibid= $run['bibid'];
	$systematicNumber = getSpecificSystematicNumbers($bibid);
	$systematicNumber = $systematicNumber['category'].".".$systematicNumber['category'].".".$systematicNumber['sub_category'];
	#*************************Tabelle anzahl sprache ort erstellen************************************************
	
	if($languageAmount['$location']['$language']['amount'] == ""){
		array("location" =>$location, "language" => $language, "amount" => 0);
	}
	$amount = $languageAmount[$location][$language][0];
	$languageAmount[$location][$language][0]= $amount + 1;
	#************************************************************************************************************
	$table .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr></div>',
	$location ,$language ,$run['title'] ,$systematicNumber.".", $run['copy_desc'] ,$run['copyid'],$run['create_dt']
			   );
}
#*************************************************************#
#**************tabelle anzahl erstellen **********************#
#*************************************************************#
/*$amountTabel =  sprintf('<table><th>Ort</th><th>Sprache</th><th>Anzahl zu bearbeitender Exemplare</th></table>');
foreach($languageAmount['location']as  $location){
	echo "<br>".$location;
}
#echo $amountTabel;*/



#**************************************************************#
#zweite abfrage in tabelle umwandeln                           #
#**************************************************************#

while ($run = mysqli_fetch_assoc($query2)){
	$location = getLocationDescription($run['location']);
	$language = getLanguageDescription($run['language']);
	$signature = $run['signature'];
	$copy_desc = $run['copy_desc'];
	$bibid= $run['bibid'];
	$systematicNumber = getSpecificSystematicNumbers($bibid);
	$systematicNumber = $systematicNumber['category'].".".$systematicNumber['category'].".".$systematicNumber['sub_category'];
	#*************************Tabelle anzahl sprache ort erstellen************************************************
	
	if($languageAmount['$location']['$language']['amount'] == ""){
		array("location" =>$location, "language" => $language, "amount" => 0);
	}
	$amount = $languageAmount[$location][$language][0];
	$languageAmount[$location][$language][0]= $amount + 1;
	#************************************************************************************************************
	$table .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr></div>',
	$location ,$language ,$run['title'] ,$systematicNumber.".", $run['copy_desc'] ,$run['copyid'],$run['create_dt']
			   );
}

#***************************************************************#
# dritte Abfrage in tabelle umwandeln                           #
#***************************************************************#

while ($run = mysqli_fetch_assoc($query3)){
	$location = getLocationDescription($run['location']);
	$language = getLanguageDescription($run['language']);
	$signature = $run['signature'];
	$copy_desc = $run['copy_desc'];
	$bibid= $run['bibid'];
	$systematicNumber = getSpecificSystematicNumbers($bibid);
	$systematicNumber = $systematicNumber['category'].".".$systematicNumber['category'].".".$systematicNumber['sub_category'];
	#*************************Tabelle anzahl sprache ort erstellen************************************************

	if($languageAmount['$location']['$language']['amount'] == ""){
		array("location" =>$location, "language" => $language, "amount" => 0);
	}
	$amount = $languageAmount[$location][$language][0];
	$languageAmount[$location][$language][0]= $amount + 1;
	#************************************************************************************************************
	$table .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr></div>',
	$location ,$language ,$run['title'] ,$systematicNumber.".", $run['copy_desc'] ,$run['copyid'],$run['create_dt']
			   );
}

#*********#
#*Ausgabe*#
#*********#
$table .= $table3;
echo $table;

?>
