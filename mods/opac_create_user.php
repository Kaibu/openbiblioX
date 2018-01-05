<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

/**
 * Public interface for user account applications
 */

require_once("../shared/common.php");
session_cache_limiter(null);

$tab = "opac";
$nav = "create";
$helpPage = "opac";
$focus_form_name = "phrasesearch";
$focus_form_field = "searchText";
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$lookup = "N";
if (isset($_GET["lookup"])) {
    $lookup = "Y";
    $helpPage = "opacLookup";
}
if (isset($_GET["msg"])) {
    $msg = "<font class=\"error\">" . H($_GET["msg"]) . "</font><br><br>";
} else {
    $msg = "";
}

// -------------------------------------------------------------------
// Error Handling - R
// -------------------------------------------------------------------

// INIT Standard Value
$errno = False;
$cLname = '';
$cFname = '';
$cMatnum = '';
$cAddr = '';
$cEmail = '';
$cAGB = '';


$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : "";
$matnum = isset($_POST['matnum']) ? $_POST['matnum'] : "";
$address = isset($_POST['address']) ? $_POST['address'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
if (isset($_POST['tel'])) {
    $tel = $_POST['tel'];
} else {
    $tel = "";
}

// Check Input Values
if (isset($_POST['lastname']) && $_POST['lastname'] == '') {
    $cLname = "<span class='error'>*</span>";
    $errno = True;
}
if (isset($_POST['firstname']) && $_POST['firstname'] == '') {
    $cFname = "<span class='error'>*</span>";
    $errno = True;
}
if (isset($_POST['matnum']) && ($_POST['matnum'] == '' ||
        !preg_match('/^[0-9]{4,8}$/', $_POST['matnum']))
) {
    $cMatnum = "<span class='error'>*</span>";
    $errno = True;
}
if (isset($_POST['address']) && $_POST['address'] == '') {
    $cAddr = "<span class='error'>*</span>";
    $errno = True;
}
if (isset($_POST['email']) && ($_POST['email'] == '' ||
        #!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',
        #            $_POST['email']))
        !preg_match('/^([^0-9][a-zA-Z0-9_]+)*[@](([a-zA-Z0-9_]+)*[-]*)+[.][a-zA-Z]{2,4}$/', $_POST['email']))

) {
    $cEmail = "<span class='error'>*</span>";
    $errno = True;
}
if (isset($_POST['submit']) && $_POST['agb'] != 'accept') {
    $cAGB = "<span class='error'>*</span>";
    $errno = True;
}

// -------------------------------------------------------------------
// DB -> Eintrag in member_waiting Tabelle
// -------------------------------------------------------------------
if (isset($_POST['submit']) && !$errno) {
    try {
        include("./include_mods.php");
        require_once("../classes/Query.php");
        $q = new Query();
        $sql = sprintf("INSERT INTO 
                      member_waiting(	register_date, last_name, first_name, 
                                      degree, mat_nr, address, birthday, email,tel_nmbr) 
                      VALUES(NOW(), '%s', '%s', NULL, '%s', '%s', NULL, '%s', '%s')",
            $q->escape_data($_POST['lastname']),
            $q->escape_data($_POST['firstname']),
            $q->escape_data($_POST['matnum']),
            $q->escape_data($_POST['address']),
            $q->escape_data($_POST['email']),
            $q->escape_data($_POST['tel'])
        );
        db_query($sql);
        echo '<META HTTP-EQUIV="refresh" content="0;URL=../opac/index.php">';
    } catch (Exception $e) {
        $errno = True;
    }
}

// -------------------------------------------------------------------
// Formular:
// -------------------------------------------------------------------
require_once("../shared/header_opac.php");
?>

<h3><a href="URL" target="_blank">Titel</a></h3>
<br/>
<h5>Antrag auf Zulassung zur Entleihung von Medien</h5>
<p>Hiermit beantrage ich die Zulassung zur Entleihung
    von Medien aus der Mediothek</p>
<?php

// Error Message if one is not correct
if ($errno) {
    print('
  <div>
    <p class="error">Es ist leider ein Fehler aufgetreten. Bitte &uuml;berpr&uuml;fen Sie
    die von Ihnen eingebenen Werte.</p>
  </div>');
}
?>
<div>
    <form id="neuer_nutzer" method='POST' name='formCreateUser' style="border: hidden">
        <div class="row">
            <div class="col-md-6">
                <p><label>Name:<?php echo $cLname; ?></label><input type='text' name='lastname' class="form-control"
                                                                    value='<?php echo $lastname; ?>'/></p>
            </div>
            <div class="col-md-6">
                <p><label>Vorname:<?php echo $cFname; ?></label><input type='text' name='firstname' class="form-control"
                                                                       value='<?php echo $firstname; ?>'/></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <p><label>Telefonnummer:<!--<?php echo $tel; ?>--></label><input type='text' name='tel'
                                                                                 class="form-control"
                                                                                 value='<?php echo $tel; ?>'/></p>
            </div>
            <div class="col-md-6">
                <p><label>E-Mail-Adresse:<?php echo $cEmail; ?></label><input type='text' name='email'
                                                                              class="form-control"
                                                                              value='<?php echo $email; ?>'/></p>
            </div>
        </div>


        <p><label>Adresse:<?php echo $cAddr; ?></label><textarea name='address' rows='4' class="form-control"
                                                                 cols='35'><?php echo $address; ?></textarea></p>

        <p><label>Matrikel-Nr.:<?php echo $cMatnum; ?></label><input type='number' name='matnum' class="form-control"
                                                                     value='<?php echo $matnum; ?>'/></p>

        <h3>Erkl&auml;rung:</h3>
        <p>Hiermit erkläre ich, dass ich die <a href="../mods/Benutzungsordnung.pdf"
                                                target="_blank">Benutzungsordnung</a> der Mediothek zur Kenntnis
            genommen habe und die darin bekannten Benutzungsbedingungen anerkenne.
            <br/>
            Mit der
            Speicherung von Daten für den Ausleihbetrieb bin ich einverstanden.
        </p>


        <p><input type='checkbox' name='agb' value='accept'/>akzeptieren<?php echo $cAGB ?></p>
        <p><input type='reset' name='reset' class="btn btn-outline-warning"/>
            <input type='submit' name='submit' class="btn btn-outline-primary pull-right"/></p>
    </form>
</div>

<?php echo $msg ?>

<?php include("../shared/footer.php"); ?>
