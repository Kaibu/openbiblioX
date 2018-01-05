<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");

$tab = "reports";
$nav = "reportlist";

include("../shared/logincheck.php");
require_once("../classes/LogQuery.php");
require_once("../classes/Report.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

include("../shared/header.php");

$lQ = new LogQuery();
$resNP = $lQ->getMaxViews(1);
$resGS = $lQ->getMaxViews(2);

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-10">
        <h3>Aufrufe pro Tag in den letzen 30 Tagen</h3>
        <div id="chart_div"></div>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-md-5">
        <h3>Aufrufe Standort 1 (letzte 30 Tage)</h3>
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>
                        Titel
                    </th>
                    <th>
                        Aufrufe
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = $lQ->fetchRowQ($resNP)) {
                        echo "<tr><td><a href='../shared/biblio_view.php?bibid=".HURL($row['bibid'])."'>".H($row['title'])."</a></td><td>".H($row['count'])."</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-5">
        <h3>Aufrufe Standort 2 (letzte 30 Tage)</h3>
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th>
                    Titel
                </th>
                <th>
                    Aufrufe
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $lQ->fetchRowQ($resGS)) {
                echo "<tr><td><a href='../shared/biblio_view.php?bibid=".$row['bibid']."'>".$row['title']."</a></td><td>".$row['count']."</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<br/>
<hr/>
<div class="row">
    <div class="col-md-2">
        <button class="text-md-center btn btn-outline-primary" onclick="location.href='report_media.php';">
            <i class="fa fa-book fa-5x" aria-hidden="true"></i><br/>
            Ausgeliehene Medien
        </button>
    </div>
    <div class="col-md-2">
        <button class="text-md-center btn btn-outline-primary" onclick="location.href='report_media.php?ov=Y';">
            <i class="fa fa-users fa-5x" aria-hidden="true"></i><br/>
            Überfällige Medien
        </button>
    </div>
    <div class="col-md-2">
        <button class="text-md-center btn btn-outline-primary" onclick="location.href='report_stat_history.php';">
            <i class="fa fa-history fa-5x" aria-hidden="true"></i><br/>
            Ausleih Historie
        </button>
    </div>
    <div class="col-md-2">
        <button class="text-md-center btn btn-outline-primary" onclick="location.href='report_preorder.php';">
            <i class="fa fa-shopping-cart fa-5x" aria-hidden="true"></i><br/>
            Vorbestellungen
        </button>
    </div>
    <div class="col-md-2" hidden>
        <button class="text-md-center btn btn-outline-primary">
            <i class="fa fa-book fa-5x" aria-hidden="true"></i><br/>
            WIP
        </button>
    </div>
</div>

<script type="text/javascript">

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'X');
        data.addColumn('number', 'Aufrufe pro Tag');

        data.addRows([<?php echo $lQ->totalViewsLastMonth(); ?>]);

        var options = {
            hAxis: {
                title: 'Tag'
            },
            vAxis: {
                title: 'Aufrufe'
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    }
</script>
<?php include("../shared/footer.php"); ?>

