<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");
require_once("include_mods.php");
session_cache_limiter(null);

$tab = "opac";
$nav = "feedback";
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


// -------------------------------------------------------------------
// Neu eingefügt
// - R
// -------------------------------------------------------------------

$errno = False;

$cTopic = "";
$cMSG = "";
$cMail = "";

$topic = isset($_POST['topic']) ? $_POST['topic'] : -1;
$textmsg = isset($_POST['msg']) ? $_POST['msg'] : "";
$mail = isset($_POST['email']) ? $_POST['email'] : "";


if (isset($_POST['topic']) && (int)$_POST['topic'] == 0) {
    $cTopic = "<span class='error'>*</span>";
    $errno = True;
}
if (isset($_POST['email']) && !isMail($_POST['email'])) {
    $cMail = "<span class='error'>*</span>";
    $errno = True;
}
if (isset($_POST['msg']) && $_POST['msg'] == '') {
    $cMSG = "<span class='error'>*</span>";
    $errno = True;
}

// speichert Feedback in DB -> feedback Tabelle
if (isset($_POST['submit']) &&
    isset($_POST['topic']) && isset($_POST['email']) && isset($_POST['msg']) &&
    (int)$_POST['topic'] > 0 && isMail($_POST['email']) && $_POST['msg'] != ''
) {
    try {
        require_once("../classes/Query.php");
        $q = new Query();
        $sql = sprintf("INSERT INTO feedback(	date, topic, email, msg) VALUES(NOW(), %s, '%s', '%s')",
            $q->escape_data($_POST['topic']),
            $q->escape_data($_POST['email']),
            $q->escape_data($_POST['msg'])
        );
        if(!db_query($sql)){
            $msg = "<div class=\"alert alert-warning\" role=\"alert\">Feedback konnte nicht gespeichert werden</div>";
        }else{
            $msg = "<div class=\"alert alert-success\" role=\"alert\">Feedback erfolgreich eingesendet</div>";
        }

    } catch (Exception $e) {
        $errno = True;
    }
}
// prüft ob $mail dem muster einer EmailAdresse entspricht
function isMail($mail)
{
    /* TODO for now this is disabled as there isn't any good way to regex email addresses, should develope a grammar pattern function in common.php
    if (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $mail)) {
        return true;
    } else {
        return false;
    }
    */
    return true;
}

// holt Topics aus DB und Ausgabe als <option>...</option>
function list_topics($sel = -1)
{
    if ($sel == -1) {
        $res = db_query("SELECT * FROM feedback_topics");
        while ($row = mysqli_fetch_assoc($res)) {
            printf("<option value=\"%s\">%s</option>", $row['id'], $row['topic']);
        }
    } else {
        $res = db_query("SELECT * FROM feedback_topics");
        while ($row = mysqli_fetch_assoc($res)) {
            if ((int)$row['id'] == $sel)
                printf("<option value=\"%s\" selected='selected'>%s</option>", $row['id'], $row['topic']);
            else
                printf("<option value=\"%s\">%s</option>", $row['id'], $row['topic']);
        }
    }
}

// -------------------------------------------------------------------
// Ende Neu eingefügt
// -------------------------------------------------------------------
require_once("../shared/header_opac.php");
?>

<!-- Freie Suche -->
<h1>Online-Feedback</h1>
<p>Willkommen bei der Online Feedback Option des OPAC. Bitte teilen Sie uns
    Ihr Anliegen mit, sodass wir uns schnellst m&ouml;glich darum k&uuml;mmern k&ouml;nnen.</p>
<?php
// Error Message if one is not correct
if ($errno) {
    print('
    <div>
      <p class="error">Es ist leider ein Fehler aufgetreten. Bitte &uuml;berpr&uuml;fen Sie
      die von Ihnen eingebenen Werte.</p>
    </div>');
}

if(isset($msg)){
    echo $msg;
    $mail = "";
    $textmsg = "";
    $topic = -1;
}
?>
<form name="phrasesearch" method="POST" action="" style="border: hidden">
    <br/>
    <table class="primary">
        <tr>
            <th valign="top" nowrap="yes" align="left">Feedback-Bogen:
            </td>
        </tr>
        <tr>
            <td nowrap="true" class="primary"><?php echo $cTopic; ?>
                <select name="topic" class="form-control">
                    <option value="0">--Thema ausw&auml;hlen--</option>
                    <?php list_topics($topic); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <br/>
                Absender(Email-Adresse): <?php echo $cMail; ?><input class="form-control" type="email" name="email"
                                                                     value="<?php echo $mail; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                Feedback:
                <textarea name="msg" rows="15" cols="50"
                          class="form-control"><?php echo $textmsg; ?></textarea><?php echo $cMSG; ?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Absenden" class="btn" name="submit">
            </td>
        </tr>
    </table>
</form>

<?php include("../shared/footer.php"); ?>
