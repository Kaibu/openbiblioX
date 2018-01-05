<?php
/**
 * User: vabene1111
 * Date: 01.09.2016
 * Time: 11:58
 */

header('Content-Type: text/html; charset=utf-8');
require_once("../shared/common.php");

$tab = "cataloging";
$nav = "jsonUpload";

require_once("../shared/logincheck.php");
require_once("../shared/header.php");

require_once("../classes/JsonImportQuery.php");

require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$msg = "";
$warning = "";
$jsQuery = new JsonImportQuery();

if ($_POST['type'] == 'jsonUpload') {

    $target = "../upload/";
    $target = $target . basename($_FILES['upload']['name']);
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $target)) {
        $msg .= "Die Datei " . basename($_FILES['upload']['name']) . " wurde hochgeladen<br/>";
    } else {
        $warning .= "Es gab ein problem beim Upload der Datei.<br/>";
    }
    //TODO validate file
    $contents = file_get_contents($target, FILE_USE_INCLUDE_PATH);
    if (mb_detect_encoding($contents) != "ASCII") {
        $warning .= "Die hochgeladene Datei muss UTF-8 ohne BOM (ASCII) encoded sein!<br/>";
    } else {
        $bookList = json_decode($contents, true, 512, JSON_BIGINT_AS_STRING);

        $importQ = new JsonImportQuery();
        $msg .= count($bookList['books']) . " Datensätz(e) gefunden.<br/>";
        for ($i = 0; $i < count($bookList['books']); $i++) {
            $bookObj = $bookList['books'][$i];
            $r = $importQ->insertRecord($bookObj['ISBN'], $bookObj['title'], $bookObj['subtitle'], $bookObj['author'], $bookObj['publisher'], $bookObj['pub_year'], $bookObj['pub_loc']);
            if ($r != 1) {
                $warning .= "Fehler beim importieren von: " . $bookObj['title'] . "<br/>";
            }
        }
        $msg .= "Buch Import erfolgreich abgeschlossen <br/>";
    }
}

if (isset($_GET['r'])) {
    if ($_GET) {
        $jsQuery->removeImported();
        $msg = "Alle markierten Medien wurden archiviert!";
    }
}

$list = $jsQuery->getImportList();

//TODO make nice and fancy functions for this
if ($msg != "") {
    echo "<div class=\"alert alert-success alert-dismissible fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" . $msg . "</div>";
}

if ($warning != "") {
    echo "<div class=\"alert alert-warning alert-dismissible fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" . $warning . "</div>";
}
?>


    <h3>JSON Buch Import</h3>
    Hier können sie eine Liste von Büchern aus einer Datei hochladen.
    <br/><br/>

    <form action="../mods/import.php" method="post" enctype="multipart/form-data" name='upload'>
        <input type="hidden" name="type" value="jsonUpload">
        <input type="file" name="upload" accept="text/json">
        <input type="submit" name="btn[upload]">
    </form>

    <hr/>
    <h3>Import Halteliste
        <small><a href="../mods/import.php?r=1"
                  onclick="confirm('Möchten sie alle als Importiert markierten Medien archivieren?');">Importierte
                Medien archivieren</a></small>
    </h3>
    <table class="table table-bordered table-hover">
        <thead>
        <th>ID</th>
        <th>ISBN</th>
        <th>Titel</th>
        </thead>
        <?php if (mysqli_num_rows($list) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($list)): ?>
                <?php if ($row['imported']) {
                    echo "<tr class=\"table-success\">";
                } else {
                    echo "<tr>";
                } ?>
                <td><?php echo "<a href='../catalog/biblio_new.php?import_id=" . $row['id'] . "'>" . $row['id'] . "</a>"; ?></td>
                <td><?php echo $row['ISBN']; ?></td>
                <td><?php echo $row['title']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
<?php
include("../shared/footer.php");