<?php
/**
 * Created by PhpStorm.
 * User: vabene1111
 * Date: 23.08.2016
 * Time: 18:48
 */

require_once("../shared/common.php");

$tab = "reports";
$nav = "changelog";

include("../shared/logincheck.php");
require_once("../classes/Report.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

include("../shared/header.php");

$changeTypes = array("added","changed","security","fixed","removed");

$changeData = file_get_contents("changelog.json");
$changeJson = json_decode($changeData);

foreach($changeJson as $chObj){
    echo "<div class=\"card card-outline-primary\"><div class=\"card-header\">";
    if($chObj->releaseDate == ""){
        echo "Unreleased - Version : ".$chObj->version."";
    }else{
        $date = new DateTime($chObj->releaseDate);
        if($date > new DateTime()){
            echo "Geplant für: ".$date->format('d.m.Y')." - Version : ".$chObj->version."";
        }else{
            echo "Veröffentlicht am: ".$date->format('d.m.Y')." - Version : ".$chObj->version."";
        }
    }
    echo "</div><div class=\"card-block\"><p class=\"card-text\">";
    foreach ($changeTypes as $type){
        if(count($chObj->{$type}) > 0){
            echo "<h5><i class=\"".getIcon($type)."\" aria-hidden=\"true\"></i> ".$loc->getText($type)."</h5><p><ul>";
            foreach ($chObj->{$type} as $entry){
                echo "<li>".$entry."</li>";
            }
            echo "</ul></p>";
        }
    }
    echo "</p></div></div>";
}

function getIcon($type){
    switch ($type){
        case "added" : return "fa fa-plus";
        case "changed" : return "fa fa-cogs";
        case "security" : return "fa fa-lock";
        case "fixed" : return "fa fa-wrench";
        case "removed" : return "fa fa-remove";
    }
    return "fa fa-question";
}

include("../shared/footer.php");

?>
