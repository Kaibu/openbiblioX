<?php
	//Functions 
	function addPerson(&$personen, $vorname, $name, $email){
		$person = $personen->appendChild(new DOMElement("Person"));
		$person->appendChild(new DOMElement("Vorname", $vorname));
		//$person->appendChild(new DOMElement("Nachname", $name));
		//$person->appendChild(new DOMElement("Email", $email));
	}
	
	
	//---------
	header("content-Type: text/xml");
	
	$dom = new DOMDocument("1.0", "UTF-8");
	$mitarbeiter = $dom->appendChild(new DOMElement("Mitarbeiter"));
	$personen = $mitarbeiter->appendChild(new DOMElement("Personen"));
	addPerson($personen, "Max", "Mustermann", "helge@video2brain.de");
	
	$dom->save("xmldata.xml");
	echo $dom->saveXML();	
	
?>
