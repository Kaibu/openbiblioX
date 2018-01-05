<?php
/**
 * autor: **REMOVED**
 * date: 2013-01-05
 *
 **/


//TODO convert to define and refactor usage
$BIBLIO = "biblio";
$COPY = "biblio_copy";
//

// ---------------------------------------------------------------------
// ------------------- Datenbank Funktionen ----------------------------
// ---------------------------------------------------------------------

/**
 * Main function used everywhere where non Class based query's are used
 * Uses Query::queryDb()
 * @param string|$sql - Query String
 * @return bool|mysqli_result - false on error, result on success
 */
function db_query($sql)
{
    $query = new Query();
    $result = $query->queryDb($sql);

    if (!$result) {
        return false;
    }
    return $result;
}

/**
 * Function to escape data before it is passed to the DB
 * @param mixed|$val whatever data should be escaped
 * @return string - escaped data
 */
function db_escape($val)
{
    $query = new Query();
    $val = $query->escape_data($val);
    return $val;
}

// ---------------------------------------------------------------------
// --------------- Many undocumented functions -------------------------
// ---------------------------------------------------------------------
//TODO document them all (and probably test/clean/delete)

//careful wilderness starts here :

/*
 * Medien 
 * insert, update, delete
 * 
 * nt_fertigkeiten, nt_sprachen, nt_sprachniveau
 *  
 */
/*katalogisierungs funktion --> neues medium anlegen im aufbau*/
function insert_medium($data)
{
    $data[0] = date('YmdHis', time());
    $data[1] = date('YmdHis', time());
    $data[2] = "2"; // User id funkrion muss noch geschrieben werden
    db_query("INSERT INTO biblio(create_date,last_change_date,last_change_userid,material_cd,collection_cd,call_nmbr1,call_nmbr2,call_nmbr3,title,title_reminder,responsibility_stmt,author,topic1,topic2,topic3,topic4,topic5,opac_flg)
	VALUES ($data[0],'$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13],$data[14],$data[15],$data[16],$data[17],$data[18])");
}

//
// 
//
function db_show_biblio()
{
    global $BIBLIO;
    $tabelle = "<table id=\"user_table\" style=\"font-size:10px;\" border=\"1\">\n";
    $result = db_query("SELECT * FROM $BIBLIO LIMIT 10");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        foreach ($row as $name => $val) {
            $tabelle .= "<th>$name</th>";
        }
        while ($row) {
            $tabelle .= "<tr>\n\t";
            foreach ($row as $val) {
                if (!$val) {
                    $val = '-';
                }
                $tabelle .= "<td>" . $val . "</td>";
            }
            $tabelle .= "\n</tr>\n";
            $row = mysqli_fetch_assoc($result);
        }
    }
    $tabelle .= "</table>\n";
    return $tabelle;
}

function db_show_biblio_value($id)
{
    global $BIBLIO;
    $sql = db_query("SELECT * FROM $BIBLIO WHERE call_nmbr3 = '$id' ");
    //echo $sql;

    $tabelle = "<fieldset><table>";//ausgeben als tabelle wo stärke =1
    $tabelle .= "<tr><th>Aktion</th><th>bibid</th><th>create_td</th><th>last_change_dt</th><th>last_change_userid</th>
					<th>Materialart</th><th>Genre</th><th>Standort</th><th>Signatur</th><th>Mediennummer</th><th>Title</th>
					<th>title_remainder</th><th>resp_stmt</th><th>Autor</th><th>Schlagw&ouml;rter</th><th>Fertigkeiten</th><th>Sprache</th>
					<th>Zusatz/Abstract</th><th>Niveau</th><th>opac_flg</th></tr>";


    //$c=0;
    $class = "alt1";
    while ($row = mysqli_fetch_object($sql)) {

        //if($c==0){$class="alt1";}
        //if($c==1){$class="alt2";}
        $query = db_query("SELECT bibid FROM biblio WHERE call_nmbr3='$id'");
        if(!$query){
            die("");
        }
        $bibident = mysqli_fetch_array($query);

        $tabelle .= "<tr class=\"" . $class . "\">";
        $tabelle .= "<td><br><a href=\"/bibo/catalog/biblio_edit.php?bibid=$bibident[0]\">bearbeiten</a></td>";
        $tabelle .= "<td>" . $row->bibid . "</td>";
        $tabelle .= "<td>" . $row->create_dt . "</td>";
        $tabelle .= "<td>" . $row->last_change_dt . "</td>";
        $tabelle .= "<td>" . $row->last_change_userid . "</td>";
        $tabelle .= "<td>" . $row->material_cd . "</td>";
        $tabelle .= "<td>" . $row->collection_cd . "</td>";
        $tabelle .= "<td>" . $row->call_nmbr1 . "</td>";
        $tabelle .= "<td>" . $row->call_nmbr2 . "</td>";
        $tabelle .= "<td>" . $row->call_nmbr3 . "</td>";
        $tabelle .= "<td>" . $row->title . "</td>";
        $tabelle .= "<td>" . $row->title_remainder . "</td>";
        $tabelle .= "<td>" . $row->responsibility_stmt . "</td>";
        $tabelle .= "<td>" . $row->author . "</td>";
        $tabelle .= "<td>" . $row->topic1 . "</td>";
        $tabelle .= "<td>" . $row->topic2 . "</td>";
        $tabelle .= "<td>" . $row->topic3 . "</td>";
        $tabelle .= "<td>" . $row->topic4 . "</td>";
        $tabelle .= "<td>" . $row->topic5 . "</td>";
        $tabelle .= "<td>" . $row->opac_flg . "</td>";

        $tabelle .= "</tr>";
        if ($class == "alt1") {
            $class = "";
            $class = "alt2";
        } else {
            $class = "";
            $class = "alt1";
        }

    }
    $tabelle .= "</table></fieldset>";

    return $tabelle;
}

/*********************************Tabelle******************anzeige der Personen die Exemplar ausgeliehen haben***********************************/

function lendedExemplares($bibid, $copyid)
{

    $value = db_query("SELECT mbrid FROM biblio_copy WHERE bibid = '$bibid' and copyid ='$copyid' ");
    if(!$value){
        die("ERRO: lendedExemplares");
    }
    $query = mysqli_fetch_assoc($value);
    $mbrid = $query['mbrid'];

    return $mbrid;

}

function list_slogans()
{
    $sql = db_query('SELECT DISTINCT topic1 FROM biblio WHERE topic1 !=""');

    while ($list = mysqli_fetch_assoc($sql)) {
        foreach (explode(";", $list['topic1']) as $item) {
            $slogan_list[] = $item;
        }
    }
    $slogan_list = array_unique($slogan_list);
    sort($slogan_list);
    $slogan_list = implode(";", $slogan_list);
    $slogan_list = addslashes($slogan_list);
    return $slogan_list;
}


function search_slogans($input, $liste)
{
    $slogan_matches = "";
    if ($input != "") {

        foreach ($liste as $slogans) {

            $pattern = "/^" . $input . "/i";
            if (preg_match($pattern, $slogans)) {
                //echo "<option value='". $slogans."</option>";
                $slogan_matches .= $slogans . ";";
            }
        }
    }
    return $slogan_matches;
}


/* die funktion nimmt sich die descriptionfelder einer Tabelle und gibt sie als Select ->option aus */
function list_description($table)
{
    $table = db_escape($table);
    $value = db_query("SELECT code, description FROM $table");
    if (!$value) {
        die("Fehler");
    }

    while ($row = mysqli_fetch_array($value)) {
        echo "<option value='" . $row['code'] . "'>" . $row['description'] . "</option>";

    }
}

/*************gibt die slogans als options aus**************************************/
function list_matches($matches)
{
    foreach ($matches as $slogans) {
        echo "<option value='" . $slogans . "'></option>";
    }
}

/***************gibt einen Spalten wert für Primerykey wieder bzw. in WeakEntity Spalte+bibid+Spalte => Ergebnis************************/
function list_values_from_Table1($table, $bibid, $column)
{
    $table = db_escape($table);
    $bibid = db_escape($bibid);
    $column = db_escape($column);

    $value = db_query("SELECT $column FROM $table WHERE bibid = '$bibid'");
    if (!$value) {
        die("ERROR: list_values_from_Table1");
    }
    $query = mysqli_fetch_array($value);

    return stripslashes($query[0]);
}

function list_copy__desc($table, $barcode, $column)
{
    $table = db_escape($table);
    $barcode = db_escape($barcode);
    $column = db_escape($column);

    $value = db_query("SELECT $column FROM $table WHERE barcode_nmbr = '$barcode' ");
    if (!$value) {
        die("ERROR: list_copy__desc");
    }
    $query = mysqli_fetch_array($value);

    return $query[0];
}

/**************listet ausgeliehende Medien auf***********************/
function listLendedStatus($bibid, $copyId)
{
    $copyId = db_escape($copyId);
    $bibid = db_escape($bibid);

    $value = db_query("SELECT status_cd FROM biblio_copy WHERE bibid = '$bibid' AND copyid = '$copyId' ");
    if (!$value) {
        die("ERROR: listLendedStatus");
    }
    $query = mysqli_fetch_assoc($value);
    $status_cd = $query['status_cd'];

    return $status_cd;
}


function list_values_from_Table2($table, $bibid, $tag, $subfield_cd)
{
    $table = db_escape($table);
    $bibid = db_escape($bibid);
    $tag = db_escape($tag);
    $subfield_cd = db_escape($subfield_cd);

    $value = db_query("SELECT field_data FROM $table WHERE bibid = '$bibid' AND tag = '$tag' AND subfield_cd = '$subfield_cd' ");
    if (!$value) {
        die("ERROR: list_values_from_Table2");
    }

    $query = mysqli_fetch_array($value);
    if ($query[0] == "") {
        $query[0] = null;
    }
    return stripslashes($query[0]);
}

/* gibt Beschreibung zurück */
function listDescription($table, $code)
{
    $table = db_escape($table);
    $code = db_escape($code);

    $value = db_query("SELECT description FROM $table WHERE Code = '$code' ");

    if (!$value) {
        die("ERROR: listDescription");
    }

    $query = mysqli_fetch_array($value);

    return $query[0];
}

function listDescriptionSubfield($tag, $subfield)
{
    $tag = db_escape($tag);
    $subfield = db_escape($subfield);


    $value = db_query("SELECT description FROM usmarc_subfield_dm WHERE tag = '$tag' AND subfield_cd = '$subfield' ");
    if (!$value) {
        die("ERROR: listDescriptionSubfield");
    }
    $query = mysqli_fetch_array($value);

    return $query[0];
}

function listFieldData($bibid, $tag, $subfield)
{

    $bibid = db_escape($bibid);
    $subfield = db_escape($subfield);
    $tag = db_escape($tag);

    $value = db_query("SELECT field_data FROM biblio_field WHERE tag = '$tag' AND subfield_cd = '$subfield' AND bibid = '$bibid' ");
    if (!$value) {
        die("ERROR: listFieldData");
    }
    $query = mysqli_fetch_array($value);

    return $query[0];
}

//
// OptionFelder mit Selection als String erzeugen
//
// MaterialListe:
// $name: Name der SelectionListe, $checked: ID des vorselektierten Eintrags
function db_code_sel($name, $sql, $checked = -1)
{
    $out = "<option value='0'>--- Bitte auswaehlen ---</option>";
    $rows = db_query($sql);

    while ($row = mysqli_fetch_assoc($rows)) {
        if ($checked >= 0 && (int)$row['code'] == $checked)
            $out .= sprintf("<option value='%s' selected='selected'>%s</option>",
                $row['code'], $row['description']);
        else
            $out .= sprintf("<option value='%s'>%s</option>",
                $row['code'], $row['description']);
    }
    return sprintf("<select name='%s'>%s</select>", $name, $out);
}

function db_material_sel($name, $checked = -1)
{
    $sql = "SELECT code, description FROM material_type_dm";
    return db_code_sel($name, $sql, $checked);
}

function db_collection_sel($name, $checked = -1)
{
    $sql = "SELECT co.code, co.description, sig.category, sig.sub_category 
            FROM collection_dm co, nt_systematik_signatur sig
            WHERE co.code = sig.code";

    $out = "<option value='0'>-- Bitte auswaehlen ---</option>";
    $rows = db_query($sql);

    while ($row = mysqli_fetch_assoc($rows)) {
        if ($checked >= 0 && (int)$row['code'] == $checked)
            $out .= sprintf("<option value='%s' selected='selected'>%s.%s.%s %s</option>",
                $row['code'], $row['category'], $row['category'],
                $row['sub_category'], $row['description']);
        else
            $out .= sprintf("<option value='%s'>%s.%s.%s %s</option>",
                $row['code'], $row['category'], $row['category'],
                $row['sub_category'], $row['description']);
    }
    return sprintf("<select name='%s'>%s</select>", $name, $out);
}

function db_location_sel($name, $checked = -1)
{
    $sql = "SELECT location_number AS code, description FROM locations";
    return db_code_sel($name, $sql, $checked);
}

function db_skills_sel($name, $checked = -1)
{
    $sql = "SELECT DISTINCT code, description FROM nt_fertigkeiten ORDER BY code";
    return db_code_sel($name, $sql, $checked);
}

function db_language_sel($name, $checked = -1)
{
    $sql = "SELECT DISTINCT code, description FROM nt_sprachen ORDER BY code";
    return db_code_sel($name, $sql, $checked);
}

/**
 * @param string $name - name of input to create
 * @param string $val - selected value
 * @param string $attr - string that gets pasted into <select> tag
 * @return string - select input with niveau code/descriptions
 */
function db_niveau_sel($name, $val = "",$attr = "")
{
    $sql = "SELECT code,description FROM nt_niveau ORDER BY code";
    $out = "";
    $rows = db_query($sql);

    if($val == ""){
        $out = "<option value='-'>-</option>";
    }

    while ($row = mysqli_fetch_assoc($rows)) {
        if ($row['code'] != $val) {
            $out .= sprintf("<option value='%s' >%s</option>", $row['code'], $row['description']);
        } else {
            $out .= sprintf("<option value='%s' selected='selected'>%s</option>",$row['code'], $row['description']);
        }
    }

    return sprintf("<select name='%s' ".$attr.">%s</select>", $name, $out);
}

function db_physics_sel($name, $checked = -1)
{
    $sql = "SELECT code, description FROM nt_physics ORDER BY code";
    return db_code_sel($name, $sql, $checked);
}

function db_opacflg_sel($name, $checked = "")
{
    $out = "";
    $sql = "SELECT DISTINCT opac_flg AS description FROM biblio";
    $rows = db_query($sql);
    while ($row = mysqli_fetch_assoc($rows)) {
        if ($checked != "" && $row['description'] == $checked)
            $out .= sprintf("<option value='%s' selected='selected'>%s</option>",
                $row['description'], $row['description']);
        else
            $out .= sprintf("<option value='%s'>%s</option>",
                $row['description'], $row['description']);
    }
    return sprintf("<select name='%s'>%s</select>", $name, $out);
}

/*************abgewandelt **********************/
function db_location_sel2($name, $checked = -1)
{
    $sql = "SELECT code, description FROM locations";
    return db_code_sel($name, $sql, $checked);
}


/****************************INSERT Funktion für Secure_copy_FLG == on ****************Folgende daten sollen nicht übernommen werde ISBN, Verlag, Erscheinungsort, Erscheinungsjahr
 *  mediennummer wird vom OPAC kreiert (6-stellig inkrement (Max(bibid)))**************************************************************************************************************/


function catalog_insert_secure_copy()
{
}

//
//

//Hauptkategorien query erstellen

function getCategoryList()
{
    $query = "SELECT code, description FROM nt_systematik_main_category ORDER BY code ASC";
    $rows = db_query($query);
    while ($row = mysqli_fetch_assoc($rows)) {
        $array[$row['code']] = $row['description'];
    }
    return $array;
}

/***************Systematik/Genre joinabfrage zeigt signatur und systematik****************************************************************************************************/


function getSystematicNumbers()
{
    $query = "SELECT sig.category AS main_sig, sig.sub_category AS sub_sig, main_cat.description AS main_category, sub_cat.description AS sub_category , sub_cat.code 
			FROM collection_dm sub_cat, nt_systematik_main_category main_cat, nt_systematik_signatur sig
			WHERE sub_cat.code = sig.code   AND sig.category= main_cat.code 
            ORDER BY main_sig ASC, sub_sig ASC";


    return $query;
}

/***************************************/


/*************************return in selectfeld von suchanfrage $name -> neuer name des selectfeldes**************************************************************************/

function listQuery($sql, $name, $checked = -1,$attr = "")
{
    $out = "<option value='0'>-- Bitte auswaehlen ---</option>";
    $rows = db_query($sql);


    while ($row = mysqli_fetch_assoc($rows)) {
        if ($row['sub_sig'] == 1) {
            $out .= sprintf("<option disabled='disabled' value='%s'> %s.%s</option>",
                $row['main_sig'], $row['main_sig'], $row['main_category']);

            $systematic = $row['main_sig'] . "." . $row['sub_sig'];
            if ($systematic == $checked) {
                $out .= sprintf("<option value='%s.%s' selected='selected'>&nbsp;&nbsp;&nbsp;%s</option>",
                    $row['main_sig'], $row['sub_sig'], $row['sub_category']);
            } else {

                $out .= sprintf("<option value='%s.%s'>&nbsp;&nbsp;&nbsp;%s</option>",
                    $row['main_sig'], $row['sub_sig'], $row['sub_category']);
            }
        } else {
            $systematic = $row['main_sig'] . "." . $row['sub_sig'];

            if (strcmp($systematic, $checked) == 0) //if($systematic == $checked) ////<---old errors while sub_category greater 10 ???
            {
                $out .= sprintf("<option value='%s.%s' selected='selected'>&nbsp;&nbsp;&nbsp;%s</option>",
                    $row['main_sig'], $row['sub_sig'], $row['sub_category']);
            } else {
                $out .= sprintf("<option value='%s.%s'>&nbsp;&nbsp;&nbsp;%s</option>",
                    $row['main_sig'], $row['sub_sig'], $row['sub_category']);
            }
        }
        $onchange = sprintf(" onchange=\"listSignature(document.getElementById('%s').getElementsByTagName('option')[document.getElementById('%s').selectedIndex].value,'start_sig')\"", $name, $name);
        //$onchange="";
    }
    return sprintf("<select id='%s' name='%s' ".$attr." %s>%s</select>", $name, $name, $onchange, $out);
}

/*****************************************************************************************************************************************************************************/


function db_code_sel2($name, $sql, $checked)
{
    $out = "<option value='0'>-- Bitte auswaehlen ---</option>";
    $rows = db_query($sql);
    /*
     * Sortierung A->Z
     */
    $rows = sort($rows);


    while ($row = mysqli_fetch_assoc($rows)) {
        if ($checked > 0 && (int)$row['code'] == $checked)
            $out .= sprintf("<option value='%s' selected='selected'>%s</option>",
                $row['code'], $row['description']);
        else
            $out .= sprintf("<option value='%s'>%s</option>",
                $row['code'], $row['description']);
    }
    return sprintf("<select name='%s'>%s</select>", $name, $out);
}


/********************biblio view functions*****************************************/

/*hauptkategorie (description)der systematik ausgeben lassen*/

function getSystematicMainCategory($bibid)
{
    $bibid = db_escape($bibid);
    $sql = "SELECT main.description FROM nt_systematik_signatur sys, biblio bib, nt_systematik_main_category main WHERE
			bibid='$bibid' AND bib.collection_cd = sys.code AND sys.category = main.code";

    $query = db_query($sql);
    if(!$query){
        die("ERROR: getSystematicMainCategory");
    }
    $query = mysqli_fetch_array($query);
    return $query[0];
}

/**
 * @param $bibid
 * @return array|bool|mysqli_result|null
 * @deprecated
 */
function getSpecificSystematicNumbers($bibid)
{
    $bibid = db_escape($bibid);
    $sql = "SELECT sig.category, sig.sub_category from biblio bib, nt_systematik_signatur sig
		  WHERE bib.bibid ='$bibid' and bib.collection_cd = sig.code ";
    $query = db_query($sql);
    if(!$query){
        die("ERROR: getSpecificSystematicNumbers");
    }
    $query = mysqli_fetch_array($query);
    return $query;
}

/**
 * @param $bibid
 * @return array|bool|mysqli_result|null
 * @deprecated
 */
function getSystematicMainSubNumbers($bibid)
{
    $bibid = db_escape($bibid);
    $sql = "SELECT sys.category, sys.sub_category FROM biblio bib, nt_systematik_signatur sys WHERE
			bib.bibid='$bibid' AND bib.collection_cd = sys.code";
    $query = db_query($sql);
    if(!$query){
        die("ERROR: getSystematicMainSubNumbers");
    }
    $query = mysqli_fetch_array($query);
    return $query;
}


/**
 * funktion für die Systematiksuche erwartet String in From von bsp.: 4.4.2.1
 * @param $systematic
 * @return array|string
 */
function getCallNmbr2BySystematic($systematic)
{
    $regexp = '/^([0-9]){1,2}\.([0-9]){1,2}\.([0-9]){1,2}\.([0-9])?$/';

    if (preg_match($regexp, $systematic)) {
        $systematic = db_escape($systematic);
        $sysArray = explode('.', $systematic);
        $sql = "select sys.code from nt_systematik_signatur sys 
			where sys.category ='$sysArray[0]'
			and sys.sub_category ='$sysArray[2]'";
        $result = mysqli_fetch_assoc(db_query($sql));
        $sysArray = $result['code'];

    } else {
        $sysArray = 'null';
    }
    return $sysArray;
}

function getSignaturRest($systematic)
{

    $regexp = '/^([0-9]){1,2}\.([0-9]){1,2}\.([0-9]){1,2}\.([0-9]){1,3}$/';

    if (preg_match($regexp, $systematic)) {
        $sysArray = explode('.', $systematic);
        return $sysArray[3];
    } else {
        return $sysArray = "null";
    }
}

function getNiveauCode($description)
{

    $description = db_escape($description);

    $sql = "SELECT code FROM nt_niveau WHERE description = '$description'";
    $query = db_query( $sql);
    if(!$query){
        die("EROR: getNiveauCode");
    }
    $query = mysqli_fetch_array($query);
    $niveau = $query[0];

    return $niveau;
}

function getNiveauDescription($code)
{
    $code = db_escape($code);

    $sql = "SELECT description FROM nt_niveau WHERE code = '$code'";
    $query = db_query( $sql);
    if(!$query){
        die("ERROR: getNiveauDescription");
    }
    $query = mysqli_fetch_array($query);
    $niveau = $query[0];

    return $niveau;
}

function getCollection_cdBySystematics($sys)
{
    $sys = db_escape($sys);

    $category = explode(".", $sys);
    $sql = "SELECT code FROM  nt_systematik_signatur  WHERE 
			category = '$category[0]' and sub_category = '$category[1]'";
    $query = db_query($sql);
    if(!$query){
        die("ERROR: getCollection_cdBySystematics");
    }
    $query = mysqli_fetch_array($query);

    return $query[0];
}

function getSystematicByCode($code)
{
    $code = db_escape($code);
    $sql = "SELECT category , sub_category from nt_systematik_signatur where code ='$code'";
    $query = db_query( $sql);
    if(!$query){
        die("ERROR: getSystematicByCode");
    }
    $query = mysqli_fetch_array($query);
    $return = $query[0] . "." . $query[1];
    return $return;
}


/*****************************Anzeige von Seeitenlinks und Anzahl sheared/biblio_search.php*********überarbeitet*********************/

function printResultPages2(&$loc, $currPage, $pageCount, $sort)
{
    if ($pageCount <= 1) {
        return false;
    }
    echo $loc->getText("biblioSearchResultPages") . ": "; // anzeige der Anzahl der Ergebnisse
    $maxPg = OBIB_SEARCH_MAXPAGES + 1;
    if ($currPage > 1) {
        echo "<a href=\"javascript:changePage(" . H(addslashes($currPage - 1)) . ",'" . H(addslashes($sort)) . "')\">&laquo;" . $loc->getText("biblioSearchPrev") . "</a> "; // javscript function zum wechesln der Seiten
    }
    for ($i = 1; $i <= $pageCount; $i++) {
        if ($i < $maxPg) {
            if ($i == $currPage) {
                echo "<b>" . H($i) . "</b> ";
            } else {
                if ($i + 4 >= $currPage && $i - 4 <= $currPage)// Range der anzuzeigenden Zahlen
                {
                    echo "<a href=\"javascript:changePage(" . H(addslashes($i)) . ",'" . H(addslashes($sort)) . "')\">" . H($i) . "</a> "; //javscript function zum wechesln der Seiten Zahlen
                }
            }
        } elseif ($i == $maxPg) {
            echo "... ";
        }
    }
    if ($currPage < $pageCount) {
        echo "<a href=\"javascript:changePage(" . ($currPage + 1) . ",'" . $sort . "')\">" . $loc->getText("biblioSearchNext") . "&raquo;</a> "; //javscript function zum wechesln der Seiten NEXT
    }
}


/**************************************************Update-Datenbearbeiten********************************************************************/

function updateBiblioField($bibid, $tag, $subfield_cd, $field_data)
{
    $bibid = db_escape($bibid);
    $tag = db_escape($tag);
    $subfield_cd = db_escape($subfield_cd);
    $field_data = db_escape($field_data);

    $donotUpdate = 0;
    if ($field_data != null and list_values_from_Table2('biblio_field', $bibid, $tag, $subfield_cd) == null) {
        $sql = "INSERT INTO biblio_field (bibid,field_data,tag,subfield_cd) VALUES ('$bibid','$field_data',$tag,'$subfield_cd')";
        $new = db_query( $sql);

        $donotUpdate = 1;
    } elseif ($field_data == null) {

        $sql = "DELETE FROM biblio_field WHERE bibid = '$bibid' AND tag ='$tag' AND subfield_cd ='$subfield_cd' ";
        $new = db_query($sql);

        $donotUpdate = 1;
    }

    if (list_values_from_Table2('biblio_field', $bibid, $tag, $subfield_cd) != null and $donotUpdate == 0) {
        $sql = "UPDATE biblio_field SET field_data ='$field_data' WHERE bibid = '$bibid' AND tag ='$tag' AND subfield_cd ='$subfield_cd' ";
        $new = db_query($sql);
    }

    if(!$new){
        die("ERROR: updateBiblioField");
    }

}


function updateBiblioSkills($bibid, $skills)
{

    $bibid = db_escape($bibid);
    $skills = db_escape($skills);

    $query[0] = "hearing_skill";
    $query[1] = "speak_skill";
    $query[2] = "write_skill";
    $query[3] = "grammar_skill";
    $query[4] = "read_skill";

    $sql = "SELECT  * FROM biblio_skills where bibid  = '$bibid'";
    $result = db_query($sql);
    $alreadyExist = mysqli_fetch_array($result);
    if ($alreadyExist[0] == $bibid) {

        for ($i = 0; $i < count($query); $i++) {
            if ($skills[$i] != list_values_from_Table1("biblio_skills", $bibid, $query[$i])) // wenn eigebener wert ungleich dem in der Datenbank
            {
                $sql = "UPDATE biblio_skills SET " . $query[$i] . " = " . $skills[$i] . " WHERE bibid ='$bibid' ";
                $new = db_query($sql);
                if ($new) {
                    //echo "<br>update set ".$query[$i]." to ".$skills[$i];
                } else {
                    echo "<br> update error";
                }
            }

        }
    } else {
        $sql = " Insert Into biblio_skills Values ('$bibid','$skills[0]','$skills[1]','$skills[2]','$skills[3]','$skills[4]')";
        $query = db_query( $sql);
        if ($query) {
            //echo "<br>insert new data in biblio_skills for medium $bibid";#
        } else {
            echo "<br> insert failed in biblio_fields ";
        }
    }
}

function getSkills($name, $bibid)
{
    $bibid = db_escape($bibid);
    $name = db_escape($name);

    $sql = "SELECT  * FROM biblio_skills where bibid  = '$bibid'";
    $query = db_query( $sql);
    if(!$query){
        die("ERROR: getSkills");
    }
    $query = mysqli_fetch_array($query);

    /*echo "<br>hear: ".*/
    $skills_hear = $query[1];
    /*echo "<br>speak: ".*/
    $skills_speak = $query[2];
    /*echo "<br>write: ".*/
    $skills_write = $query[3];
    /*echo "<br>grammar: ".*/
    $skills_grammar = $query[4];
    /*echo"<br>read: ".*/
    $skills_read = $query[5];

    if ($skills_hear != null) {
        $checked_skills_hear = "checked";
    } else {
        $checked_skills_hear = "";
    }
    if ($skills_speak != null) {
        $checked_skills_speak = "checked";
    } else {
        $checked_skills_speak = "";
    }
    if ($skills_write != null) {
        $checked_skills_write = "checked";
    } else {
        $checked_skills_write = "";
    }
    if ($skills_grammar != null) {
        $checked_skills_grammar = "checked";
    } else {
        $checked_skills_grammar = "";
    }
    if ($skills_read != null) {
        $checked_skills_read = "checked";
    } else {
        $checked_skills_read = "";
    }


    $out = sprintf("H&ouml;ren<input type='checkbox' name='skills_hear' value='1' %s>
	  Sprechen<input type='checkbox' name='skills_speak' value='2' %s> 
	  Schreiben<input type='checkbox' name='skills_write' value='3' %s>
	  Grammatik<input type='checkbox' name='skills_grammar' value='4' %s>
	  Lesen<input type='checkbox' name='skills_read' value='5' %s>", $checked_skills_hear, $checked_skills_speak, $checked_skills_write, $checked_skills_grammar, $checked_skills_read);

    return sprintf("<span name='%s'>%s</span>", $name, $out);


}

/**************************************************BIBLIO VIEW*********************************************************/

function listSkills($bibid)
{
    $bibid = db_escape($bibid);

    $sql = "SELECT  * FROM biblio_skills where bibid  = '$bibid'";
    $query = db_query($sql);
    if(!$query){
        die("ERROR: listSkills");
    }
    $query = mysqli_fetch_array($query);
    $out = "";
    for ($i = 1; $i <= 5; $i++) {


        if ($query[$i] != "NULL") {
            $query_description = listDescription("nt_fertigkeiten_tmp", $query[$i]);
            $out .= sprintf("<tr><td></td><td class='primary' valign='left'>%s</td></tr>", $query_description);
        }
    }
    return sprintf("<span name='skills'>%s</span>", $out);
}

function getCallNmbr2($bibid)
{

    $bibid = db_escape($bibid);

    $sql = "SELECT call_nmbr2 FROM biblio WHERE bibid ='$bibid'";
    $query = db_query($sql);
    if(!$query){
        die("ERROR: getCallNmbr2");
    }
    $query = mysqli_fetch_array($query);

    return $query[0];
}

/**
 * String needs to be only N or G, if anything else like N12341
 * is given that will be used as the new number
 * otherwise a new number will be generated
 * @param string|$barcodeNumber string N || G or anything
 * @return string - basically the same that was given from what i can see
 */
function createNewDozentNumber($barcodeNumber)
{
    $barcodeNumber = db_escape($barcodeNumber);

    $location = substr($barcodeNumber, 0, 1);

    $sql = "SELECT barcode_nmbr FROM member WHERE barcode_nmbr LIKE '%$location%'";
    $new = db_query($sql);

    $q = new Query();
    $q->connect();
    $affectedRows = $q->affect();
    $q->close();

    if ($affectedRows == 0) {
        $result = $barcodeNumber;
    } # create sortable number array for choosen location #
    else {
        $i = 0;
        while ($barcode = mysqli_fetch_assoc($new)) {
            $barcode_dozent[$i] = substr($barcode['barcode_nmbr'], 1);
            $i = $i + 1;
        }
        # else take max(numbers) +1 for new number #
        $barNmbr = max($barcode_dozent) + 1;
        $result = $location . $barNmbr;
    }
    return $result;
}

function getLanguageDescription($languageNumber)
{
    $languageNumber = db_escape($languageNumber);
    $sql = 'SELECT description  FROM nt_sprachen WHERE code =' . $languageNumber;
    $query = db_query( $sql);
    if(!$query){
        die("ERROR: getLanguageDescription");
    }
    $result = mysqli_fetch_assoc($query);
    $value = $result['description'];
    return $value;
}

function getLocationDescription($location)
{
    $location = db_escape($location);
    $sql = 'SELECT description FROM locations WHERE code =' . $location;
    $query = db_query($sql);
    $result = mysqli_fetch_assoc($query);
    if(!$query){
        die("ERROR: getLocationDescription");
    }
    $value = $result['description'];
    return $value;
}

function getLocationNumber($description)
{
    $description = db_escape($description);

    $sql = "select code from locations where description LIKE '%$description%'";
    $query = db_query( $sql);
    if(!$query){
        die("ERROR: getLocationNumber");
    }
    $result = mysqli_fetch_assoc($query);
    return $result['code'];
}

/*********** get Member **************/

function getMemberWaiting($mbrid)
{
    $mbrid = db_escape($mbrid);

    $sql = 'SELECT
		create_dt AS register_date,
		barcode_nmbr AS mat_nr,
		last_name,
		first_name,
		address,
		email,
		home_phone AS tel_nmbr
	      FROM
		member
	      WHERE
		mbrid =' . $mbrid;
    $result = db_query( $sql);
    if(!$result){
        die("ERROR: getMemberWaiting");
    }
    echo "<br>numrows " . mysqli_num_rows($result);
    if (mysqli_num_rows($result) == 1) {
        return $result;
    } else {
        $sql = 'SELECT * FROM member_waiting WHERE mat_nr =' . $mbrid;
        $result = db_query( $sql);
        if(!$result){
            die("ERROR: getMemberWaiting");
        }
        return $result;
    }
}


?>
