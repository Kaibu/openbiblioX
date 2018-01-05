<?php

require_once("../shared/common.php");
require_once("../mods/include_mods.php");

$search = "";
if(isset($_POST['in_search'])){
    $search = trim($_POST['in_search']);
    $numRes = 0;
    $time_start = microtime(true);

    $isbnSearch = trim(str_replace('-','',$search));
    if(preg_match('/[0-9]{9,13}/',$isbnSearch)){
        $res = db_query("SELECT bibid,title FROM biblio WHERE REPLACE(isbn,'-','') LIKE '%".$isbnSearch."%'");
        if($res->num_rows > 0){
            $numRes = $numRes + $res->num_rows;
            echo "<hr/> ISBN RESULTS<br/>";
            printSimpleRows($res);
        }
    }

    if(preg_match('/[A-Z]{0,2}[a-z]{0,2}[0-9]{4,8}[A-Z]{0,2}[a-z]{0,2}/',$search)){
        $res = db_query("SELECT * FROM biblio_copy JOIN biblio ON biblio_copy.bibid = biblio.bibid WHERE biblio_copy.barcode_nmbr LIKE '%".$search."%'");
        if($res->num_rows > 0){
            $numRes = $numRes + $res->num_rows;
            echo "<hr/> BARCODE RESULTS<br/>";
            printSimpleRows($res);
        }
    }

    if(preg_match('/[A-Z]{0,2}[a-z]{0,2}[0-9]{6,13}[A-Z]{0,2}[a-z]{0,2}/',$search)){
        $res = db_query("SELECT * FROM biblio_copy JOIN biblio ON biblio_copy.bibid = biblio.bibid WHERE biblio_copy.barcode_nmbr LIKE '%".$search."%'");
        if($res->num_rows > 0){
            $numRes = $numRes + $res->num_rows;
            echo "<hr/> LOW MATCH BARCODE RESULTS<br/>";
            printSimpleRows($res);
        }
    }


    $res = db_query("
        SELECT  bibid, title, publisher, tags,
        match(`title`, `subtitle`, `author`, `publisher`, `pub_loc`, `summary`, `tags`) AGAINST ('".$search."') as `relevance` FROM biblio
        WHERE match(`title`, `subtitle`, `author`, `publisher`, `pub_loc`, `summary`, `tags`) AGAINST ('".$search."')
        ORDER BY `relevance` DESC LIMIT ".OBIB_ITEMS_PER_PAGE."
    ");

    if(isset($page)){

    }

    if($res->num_rows > 0){
        $numRes = $numRes + $res->num_rows;
        echo "<hr/> TITEL RESULT<br/>";
        printSimpleRows($res);
    }

    if($numRes == 0){
        echo "Leider keine ergebnisse gefunden<br/>";
    }

    echo "Found ".$numRes." results in ".round(microtime(true)-$time_start,4)." Seconds<br/>";
}


function printSimpleRows($res){
    $q = new Query();
    while ($row = $q->fetchRowQ($res)){
        echo $row['bibid']." - ".$row['title']." - ".$row['relevance']."<br/>";
    }
}
?>

