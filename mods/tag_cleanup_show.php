<?php
/**
 * User: vabene1111
 * Date: 27.10.2016
 * Time: 15:39
 */

require_once("../shared/common.php");

require_once("../shared/logincheck.php");
require_once("../classes/Query.php");

?>
    <META http-equiv="content-type" content="text/html; charset=<?php echo H(OBIB_CHARSET); ?>">
    <a href="../index.php"><-- Zurück zum OPAC</a>

<?php

$query = new Query();
$hint = "";
$q=$_GET["tag"];

if($q == ""){
    die();
}

$q = strtolower($q);
$q = $query->escape_data($q);
$sql = "SELECT title,bibid FROM biblio WHERE LCASE(tags) LIKE '%".$q."%'";
$res = $query->queryDb($sql);

$list = array();

while($row = $query->fetchRowQ($res)){

    $hint .= "<li><a target='_blank' href='../shared/biblio_view.php?bibid=".HURL($row['bibid'])."'>".H($row['title'])."</a></li>";

}

if ($hint=="") {
    echo "Kein Treffer";
} else {
    echo "<ol>".$hint."</ol>";
}

