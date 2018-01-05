<?php

$page = 1;
$numRes = 0;

$bibObj = new Biblio();
$bibQ = new BiblioQuery();
$bibSearchQ = new BiblioSearchQuery();

$result = false;

if (isset($_POST['is_postback'])) {
    if(isset($_POST['search_page'])){
        $page = $_POST['search_page']; //TODO sanitize
    }

    $result = true;
    $bibObj->setMaterialCd($_POST['in_material']);

    $sigArr = explode('.', $_POST['in_sys']);
    $bibObj->setCollectionCd($bibQ->getSignatureId($sigArr[0], $sigArr[1]));
    $bibObj->setLanguageId($_POST['in_language']);

    if ($_POST['in_skill_hear'] == "on") {
        $bibObj->setSkillHear(true);
    }
    if ($_POST['in_skill_speak'] == "on") {
        $bibObj->setSkillSpeak(true);
    }
    if ($_POST['in_skill_write'] == "on") {
        $bibObj->setSkillWrite(true);
    }
    if ($_POST['in_skill_grammar'] == "on") {
        $bibObj->setSkillGrammar(true);
    }
    if ($_POST['in_skill_read'] == "on") {
        $bibObj->setSkillRead(true);
    }

    $bibObj->setLocationId($_POST['in_location']);
    $bibObj->setLanFromLvl($_POST['in_from_lvl']);
    $bibObj->setLanToLvl($_POST['in_to_lvl']);

    $bibObj->setTitle($_POST['in_title']);
    $bibObj->setSubtitle($_POST['in_subtitle']);
    $bibObj->setAuthor($_POST['in_author']);
    $bibObj->setPublisher($_POST['in_publisher']);
    $bibObj->setPubLoc($_POST['in_pub_loc']);
    $bibObj->setPubYear($_POST['in_pub_year']);

    $res = $bibSearchQ->getAdvancedSearchResult($bibObj,$isOpac,$page);
    $numRes = $bibSearchQ->mainRowCount;

    $result = true;
}

$formAttr = array(
    "class" => "form-control",
);

$systematics = getSystematicNumbers();
if($isOpac){$tabAddon= "&tab=opac";}else{$tabAddon= "";}
?>
<div class="row">
    <div class="col-md-10">
        <a href="../<?php if($isOpac){echo "opac";}else{echo "catalog";} ?>/index.php">Zur normalen Suche</a>

        <?php
        if ($result AND isset($res)) {
            if($isOpac){$path= "opac";}else{$path= "catalog";}
            echo "<a href='../".$path."/index.php?search=advanced' class='pull-right'>Neue Suchanfrage</a>";
        }
        ?>
    </div>
</div>

<br/>
<div class="text-md-center">
    <?php
    if ($result AND isset($res)) {
        echo $numRes." Ergebniss(e) gefunden<br/>";
        if($numRes > 80){
            echo "<b>ACHTUNG: Es wurden sehr viele Ergebnisse gefunden, möglicherweise sollten sie die Suche spezifizieren.</b>";
        }
    }
    ?>
</div>

<div class="row" <?php if(!$result OR $numRes < 40){echo "hidden";} ?> >
    <div class="col-md-10 text-md-center">
        <nav aria-label="Seiten Navigation" id="nav_pager">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="../mods/jswarning.html" onclick="setPage(1);return false;" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <?php $total = makePager($numRes,$page);?>
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
            if ($result AND isset($res)) {
                while ($row = $bibQ->fetchRowQ($res)) {
                    echo "<tr><td><dl class='row'><dt class='col-sm-3'>Titel</dt><dd class='col-sm-9'><a href='../shared/biblio_view.php?bibid=" . $row['bibid'] .$tabAddon ."'>" . $row['title'] . "</a></dd>";
                    echo "<dt class='col-sm-3'>Autor</dt><dd class='col-sm-9'>" . $row['author'] . "</dd></dl></td><td align='right'>";
                    echo "<button type='button' class='btn btn-primary btn-lg' data-toggle='modal' data-target='#detail_modal' data-id='" . $row['bibid'] . "'>";
                    echo "<i class='fa fa-info' aria-hidden='true'></i></button></td></tr>";
                }
            }
            ?>
        </table>
    </div>
</div>


<div <?php if ($result) {
    echo "hidden";
} ?>>
    <!-- Include some Javascript Functions for showing stuff when selecting -->
    <script type="text/javascript" src="../mods/lib/javascript.js"></script>

    <form name="search_adv_form" method="POST" style="border:hidden !important;">
        <input type="hidden" name="is_postback" value="1">
        <input type="hidden" name="search_page" value="1" id="search_page">

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Medienart</label>
            <div class="col-xs-8">
                <?php echo dmSelect("material_type_dm", "in_material", "", false, $formAttr,true); ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Systematik</label>
            <div class="col-xs-8">
                <?php echo listQuery($systematics, "in_sys", 0, "class='form-control'") ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Sprache</label>
            <div class="col-xs-8">
                <?php echo dmSelect("nt_sprachen", "in_language", "", false, $formAttr,true); ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Standort</label>
            <div class="col-xs-8">
                <?php echo dmSelect("locations", "in_location", 0, false, $formAttr); ?>
            </div>
        </div>

        <hr/>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Skills</label>
            <div class="col-xs-8">
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox" name="in_skill_hear"> Hören
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox" name="in_skill_speak"> Sprechen
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox" name="in_skill_write"> Schreiben
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox" name="in_skill_grammar"> Grammatik
                </label>
                <label class="form-check-inline">
                    <input class="form-check-input" type="checkbox" name="in_skill_read"> Lesen
                </label>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-text-input" class="col-xs-2 col-form-label">Niveau</label>
            <div class="col-xs-3">
                <label class="form-check-inline">
                    <?php echo db_niveau_sel("in_from_lvl", "", "class=form-control"); ?>
                </label>
            </div>
            <div class="col-xs-2">
                <label class="form-check-inline">
                    bis
                </label>
            </div>
            <div class="col-xs-3">
                <label class="form-check-inline">
                    <?php echo db_niveau_sel("in_to_lvl", "", "class=form-control"); ?>
                </label>
            </div>
        </div>

        <hr/>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Titel</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_title">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Untertitel</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_subtitle">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Autor</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_author">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Verlag</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_publisher">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Erscheinungsort</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_pub_loc">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xs-2 col-form-label">Erscheinungsjahr</label>
            <div class="col-xs-8">
                <input class="form-control" name="in_pub_year">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-8 offset-xs-2">
                <input type="submit" class="btn btn-primary pull-right" value="Suchen">
            </div>
        </div>
    </form>
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
        document.search_adv_form.submit();
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
