<?php
/**
 * Author: vabene1111
 * Date: 11.10.2016
 * Time: 10:27
 */

require_once('../classes/Biblio.php');

function download_page($path){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$path);
    curl_setopt($ch, CURLOPT_FAILONERROR,1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $retValue = curl_exec($ch);
    curl_close($ch);
    return $retValue;
}

if(isset($_GET['isbn'])){
    $queryString = "http://sru.gbv.de/gvk?version=1.1&operation=searchRetrieve&query=" . str_replace('-','',$_GET['isbn']) . "&maximumRecords=10&recordSchema=mods";
    //$res = file_get_contents($queryString);
    $res = download_page($queryString);
    $m = substr($res, strpos($res, '<mods'));
    $m = substr($m, 0, strpos($m, '</mods>') + 7);

    $bibObj = new Biblio;

    libxml_disable_entity_loader(true);

    $parsed = simplexml_load_string($m);
    $placeString = "";

    foreach ($parsed->originInfo->place as $place){
        foreach ($place->placeTerm->attributes() as $test) {
            if ($test == "text") {
                $placeString =  $place->placeTerm;
                break;
            }
        }
        if($placeString != ""){break;}
    }

    $yearString = "";
    foreach ($parsed->originInfo->dateIssued as $date){
        foreach ($date->attributes() as $attr) {
            if ($attr == "marc") {
                $yearString =  $date;
                break;
            }
        }
        if($yearString != ""){break;}
    }

    $authors = "";
    foreach ($parsed->name as $name){
        $authors .= $name->namePart[0].", ".$name->namePart[1].' / ';
    }
    $authors = rtrim($authors, "/ ");

    $arr = array(
        'title' => (string)$parsed->titleInfo->title,
        'sub_title' => (string)$parsed->titleInfo[0]->subTitle,
        'isbn' => $_GET['isbn'],
        'publisher' => (string)$parsed->originInfo->publisher,
        'pub_year' => (string) $yearString,
        'pub_place' => (string) $placeString,
        'author' => (string) $authors,
    );

    $str = json_encode($arr);
    echo $str;
}