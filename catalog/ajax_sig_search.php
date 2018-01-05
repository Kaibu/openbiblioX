<?php
/**
 * User: vabene1111
 * Date: 04.11.2016
 * Time: 12:04
 */

require_once("../shared/common.php");

require_once("../shared/logincheck.php");
require_once("../classes/Query.php");

$query = new Query();
$hint = "";
$s=$_GET["s"];
$sc=$_GET["sc"];
$l=$_GET["l"];

if($s == "" || !is_int((int)$s)){
    die("ERROR S");
}

if($sc == "" || !is_int((int)$sc)){
    die("ERROR SC");
}

if($l == "" || !is_int((int)$l)){
    die("ERROR L");
}

$s = $query->escape_data($s);
$sc = $query->escape_data($sc);
$l = $query->escape_data($l);

$sql = "SELECT signature FROM biblio WHERE collection_cd = (SELECT code FROM nt_systematik_signatur WHERE category = ".$s." AND sub_category = ".$sc.") AND language_id = ".$l." ORDER BY cast(signature as unsigned) DESC LIMIT 1";
$res = $query->queryDb($sql);

$response = $query->fetchRowQ($res);

$response = $response["signature"];

echo ((int)$response + 1);