<?php
$biblioQ = new BiblioSearchQuery();

//Init default values
$page = 1;
$search = "";
$numRes = 0;
$barcodeRes = false;
$isbnRes = false;
$sigResult = false;

//Catch POST
if(isset($_POST['in_search'])){
    if(isset($_POST['search_page'])){
        $page = $_POST['search_page']; //TODO sanitize
    }
    $search = $_POST['in_search']; //TODO sanitize

    $resFullText = $biblioQ->getMainResults($search,$isOpac,$page);
    $numRes = $biblioQ->mainRowCount;

    $barcodeRes = $biblioQ->getBarcodeResult($search,$isOpac);
    $sigResult = $biblioQ->getSignatureResult($search,$isOpac);
    $isbnRes = $biblioQ->getIsbnResult($search,$isOpac);
}

if($isOpac){$tabAddon= "&tab=opac";}else{$tabAddon= "";}
?>
<a href="../<?php if($isOpac){echo "opac";}else{echo "catalog";} ?>/index.php?search=advanced">Zur erweiterten Suche</a>

<div class="row">
    <div class="col-md-10">
        <form method="POST" style="border: hidden!important;" name="search_form">
            <input type="hidden" name="search_page" value="1" id="search_page">
            <div class="input-group">
                <input type="text" name="in_search" value="<?php echo H($search); ?>" class="form-control"
                       placeholder="Suche nach ..." autofocus>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-success" style="height: 37.7px">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
    </div>
</div>

<div class="row" <?php if($search == ""){echo "hidden";} ?>>
    <div class="col-md-5">
        <div class="card card-block">
            <h4 class="card-title">Barcode/Signatur Ergebnis</h4>
            <?php
            if(!$barcodeRes && !$sigResult){
                echo "<p class='card-text'>Es wurde kein Barcode/Signatur zu der eingegebenen Suche gefunden</p>";
            }else{
                if(!$barcodeRes){
                    $barcodeRes = $sigResult;
                }
                echo "<dl class='row'><dt class='col-sm-4'>Titel</dt><dd class='col-sm-8'>" . $barcodeRes['title'] . "</dd><dt class='col-sm-4'>Autor</dt><dd class='col-sm-8'>" . $barcodeRes['author'] . "</dd></dl>";
                echo "<a href='../shared/biblio_view.php?bibid=" . $barcodeRes['bibid'] .$tabAddon. "' class='card-link pull-right'>Details ansehen</a>";
            }
            ?>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card card-block">
            <h4 class="card-title">ISBN Ergebnis</h4>
            <?php
            if(!$isbnRes){
                echo "<p class='card-text'>Es wurde keine ISBN zu der eingegebenen Suche gefunden</p>";
            }else{
                echo "<dl class='row'><dt class='col-sm-4'>Titel</dt><dd class='col-sm-8'>" . $isbnRes['title'] . "</dd><dt class='col-sm-4'>Autor</dt><dd class='col-sm-8'>" . $isbnRes['author'] . "</dd></dl>";
                echo "<a href='../shared/biblio_view.php?bibid=" . $isbnRes['bibid'] .$tabAddon ."' class='card-link pull-right'>Details ansehen</a>";
            }
            ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10 text-md-center">
        <?php
        if ($numRes == 0 AND $search != "" && !$isbnRes && !$barcodeRes) {
            echo "Leider wurden keine Ergebnisse zu ihrer Suche gefunden.<br/>";
            include("../shared/footer.php");
            die();
        }else if($search == "" OR $numRes == 0){
            include("../shared/footer.php");
            die();
        }
        ?>

        <?php echo $numRes . " Ergebnisse in " . $biblioQ->mainQueryTime . " Sekunden gefunden<br/>"; ?>
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

<div class="row">
    <div class="col-md-10">
        <table class="table table-bordered table-hover">

            <?php
            if(isset($resFullText)){
                while ($row = $biblioQ->fetchRowQ($resFullText)) {
                    echo "<tr><td><dl class='row'><dt class='col-sm-3'>Titel</dt><dd class='col-sm-9'><a href='../shared/biblio_view.php?bibid=" . $row['bibid'] .$tabAddon . "'>" . $row['title'] . "</a></dd>";
                    echo "<dt class='col-sm-3'>Autor</dt><dd class='col-sm-9'>" . $row['author'] . "</dd></dl></td><td align='right'>";
                    echo "<button type='button' class='btn btn-primary btn-lg' data-toggle='modal' data-target='#detail_modal' data-id='" . $row['bibid'] . "'>";
                    echo "<i class='fa fa-info' aria-hidden='true'></i></button></td></tr>";
                }
            }
            ?>
        </table>
    </div>
</div>


<div class="modal fade" id="detail_modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Buch Info</h4>
            </div>
            <div class="modal-body" id="modal_info_body">
                <div class="loader"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">

    function setPage(page) {
        document.getElementById('search_page').value = page;
        document.search_form.submit();
    }

    modal = $('#detail_modal');
    modal.on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        var modal = $(this);
        modal.find('.modal-dialog').load("../mods/bibInfoFrame.php?bibid=" + id);
    });

    modal.on('hide.bs.modal', function () {
        $('#modal_info_body').text('Lade Daten ....');
    });

</script>

