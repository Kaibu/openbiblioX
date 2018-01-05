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
 
    require_once("../shared/common.php");
    session_cache_limiter(null);

    $tab = "cataloging";
    $nav = "mod_keywordliste";
    $focus_form_name = "barcodesearch";
    $focus_form_field = "searchText";

    require_once("../shared/logincheck.php");
    require_once("../shared/header.php");
    require_once("../classes/Localize.php");
    require_once("include_mods.php");

    $loc = new Localize(OBIB_LOCALE,$tab);
    if (isset($_GET["msg"])) {
    $msg = "<font class=\"error\">".H($_GET["msg"])."</font><br><br>";
    } else {
    $msg = "";
    }
    
    //
    // Eventhandling
    //
    if(isset($_POST['delete']) && isset($_POST['choice']) && 
        count($_POST['choice']) != 0)
    {
        db_delete_keywords($_POST['choice']);
    }
    //
    if(isset($_POST['compose']) && isset($_POST['choice']) && isset($_POST['root']) &&
        count($_POST['choice']) > 0)
    {
        $keys = array();
        $keys[] = $_POST['root'];
        foreach($_POST['choice'] as $k)
            $keys[] = $k;
        db_unique_keywords($keys);
    }
    if(isset($_POST['rename']) && isset($_POST['choice']) && isset($_POST['newRoot']) &&
        count($_POST['choice']) > 0 && $_POST['newRoot'] != "")
    {
        $keys = array();
        $keys[] = $_POST['newRoot'];
        foreach($_POST['choice'] as $k)
            $keys[] = $k;
        db_unique_keywords($keys);
    }
    
    //
    // Erzeugt die Ausgabe der Keyword Liste
    // Wenn Suchwort übergeben in skey, dann nur bedingte Ausgabe
    //
    function db_list_keywords()
    {
        $search = False;
        if(isset($_POST['search']) && isset($_POST['skey']) && $_POST['skey'] != '')
        {
            $sql = sprintf("SELECT topic1, COUNT(topic1) FROM biblio
                WHERE topic1 LIKE '%%%s%%' GROUP BY topic1 ORDER BY topic1",
                $_POST['skey']);
            $search = True;
        }
        else
            $sql = "SELECT topic1, COUNT(topic1) FROM biblio GROUP BY topic1 ORDER BY topic1";
        
        $keywordlist = db_query($sql);
        $keylist = array(); // Assoziatives Array ordnet jedem Key die Anzahl zu
        while($keys = mysqli_fetch_assoc($keywordlist))
        {
            $temp = explode(";", $keys['topic1']);            
            foreach($temp as $keyword)
            {
                
                if($search && strpos(strtolower($keyword), strtolower($_POST['skey']))===false)
                {
                    continue;
                }
                if(isset($keylist[$keyword]) && $keylist[$keyword] > 0)
                    $keylist[$keyword] = $keylist[$keyword] + $keys['COUNT(topic1)'];
                else
                    $keylist[$keyword] = $keys['COUNT(topic1)'];
            }
        }
        // Sortiert die Ergebnis-Keylist alphabetisch
        // k[ey]sort() :- für Schlüsselsortierung
        ksort($keylist);
        // Ausgabe erzeugen als Tabelleneintrag <tr>
        foreach($keylist as $key => $num)
        {
            if($key == '')
                printf('<tr><td>{leer}</td><td>%s</td>
                        <td></td><td></td></tr>
                        
                        <tr><td colspan="12"><div  class="deviderVertical"></div></td></tr>
                        ', $num);
                        
   
            else
                printf('<tr><td>%s</td><td>%s</td>
                <td><input type="checkbox" name="choice[]" value="%s" /></td>
                <td><input type="radio" name="root" value="%s" /></td></tr>
                
                <tr><td colspan="12"><div  class="deviderVertical"></div></td></tr>
                ',
                $key, $num, $key, $key);
                
                 
                           
        }
    }
    //
    // Zusammenfügen von Keywords
    // $keys: String[] mit Liste der Schlagwörter die zusammengfügt
    // werden sollen
    // $keys[0]: root Keyword
    //
    function db_unique_keywords($keys)
    {
        for($k = 1; $k < count($keys); $k++)
        {
            $res = db_query(sprintf("SELECT bibid, topic1 FROM biblio WHERE topic1 LIKE '%%%s%%'",
                                $keys[$k]));
            while($row = mysqli_fetch_assoc($res))
            {
                $rep = str_replace($keys[$k], $keys[0], $row['topic1']);
                $rep_list = array_unique(explode(';', $rep));
                $rep = implode(';', $rep_list);
                db_query(sprintf("UPDATE biblio SET topic1 = '%s' WHERE bibid = %s",
                                    $rep, $row['bibid']));
            }            
        }
    }
    
    function db_delete_keywords($keys)
    {
        foreach($keys as $key)
        {
            $res = db_query(sprintf("SELECT * FROM biblio WHERE topic1 LIKE '%%%s%%'", $key));
            while($row = mysqli_fetch_assoc($res))
            {
                $keywordlist = explode(";", $row['topic1']);
                for($i = 0; $i < count($keywordlist); $i++)
                {
                    if($keywordlist[$i] == $key)
                        unset($keywordlist[$i]);
                }
                $keywordlist = array_values($keywordlist);
                $rep = join(";", $keywordlist);
                db_query(sprintf("UPDATE biblio SET topic1 = '%s' WHERE bibid = %s",
                                    $rep, $row['bibid']));
            }
        }
    }

?>
<h1 style=""><img src="../images/catalog.png" border="0" width="30" height="30" align="top"> <?php echo "Medienliste"; ?></h1>

<h2 style="">&Uuml;bersicht</h2>
<form action="" method="POST" style="width: auto;">
<fieldset><legend>Suche</legend>
    <input type="text" name="skey" />
    <input type="submit" name="search" value="Suchen" />
</fieldset>
<fieldset>
    <legend>Optionen</legend>
    <input type="submit" name="delete" value="L&ouml;schen" />
    <input type="submit" name="compose" value="Zusammenf&uuml;gen" /><br>
    <label>Umbennenen:</label><input type="text" name="newRoot" /><input type="submit" name="rename" value="Umbenennen" />
</fieldset>
<fieldset>
	<!-- Style outtake table=> style='font-size: 0.8em; border: thin solid black;'-->
	<table style='font-size: 0.8em; '>
	<tr><th>Schlagwort</th><th>Anzahl Vorkommen</th><th>Auswahl</th><th>Zusammenf&uuml;gen</th></tr>
<?php
    db_list_keywords()   
?>
	</table>
	</fieldset>

</form>
<?php

echo $msg ?>

<?php include("../shared/footer.php"); ?>
