
<style type="text/css">
// überschreiben schon vorhandener Styles //	
	/*Trenner */
	div.deviderVertical
	{
	width:100%;
	border-top-style: solid;
	border-color: rgb(197, 221, 233);
	}
	
	/*Bestätigungen*/
	a:link.ok{
		font-size : 12px;
		color: #008000;
		font-weight: normal;
	}
	/*Löschen / Error */
	a:link.error{
		font-size : 12px;
		color: #ff1111;
		font-weight: normal;
	}
</style>

<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
	//var_dump($media['material_cd']);
	//echo "<br>";
	//var_dump($_GET);
	
 
    require_once("../shared/common.php");
    session_cache_limiter(null);

    $tab = "cataloging";
    $nav = "mod_medialiste";
    $focus_form_name = "barcodesearch";
    $focus_form_field = "searchText";

    require_once("../shared/logincheck.php");
    require_once("../shared/header.php");
    require_once("../classes/Localize.php");
    require_once("include_mods.php");
    require_once("lib/medialist_functions.php");
    ?><script type="text/javascript" src="../mods/lib/javascript.js"></script><?php
    $loc = new Localize(OBIB_LOCALE,$tab);
    $msg = "";
    

    
    
    if (isset($_GET["msg"])) {
        $msg = "<font class=\"error\">".H($_GET["msg"])."</font><br><br>";
    }
    $ioerror_msg = "";
    
    //
    // EVENT Handling
    //
    if(isset($_GET['complete']))
    {
        db_medialist_clear();
        db_medialist_update();
        db_clear_data();
        db_correct_entries();        
    }
    // Datenbank neu aufsetzen mittels SQL-Files
    if(isset($_GET['update_db']))
    {
        db_medialist_update();
    }
    // Datenbank löschen - TRUNC - Nur Inhalt, nicht Struktur
    if(isset($_GET['clear_db']))
    {
        db_medialist_clear();
    }
    //
    // Übertragung gültiger Einträge nach festgesetzten Kriterien
    //
    if(isset($_GET['accept']))
    {
        db_correct_entries();
    }
    // Datensatz von temp_biblio in OPAC übernehmen
    // Nach bearbeitung
    if(isset($_POST['saveEntry']))
    {
        if(!(isset($_POST['form_author']) && isset($_POST['form_title'])
        && isset($_POST['form_niv1']) && isset($_POST['form_niv2'])
        && $_POST['form_author'] != '' && $_POST['form_title'] != ''))
        {
          $ioerror_msg = "Fehlerhafte oder unvollst&aauml;ndige Eingabe";
        }
        else
        {
          // Zusätzliche Felder aktualisieren: temp_biblio_field
          {
              $form_note = $_POST['520a']; //Zusammenfassung
              $form_duration = $_POST['901e']; // Dauer
              $form_pagecount = $_POST['903a']; // Seitenzahl
              $form_link = $_POST['130u']; // Link
              $form_publishLocation = $_POST['260a']; // Erscheinungsort
              $form_publisher = $_POST['260b']; // Verlag
              $form_year = $_POST['260c']; // Erscheinungsjahr
              $form_undertitle = $_POST['245b']; // Untertitel
              $form_isbn = $_POST['20a']; // ISBN
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_isbn), 20, 'a', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 20, 'a', mysqli_real_escape_string($form_isbn)));
              }
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_publishLocation), 260, 'a', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 260, 'a', mysqli_real_escape_string($form_publishLocation)));
              }
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_publisher), 260, 'b', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 260, 'b', mysqli_real_escape_string($form_publisher)));
              }
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_year), 260, 'c', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 260, 'c', mysqli_real_escape_string($form_year)));
              }
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_undertitle), 245, 'b', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 245, 'b', mysqli_real_escape_string($form_undertitle)));
              }
              
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_note), 520, 'a', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 520, 'a', mysqli_real_escape_string($form_note)));
              }
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_duration), 901, 'e', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 901, 'e', mysqli_real_escape_string($form_duration)));
              }
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_pagecount), 903, 'a', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 903, 'a', mysqli_real_escape_string($form_pagecount)));
              }
              
              db_query(sprintf("UPDATE temp_biblio_field SET field_data = '%s' WHERE tag = %s AND subfield_cd = '%s' AND bibid = %s",
                                mysqli_real_escape_string($form_link), 130, 'u', $_POST['form_bibid']));
              if(mysqli_affected_rows() == 0)
              {
                  db_query(sprintf("INSERT INTO temp_biblio_field (bibid, tag, ind1_cd, ind2_cd, subfield_cd, field_data) 
                                    VALUES(%s, %s, 'N', 'N', '%s', '%s')",
                                    $_POST['form_bibid'], 130, 'u', mysqli_real_escape_string($form_link)));
              }
          }
          
          $sql = sprintf("
          UPDATE temp_biblio
          SET material_cd=%s, collection_cd=%s, call_nmbr1='%s', call_nmbr2='%s',
              title='%s',
              author='%s', topic1='%s', topic2='%s', topic3='%s', topic4='%s',
              topic5='%s-%s', opac_flg='%s' 
          WHERE bibid=%s",
          $_POST['form_material'], getCollection_cdBySystematics($_POST['form_collection']), $_POST['form_cnmbr1'],
          $_POST['form_cnmbr2'],
          $_POST['form_title'],
          $_POST['form_author'], inputKeywordsRead(), 1,
          $_POST['form_top3'], $_POST['form_top4'], 
          $_POST['form_niv1'], $_POST['form_niv2'],
          $_POST['form_flg'], $_POST['form_bibid']);
          db_query($sql);
          $newBibid = acceptBiblioEntry((int)$_POST['form_bibid']);
          
          //printf("<br><bold>%s</bold><br>", $newBibid);
          
          if(isset($_POST['form_top2'])) {
              $s[1] = in_array('1', $_POST['form_top2']) ? '1' : 'NULL';
              $s[2] = in_array('2', $_POST['form_top2']) ? '2' : 'NULL';
              $s[3] = in_array('3', $_POST['form_top2']) ? '3' : 'NULL';
              $s[4] = in_array('4', $_POST['form_top2']) ? '4' : 'NULL';
              $s[5] = in_array('5', $_POST['form_top2']) ? '5' : 'NULL';
              //var_dump($s);
              $sql = sprintf("
              UPDATE biblio_skills
              SET hearing_skill = %s, speak_skill = %s, write_skill = %s, grammar_skill = %s, read_skill = %s
              WHERE bibid = %s",
              $s[1], $s[2], $s[3], $s[4], $s[5], $newBibid);
              db_query($sql);
          }
        }
    }
    
    // Test
    /*
        DELETE FROM biblio      WHERE bibid = 7846;# 1 Datensatz betroffen.
        DELETE FROM biblio_copy WHERE bibid = 7846;# 97 Datensätze betroffen.
        INSERT INTO temp_biblio      SELECT * FROM mediobibo.biblio WHERE title LIKE 'New Scientist';
        INSERT INTO temp_biblio_copy SELECT * FROM mediobibo.biblio_copy WHERE bibid IN (SELECT bibid FROM mediobibo.biblio WHERE title LIKE 'New Scientist');
     */
    
    // übernehmen
    if(isset($_GET['bibid']) && (int)$_GET['bibid'] >= 0 && isset($_GET['ok']) && (int)$_GET['ok'] == 1)
    {
        if(!acceptBiblioEntry((int)$_GET['bibid']))
        {
            unset($_GET['ok']);
            echo "ERROR!<br>";
        }
    }
    // löschen
    if(isset($_GET['bibid']) && (int)$_GET['bibid'] >= 0 && isset($_GET['del']) && (int)$_GET['del'] == 1)
    {
        db_query(sprintf("DELETE FROM temp_biblio WHERE bibid = %s", $_GET['bibid']));
        db_query(sprintf("DELETE FROM temp_biblio_copy WHERE bibid = %s", $_GET['bibid']));
        db_query(sprintf("DELETE FROM temp_biblio_field WHERE bibid = %s", $_GET['bibid']));
    }
      
	   
	   /****************Schlagwörter  für Schlagwortliste suchen ********(onchange)***************************/
	   /*   																								 */
	   /*                                                                                                    */
	   /* eine Suchwort-Liste (slogan-list) aus den daten der Datenbank erstellen und an Javascript übergeben*/ 
	   /******************************************************************************************************/
	   
	                                                           
	  $list_slogans=list_slogans();
	  $lange=strlen($list_slogans); 
	  $list=explode(";",$list_slogans);
	  ?>
	<script>
		function get_list(){
			 
			return '<?php echo $list_slogans;?>';
		}	
	</script>
	<?php
    
    
    function inputKeywordsPrint($keywords)
    {
        $out = "";
        $keylist = explode(';', $keywords);
        
        for($i = 0; $i < 5; $i++)
        {
            if($i < count($keylist))
            {
                $out .= sprintf("<tr><td><input type='text' id=\"form_keyword%s\"
								  onkeyup=\"hinzu(select_erstellen('Schlagwortliste%s', 'form_keywords%s','form_keyword%s'))\" 
								  onchange=\"hinzu(select_erstellen('Schlagwortliste%s', 'form_keywords%s','form_keyword%s'))\" 
								  value='%s' name='form_keyword%s' /></td></tr>
								<tr><td><div id=\"Schlagwortliste%s\"></div></td></tr>",
                                $i,$i,$i,$i,$i,$i,$i,$keylist[$i], $i,$i);
            }
            else
            {
                $out .= sprintf("<tr><td><input type='text'id=\"form_keyword%s\"
								  onkeyup=\"hinzu(select_erstellen('Schlagwortliste%s', 'form_keywords%s','form_keyword%s'))\" 
								  onchange=\"hinzu(select_erstellen('Schlagwortliste%s', 'form_keywords%s','form_keyword%s'))\"
								  value='' name='form_keyword%s' /></td></tr>
								<tr><td><div id=\"Schlagwortliste%s\"></div></td></tr>", $i,$i,$i,$i,$i,$i,$i, $i, $i);
            }
        }
        
        return sprintf("<table>%s</table>", $out);
    }
    function inputKeywordsRead()
    {
        $keys = array();
        for($i = 0; $i < 5; $i++)
        {
            $name = 'form_keyword'.$i;
            if(isset($_POST[$name]) && $_POST[$name] != "")
                $keys[] = $_POST[$name];
        }        
        return join(';', $keys);
    }
    
    function db_skills_option($name, $bibid) {
        $sql = sprintf("SELECT * FROM nt_fertigkeiten_tr sk, temp_biblio b WHERE b.topic2 = sk.code AND b.bibid = %s", $bibid);
        $rows = db_query($sql);
        $row = mysqli_fetch_assoc($rows);
        $res = '';
        
        if((int)$row['tr_skill_hear'] == 1)
            $res .= sprintf("<input type='checkbox' name='%s[]' value='1' checked >Hören", $name);
        else
            $res .= sprintf("<input type='checkbox' name='%s[]' value='1'>Hören", $name);
        if((int)$row['tr_skill_speak'] == 2)
            $res .= sprintf("<input type='checkbox' name='%s[]' value='2' checked >Sprechen", $name);
        else
            $res .= sprintf("<input type='checkbox' name='%s[]' value='2'>Sprechen", $name);
        if((int)$row['tr_skill_write'] == 3)
            $res .= sprintf("<input type='checkbox' name='%s[]' value='3' checked >Schreiben", $name);
        else
            $res .= sprintf("<input type='checkbox' name='%s[]' value='3'>Schreiben", $name);
        if((int)$row['tr_skill_grammar'] == 4)
            $res .= sprintf("<input type='checkbox' name='%s[]' value='4' checked >Grammatik", $name);
        else
            $res .= sprintf("<input type='checkbox' name='%s[]' value='4'>Grammatik", $name);
        if((int)$row['tr_skill_read'] == 5)
            $res .= sprintf("<input type='checkbox' name='%s[]' value='5' checked >Lesen", $name);
        else
            $res .= sprintf("<input type='checkbox' name='%s[]' value='5'>Lesen", $name);    
        return utf8_decode($res);
    }
    
?>
<h1 style="margin-left: 140;"><img src="../images/catalog.png" border="0" width="30" height="30" align="top"> <?php echo "MedienImportierung"; ?></h1>

<?php
//
// Detail Ansicht erzeugen: Anzeigen, wenn bibid gesetzt ist
//
if(isset($_GET['bibid']) && (int)$_GET['bibid'] >= 0 && isset($_GET['show']) && (int)$_GET['show'] == 1)
{
    // Rückgabe des Datensatzes, welcher der bibid in der biblio-Tabelle entspricht
    $medialist = db_query(sprintf("SELECT * FROM temp_biblio WHERE bibid = %s", $_GET['bibid']));
    // Rückgabe aller Einträge in der biblio_field, welche zur bibid gehören
    $marcdatalist = db_query(sprintf("SELECT *
                                      FROM usmarc_subfield_dm
                                      WHERE (tag = 520 AND subfield_cd = 'a') OR (tag = 901 AND subfield_cd = 'e') 
                                         OR (tag = 903 AND subfield_cd = 'a') OR (tag = 130 AND subfield_cd = 'u')
                                         
                                         
                                         OR (tag = 260 AND subfield_cd = 'a') OR (tag = 260 AND subfield_cd = 'b')
                                         OR (tag = 260 AND subfield_cd = 'c') OR (tag = 245 AND subfield_cd = 'b')
                                         OR (tag = 20 AND subfield_cd = 'a')
                                         
                                         
                                         "));
    
    // Auslesen der DB Infos -> nur ein Schleifendurchlauf
    while($media = mysqli_fetch_assoc($medialist))
    {
        $niv = explode('-', $media['topic5']);
        if(count($niv) != 2) $niv = array(-1,-1);
        //$niv_trans = array(""=>-1,"A1"=>1, "A2"=>2, "B1"=>3, "B2"=>4, "C1"=>5, "C2"=>6);
        $mediennummernDb = db_query(sprintf("SELECT * FROM temp_biblio_copy WHERE bibid = %s", $media['bibid']));
        $medien = "";
        while($mnum = mysqli_fetch_assoc($mediennummernDb)) {
            $medien .= $mnum['copyid'] . " - " . $mnum['barcode_nmbr'] . "\n";
        }
        
        print("<h2 style='margin-left: 140;'>Details</h2>
                <form action='' method='POST' style='width: auto; margin-left: 140;'> ");
        $tab_left = sprintf("
                <tr><td>Autor:</td><td><input type='text' value='%s' name='form_author'></td></tr>
                <tr><td>Titel:</td><td><input type='text' value='%s' name='form_title'></td></tr>
                
                <tr><td>Medienart:</td><td>%s</td></tr>
                <tr><td>Standort:</td><td>%s</td></tr>
                <tr><td>Systematik:</td><td>%s</td></tr>                
                <tr><td>Signatur(X.X.X.?):</td><td><input type='text' value='%s' name='form_cnmbr2'></td></tr>
                <tr><td>Schlagw&ouml;rter:</td><td><hr>%s<hr></td></tr>
                <tr><td>Fertigkeiten:</td><td>%s</td></tr>
                <tr><td>Sprache:</td><td>%s</td></tr>
                <tr><td>Niveau:</td><td>Von:%sBis:%s</td></tr>
                <tr><td>Anzeigen?:</td><td>%s</td></tr>
                <tr><td>Zusatz:</td><td><span><br>Bitte verteilen Sie die Informationen aus dem Zusatzfeld <br> auf andere Felder. 
                Dieses Feld wird nicht gespeichert.</span><br>
                <textarea name='form_top4' rows='10' cols='60'>%s</textarea></td></tr>
                <tr><td>Zugeh&ouml;rige Mediennummern:</td><td><textarea name='form_mid' rows='10' cols='60' readonly>%s</textarea></td></tr>",                    
                htmlentities($media['author'], ENT_QUOTES | ENT_SUBSTITUTE), htmlentities($media['title'], ENT_QUOTES | ENT_SUBSTITUTE), 
                db_material_sel("form_material", $media['material_cd']),
                db_location_sel("form_cnmbr1", htmlentities($media['call_nmbr1'], ENT_QUOTES | ENT_SUBSTITUTE)),
                //db_collection_sel("form_collection", $media['collection_cd']),  
                listQuery(getSystematicNumbers(),"form_collection",getSystematicByCode($media['collection_cd'])),           
                $media['call_nmbr2'],
                inputKeywordsPrint($media['topic1']),
                db_skills_option("form_top2", $media['bibid']), db_language_sel("form_top3", $media['topic3']),
                //$media['topic5'],
                db_niveau_sel('form_niv1', $niv[0]), db_niveau_sel('form_niv2', $niv[1]),
                db_opacflg_sel("form_flg", $media['opac_flg']), $media['topic4'],
                $medien);
        
        // MARC Daten Tabelle erzeugen            
        $tab_right = "";
        
        while($data = mysqli_fetch_assoc($marcdatalist))
        {
            $db = db_query(sprintf("SELECT * FROM temp_biblio_field WHERE tag = %s AND subfield_cd ='%s' AND bibid = %s", 
                                    $data['tag'], $data['subfield_cd'], $_GET['bibid']));
            
            if($db)
                $value = mysqli_fetch_assoc($db);
            else
                $value['field_data'] = '';
            
            $id = sprintf("%s%s", $data['tag'], $data['subfield_cd']);
            $tab_right .= sprintf("
                <tr><td>%s:</td><td><input type='text' value='%s' name='%s'></td></tr>",
                $data['description'], $value['field_data'], $id);
        }      
        
        // Beide Teiltabellen zusammenfügen in neue Tabelle
        printf("<table>
                  <tr><td><span style='color:red;font-size: 1.2em;'>%s</span></td></tr>
                  <tr><td><table>%s</table></td><td><table>%s</table></td></tr>
                </table>", 
                $ioerror_msg, $tab_left, $tab_right);
        // Speichern der geänderten Werte
        print(" <input type='hidden' value='".$media['bibid']."' name='form_bibid'/>
                <input type='submit' value='Speichern' name='saveEntry' />
                </form>");
    }
}
//
// Informationsansicht: Detail nach Eintragung in DB
//
if(isset($_GET['bibid']) && (int)$_GET['bibid'] >= 0 && isset($_GET['ok']) && (int)$_GET['ok'] == 1)
{
    // Rückgabe des Datensatzes, welcher der bibid in der biblio-Tabelle entspricht
    $medialist = db_query(sprintf("SELECT * FROM temp_biblio WHERE bibid = %s", $_GET['bibid']));
    // Rückgabe aller Einträge in der biblio_field, welche zur bibid gehören
    $marcdatalist = db_query(sprintf("SELECT * FROM temp_biblio_field WHERE bibid = %s", $_GET['bibid']));
    
    while($media = mysqli_fetch_assoc($medialist))
    {
        print("<h2 style='margin-left: 100;'>Details</h2>
               <form style='width: auto; margin-left: 100;'> ");
        $tab_left = sprintf("
                <tr><td>Folgendes Medium wurde eingetragen: </td></tr>
                <tr><td>Autor: %s</td></tr>
                <tr><td>Title: %s</td></tr>
                <tr><td>Medienart: %s</td></tr>
                <tr><td>Systematik: %s</td></tr>
                <tr><td>Standort: %s</td></tr>
                <tr><td>Signatur: %s</td></tr>
                <tr><td>Schlagw&ouml;rter: %s</td></tr>
                <tr><td>Fertigkeiten: %s</td></tr>
                <tr><td>Sprache: %s</td></tr>
                <tr><td>Zusatz: %s</td></tr>
                <tr><td>Niveau: %s</td></tr>
                <tr><td>Anzeigen?: %s</td></tr>",                    
                $media['author'], $media['title'], $media['material_cd'], 
                $media['collection_cd'], $media['call_nmbr1'], 
                $media['call_nmbr2'],
                $media['topic1'], 
                $media['topic2'], $media['topic3'], $media['topic4'], 
                $media['topic5'], $media['opac_flg']);

        // MARC Daten Tabelle erzeugen            
        $tab_right = "";
        while($marcdata = mysqli_fetch_assoc($marcdatalist))
        {
            $tagnames = db_query(sprintf("SELECT * FROM usmarc_tag_dm WHERE tag = %s", $marcdata['tag']));
            $tagname = mysqli_fetch_assoc($tagnames);
            $tab_right .= sprintf("<tr><td>%s: %s</td></tr>",                    
                                   $tagname['description'], $marcdata['field_data'], 
                                   $marcdata['bibid'], $marcdata['fieldid']);
        }
        
        // Beide Teiltabellen zusammenfügen in neue Tabelle
        printf("<table><tr><td><table>%s</table></td><td><table>%s</table></td></tr></table></form>", $tab_left, $tab_right);
    }
}
?>
<h2 style="margin-left: 140;">&Uuml;bersicht</h2>
<form style="width: auto; margin-left: 140;" action="" method="GET">
<fieldset><legend>Suche</legend>
<table>
    <tr>
        <td>
            <input type="text" name="skey" />
            <!-- <input type="submit" name="luckySearch" value="&Auml;hnlichkeitssuche" /> -->
        </td>
        <td>
            <?php
                if(isset($_GET['sel_language']))
                    echo db_language_sel("sel_language", $_GET['sel_language']);
                else
                    echo db_language_sel("sel_language");

            ?>
        </td>
        <td>
            <?php
                if(isset($_GET['sel_location']))
                    echo db_location_sel("sel_location", $_GET['sel_location']);
                else
                    echo db_location_sel("sel_location");
            ?>
        </td>
    </tr>
    <tr><td><input type="submit" name="search" value="Suchen" /></td></tr>
</table>
</fieldset>
<?php
//
// Interne Administrationsbedienung der MySQL DB
//
if(isset($_GET['adm']))
{
?>
<fieldset>
    <legend>Optionen</legend>
    <input type="submit" name="accept" value="Vollst&auml;ndige Daten &uuml;bertragen" /><br/>
    <input type="submit" name="clear" value="Aufr&auml;umen" />
    <input type="submit" name="clear_db" value="Datenbank l&ouml;schen" />
    <input type="submit" name="update_db" value="Datenbank updaten" /><br>
    <input type="submit" name="complete" value="Complete" />
</fieldset>
<?php
}

    $medialist = Null;
    //
    // Bereinigen der Datenbank von unerwünschten Formaten und Einträgen
    //
    if(isset($_GET['clear']))
    {
        // Bereinigt Datenbank nach entwickeltem Filter
        db_clear_data();
        
        // Nur für Ausgabe:
        $medialist = db_query("SELECT * FROM temp_biblio ORDER BY title");
    }
    //
    // Ausgabe bei Suche mit Keyword
    //
    elseif(isset($_GET['search']) && isset($_GET['skey']))
    {
        $opt1 = ''; $opt2 = '';
        if(isset($_GET['sel_language']) && (int)$_GET['sel_language'] > 0)
            $opt1 = 'AND topic3 = ' . $_GET['sel_language'];
        if(isset($_GET['sel_location']) && (int)$_GET['sel_location'] > 0)
            $opt2 = 'AND call_nmbr1 = ' . $_GET['sel_location'];
        $sql = sprintf("SELECT * FROM temp_biblio 
                        WHERE (
                            title   LIKE '%%%s%%'
                            OR  author  LIKE '%%%s%%'
                            OR	topic1	LIKE '%%%s%%'
                            OR  topic3  LIKE '%%%s%%'
                            OR  bibid   IN (SELECT bibid FROM temp_biblio_copy WHERE barcode_nmbr LIKE '%%%s%%')
                            ) %s %s
                        ORDER BY title
                        ",
                        $_GET['skey'], $_GET['skey'], $_GET['skey'], $_GET['skey'], $_GET['skey'], $opt1, $opt2);
        
        $medialist = db_query($sql);
    }
    //
    // Standartausgabe bei normaler Anzeige und Ähnlichkeitssuche
    //
    else
    {
        $medialist = db_query("SELECT * FROM temp_biblio ORDER BY title");
    }
    $numrows = 42;
    printf("<p>Beeintr&auml;chtige Datens&auml;tze: %s</p>", $numrows);

?>
<table style='font-size: 0.8em;'>
    <tr>
        <th>Optionen</th><th>Autor</th><th>Titel</th><th>Medienart</th><th>Systematik</th><th>Standort</th><th>Signatur</th>
        <th>Schlagw&ouml;rter</th><th>Fertigkeiten</th><th>Sprache(n)</th><th>Zusatz</th><th>Niveau</th>
    </tr>
            
<?php

    //
    // Allgemeine Ausgabe
    //
    while($media = mysqli_fetch_assoc($medialist))
    {            
        // = Formatierung =
        // Standort
        $location = $media['call_nmbr1'];
        if($media['call_nmbr1'] == "1880")
            $location = "<span style='color: green'>NP</span>";
        elseif($media['call_nmbr1'] == "3880")
            $location = "<span style='color: red'>GS</span>";
        elseif($media['call_nmbr1'] == "1870")
            $location = "<span style='color: blue'>SEP H</span>";
        elseif($media['call_nmbr1'] == "3870")
            $location = "<span style='color: blue'>Stud+</span>";
        elseif($media['call_nmbr1'] == "1350")
            $location = "<span style='color: blue'>SEP</span>";
            
        // Medienart
        $materials = mysqli_fetch_assoc(db_query("SELECT * FROM material_type_dm WHERE code = " . $media['material_cd']));
        $material = $materials['description'];
        
        // Medienart
        $db = db_query("SELECT * FROM nt_fertigkeiten WHERE code = " . $media['topic2']);
        if($db)
        {
          $skills = mysqli_fetch_assoc($db);
          $skill = $skills['description'];
        }
        else
          $skill = 'KEINE';
          
        // Medienart
        $languages = mysqli_fetch_assoc(db_query("SELECT * FROM nt_sprachen WHERE code = " . $media['topic3']));
        $language = $languages['description'];
        
        // Genre
        $collections = mysqli_fetch_assoc(db_query("SELECT * FROM collection_dm WHERE code = " . $media['collection_cd']));
        $collection = $collections['description'];
        
        // Link zusammenstellen um immer die gleichen Suchergebnisse zu behalten
        $param = "";
        if(isset($_GET['search']))
            $param .= '&search=' . $_GET['search'];
        if(isset($_GET['skey']))
            $param .= '&skey=' . $_GET['skey'];
        if(isset($_GET['sel_language']) && (int)$_GET['sel_language'] > 0)
            $param .= '&sel_language=' . $_GET['sel_language'];
        if(isset($_GET['sel_location']) && (int)$_GET['sel_location'] > 0)
            $param .= '&sel_location=' . $_GET['sel_location'];
        
        // Ausgabe
        printf("<tr><td>
                        <a href='?bibid=%s&show=1%s'>Bearbeiten</a><br>
                        <a class='ok' href='?bibid=%s&ok=1%s'>&Uuml;bernehmen</a><br>
                        <a class='error' href='?bibid=%s&del=1%s' onclick='return confirm(\"Wirklich l&ouml;schen?\")'>L&ouml;schen</a>
                    </td>
                <td>%s</td><td>%s</td><td>%s</td><td>%s</td>
                <td>%s</td><td>%s</td><td>%s</td><td>%s</td>
                <td>%s</td><td>%s</td><td>%s</td></tr>
               
              
              
                ",
                $media['bibid'], $param, $media['bibid'], $param, $media['bibid'], $param,
                $media['author'], $media['title'], $material, 
                $collection, $location, 
                $media['call_nmbr2'], // <td>%s</td> -> $media['call_nmbr3'], 
                $media['topic1'], 
                $skill, $language, $media['topic4'], 
                $media['topic5']);
       echo" <tr><td colspan=\"12\"><div  class=\"deviderVertical\"></div></td></tr>";
    }
    // Anzeige, wie viele Datensätze ausgegeben wurden
    // JUST DEBUG
    printf("<tr><td>Beeintr&auml;chtige Datens&auml;tze: %s</td></tr>", $numrows);
?>
</table></form>


<?php 
    printf("<div id='error_msg'><p style='font-color:red;font-size: 1.5em;'>%s</p></div>",$msg);
    include("../shared/footer.php"); 
?>
