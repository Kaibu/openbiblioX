<?php
/**
 * User: vabene1111
 * Date: 06.10.2016
 * Time: 15:09
 */

require_once("../shared/common.php");

$tab = "reports";
$nav = "report_result";

include("../shared/logincheck.php");
require_once("../classes/ReportQuery.php");
require_once("../classes/Report.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

include("../shared/header.php");

$onlyOver = false;
if($_GET['ov'] == "Y"){
    $onlyOver = true;
}

$reverted = false;
if($_GET['rv'] == "Y"){
    $reverted = true;
}

$page = 1;
if(isset($_GET['page']) AND is_int((int) $_GET['page'])){
    $page = (int)$_GET['page'];
}

$loc = 0;
if(isset($_GET['loc']) AND is_int((int) $_GET['loc'])){
    $loc = (int)$_GET['loc'];
}

$rQ = new ReportQuery();

if($onlyOver){
    $res = $rQ->mediaOverReport($page,$loc,$reverted);
}else{
    $res = $rQ->mediaOutReport($page,$loc,$reverted);
}
$numRes = $rQ->numRes;

?>

<h1><?php
    if($onlyOver){echo "Überfällige Medien (Studenten)";}else{echo "Aktuell ausgeliehene Medien";}
?></h1>

<div class="row">
    <div class="col-md-10 text-md-center">
        <div class="btn-group" role="group" aria-label="Basic example">
            <?php echo "Ergebnisse: ".$numRes;?>
            <br/><br/>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10 text-md-center">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="<?php if($loc == 2){echo "btn btn-primary";}else{echo "btn btn-outline-primary";} ?>" onclick="setFilter(2)">Standort 2</button>
            <button type="button" class="<?php if($loc == 0){echo "btn btn-primary";}else{echo "btn btn-outline-primary";} ?>" onclick="setFilter(0)">Alle</button>
            <button type="button" class="<?php if($loc == 1){echo "btn btn-primary";}else{echo "btn btn-outline-primary";} ?>" onclick="setFilter(1)">Standort 1</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10 text-md-center">
        <nav aria-label="Seiten Navigation" id="nav_pager" <?php if($numRes < 40){echo "hidden";} ?>>
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="../mods/jswarning.html" onclick="setPage(1);return false;" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <?php $total = makePager($numRes,$page); ?>
                <li class="page-item">
                    <a class="page-link" href="../mods/jswarning.html" onclick="setPage(<?php echo $total; ?>);return false;" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>


<?php ?>
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>Barcode</th>
            <th>Titel</th>
            <th>Autor</th>
            <th>Nutzer</th>
            <th>Statusanfang</th>
            <th><a onclick="toggleOrder();return false;" href="">Rückgabe</a></th>
        </tr>
    </thead>
    <?php
        while ($row = $rQ->fetchRowQ($res)){
            echo "<tr><td>".$row['barcode_nmbr']."</td><td><a href='../shared/biblio_view.php?bibid=".$row['bibid']."'>".$row['title']."</a></td><td>".$row['author']."</td><td><a href='../circ/mbr_view.php?mbrid=".$row['mbrid']."'>".$row['last_name'].",".$row['first_name']."</a></td><td>".timestampToDate($row['status_begin_dt'])."</td><td>".timestampToDate($row['due_back_dt'])."</td></tr>";
        }
    ?>
</table>


<script type="text/javascript">
    function setPage(page) {
        window.location = location.protocol + "//" + location.host + location.pathname + "?page=" + page <?php echo "+ \"&loc=".$loc."\""; if($onlyOver){echo "+ \"&ov=Y\"";}; if($reverted){echo "+ \"&rv=Y\"";}else{echo "+ \"&rv=N\"";}?>;
    }

    function setFilter(locId){
        window.location = location.protocol + "//" + location.host + location.pathname + "?page=1" <?php if($onlyOver){echo "+ \"&ov=Y\"";} ?> + "&loc=" + locId;
    }

    function toggleOrder(){
        window.location = location.protocol + "//" + location.host + location.pathname + "?page=1" <?php echo "+ \"&loc=".$loc."\""; if($onlyOver){echo "+ \"&ov=Y\"";}; if($reverted){echo "+ \"&rv=N\"";}else{echo "+ \"&rv=Y\"";} ?>;
    }
</script>



<?php include("../shared/footer.php"); ?>