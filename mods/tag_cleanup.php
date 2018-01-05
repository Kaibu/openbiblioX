<?php
/**
 * User: vabene1111
 * Date: 27.10.2016
 * Time: 15:20
 */
require_once("../shared/common.php");
$tab = "tagcheck";
require_once("../shared/logincheck.php");
require_once("../classes/Query.php");

$query = new Query();
$hint = "";
$q=$_POST["in_tag"];

?>
<META http-equiv="content-type" content="text/html; charset=<?php echo H(OBIB_CHARSET); ?>">
<a href="../index.php"><-- Zurück zum OPAC</a>
<form method="POST">
    <input type="text" name="in_tag" value="<?php echo H($q); ?>">
    <input type="submit">
</form>
<?php

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
        if(strpos(strtolower($t),$q) === 0){
            $tag = $t;
            if(array_search($tag,$list) === false){
                array_push($list,$tag);
                $hint .= "<li><a target='_blank' href='tag_cleanup_show.php?tag=".HURL($tag)."'>".H($tag)."</a></li>";
            }
        }
    }
}

if ($hint=="") {
    echo "Kein Treffer";
} else {
    echo "<ol>".$hint."</ol>";
}

