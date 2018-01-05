<?php
/**
 * Author: vabene1111
 * Date: 06.09.2016
 * Time: 12:11
 */

header('Content-Type: text/html; charset=utf-8');
require_once("../shared/common.php");

$tab = "reports";
$nav = "mods_time";

require_once("../shared/logincheck.php");
require_once("../shared/header.php");

require_once("../classes/TimeQuery.php");

require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$timeQ = new TimeQuery();

if(isset($_POST['type'])){
    switch ($_POST['type']){
        case 'start':
            if(!$timeQ->getCurrentSession()){
                $timeQ->startSession();
            }
            break;
        case 'end':
            $timeQ->endSession($_POST['pause'],$_POST['msg']);
            break;
    }
}

?>
<form method="POST">
    <h3>Aktuelle Session <small>Nach beenden der Session könne die Zeiten noch editiert werden</small></h3>
    <table class="table table-bordered table-hover">
        <thead>
        <th>#</th>
        <th>Start</th>
        <th>Bis Jetzt</th>
        </thead>
        <tr>
            <?php
            $result = $timeQ->getCurrentSession();
            if($result){
                $date = new DateTime($result['start']);
                echo "<td>".$result['id']."</td><td id='start_dt'>".$date->format('d.m.Y H:i')."</td><td id='cur_dur'></td>";
                $type = "end";
                $btn = "Session beenden";
            }else{
                echo "<td>Keine</td><td>Aktive</td><td>Session</td>";
                $type = "start";
                $btn = "Session beginnen";
            }
            ?>
        </tr>

    </table>
    <?php
    if($result){
        echo "<div class='form-group'><label for='msg'>Kommentar</label>";
        echo "<textarea id='i_msg' class='form-control' name='msg' placeholder='Kommentar' rows='2'></textarea>";
        echo "</div><div class='form-group'><label for='msg'>Pause (in Minuten)</label>";
        echo "<input id='i_pause' class='form-control' type='number' name='pause' >";
        echo "</div>";
    }
    ?>
    <input type="hidden" name="type" value="<?php echo $type; ?>">
    <input type="submit" value="<?php echo $btn; ?>" class="form-control btn btn-primary">
</form>

<h3>Zeit Übersicht <small>Letzte 25 Sessions</small></h3>

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
    $res = $timeQ->getLastSessions();
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

        echo "<tr><td><a href='edit_time.php?id=".$row['id']."'>Bearbeiten</a></td><td>".$start->format('d.m.y H:i')."</td><td>".$end->format('d.m.y H:i')."</td>";
        echo "<td>".$row['pause']."</td><td>".$length."</td><td>".$row['comment']."</td>";
        echo "</tr>";
    }
    ?>
</table>

<script type="text/javascript">
    $( document ).ready(function() {
        start_time = new Date('<?php echo $date->format('Y-m-d H:i')?>');
        now = new Date(Date.now());

        var diff = now - start_time;

        var diffSeconds = diff / 1000;
        var HH = Math.floor(diffSeconds / 3600);
        var MM = Math.floor((diffSeconds % 3600) / 60);

        var formatted = ((HH < 10) ? ("0" + HH) : HH) + ":" + ((MM < 10) ? ("0" + MM) : MM)
        $('#cur_dur').html(formatted);
    });
</script>