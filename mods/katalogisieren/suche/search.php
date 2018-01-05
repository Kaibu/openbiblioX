<?php
	$get = $_GET["searchInput"];
	$suchbool = false;
	
	if(empty($get)){
		exit;
	}
	
	if(file_exists("xmldata.xml")){
		$xml = simplexml_load_file("xmldata.xml");
		
		foreach($xml as $personen){
			echo "<ul>\n";
			
			foreach($personen as $person){
				
				$pattern = "/^".$get."/i";
				
				if(preg_match($pattern, $person->Vorname) || preg_match($pattern, $person->Nachname)){
					$name = sprintf("%s %s", $person->Vorname, $person->Nachname);
					$link = sprintf("<a href=\"mailto:%1\$s\">%1\$s</a>", $person->Email);
					printf("<li>%s (%s)</li>\n", $name, $link);
					$suchbool = true;
				}		
						
			}
			
			if(!$suchbool){
					print("<li>Leider keinen Eintrag gefunden!</li>");			
			}
			
			echo "</ul>\n";
		}	
		
	}
?>