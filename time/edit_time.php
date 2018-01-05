<?php
/**
 * User: vabene1111
 * Date: 08.09.2016
 * Time: 10:43
 */

require_once("../shared/common.php");

$tab = "reports";
$nav = "mods_time_edit";

require_once("../shared/logincheck.php");
require_once("../shared/header.php");

require_once("../classes/TimeQuery.php");

require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$timeQ = new TimeQuery();

$error = false;

if(isset($_POST['deleteId'])){
    if(is_int((int) $_POST['deleteId'])){
        $timeQ->deleteSession((int) $_POST['deleteId']);
        echo '<META HTTP-EQUIV="refresh" content="0;URL=track.php">';
        die();
    }
}

if(isset($_POST['recordId'])){

    if(!preg_match('/[0-9]{4}\/[0-9]{2}\/[0-9]{2} [0-9]{2}:[0-9]{2}:*[0-9]{0,2}/',trim($_POST['time_start']))){
        $error = true;
    }

    if(!preg_match('/[0-9]{4}\/[0-9]{2}\/[0-9]{2} [0-9]{2}:[0-9]{2}:*[0-9]{0,2}/',trim($_POST['time_end']))){
        $error = true;
    }

    if(!$error){
        $start = str_replace('/','-',$_POST['time_start']);
        $end = str_replace('/','-',$_POST['time_end']);

        $timeQ->editSession($_POST['recordId'],$start,$end,$_POST['pause'],$_POST['msg']);

        echo '<META HTTP-EQUIV="refresh" content="0;URL=track.php">';
        die();
    }
}

if(!isset($_GET['id']) OR !is_int((int )$_GET['id'])){
    if(!isset($_POST['recordId']) OR !is_int((int )$_POST['recordId'])){
        //echo '<META HTTP-EQUIV="refresh" content="0;URL=../index.php">';
        die('Wrong parameter');
    }else{
        $recordId = (int) $_POST['recordId'];
    }
}else{
    $recordId = (int) $_GET['id'];
}

if(!$timeQ->isUserRecord($recordId)){
    //echo '<META HTTP-EQUIV="refresh" content="0;URL=../index.php">';
    die('This is not your record');
}

$record = $timeQ->getRecordById($recordId);

?>

<link href="../resources/css/jquery.datetimepicker.min.css" rel="stylesheet">
<script src="../resources/js/jquery.datetimepicker.full.min.js" ></script>

<a href="track.php" ><- Zurück zur Übersicht</a>
<br/>
<br/>

<h3>Eintrag bearbeiten</h3>
<form style="border:hidden!important;" method="post">
    <input type="hidden" name="recordId" value="<?php echo H($record['id']) ?>">
    <label>
        Start Zeit
        <input id="dt_start" class="form-control" name="time_start"  value="<?php echo H(str_replace('-','/',$record['start'])); ?>">
    </label>
    <label>
        End Zeit
        <input id="dt_end" class="form-control" name="time_end"  value="<?php echo H(str_replace('-','/',$record['end'])); ?>">
    </label>

    <br/>
    <label for="msg">Kommentar</label>
    <textarea class="form-control" name="msg" placeholder="Kommentar"><?php echo H($record['comment']); ?></textarea>


    <br/>
    <label for="pause">Pause (in Minuten)</label>
    <input class="form-control" name="pause" type="number"  placeholder="Pause in Minuten" value="<?php echo H($record['pause']); ?>">

    <br/>
    <input type="submit" value="Speichern" class="btn btn-primary pull-right">
</form>
<br/>
<br/>
<h3>Eintrag Löschen<small> Möchten sie diesen Eintrag löschen</small></h3>
<div class="row">
    <div class="col-md-6">
        <br/>
        Eintrag Löschen ?
    </div>
    <form method="post" style="border: hidden!important;"><input type="submit" value="Löschen" class="btn btn-danger pull-right" onclick="confirm('Sind sie sicher das sie diesen Eintrag Löschen wollen')"><input type="hidden" name="deleteId" value="<?php echo H($record['id']) ?>"></form>
</div>


<script>
    $('document').ready(function () {
        $('#dt_start').datetimepicker();
        $('#dt_end').datetimepicker();
    });
</script>