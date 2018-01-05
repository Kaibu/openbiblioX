<?php
/**
 * Author: vabene1111
 * Date: 06.09.2016
 * Time: 12:12
 */

header('Content-Type: text/html; charset=utf-8');
require_once("../shared/common.php");

$tab = "admin";
$nav = "time_report";

require_once("../shared/logincheck.php");
require_once("../shared/header.php");

require_once("../classes/TimeQuery.php");

require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$timeQ = new TimeQuery();

if(isset($_POST['type'])){
    switch ($_POST['type']){
        case 'user':
            $res = $timeQ->getUserReport($_POST['user']);
            break;
    }
}
?>
<h1> Auswertung Zeiterfassung</h1>
<form method="post" style="border-style: hidden!important;">
    <div class="form-group">
        <div class="form inline" style="display: inline;">
            <select class="form-control" name="user" id="sel_user" style="display: inline;">
                <?php
                $staff = $timeQ->getUsers();
                while ($row = $timeQ->fetchRowQ($staff)){
                    echo "<option id='user_sel_".$row['userid']."' value='".$row['userid']."'>".$row['first_name']." ".$row['last_name']."</option>";
                }
                ?>
            </select>

        <input class="btn btn-outline-primary pull-right" type="submit" value="Auswertung Anzeigen">

        </div>

        <input type="hidden" name="type" value="user">
    </div>

</form>

<br/>
<h3>Auswertung für: <label id="user_name">Bitte Nutzer wählen</label></h3>
<table class="table table-bordered table-hover">
    <thead>
    <th>#</th>
    <th>Start</th>
    <th>Ende</th>
    <th>Pause</th>
    <th>Länge</th>
    <th>Kommentar</th>
    </thead>
    <?php
    if(isset($res)){
    while ($row = $timeQ->fetchRowQ($res)){

        $start = new DateTime($row['start']);
        $end = new DateTime($row['end']);
        $dI = $start->diff($end->sub(new DateInterval('PT'.$row['pause'].'M')));

        if($end > $start){
            $length = $dI->h."h ".$dI->i."m";
        }else{
            $length = 0;
        }

        $end = new DateTime($row['end']);

        echo "<tr><td>".$row['id']."</td><td>".$start->format('d.m.y H:i')."</td><td>".$end->format('d.m.y H:i')."</td>";
        echo "<td>".$row['pause']."</td><td>".$length."</td><td>".$row['comment']."</td>";
        echo "</tr>";
    }
    }
    ?>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        var curId = '<?php echo $_POST['user']; ?>';

        if(curId != ""){

            $('#sel_user').val(curId);
            //not so nice but so be it
            var userSelect = document.getElementById("sel_user");
            var selectedText = userSelect.options[userSelect.selectedIndex].text;
            $('#user_name').text(selectedText);
        }
    })
</script>