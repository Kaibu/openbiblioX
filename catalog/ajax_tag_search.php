<?php
/**
 * Author: vabene1111
 * Date: 04.10.2016
 * Time: 09:07
 */

require_once("../shared/common.php");

require_once("../shared/logincheck.php");
require_once("../classes/Query.php");

$query = new Query();
$hint = "";
$q=$_GET["q"];

if($q == ""){
    die();
}

$q = strtolower($q);
$q = $query->escape_data($q);
$sql = "SELECT tags FROM biblio WHERE LCASE(tags) LIKE '%".$q."%'";
$res = $query->queryDb($sql);

$list = array();

while($row = $query->fetchRowQ($res)){
    $tags = explode(';',$row['tags']);
    $tag = "";
    foreach ($tags as $t){
        if(strpos(strtolower($t),$q) !== false){
            $tag = $t;
        }
    }

    if(count($list) >= 10){break;}
    if(array_search($tag,$list) === false && $tag != ""){
        array_push($list,$tag);

        if($hint == ""){
            $hint .= "<a id='first_result' href='javascript:addTag(\"".$tag."\")'>".$tag."</a>";
        }else{
            $hint .= "<br/><a href='javascript:addTag(\"".$tag."\")'>".$tag."</a>";
        }
    }
}

if ($hint=="") {
    $response= "Kein Treffer";
} else {
    $response = $hint;
}

//output the response
echo $response;