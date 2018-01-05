<?php
/**
* autor:
*
* date:
*
**/

//
// Tabellen für Medienliste komplett löschen
// Tabellenstrukturen bleiben erhalten
//
function db_medialist_clear()
{
    db_query("TRUNCATE TABLE temp_biblio");
    db_query("TRUNCATE TABLE temp_biblio_copy");
    db_query("TRUNCATE TABLE temp_biblio_field");
    
    db_query("TRUNCATE TABLE biblio");
    db_query("TRUNCATE TABLE biblio_copy");
    db_query("TRUNCATE TABLE biblio_field");
    db_query("TRUNCATE TABLE biblio_skills");
}

// Daten aus alter DB übertragen in die neuen temporären Tabellen (Medienliste)
// Quelle: sql backup files
function db_medialist_update()
{
    //$sql = @file("./db_sql/001/member.sql");
    //foreach($sql as $line)
    //{
    //    if(strpos($line, "INSERT INTO") === 0) db_query(utf8_decode($line));
    //}
    $sql = @file("./db_sql/001/temp_biblio.sql");
    foreach($sql as $line)
    {
        if(strpos($line, "INSERT INTO") === 0) db_query(utf8_decode($line));
    }
    $sql = @file("./db_sql/001/temp_biblio_copy.sql");
    foreach($sql as $line)
    {
        if(strpos($line, "INSERT INTO") === 0) db_query(utf8_decode($line));
    }
    $sql = @file("./db_sql/001/temp_biblio_field.sql");
    foreach($sql as $line)
    {
        if(strpos($line, "INSERT INTO") === 0) db_query(utf8_decode($line));
    }
}

// Trennen der Schlagwörte mit ';'
// ungültige Einträge werden nicht umgewandelt
function db_seperate_keywords($keywords)
{
    $keywords = str_replace(array(" ", "-", "/", ",", "'", '"'), ";", $keywords);
    $keywords = preg_replace ('/[;]+/' , ';' , $keywords);
    $keywords = trim($keywords, ' ;.,:-()');
    //$keywords = str_replace(array('"', "'"), "", $row['topic1']);
            
    $keywordlist = explode(";", $keywords);
    
    if(count($keywordlist) >= 1)
    {
        //$msg .= sprintf("<p>Laenge: %s</p>", count($keywords)); // --- DEBUG
        for($i = 0; $i < count($keywordlist); $i++)
        {
            // Schlagwörter die weniger als 3 Buchstaben haben, 
            // werden verworfen.
            // Weiterhin folgende Schlagwörter auch:
            if( strlen($keywordlist[$i]) <= 2 or 
                $keywordlist[$i] == "mit" or $keywordlist[$i] == "als" or
                $keywordlist[$i] == "N" or $keywordlist[$i] == "N.N" or
                $keywordlist[$i] == "und" or $keywordlist[$i] == "oder" or
                $keywordlist[$i] == "auf")
                unset($keywordlist[$i]);
        }
        //$msg .= sprintf("<p>NEU: %s</p>", join(";", $keywords)); // --- DEBUG
        
        // Array neu indiziert
        $keywordlist = array_values($keywordlist);
        // Einträge mit mehr als 5 Schlagwörtern werden verworfen
        // und mit " " verbunden
        // Sonst: Trennung mit ";"
        if(count($keywordlist) > 5)
            $keywords = join(" ", $keywordlist);
        else
            $keywords = join(";", $keywordlist);
        
        $keywords = preg_replace('/[;]+/' ,';' , $keywords);
        $keywords = trim($keywords, ' ;.,:-()');
    }
    
    // Rückgabe des normalisierten Eintrags
    return $keywords;
}

// *********************************************************************
// * Bis hier sicher !
// *********************************************************************


//
// Tabellen werden gefiltert und nach bestimmten
// Kriterien aufbearbeitet
// Aufruf durch: Button(Aussortieren)
//
function db_clear_data()
{
    global $msg; // Fehlerausgabe: DEBUG
    
    // Updaten der Standorte
    db_query("UPDATE temp_biblio SET call_nmbr1 = '1880' 
                WHERE   call_nmbr1 LIKE '2880%' 
                    OR  call_nmbr1 LIKE 'Neues%'
                    OR  call_nmbr1 = 'NP'
                    OR  call_nmbr1 LIKE '1880%' ");
                    
    db_query("UPDATE temp_biblio SET call_nmbr1 = '3880' 
                WHERE   call_nmbr1 LIKE 'Grieb%'
                    OR  call_nmbr1 LIKE 'GS%' 
                    OR  call_nmbr1 LIKE '3880%' ");
                    
    db_query("UPDATE temp_biblio SET call_nmbr1 = '1870' 
                WHERE   call_nmbr1 LIKE '1870%' ");
                
    db_query("UPDATE temp_biblio SET call_nmbr1 = '1350' 
                WHERE   call_nmbr1 LIKE '1350%' ");
                
    db_query("UPDATE temp_biblio SET call_nmbr1 = '3870' 
                WHERE   call_nmbr1 LIKE '3870%' ");
                
    // NiveaStufen anpassen
    // Alle NiveauStufen => A1-C2
    db_query("UPDATE temp_biblio SET topic5 = 'A1-C2' 
                WHERE   topic5 LIKE 'alle Niveaustufen' ");
    
    // Fertigkeiten anpassen
    // "Bezeichnung" => ID (Zuordnung über nt_fertigkeiten Tabelle)
    db_query("UPDATE temp_biblio, nt_fertigkeiten
                SET temp_biblio.topic2 = nt_fertigkeiten.code
                WHERE temp_biblio.topic2 = nt_fertigkeiten.description");

    // Sprachen anpassen
    // "Bezeichnung" => ID (Zuordnung über nt_sprachen Tabelle)        
    db_query("UPDATE temp_biblio, nt_sprachen
                SET temp_biblio.topic3 = nt_sprachen.code
                WHERE temp_biblio.topic3 = nt_sprachen.description");
    
    //
    // Schlagwörter durch Semikolon seperieren
    //
    $rows = db_query("SELECT bibid, topic1 FROM temp_biblio");
    //$msg .= "<p>DEBUG ==> <b>Zeilen: ".mysqli_affected_rows()."</b></p>"; // DEBUG $msg Var
    while($row = mysqli_fetch_assoc($rows))
    {
        $keywords = db_seperate_keywords($row['topic1']);
        //echo "<br>DEBUG ==> <b>Keywords: ".$keywords."</b><br>"; // --- DEBUG
        db_query("UPDATE temp_biblio SET topic1 = '" . $keywords . "' 
                  WHERE  bibid = " . $row['bibid']);
    }
    
    // leere (NULL) Eintraege aus der temp_biblio_field loeschen
    db_query("DELETE FROM temp_biblio_field WHERE field_data IS NULL");
    
    // Link zur Medienseite aktualisieren
    db_query("UPDATE temp_biblio_field SET subfield_cd = 'u' 
              WHERE tag = 130 AND subfield_cd = 'h'");
}

//Systematik aktualisieren -> Signatur update
function update_systematic_signatur($bibid) 
{
    $db = db_query(sprintf("SELECT call_nmbr2 FROM temp_biblio WHERE bibid = %s", $bibid));
    $row = mysqli_fetch_assoc($db);
    $signatur = explode('.', $row['call_nmbr2']);

    if(count($signatur) == 4)
    {
      $db = db_query(sprintf("SELECT count(*) as count FROM nt_systematik_signatur 
                              WHERE category = %s AND sub_category = %s", $signatur[0], $signatur[2]));
      if($db)
        $count = mysqli_fetch_assoc($db);
      else
        $count['count'] = 0;
    }
    if(count($signatur) != 4 || (int)$count['count'] == 0)
    {
      // Fehler Systematik: "Sontiges - Keine Systematik"
      db_query(sprintf("UPDATE temp_biblio SET collection_cd = '44' WHERE bibid = %s", $bibid));
    }
    else
    {
        $sql = sprintf("SELECT code FROM nt_systematik_signatur 
                        WHERE category = %s AND sub_category = %s
                        ", $signatur[0], $signatur[2]);
        $db = db_query($sql);
        $row = mysqli_fetch_assoc($db);
        $newCollectionID = $row['code'];
        
        db_query(sprintf("UPDATE temp_biblio SET collection_cd = '%s' WHERE bibid = %s", $row['code'], $bibid));
        db_query(sprintf("UPDATE temp_biblio SET call_nmbr2 = '%s' WHERE bibid = %s",
                          $signatur[3], $bibid));
    }
}

//db_query(sprintf("UPDATE temp_biblio SET call_nmbr2 = '%s.%s' WHERE bibid = %s", $signatur[2], $signatur[3], $bibid));
//
//
function acceptBiblioEntry($bibid)
{
    // Code-Abfrage für Standort
    $db = db_query(sprintf("SELECT code FROM locations 
                            WHERE location_number = (
                                SELECT call_nmbr1 FROM  temp_biblio WHERE bibid=%s
                            )", $bibid));
    $row = mysqli_fetch_assoc($db);
    $locationId = $row['code'] ? $row['code'] : 0;
    
    $db = db_query(sprintf("SELECT topic2 FROM temp_biblio 
                            WHERE bibid = %s", $bibid));
    if($db) {
        $row = mysqli_fetch_assoc($db);
        $skillId = $row['topic2'];
    } else {
        $skillId = '1';
    }
    
    // biblio-Eintrag übernehmen
    db_query("  INSERT INTO `biblio`
                (`create_dt`, `last_change_dt`, `last_change_userid`, 
                 `material_cd`, `collection_cd`, `call_nmbr1`, `call_nmbr2`, 
                 `call_nmbr3`, `title`, `title_remainder`, `responsibility_stmt`, 
                 `author`, `topic1`, `topic2`, `topic3`, `topic4`, `topic5`, `opac_flg`) 
                SELECT  temp_biblio.`create_dt`, NOW(), 
                        ".$_SESSION['userid'].", temp_biblio.`material_cd`, 
                        temp_biblio.`collection_cd`, '".$locationId."', 
                        temp_biblio.`call_nmbr2`, temp_biblio.`call_nmbr3`, 
                        temp_biblio.`title`, temp_biblio.`title_remainder`, 
                        temp_biblio.`responsibility_stmt`, temp_biblio.`author`, 
                        temp_biblio.`topic1`, temp_biblio.`topic2`, temp_biblio.`topic3`, 
                        NULL, temp_biblio.`topic5`, temp_biblio.`opac_flg` 
                FROM temp_biblio
                WHERE temp_biblio.bibid = ".$bibid);
    
    $newBibid = mysqli_insert_id();
    
    // Skills eintragen in neue Tabelle biblio_skills
    db_query("  INSERT INTO biblio_skills
                (bibid, hearing_skill, speak_skill, write_skill, grammar_skill, read_skill)
                SELECT ".$newBibid.", tr_skill_hear, tr_skill_speak, tr_skill_write, tr_skill_grammar, tr_skill_read
                FROM nt_fertigkeiten_tr tr
                WHERE tr.code = ".$skillId);
    
    // Alle Kopien mit neuer Bibid eintragen
    db_query('  INSERT INTO `biblio_copy`
                (`bibid`, `copyid`, `create_dt`, `copy_desc`, `barcode_nmbr`, 
                 `status_cd`, `status_begin_dt`, `due_back_dt`, `mbrid`, 
                 `renewal_count`) 
                SELECT '.$newBibid.', temp_biblio_copy.`copyid`, temp_biblio_copy.`create_dt`, 
                temp_biblio_copy.`copy_desc`, temp_biblio_copy.`barcode_nmbr`,
                temp_biblio_copy.`status_cd`, temp_biblio_copy.`status_begin_dt`, 
                temp_biblio_copy.`due_back_dt`, temp_biblio_copy.`mbrid`, 
                temp_biblio_copy.`renewal_count`
                FROM temp_biblio_copy
                WHERE bibid = '.$bibid);
    
    // Alle Medieneigenschaften aus altem Opac übernehmen
    db_query("  INSERT INTO `biblio_field` 
                (`bibid` , `fieldid` , `tag` , `ind1_cd` , `ind2_cd` , 
                 `subfield_cd` , `field_data` )
                SELECT ".$newBibid." , `fieldid` , `tag` , `ind1_cd` , `ind2_cd` , `subfield_cd` , `field_data`
                FROM temp_biblio_field
                WHERE bibid = ".$bibid);

    db_query("DELETE FROM temp_biblio WHERE bibid = ".$bibid);
    db_query("DELETE FROM temp_biblio_copy WHERE bibid = ".$bibid);
    db_query("DELETE FROM temp_biblio_field WHERE bibid = ".$bibid);
    
    return $newBibid;
}

function db_correct_entries()
{
    $medialist = db_query("
        SELECT bibid FROM temp_biblio 
        WHERE    call_nmbr2 <> ''
            AND  call_nmbr2 IS NOT NULL
            AND  call_nmbr2 LIKE '%.%.%.%'
            AND  title <> ''
            AND  title IS NOT NULL
            AND  author <> ''
            AND  author IS NOT NULL
            AND  call_nmbr1 IN ('1880', '3880')
            AND  topic3 NOT LIKE 'C%t%l%'
            
            ");
    
    // Datensätze die dem Test standhalten, werden in die richtige
    // Tabelle biblio übernommen:
    while($row = mysqli_fetch_assoc($medialist))
    {
        update_systematic_signatur($row['bibid']);
        acceptBiblioEntry($row['bibid']);
    }
}

/*
 * Sicherheitskopie der Ähnlichkeitssuche
 * Vorlage kann verwendet werden um selbige zu implementieren
 * Ist noch nicht fertig -> nur Ansatz!
 * 
//
// Ähnlichkeitssuche
//
if(isset($_GET['luckySearch']) && isset($_GET['skey']) && $_GET['skey'] != "")
{
  $medialist = db_query("SELECT * FROM temp_biblio");
  $perc = 0;
  $SIMILAR = 60;
  $key = $_GET['skey'];
  
  while($media = mysqli_fetch_assoc($medialist))
  {
      $percs = array();
      $words = explode(" ", $media['title']);
      for($i = 0; $i < count($words); $i++)
      {
          similar_text($key, $words[$i], $perc);
          $percs[] = $perc;
          
      }
      //if(max($percs) > 80.0)
      //    printf("Vergleich von: %s und %s => %s<br>", $key, $media['title'], max($percs));
      if(max($percs) > 80.0)
      {
          // = Formatierung =
          // Standort
          $location = $media['call_nmbr1'];
          if($media['call_nmbr1'] == "1880")
              $location = "<span style='color: green'>NP</span>";
          elseif($media['call_nmbr1'] == "3880")
              $location = "<span style='color: green'>GS</span>";
          elseif($media['call_nmbr1'] == "1870")
              $location = "<span style='color: green'>SEP H</span>";
          elseif($media['call_nmbr1'] == "3870")
              $location = "<span style='color: green'>Stud+</span>";
              elseif($media['call_nmbr1'] == "1350")
              $location = "<span style='color: green'>SEP</span>";
              
          // Medienart
          $materials = mysqli_fetch_assoc(db_query("SELECT * FROM material_type_dm WHERE code = " . $media['material_cd']));
          $material = $materials['description'];
          
          // Genre
          $collections = mysqli_fetch_assoc(db_query("SELECT * FROM collection_dm WHERE code = " . $media['collection_cd']));
          $collection = $collections['description'];
          
          // Ausgabe        
          printf("<tr><td>
                          <a href='?bibid=%s'>Bearbeiten</a><br>
                          <a href=''>&Uuml;bernehmen</a>
                      </td>
                  <td>%s</td><td>%s</td><td>%s</td><td>%s</td>
                  <td>%s</td><td>%s</td><td>%s</td><td>%s</td>
                  <td>%s</td><td>%s</td><td>%s</td><td>%s%%</td></tr>",
                  $media['bibid'],
                  $media['author'], $media['title'], $material, 
                  $collection, $location, 
                  $media['call_nmbr2'],
                  $media['topic1'], 
                  $media['topic2'], $media['topic3'], $media['topic4'], 
                  $media['topic5'], max($percs));
      }
  }
}
*/



?>
