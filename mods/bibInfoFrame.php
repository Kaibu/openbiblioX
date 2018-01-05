<?php
/**
 * User: vabene1111
 * Date: 16.09.2016
 * Time: 11:23
 */

require_once("../shared/common.php");
require_once("../classes/Biblio.php");
require_once("../classes/BiblioQuery.php");

$bibid = $_GET["bibid"];

$biblioQ = new BiblioQuery();
$biblio = $biblioQ->doQuery($bibid);

?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Buch Info</h4>
    </div>
    <div class="modal-body" id="modal_info_body">
        <dl class="row">
            <dt class="col-sm-4">Titel</dt>
            <dd class="col-sm-8"><?php echo $biblio->getTitle() ?></dd>

            <dt class="col-sm-4">Untertitel</dt>
            <dd class="col-sm-8"><?php echo $biblio->getSubtitle() ?></dd>

            <dt class="col-sm-4">Autor</dt>
            <dd class="col-sm-8"><?php echo $biblio->getAuthor() ?></dd>

            <dt class="col-sm-4">Standort</dt>
            <dd class="col-sm-8">
                <?php
                if($biblio->getLocationId() == 1){
                    echo "<span style='color: #319d01'><i class=\"fa fa-fort-awesome\" aria-hidden=\"true\"></i> ".$biblioQ->getLocationString($biblio)."</span>";
                }else if($biblio->getLocationId() == 2){
                    echo "<span style='color: #3e57b2'><i class=\"fa fa-building\" aria-hidden=\"true\"></i> ".$biblioQ->getLocationString($biblio)."</span>";
                }else{
                    echo $biblioQ->getLocationString($biblio);
                }
                ?></dd>
            <dt class="col-sm-4">Sprache</dt>
            <dd class="col-sm-8"><?php echo $biblioQ->getLanguageString($biblio) ?></dd>

            <dt class="col-sm-4">Systematik</dt>
            <dd class="col-sm-8"><b><?php echo $biblioQ->getFullSystematic($biblio); ?></b></dd>

            <dt class="col-sm-4">Medienart</dt>
            <dd class="col-sm-8"><?php echo $biblioQ->getMaterialString($biblio) ?></dd>

            <dt class="col-sm-4">Haupt-/Unterkategorie</dt>
            <dd class="col-sm-8"><?php echo $biblioQ->getMainCategory($biblio)." / ".$biblioQ->getSubCategory($biblio) ?></dd>
        </dl>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
    </div>
</div>
