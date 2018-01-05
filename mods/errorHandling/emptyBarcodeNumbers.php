

<?php
include('../include_mods.php');

$DBopenbiblio = connect_to_database();
# Suchanfrage Fehlerhafte Exemplare



$sql ='
	SELECT 
		op_copy.copy_desc,
		op_biblio.title,
		op_copy.bibid,
		op_copy.copyid,
		op_copy.create_dt as create_dt,
		op_biblio.collection_cd as systematic,
		op_biblio.call_nmbr2 as additionalSignature,
		op_biblio.topic3 as language,
		op_biblio.call_nmbr1 as location,
		op_biblio.call_nmbr3 as unknown
	from
		 openbiblio.biblio_copy as op_copy ,
		 openbiblio.biblio as op_biblio
		 				
	where
		op_copy.bibid = op_biblio.bibid				
	and 
		op_copy.barcode_nmbr ="in"
	and 
		op_copy.create_dt < "2013-07-07 00:00:00"
';

# Zuordnen der Exemplare ohne eindeutigen Primary Key	
		
$query = db_query($sql);
echo "<br>anzahl beeinträchtigeer Datensätze ".mysqli_num_rows($query)."<br>";
$manualRecovery = 0;
$count = 1;
while ($run = mysqli_fetch_assoc($query)){
	$title = mysqli_escape_string($run['title']);
	$create_dt = mysqli_escape_string($run['create_dt']);
	$copyid = mysqli_escape_string($run['copyid']);
	$language = mysqli_escape_string($run['language']);
	$systematic = mysqli_escape_string($run['systematic']);
	$addSignature = mysqli_escape_string($run['additionalSignature']);
	$copy_desc = mysqli_escape_string($run['copy_desc']);
	$bibid = $run['bibid'];
	$location = $run['location'];
	$unknownField = $run['unknown'];
	
	
	
# etwas variablezusammenbau der select anfrage
	
	if($copy_desc!=""){
		$copyStatement ='and 
		db_copy.copy_desc ="'.$copy_desc.'"';
	}else{$copyStatement="";}
	
	if($addSignature!=""){
		$addSignatureStatement='and 
				db_biblio.call_nmbr2 ="'.$addSignature.'"';
	}else{$addSignatureStatement="";}
	
	if($unknownField!=""){
		$unknownFieldStatement ='and db_biblio.call_nmbr3 ="'.$unknownField.'"';
	}else{$unknownFieldStatement="";}	
	
	
	$sql2 =sprintf('
		Select 
			db_copy.bibid,
			db_copy.copyid,
			db_copy.barcode_nmbr,
			db_copy.copy_desc
			
		from 
			openbiblio_backup_20130707.temp_biblio_copy as db_copy,
			openbiblio_backup_20130707.temp_biblio as db_biblio
		where 
			db_biblio.bibid = db_copy.bibid
		and
			db_copy.create_dt = "%s"	
		and 
			db_copy.copyid = "%s"
		and
			db_biblio.title ="%s"
		and 
			db_biblio.collection_cd ="%s"
		%s
		and 
			db_biblio.topic3 ="%s"
		%s
		
		',
		  $create_dt, $copyid,$title,$systematic, $addSignatureStatement,$language, $copyStatement
	);
			
	
	
		$sqlBetween =sprintf('
			SELECT  
				db_biblio.bibid
			from 
				 openbiblio_backup_20130707.temp_biblio as db_biblio
			where 
				db_biblio.collection_cd ="%s"
			and 
				db_biblio.topic3 ="%s"
			%s		
			%s
			and db_biblio.call_nmbr1 ="%s"	
				',$systematic,  $language, $addSignatureStatement,$unknownFieldStatement,$location
			);
			
		$queryBetween = db_query($sqlBetween);
		echo "<br>".$sqlBetween."<br>";
		echo "<br>------------BETWEEN affected Rows = ".mysqli_num_rows($queryBetween)."-------------------------<br>";
		if(mysqli_num_rows($queryBetween)== 1){
		$runBetween = mysqli_fetch_assoc($queryBetween);
		
		
		$oldBibidStatement ='and db_copy.bibid ="'.$runBetween['bibid'].'"';
		}
		else{ $oldBibidStatement ="";}
		
		$sql21=sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 
				db_copy.bibid = db_biblio.bibid
			and
				db_copy.create_dt = "%s"	
			and 
				db_copy.copyid = "%s"
			%s
			',
			$create_dt,$copyid,$oldBibidStatement
		);
				
		$sql3 =sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 
				db_copy.bibid = db_biblio.bibid
			and			
				db_copy.create_dt = "%s"	
			and 
				db_copy.copyid = "%s"
			and
				db_biblio.title ="%s"
			and 
				db_biblio.collection_cd ="%s"
			%s
			
			and 
				db_biblio.topic3 ="%s"
			%s
			%s
			',
			  $create_dt, $copyid, $title,$systematic, $addSignatureStatement,$language,$copyStatement,$oldBibidStatement
		);
		
		$sql4 =sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 	 
				db_copy.bibid = db_biblio.bibid
			and
				db_copy.create_dt = "%s"	
			and 
				db_copy.copyid = "%s"
			and
				db_biblio.title ="%s"
			and 
				db_biblio.collection_cd ="%s"
			and 
				db_biblio.topic3 ="%s"
			%s
			%s
			',
			  $create_dt, $copyid, $title,$systematic,$language,$copyStatement,$addSignatureStatement,$oldBibidStatement
		);
		
		$sql5=sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 	 
				db_copy.bibid = db_biblio.bibid
			and
				db_copy.create_dt = "%s"	
			and
				db_copy.copy_desc ="%s"
			and
				db_copy.copyid ="%s"
			%s
			%s
			%s
			',
			  $create_dt,$copy_desc,$copyid,$copyStatement,$addSignatureStatement,$oldBibidStatement
		);
		
		$sql6 =sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 	
				db_copy.bibid = db_biblio.bibid
			and
				db_copy.create_dt = "%s"	
			and
				db_copy.copyid ="%s"
			and 
				db_biblio.collection_cd ="%s"
			and 
				db_biblio.topic3 ="%s"
			%s
			%s
			%s
			',
			  $create_dt,$copyid,$systematic,$language,$copyStatement,$addSignatureStatement,$oldBibidStatement
		);
		
		$sql7 =sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 	
				db_copy.bibid = db_biblio.bibid
			and
				db_copy.create_dt = "%s"	
			and
				db_copy.copyid ="%s"
			and 
				db_biblio.topic3 ="%s"
			%s
			%s
			%s
			
			',
			  $create_dt,$copyid,$language,$copyStatement,$addSignatureStatement,$oldBibidStatement
		);
		
		$sql8 =sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 	
				db_copy.bibid = db_biblio.bibid
			and
				db_copy.create_dt = "%s"	
			and
				db_copy.copyid ="%s"
			and 
				db_biblio.topic3 ="%s"
			and 
				db_biblio.collection_cd ="%s"
			%s
			%s
			%s
			
			',
			  $create_dt,$copyid,$language, $systematic,$copyStatement,$addSignatureStatement,$oldBibidStatement
		);
		
		$sql9 =sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 	
				db_copy.bibid = db_biblio.bibid
			and
				db_copy.create_dt = "%s"	
			and
				db_biblio.title ="%s"
			and
				db_copy.copyid="%s"
			%s
			',
			  $create_dt,$title,$copyid,$oldBibidStatement
		);
		
		$sql10 =sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy,
				openbiblio_backup_20130707.temp_biblio as db_biblio
			where 	
				db_copy.bibid = db_biblio.bibid
			and
				db_copy.create_dt = "%s"	
			and
				db_copy.copyid="%s"
			%s
			%s
			%s
			',
			  $create_dt,$copyid,$copyStatement,$unknownFieldStatement,$oldBibidStatement
		);
		
		$sql11 =sprintf('
			Select 
				db_copy.bibid,
				db_copy.copyid,
				db_copy.barcode_nmbr,
				db_copy.copy_desc
				
			from 
				openbiblio_backup_20130707.temp_biblio_copy as db_copy
			where
				db_copy.copyid="%s" 	
				%s
						
			',
			  $copyid,$oldBibidStatement
		);
		
	###########Abfragen auswertung ########################
	echo "<br>".$sql2."<br>";
	$query2 = db_query($sql2);
	if (!query2) {
		die('Ungültige Anfrage: ' . mysqli_error());
	}
	$affectedRows2 = mysqli_num_rows($query2);
	
	# ein Exemplar gefunden --> updaten		
	if($affectedRows2 == 1){
		echo "<br> gefundene Exemplare ".$affectedRows2." in Backup zu Medium ".$run['title']." -> Beschreibung ".html_entity_decode($run['copy_desc'])."<br>";	
		$run2 = mysqli_fetch_assoc($query2);
		$barcode_nmbr=$run2['barcode_nmbr'];
		echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run2)."<br></div>";
		####################################UPDATE BIBLIO COPY#######################################
		echo "<br>UPDATE";
		$sqlUpdate = sprintf('Update 
									openbiblio.biblio_copy 
								SET
									barcode_nmbr ="%s"
								where 
									bibid = "%s"
								and 
									copyid = "%s" 							   
		', 
		$barcode_nmbr,$bibid, $copyid
		);
		$result = db_query($sqlUpdate);
		echo "<br><div class=\"error\" style=\"color:green\">es wurden ".mysqli_affected_rows()."   Exemplare geupdated <br></div>";
	}
	else{
		
		#########Weiter Abfragen nacheinander Abarbeiten##########
		if($affectedRows2 != 1){	
			
			echo "<div class=\"ok\" style=\"color:red\">Kein Ergebnis bei<br>Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run2)."<br></div>";	
			echo "<br> neuer Versuch";
			echo "<br>".$sql3."<br>";
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 21
			$affectedRows = 1;
			$query21 = db_query($sql21);
			echo "<br>".$sql21."<br>";
			echo "<br>result in rows run 21 = ".mysqli_num_rows($query21);
			if(mysqli_num_rows($query21)== 1){
				$run21 = mysqli_fetch_assoc($query21);
				$barcode_nmbr=$run21['barcode_nmbr'];
				echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
				echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run21)."<br></div>";
				$affectedRows = 0;
			}
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 3
			if($affectedRows){
				$query3 = db_query($sql3);
				echo "<br>".$sql3."<br>";
				echo "<br>result in rows run 3 = ".mysqli_num_rows($query3);
				if(mysqli_num_rows($query3)== 1){
					$run3 = mysqli_fetch_assoc($query3);
					$barcode_nmbr=$run3['barcode_nmbr'];
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run3)."<br></div>";
					$affectedRows = 0;
				}
			}
			
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 4
			if($affectedRows){
				echo "<br> neuer Versuch";
				echo "<br>".$sql4."<br>";
				$query4 = db_query($sql4);
				$barcode_nmbr=$run4['barcode_nmbr'];
				echo "<br>result in rows run 4 = ".mysqli_num_rows($query4);
				if(mysqli_num_rows($query4)==1 and mysqli_num_rows($query21)!=1 and mysqli_num_rows($query3)!= 1){
					$run4 = mysqli_fetch_assoc($query4);
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run4)."<br></div>";
				$affectedRows = 0;	
				}
			}
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 5
			if($affectedRows){
				echo "<br> neuer Versuch";
				echo "<br>".$sql5."<br>";
				$query5 = db_query($sql5);
				$barcode_nmbr=$run5['barcode_nmbr'];
				echo "<br>result in rows run 5 = ".mysqli_num_rows($query5);
				if(mysqli_num_rows($query4)!=1 and mysqli_num_rows($query21)!=1 and mysqli_num_rows($query3)!= 1 and mysqli_num_rows($query5)==1){
					$run5 = mysqli_fetch_assoc($query5);
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run5)."<br></div>";
					$affectedRows = 0;	
				}
			}
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 6
			if($affectedRows){
				echo "<br> neuer Versuch";
				echo "<br>".$sql6."<br>";
				$query6 = db_query($sql6);
				$barcode_nmbr=$run6['barcode_nmbr'];
				echo "<br>result in rows run 6 = ".mysqli_num_rows($query6);
				if(mysqli_num_rows($query4)!=1 and mysqli_num_rows($query21)!=1 and mysqli_num_rows($query3)!=1 and mysqli_num_rows($query5)!=1 and mysqli_num_rows($query6)==1){
					$run6 = mysqli_fetch_assoc($query6);
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run6)."<br></div>";
					$affectedRows = 0;				
				}
			}
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 7
			if($affectedRows){
				echo "<br> neuer Versuch";
				echo "<br>".$sql7."<br>";
				$query7 = db_query($sql7);
				$barcode_nmbr=$run7['barcode_nmbr'];
				echo "<br>result in rows run 7 = ".mysqli_num_rows($query7);
				if(mysqli_num_rows($query4)!=1 and mysqli_num_rows($query3)!= 1 and mysqli_num_rows($query21)!=1 and mysqli_num_rows($query5)!=1 and mysqli_num_rows($query6) !=1 and mysqli_num_rows($query7) ==1){
					$run7 = mysqli_fetch_assoc($query7);
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run7)."<br></div>";
					$affectedRows = 0;	
				}
			}
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 8
			if($affectedRows){
				echo "<br> neuer Versuch";
				echo "<br>".$sql8."<br>";
				$query8 = db_query($sql8);
				$barcode_nmbr=$run8['barcode_nmbr'];
				echo "<br>result in rows run 8 = ".mysqli_num_rows($query8);
				if(mysqli_num_rows($query4)!=1 and mysqli_num_rows($query3)!= 1 and mysqli_num_rows($query21)!=1 and mysqli_num_rows($query5)!=1 and mysqli_num_rows($query6) !=1 and mysqli_num_rows($query7) !=1
					and mysqli_num_rows($query8) ==1){
					$run8 = mysqli_fetch_assoc($query8);
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run8)."<br></div>";
					$affectedRows = 0;
				}
			}
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 9
			if($affectedRows){
				echo "<br> neuer Versuch";
				echo "<br>".$sql9."<br>";
				$query9 = db_query($sql9);
				$barcode_nmbr=$run9['barcode_nmbr'];
				echo "<br>result in rows run 9 = ".mysqli_num_rows($query9);
				if(mysqli_num_rows($query4)!=1 and mysqli_num_rows($query3)!= 1 and mysqli_num_rows($query21)!=1 and mysqli_num_rows($query5)!=1 and mysqli_num_rows($query6) !=1 and mysqli_num_rows($query7) !=1
					and mysqli_num_rows($query8) !=1 and mysqli_num_rows($query9) ==1){
					$run9 = mysqli_fetch_assoc($query9);
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run9)."<br></div>";
					$affectedRows = 0;
				}
			}
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 10
			if($affectedRows){
				echo "<br> neuer Versuch";
				echo "<br>".$sql10."<br>";
				$query10 = db_query( $sql10);
				$barcode_nmbr=$run10['barcode_nmbr'];
				echo "<br>result in rows run 10 = ".mysqli_num_rows($query10);
				if(mysqli_num_rows($query4)!=1 and mysqli_num_rows($query3)!= 1 and mysqli_num_rows($query21)!=1 and mysqli_num_rows($query5)!=1 and mysqli_num_rows($query6) !=1 and mysqli_num_rows($query7) !=1
					and mysqli_num_rows($query8) !=1 and mysqli_num_rows($query9) !=1 and mysqli_num_rows($query10) ==1){
					$run9 = mysqli_fetch_assoc($query10);
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run10)."<br></div>";
					$affectedRows = 0;
				}
			}
			
			####################################################
			# weniger restriktive Suchkritereien Abfrage / Run 11
			if($affectedRows){
				echo "<br> neuer Versuch";
				echo "<br>".$sql11."<br>";
				$query11 = db_query( $sql11);
				$barcode_nmbr=$run11['barcode_nmbr'];
				echo "<br>result in rows run 11 = ".mysqli_num_rows($query11);
				if(mysqli_num_rows($query4)!=1 and mysqli_num_rows($query3)!= 1 and mysqli_num_rows($query21)!=1 and mysqli_num_rows($query5)!=1 and mysqli_num_rows($query6) !=1 and mysqli_num_rows($query7) !=1
					and mysqli_num_rows($query8) !=1 and mysqli_num_rows($query9) !=1 and mysqli_num_rows($query10) !=1 and mysqli_num_rows($query11) ==1){
					$run9 = mysqli_fetch_assoc($query11);
					echo "<br><div class=\"ok\" style=\"color:green\">Erfolgreich =><br></div>";
					echo "<br><div class=\"ok\" style=\"color:green\">Bibid =".$bibid." copyid= ".$copyid." barcode = ".$barcode_nmbr."<br>".print_r($run11)."<br></div>";
					$affectedRows = 0;
				}
			}
			
			####################################UPDATE BIBLIO COPY#######################################
			if($affectedRows == 0){
				echo "<br>UPDATE";
				$sqlUpdate = sprintf('Update 
											openbiblio.biblio_copy 
										SET
											barcode_nmbr ="%s"
										where 
											bibid = "%s"
										and 
											copyid = "%s" 							   
				', 
				$barcode_nmbr,$bibid, $copyid
				);
				$result = db_query($sqlUpdate);
				echo "<br><div class=\"error\" style=\"color:green\">es wurden ".mysqli_affected_rows()."   Exemplare geupdated <br></div>";
			}
			
			########### End Zaehlung############################
			
			if(mysqli_num_rows($query2)==0 and mysqli_num_rows($query21)==0 and mysqli_num_rows($query3)== 0 and mysqli_num_rows($quer4)==0 and mysqli_num_rows($query5)== 0 and mysqli_num_rows($query6)==0 and
			   mysqli_num_rows($query7)==0 and mysqli_num_rows($query8)== 0 and mysqli_num_rows($query9)== 0 and mysqli_num_rows($query10)== 0 and mysqli_num_rows($query11)== 0){
				#merke anzahl nicht rückzusetzbarer Exemplare
				$manualRecovery =  $manualRecovery + 1;
			}
				
		}
		else{
			# Ergebnis Fehlerhaft mehrere Exemplare gefunden
			echo "<br><div class=\"error\" style=\"color:red\">es wurden ".$affectedRows2."  verschiedene Exemplare gefunden
				  <br></div>";
				  //var_dump($run2);
			$manualRecovery =  $manualRecovery + 1;
			if($affectedRows2 >= 1){
				while ($run2 = mysqli_fetch_assoc($query2)){
					echo "<br><div class=\"error\" style=\"color:red\">".print_r($run2)."
					</div>";
				
				}
			}
		}
		
	}
		
	echo"<br>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";
	$count = $count + 1;
	$run2 ="";
	$barcode_nmbr ="";
}		
echo  "<br>".$manualRecovery." manuell zu bearbeitenden Exemplare"; 
		
?>
