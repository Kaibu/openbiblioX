<?php

/*
 * Formular zum Anlegen eines neuen Eintrags. Wurde von mir modifiziert, damit bei möglichst vielen Werten Standardeinträge
 * möglich sind, weil sonst einfach irgendwas eingetippt wird. Nun werden die Felder einfach leer gelassen, wenn man nichts einträgt
 * und das Niveau ist standard auf A1-C2 gesetzt.
 * Bei einigen Datenbankabfragen und Vorgängen habe ich keine Ahnung, was sie sollen (es werden 10000 Variablen zig mal mit irgendwelchen
 * leeren Strings belegt, wozu etc.? Habe versucht, so wenig wie möglich zu verändern, damit nicht wieder Fehler entstehen, deren Ursprung nicht
 * nachvollziehbar ist.
 */

?>

<!--header-->
<table class="primary">
	<tr>
		<td align="center" valign="top" class="primary">
		<img src="../images/catalog.png" border="0" width="30" height="30"></td>
		<td class="primary"><h1>Katalogisierung</h1></td>
	</tr>
</table>	


<script type="text/javascript" src="../mods/lib/javascript.js"></script>
<script> showOptional();</script>

<?php

    /*zurücksetzen der input felder*/
    if(isset($_POST['cancel'])){
		delete_POST();
    }
	//TODO: Analysieren und checken was das soll
    /*Variablen initialisieren*/
    //$auswahl8="";
    //$select8="";
    $skills_hear="";
    $skills_speak="";
    $skills_write="";
    $skills_read="";
    $skills_grammar="";

    /*
     * Wenn Post vorhanden werden Daten aus Post verwendet, sonst leer
     */
	//material ==  Medien
	if(isset($_POST['auswahl']) && ($_POST['material']!="0")){$auswahl=utf8_decode($_POST['auswahl']);}else{$auswahl="";}
	//genre == Zuordnung Genre
	if(isset($_POST['auswahl1'])&& ($_POST['genre']!="0")){$auswahl1=utf8_decode($_POST['auswahl1']);}else{$auswahl1="";}
	//location == Zuordnung Standort
	if(isset($_POST['auswahl2'])&& ($_POST['location']!="0")){$auswahl2=utf8_decode($_POST['auswahl2']);}else{$auswahl2="";}
    //skills == Zuordnung Fertigkeiten
	if(isset($_POST['auswahl3'])&& ($_POST['skills']!="0")){$auswahl3=utf8_decode($_POST['auswahl3']);}else{$auswahl3="";}
	//language == Zuordnung Sprache
	if(isset($_POST['auswahl4'])&& ($_POST['language']!="0")){$auswahl4=utf8_decode($_POST['auswahl4']);}else{$auswahl4="";}
    //fromNiv == Zuordnung Niveau von
	if(isset($_POST['auswahl5'])&& ($_POST['fromNiv']!="0")){$auswahl5=utf8_decode($_POST['auswahl5']);}else{$auswahl5="";}
    //toNiv == Zurodnung Niveau bis
	if(isset($_POST['auswahl6'])&& ($_POST['toNiv']!="0")){$auswahl6=utf8_decode($_POST['auswahl6']);}else{$auswahl6="";}
    //Seitenzahl, Spieldauer == Zuordnung Sorte
	if(isset($_POST['auswahl7'])&& ($_POST['physics']!="0")) {$auswahl7=utf8_decode($_POST['auswahl7']);}else{$auswahl7="";}
	/*
	 * Slogans rausgenommen
    if(isset($_POST['anzahl'])){
        if(isset($_POST['auswahl8'])&& ($_POST['anzahl']!="6") && ($_POST['anzahl']>0)){$auswahl8=$_POST['anzahl']; $select8="selected";}
        elseif (($_POST['anzahl']=="6")){$select8="selected";$auswahl8=$_POST['auswahl8']; } //anzahl der Sloganfelder//
	}
	*/

    //Wenn Post vorhanden, dann Post, sonst leer. Komische Encoding Probleme mit utf8_decode gefixt
	if(isset($_POST['physics'])){$physics=$_POST['physics'];}else{$physics="";}
    if(isset($_POST['material'])){$material=$_POST['material'];}else{$material="";}
	isset($_POST['genre']) ? $genre = $_POST['genre'] : $genre = "";
	if(isset($_POST['author'])){$author=utf8_decode($_POST['author']);}else{$author="";}
	if(isset($_POST['location'])){$location=$_POST['location'];}else{$location="";}
    //Sollte wohl mal eingebaut werden
	//if(isset($_POST['regal'])){$regal=utf8_decode($_POST['regal']);}else{$regal="";} //ist noch nicht in der Abfrage enthalten
	if(isset($_POST['additional_sig'])){$additional_sig=$_POST['additional_sig'];}else{$additional_sig="";}
	if(isset($_POST['isbn'])){$isbn=$_POST['isbn'];}else{$isbn="";}
	if(isset($_POST['title'])){$title=utf8_decode($_POST['title']);}else{$title="";}
	if(isset($_POST['publisher'])){$publisher=utf8_decode($_POST['publisher']);}else{$publisher="";}

    //Was sollte das hier?
	/*FERTIGKEITEN*/
	//if(isset($_POST['skills'])){$skills=$_POST['skills'];}else{$skills="";}

    //Wenn Post vorhanden, dann Post, sonst leer.
	if(isset($_POST['skills_hear'])){$skills_hear=$_POST['skills_hear'];}else{$skills_hear="";}
	if(isset($_POST['skills_speak'])){$skills_speak=$_POST['skills_speak'];}else{$skills_speak="";}
	if(isset($_POST['skills_read'])){$skills_read=$_POST['skills_read'];}else{$skills_read="";}
	if(isset($_POST['skills_grammar'])){$skills_grammar=$_POST['skills_grammar'];}else{$skills_grammar="";}
	if(isset($_POST['skills_write'])){$skills_write=$_POST['skills_write'];}else{$skills_write="";}
	
	if(isset($_POST['language'])){$language=$_POST['language'];}else{$language="";}
	if(isset($_POST['fromNiv'])){$fromNiv=$_POST['fromNiv'];}else{$fromNiv="";}
	if(isset($_POST['toNiv'])){$toNiv=$_POST['toNiv'];}else{$toNiv="";}
	if(isset($_POST['hyperlink'])){$hyperlink=$_POST['hyperlink'];}else{$hyperlink="";}
	
	
	
	$niveau="";
    //Schlagwörter rausgenommen
	//$slogan="";
	$exist_medium="";
	$medium_nbr="";
	$medium_nbr_check="";
	$link_to_medium="";
	$description_ph="";
	$checked="";
	$checked_secure_copy="";
	$bibid_check="";
	$skills ="";
	$checked_skills_grammar ="";
	$checked_skills_write ="";
	$checked_skills_hear ="";
	$checked_skills_speak ="";
	$checked_skills_read ="";
	$signature_check="";

    //Wenn Post vorhanden, dann Post, sonst leer. Komische Encoding Probleme mit utf8_decode gefixt
	if(isset($_POST['extra'])){$extra=utf8_decode($_POST['extra']);}else{$extra="";}
	if(isset($_POST['isbn'])){$isbn=$_POST['isbn'];}else{$isbn="";}
	if(isset($_POST['medium_nbr'])){$medium_nbr=$_POST['medium_nbr'];}else{$medium_nbr="";}
	if(isset($_POST['opacflg'])){$opac_flg=$_POST['opacflg'];}else{$opac_flg="";}
	if(isset($_POST['secure_copy'])){$secure_copy=$_POST['secure_copy'];}else{$secure_copy="";}
	if(isset($_POST['down_title'])){$down_title=utf8_decode($_POST['down_title']);}else{$down_title="";}
	
	if(isset($_POST['ph_description'])){$ph_description=$_POST['ph_description'];}else{$ph_description="";}
	if(isset($_POST['public_lc'])){$public_lc=utf8_decode($_POST['public_lc']);}else{$public_lc="";}
	if(isset($_POST['pb_year'])){$pb_year=$_POST['pb_year'];}else{$pb_year="";}
	
    
    /*Fertigkeiten*/
    if($skills_hear or $skills_write or $skills_speak or $skills_grammar or $skills_read)
    {
    	$skills = 1;
    }
    elseif(!$skills_hear && !$skills_write && !$skills_speak && !$skills_grammar && !$skills_read)
    {
    	$skills = 0;
    }
   
    //Rausgenommen
    /*Seitenanzahl / Spieldauer*/
    /*
    if(($physics !="0")&&($physics !="3")){
		if ($physics==1){$description_ph="Spieldauer";$auswahl7=$description_ph;}
		if ($physics==2){$description_ph="Seitenanzahl";$auswahl7=$description_ph;}
	}
	else if ($physics=="3"){ 
		$description_ph=$auswahl7;
		if ($description_ph=="Spieldauer"){;$physics="1";$auswahl7=$description_ph;}
		if ($description_ph=="Seitenanzahl"){$physics="2";$auswahl7=$description_ph;}
	}
	if(($physics=="0")or($physics=="")){ 
		$auswahl7=""; 
		$select7="";
		$description_ph="";
	}
    */

	//error variablen schoener machen
	for($i=1;$i<=20; $i++){
		$error[$i] = "";
	}

    //??
	/*Kontrolle Eigaben, Fehlerausgabe*/
	
	/*raussuchen der Systematik*/
	$systematics=getSystematicNumbers();

    /*Mediennummer*/
    if ($_POST['medium_nbr']!= "")  {// && ($secure_copy !="on")){// Secure Copy rausgenommen, keine Ahnung was das überhaupt sollte
    	//var_dump($_POST);
    	//debug
    	echo "<br><span class=\"ok\">Generierte Mediennummer = " . $_POST['medium_nbr'] . "</span>";
    	$new = db_query("SELECT bibid FROM biblio_copy WHERE barcode_nmbr = '$medium_nbr'");
    	$query = mysqli_fetch_array($new);
    	$medium_nbr_check = $query[0];

    	if ($medium_nbr_check != "") {
    		$error13 = "<br>Diese Mediennummer existiert bereits:";
    		$link_to_medium = "<br><a href=\"../catalog/biblio_edit.php?bibid=$medium_nbr_check\" target=\"_blank\">zeige Medium</a>";
    		$medium_nbr = "";
    	}else{
    		$medium_nbr = $_POST['medium_nbr'];
    	}
    }else{
    	//Generierung von Mediennummer. Höchste plus 1.
    	$sql = db_query("SELECT MAX(barcode_nmbr) FROM biblio_copy WHERE barcode_nmbr Regexp '^[0-9]{6}$' ");
    	$query_result = mysqli_fetch_array($sql);
    	$barcode_nmbr = $query_result[0];
    	$medium_nbr = $barcode_nmbr + 1;
    	if(!(isset($_POST['eintragen']))) {
    		echo "<br><span class=\"ok\">Generierte Mediennummer = " . $medium_nbr . "</span>";
    	}

    }

	//Generierung von Fehlern. Lässt sich sicherlich schlauer lösen. Es werden nur noch wichtige Felder überprüft
    if(isset($_POST['eintragen'])) {
        //ISBN raus, kann leer sein
		/*
        if ($isbn == "") {
            $error1 = "bitte ausf&uuml;llen";
        }
        */
		//$muster = '/^[0-9]*$/';
        //Titel MUSS drin sein
		if ($title == "") {
			$error[2] = "class=\"error\"";
		}
        //Autor Optional? Abklären, ich wäre für ja. Wesentlich netter beim eintippen
		/*
        if ($author == "") {
            $error3 = "bitte ausf&uuml;llen";
        }

		//Verlag optional
        if ($publisher == "" && ($secure_copy != "on")) {
            $error4 = "bitte ausf&uuml;llen";
        }
        */
        //Sachen sind entweder muss oder mit Standardwerten belegt
		if ($material == "0") {
			$error[5] = "class=\"error\"";
		}
		if ($genre == "0") {
			$error[6] = "class=\"error\"";
		}
		if ($location == "0") {
			$error[7] = "class=\"error\"";
		}

		if ($additional_sig == "") {
			$error[9] = "class=\"error\"";
		}

        if ($skills == 0) {
            $error[10] = "class=\"error\"";
        }

		if ($language == "0") {
			$error[11] = "class=\"error\"";
		}
		if ($niveau == "0-0") {
			$error[12] = "class=\"error\"";
		}


		/*Fertigkeiten*/
		//TODO: Was soll das?

		if ($skills_hear != 1) {
			$skills_hear = "NULL";
		}
		if ($skills_speak != 2) {
			$skills_speak = "NULL";
		}
		if ($skills_write != 3) {
			$skills_write = "NULL";
		}
		if ($skills_grammar != 4) {
			$skills_grammar = "NULL";
		}
		if ($skills_read != 5) {
			$skills_read = "NULL";
		}
        //Zum setzen der Häkchen vermutl.
		if ($skills_grammar == 4) {
			$checked_skills_grammar = "checked=\'checked\'";
		} else {
			$ckecked_skills_grammar = "";
		}
		if ($skills_hear == 1) {
			$checked_skills_hear = "checked=\'checked\'";
		} else {
			$ckecked_skills_hear = "";
		}
		if ($skills_read == 5) {
			$checked_skills_read = "checked=\'checked\'";
		} else {
			$ckecked_skills_read = "";
		}
		if ($skills_speak == 2) {
			$checked_skills_speak = "checked=\'checked\'";
		} else {
			$ckecked_skills_speak = "";
		}
		if ($skills_write == 3) {
			$checked_skills_write = "checked=\'checked\'";
		} else {
			$ckecked_skills_write = "";
		}

		/*Signatur lässt alles durch, wo kein punkt drinne steht*/
        //Regex für Signatur
		$muster = '/^\w{0,}$/';

        //Check, ob Signatur gültig ist
		if ((preg_match($muster, $additional_sig, $wert)) == 0) {
			$error9 = "<br>Keine g&uumlltige Signatur (ohne Punkt)";
			$additional_sig = "";
		} else {
			/*richtige Systematiknummer raussuchen*/

			$category = explode(".", $genre);
			$genre_main = $category[0];
			$subcategory = $category[1];


			$sql = "SELECT code FROM nt_systematik_signatur WHERE category = '$genre_main' and sub_category = '$subcategory'";
			$new = db_query($sql);
			$querry = mysqli_fetch_array($new);
			$code = $querry[0];

			/*sucht nach sprache haupt und unterkategorie der systematik und dem sionaturrest => eindeutige identifier */
			$new = db_query("SELECT bibid FROM biblio WHERE call_nmbr2='$additional_sig' and topic3='$language' and collection_cd = '$code' and call_nmbr1 = '$location'");
			$query = mysqli_fetch_array($new);
			$signature_check = $query[0];

            //Wenn Signatur bereits vorhanden, wird der Link zum Medium angezeigt.
			if ($signature_check != " ") {
				$link_to_sig = "<br><a href=\"../catalog/biblio_edit.php?bibid=$signature_check\" target=\"_blank\">Medium existiert bereits</a>";
			} else {
				$link_to_sig = "";
			}
		}

		//Niveau. Eigentlich obsolet, weil jetzt Standardwerte gesetzt werden.

		if (($fromNiv == "") && ($toNiv != "")) {
			$error12 = "<br>bitte Anfangsniveau eintragen";
		}
		if (($toNiv == "") && ($fromNiv != "")) {
			$error12 = "<br>bitte Endniveau eintragen";
		}

        //Fehler überflüssig, wegen Standardwerten
		//if(!$toNiv && !$fromNiv){$error12="<br>bitte ausf&uuml;llen";}
		if ($fromNiv && $toNiv) {
			$arr1 = str_split($fromNiv, 1);
			$arr2 = str_split($toNiv, 1);
			if ($arr1[0] > $arr2[0]) {
				$error12 = "<br>Anfangsniveau gr&ouml;sser als Endniveau";
			} else {
				if (($arr1[0] == $arr2[0]) && ($arr1[1] > $arr2[1])) {
					$error12 = "<br>Anfangsniveau gr&ouml;sser als Endniveau";
				} else {
					$niveau = "$fromNiv-$toNiv";
				}
			}
		}


        //Test eig. obsolet, weil das Jahr nicht mehr Pflicht ist.
        //Andererseits ist ein Check vllt. nicht schlecht. Muss abgesprochen werden.
		/*Erscheiungsjahr*/

		$muster = '/^[0-2]{1}[0-9]{3}$/';
		if ($pb_year) {

			if ((preg_match($muster, $pb_year, $wert) == 0) && ($secure_copy == "off")) {
				echo $pb_year;
				$error[18] = "<br>Keine g&uumlltige Jahresangabe (YYYY)";
				$pb_year = "";

			}
		}

		//TODO: ueberfluessige ueberpruefungen raus nehmen
		/*Seitenanzahl/Spieldauer*/
		/*
		$muster = '/^[0-9]{2}:[0-9]{2}$/';
		if ($physics == "1") {
			if ((preg_match($muster, $ph_description, $wert)) == 0) {
				$error[16] = "<br>Keine g&uumlltige Zeitangabe (hh:mm)";
				$ph_description = "";
			}
		}
		$muster = '/^[0-9]{1,4}$/';
		if ($physics == "2") {
			if ((preg_match($muster, $ph_description, $wert)) == 0) {
				$error[16] = "<br>Keine g&uumlltige Seitenzahl ";
				$ph_description = "";
			}
		}

		if ($physics == "0") {
			$error[16] = "<br>Bitte auswählen. Seitenanzahl/Spieldauer optional.";
		}
		*/
		/*hyperlink/Internetseite*/
        //Check ob Link gültige Adresse ist
		$muster = '/^www.*.*$/';
		if ($hyperlink != "") {
			if ((preg_match($muster, $hyperlink, $wert)) == 0) {
				$error[20] = "<br>Keine g&uumlltige Internetadresse (Bsp.: www.selfhtml.org)";
				$hyperlink = "";
			}
		}


		/*Datenbank Neuer_Eintrag*/

		/*OPAC-FLG*/
        //Anzeigen im OPAC Ja Nein
		if ($opac_flg == "on") {
			$opac_flg1 = "Y";
			$checked = "checked=\"checked\"";
		} else {
			$opac_flg1 = "N";
			$checked = "";
		}

        //Leere Strings für unwichtige Sachen
		if (true) {
			if (!$isbn) {
				$isbn = " ";
			}
			if (!$publisher) {
				$publisher = " ";
			}
			if (!$pb_year) {
				$pb_year = " ";
			}
			if (!$public_lc) {
				$public_lc = " ";
			}
			if (!$author) {
				$author = " ";
			}
			//$checked = "";
			if (!$author) {
				$author = " ";
			}
		}


		//TODO: Ueberarbeiten
		//Eintragen von Datensatz in DB
		if (isset($_POST['eintragen'])
            //Wenn alle wichtigen Felder befüllt
			&& $title && $genre && $material  && $location
            && $additional_sig && $skills && $language && $niveau
            && $medium_nbr && $opac_flg1 && !$signature_check
		) {

			//Array mit Hauptkategorien holen. Benoetigt fuer automatische Schlagwoerter.
			$kategorien = getCategoryList();
			//automatisch schlagwort generieren
			$schlagwort = $kategorien[$category[0]];
			//var_dump($schlagwort);
			$create = date('YmdHis', time());
			$last_change = date('YmdHis', time());
			$material1 = mysqli_fetch_array(db_query("SELECT description FROM material_type_dm WHERE code='$material'"));

			/*insert into biblio, biblio_fields*/

			//TODO: Was soll das
			/*richtige Systematik nummer raussuchen*/

			$category = explode(".", $genre);
			$genre_main = $category[0];
			$subcategory = $category[1];

			$sql = "SELECT code FROM nt_systematik_signatur WHERE category = '$genre_main' and sub_category = '$subcategory'";
			$new = db_query($sql);
			$genre = mysqli_fetch_array($new);
			$genre = $genre[0];

			//TODO: WAS SOLL DAS
			/* maskieren von hochkommatas  */
			//$slogan = addslashes($slogan);
			$author = addslashes($author);
			$title = addslashes($title);

			$sql = "INSERT INTO biblio (last_change_userid,create_dt,last_change_dt,material_cd,collection_cd,call_nmbr1,call_nmbr2,title,author,topic1,topic2,topic3,topic5,opac_flg)
					VALUES (2,'$create','$last_change','$material','$genre','$location','$additional_sig','$title','$author','$schlagwort',NULL,'$language','$niveau','$opac_flg1')";
			$new = db_query($sql);

			/*INSERT into biblio_copy  (Verlag,Dauer,ISBN, Erscheinungsort; Erscheinungsjahr, Seitenzahl:::::)*/

			$sql = "SELECT bibid FROM biblio ORDER BY bibid DESC LIMIT 1"; //letzte soeben eingetragene bibid auslesen
			$new = db_query($sql);
			$bibid = mysqli_fetch_array($new);
			$bibid = $bibid[0];

			/*INSERT into biblio_copy_skills*/

			$sql = "INSERT INTO biblio_skills (bibid,hearing_skill,speak_skill,write_skill,grammar_skill,read_skill)
																VALUES ('$bibid'," . $skills_hear . "," . $skills_speak . "," . $skills_write . "," . $skills_grammar . "," . $skills_read . ")";
			$new = db_query($sql);

			/*Insert isbn*/

			$description_isbn = "Internationale Standard Buch Nummer \(ISBN\)"; //tag, subfield_cd, repeatableflag für ISBN auslesen
			$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description_isbn'";
			$new = db_query($sql);
			$query = mysqli_fetch_array($new);

			$tag_isbn = $query[0];
			$subfield_cd_isbn = $query[1];
			$field_data_isbn = $isbn;

			$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag_isbn', '$subfield_cd_isbn','$field_data_isbn')";
			$new = db_query($sql);


			/*Untertitel*/

			$description_title = "Titel"; //tag, subfield_cd für Title auslesen
			$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description_title'";
			$new = db_query($sql);
			$query = mysqli_fetch_array($new);
			$subfield_cd_title = $query[1];
			$tag_title = $query[0];
			$field_data_title = $title;

			$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag_title', '$subfield_cd_title','$field_data_title')";
			$new = db_query($sql);

			$description_subtitle = "Untertitel"; //tag, subfield_cd für Untertitle auslesen
			$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description_subtitle'";
			$new = db_query($sql);
			$query = mysqli_fetch_array($new);
			$subfield_cd_subtitle = 'b';
			$tag_subtitle = 245;
			$field_data_subtitle = addslashes($down_title);


			/*Erscheinungsort*/

			$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag_subtitle', '$subfield_cd_subtitle','$field_data_subtitle')";
			$new = db_query($sql);

			$description_public_lc = "Erscheinungsort, Vertriebsort usw."; //tag, subfield_cd für ORT auslesen
			$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description_public_lc'";
			$new = db_query($sql);
			$query = mysqli_fetch_array($new);
			$subfield_cd_public_lc = $query[1];
			$tag_public_lc = $query[0];
			$field_data_public_lc = addslashes($public_lc);


			$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag_public_lc', '$subfield_cd_public_lc','$field_data_public_lc')";
			$new = db_query($sql);

			/*Verlag/Herausgeber*/

			$description = "Name des Verlags, der Vertriebsstelle usw."; //tag, subfield_cd für VERLAG/Herausgeber auslesen
			$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description'";
			$new = db_query($sql);
			$query = mysqli_fetch_array($new);
			$subfield_cd = $query[1];
			$tag = $query[0];
			$field_data = addslashes($publisher);


			$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag', '$subfield_cd','$field_data')";
			$new = db_query($sql);


			$description = "Erscheinungsjahr, Vertriebsjahr usw."; //tag, subfield_cd für Erscheinungsjahr auslesen
			$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description'";
			$new = db_query($sql);
			$query = mysqli_fetch_array($new);
			$subfield_cd = $query[1];
			$tag = $query[0];
			$field_data = $pb_year;

			$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag', '$subfield_cd','$field_data')";
			$new = db_query($sql);

			/*auswahl zwischen spieldauer und Seitenanzahl*/
			/*
			if ($physics == '1') {
				$description = "Spieldauer"; //tag, subfield_cd für Spieldauer auslesen
				$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description' AND repeatable_flg='N'";
				$new = mysqli_query($link, $sql) OR die(mysqli_error());
				$query = mysqli_fetch_array($new);
				$subfield_cd = $query[1];
				$tag = $query[0];
				$field_data = $ph_description;


				$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag', '$subfield_cd','$field_data')";
				$new = mysqli_query($link, $sql) OR die(mysqli_error());
			} else if ($physics == '2') {
				$description = "Seitenanzahl"; //tag, subfield_cd für Spieldauer auslesen
				$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description'";
				$new = mysqli_query($link, $sql) OR die(mysqli_error());
				$query = mysqli_fetch_array($new);
				$subfield_cd = $query[1];
				$tag = $query[0];
				$field_data = $ph_description;

				$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag', '$subfield_cd','$field_data')";
				$new = mysqli_query($link, $sql) OR die(mysqli_error());
			}
			*/

			/*Zusammenfassung*/
			if ($extra) {
				$description = utf8_decode("Fußnote, Zusammenfassung etc."); //tag, subfield_cd für Zusammenfassung auslesen
				$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description'";
				$new = db_query($sql);
				$query = mysqli_fetch_array($new);
				$subfield_cd = $query[1];
				$tag = $query[0];
				$field_data = addslashes($extra);


				$sql = "INSERT INTO biblio_field(bibid, tag, subfield_cd, field_data) VALUES ('$bibid', '$tag', '$subfield_cd','$field_data')";
				$new = db_query($sql);
			}
			/*Ein Exemplar anlegen*/

			$sql = "INSERT into biblio_copy (bibid, create_dt,copy_desc, barcode_nmbr, status_cd, status_begin_dt,renewal_count)
					  VALUES ('$bibid','$create','','$medium_nbr','in','$create','0')";
			$new = db_query($sql);


			/*Link zur Mediendatei eintragen !! Achtung neuer Datenbankeintrage tag 130 subfield_cd u --> biblio_field*/
			if ($hyperlink != "") {

				$description = utf8_decode("Link zur Mediendatei"); //tag, subfield_cd für Mediendatei
				$sql = "SELECT tag,subfield_cd, repeatable_flg FROM usmarc_subfield_dm WHERE description='$description'";
				$new = db_query($sql);
				$query = mysqli_fetch_array($new);
				$tag = $query[0];
				$subfield_cd = $query[1];
				$field_data = $hyperlink;

				$sql = "INSERT into biblio_field (bibid,tag,subfield_cd,field_data)
						VALUES('$bibid','$tag','$subfield_cd','$field_data')";
				$new = db_query($sql);
			}

			/*Bei erfolgreichem Datenbankeintrag alle Werte reset ()*/
			$attention = "";
			if ($opac_flg1 == "N") {
				$attention = "Achtung der Datensatz wird nicht im OPAC angezeigt!";
			}
			//TODO: Erfolgreichen Eintrag nur bescheinigen wenn auch wirklich erfolgt
			echo "<br><span class=\"ok\">Datensatz erfolgreich eingetragen</span>";

			if ($medium_nbr) {
				echo "<br><span class=\"ok\">Eingefügte Mediennummer = " . $medium_nbr . "</span>";
			}
			echo "<br><span class=\"ok\">" . $attention . "</span>";
			//delete_POST();
			?>
			<script>
				//alert("test");

				window.onload = function () {
					document.catalog.reset();
				}


			</script><?php
			/*Formular und Post leeren*/
			//TODO: Eleganter loesen. vllt array mit namen und dann foreach??
			$_POST['author'] = "";
			$author = "";
			$_POST['isbn'] = "";
			$isbn = "";
			$_POST['title'] = "";
			$title = "";
			$_POST['publisher'] = "";
			$publisher = "";
			$_POST['material'] = "";
			$material = "";
			$materiall1 = "";
			$_POST['genre'] = "";
			$genre = "";
			$_POST['location'] = "";
			$location = "";
			$location1 = "";
			$_POST['additional_sig'] = "";
			$additional_sig = "";
			$_POST['skills'] = "";
			$skills = "";
			$skils1 = "";
			$_POST['language'] = "";
			$language = "";
			$_POST['fromNiv'] = "";
			$fromNiv = "";
			$fromNiv1 = "";
			$_POST['toNiv'] = "";
			$toNiv = "";
			$toNiv1 = "";
			//$_POST['slogan0']="";$slogan="";$_POST['slogan1']="";$_POST['slogan2']="";$_POST['slogan3']="";$_POST['slogan4']="";$_POST['slogan']="";$slogan0="";$slogan1="";$slogan2="";$slogan3="";$slogan4="";$slogan="";
			$_POST['medium_nbr'] = "";
			$medium_nbr = "";
			$_POST['opacflg'] = "";
			$opacflg = "";
			$_POST['ph_description'] = "";
			$ph_description = "";
			$_POST['down_title'] = "";
			$down_title = "";
			$_POST['physics'] = "";
			$physics = "";
			$_POST['extra'] = "";
			$extra = "";
			$_POST['secure_copy'] = "";
			$secure_copy = "";
			$_POST['hyperlink'] = "";
			$hyperlink = "";
			$_POST['public_lc'] = "";
			$public_lc = "";
			$_POST['physics'] = "";
			$physics = "0";
			$_POST['pb_year'] = "";
			$pb_year = "";
			$_POST['genre'] = "";
			$genre = "";
			$genre1 = "";
			$_POST['skills_hear'] = "";
			$_POST['skills_speak'] = "";
			$_POST['skills_read'] = "";
			$_POST['skills_grammar'] = "";
			$_POST['skills_write'] = "";
			$checked = "";
			$ckecked_skills_grammar = "";
			$ckecked_skills_hear = "";
			$ckecked_skills_read = "";
			$ckecked_skills_speak = "";
			$ckecked_skills_write = "";
			//$checked_secure_copy="";
			$select0 = "selected";



		} //TODO: Irgendwie schlauer loesen und check einbauen ob alles richtig in die datenbank uebermittelt wurde.
		else {
			echo "<br><span class=error> kein Datenbankeintrag erfolgt, </span>";
			echo "<br><span class=error>bitte das Formular komplett ausf&uuml;llen!</span>";
		}
	}

   ?>
<!--//TODO: Formular anpassen-->
   <!-- /*FORMULAR ANFANG*/-->
    <br>
<?php


//var_dump(getCategoryList()) ?>

		<form id="form_catalog"  name="catalog" class="katalogisierung"  action="biblio_new.php" method="post" accept-charset="utf-8"><!-- Problem bei der isbn suche ....reihenfolge der zuweisungen der Variablen abchecken-->
			<table>
				<tr>
					<td><p id="isbn_text">ISBN:<span class="error"><?php if($bibid_check!=""){ echo $link_to_isbn ;}?></span><input type="text" name="isbn"  value="<?php echo $isbn; ?>"></input>
					</p></td>
				</tr>
                <!--
				<tr>
					<td><p>Backup erstellen:<input type="checkbox" onchange="show_constraints(this.value)" name="secure_copy" value="on" <?php echo $checked_secure_copy?>></input></p>
					</td>
				</tr>
				-->
				<tr>
					<!--//TODO: Pflichtfelder so anpassen-->
					<td><p><span class="error">*</span><span <?php echo $error[2]; ?>>Titel: </span><input type="text" name="title"  value="<?php echo stripslashes($title); ?>"></input></p>
					</td>
				</tr>
				<tr>
					<td><p>Untertitel:<input type="text" name="down_title"  value="<?php echo stripslashes($down_title); ?>"></input></p>
					</td>
				</tr>
				<tr>
					<td><p>Autor / Regisseur:<input type="text" name="author"  value="<?php echo stripslashes($author); ?>"></input></p>
					</td>
				</tr>
				<tr>
					<td><p>Herausgeber/Verlag:<input type="text" name="publisher"  value="<?php echo stripslashes($publisher); ?>"></input></p>
					</td>
				</tr>
				<!--
				<tr>
					<td><p><span class="error">*</span>Physische Beschreibung (Dauer,Seitenzahl):<span class="error"><?php echo $error[16];?></span></p>
					</td>
				</tr>
				<tr>
					<td>
						<?php //echo db_physics_sel("physics",$physics);?>
					</td>
				</tr>
				<tr>
					<td><p><input type="text" name="ph_description" value="<?php echo $ph_description ?>"></input></p>
					</td>
				</tr>
				-->
				<tr>
					<td><p>Erscheinungsort:<input type="text" name="public_lc"value="<?php echo $public_lc ?>"></input></p>
					</td>
				</tr>
				<tr>
					<td><p>Erscheinungsjahr:<input type="text" name="pb_year"value="<?php echo $pb_year ?>"></input></p>
					</td>
				</tr>				
				<tr>
				  <td><p><span class="error">*</span><span <?php echo $error[5]; ?>>Medienart: </span>
					  <?php echo db_material_sel("material", $material);?>
				  </p></td>
				</tr>
				 <tr>
					<td><p><span class="error">*</span><span <?php echo $error[6]; ?>>Systematik:</span>
					 <?php echo listQuery($systematics,"genre",$genre) ?>
					 </p></td>
				</tr>
				
				<tr>
					<td><p><span class="error">*</span><span <?php echo $error[9]; ?>>Signatur:</span><span class="error"> <?php if($signature_check!=""){echo $link_to_sig;}?></span></p>
					
					<!-- ****************achtung id =" start_sig nicht verändern oder id in function listSignature() JS änder ******  -->
					<span id="start_sig"></span><input type="text" name="additional_sig" value="<?php echo $additional_sig ?>">
					</td>
				</tr>
				
				<tr>
				  <td><p><span class="error">*</span><span <?php echo $error[7]; ?>>Standort:</span>
					     <?php echo db_location_sel2("location", $location);?>
				  </p></td>
				</tr>
				<tr>
				  <td><p><span class="error">*</span><span <?php echo $error[10]; ?>>Fertigkeiten:</span>
				 	  H&ouml;ren<input type="checkbox" name="skills_hear" value="1"<?php echo $checked_skills_hear;?>>
				 	  Sprechen<input type="checkbox" name="skills_speak" value="2"<?php echo $checked_skills_speak;?>> 
				 	  Schreiben<input type="checkbox" name="skills_write" value="3"<?php echo $checked_skills_write;?>>
				 	  Grammatik<input type="checkbox" name="skills_grammar" value="4"<?php echo $checked_skills_grammar;?>>
				 	  Lesen<input type="checkbox" name="skills_read" value="5"<?php echo $checked_skills_read;?>>
				  </p></td>				
				</tr>
				<tr>
					<td><p><span class="error">*</span><span <?php echo $error[11]; ?>>Sprache:</span>
					    <?php echo db_language_sel("language", $language);?>
					</p></td>
				</tr>
				<tr>
					<td><p>Sprachniveau:
					</td>
				</tr>
				<tr>
					<td><span>von</span></td>
				</tr>
				<tr>
					<td><?php echo db_niveau_sel("fromNiv",$fromNiv);?>
					</td>
				</tr>
				<tr>
					<td><span>bis</span>
					</td>
				</tr>
				<tr>
					<td><?php echo db_niveau_sel("toNiv",$toNiv)?></td>
				</tr>						
				<tr>
					<td>Zusammenfassung:
					</td>
				</tr>
				<tr>
					<td><textarea  class="textarea" rows="4" cols="40" name="extra"><?php echo $extra ?></textarea>
					</td>
				</tr>
				<tr>
					<td>Link zur Mediendatei:
					</td>
				</tr>
				<tr>
					<td><input type="text" name="hyperlink"  value="<?php echo $hyperlink; ?>"></input>
					</td>
				</tr>

				<tr>
					<td><p><span class="error">*</span>Mediennummer:<span><p>(Diese Mediennummer wurde automatisch generiert. Falls eine andere Mediennummer verwendet werden soll, bitte löschen und selber eingeben!)</p></span><span class="error"><?php echo $error[13];if($medium_nbr_check){ echo $link_to_medium ;}?></span><input type="text" name="medium_nbr" value="<?php echo $medium_nbr ?>">
					</p></td>
				</tr>
				<tr>
					<td><p>im Opac anzeigen:<input type="checkbox" name="opacflg" value="on" checked <?php echo $checked?>></input>
					</p></td>
				</tr>
				<tr>
					<td><input type="submit" name="eintragen"  value="eintragen" ></input>
						<input type="submit"  name="cancel" value="abbrechen" ></input>
					</td>
				</tr>
				<!-- *Übergabe Felder für Java Script u. php zum anzeigen der Daten bei Fehleintrag-->
                <!--
				<tr>
					<td><input id="auswahl8" type="hidden" name="auswahl8" value="<?php echo $auswahl8 ?>"></input>
					</td>
				</tr>
                -->
				</table>
			
				
		</form>
		<!--/* bei erneutem laden die Anzahl der Slogans mit laden*/-->
 		<!--
 		<script>
			set_dropdown_selected();
		</script>
		-->
		<!--**************************************************************************************************************-->
		<script>listSignature(document.getElementById('genre').getElementsByTagName('option')[document.getElementById('genre').selectedIndex].value,'start_sig')</script>
