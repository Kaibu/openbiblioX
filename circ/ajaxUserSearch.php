<?php
require_once("../shared/common.php");

require_once("../shared/logincheck.php");
require_once("../classes/Query.php");

$query = new Query();
$hint = "";
$q=$_GET["q"];

if($q == ""){
    die();
}

$q = $query->escape_data($q);
$res = $query->queryDb("SELECT mbrid,last_name,first_name FROM member WHERE last_name LIKE '%".$q."%' OR first_name LIKE '%".$q."%' LIMIT 10");



while($row = $query->fetchRowQ($res)){
    if($hint == ""){
        $hint .= "<a id='first_result' href='../circ/mbr_view.php?mbrid=".$row['mbrid']."&reset=Y'>".$row['last_name'].", ".$row['first_name']."</a>";
    }else{
        $hint .= "<br/><a href='../circ/mbr_view.php?mbrid=".$row['mbrid']."&reset=Y'>".$row['last_name'].", ".$row['first_name']."</a>";
    }
}

if ($hint=="") {
    $response= "Kein Treffer";
} else {
    $response = $hint;
}

//output the response
echo $response;
?>