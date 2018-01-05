<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");

  $tab = "reports";
  $nav = "mods_feedback";
  $error_msg = null;

  include("../shared/logincheck.php");
  require_once("../classes/Report.php");
  require_once("../classes/Localize.php");
  // DB connection
  require_once("include_mods.php");
  
  $loc = new Localize(OBIB_LOCALE,$tab);
/*  define("REPORT_DEFS_DIR","../reports/defs");

  #****************************************************************************
  #*  Read report definitions
  #****************************************************************************
  $reports = array();
  $errors = array();
  
  if ($handle = opendir(REPORT_DEFS_DIR)) {
    while (($file = readdir($handle)) !== false) { 
      if (preg_match('/^([^._][^.]*)\\.(rpt|php)$/', $file, $m)) {
        list($rpt, $err) = Report::create_e($m[1]);
        if (!$err) {
          if (!isset($reports[$rpt->category()])) {
            $reports[$rpt->category()] = array();
          }
          $reports[$rpt->category()][$rpt->type()] = $loc->getText($rpt->title());
        } else {
          $errors[] = $err;
        }
      } 
    }
    closedir($handle); 
  }

  ksort($reports);
  foreach (array_keys($reports) as $k) {
    asort($reports[$k]);
  }
*/  

//
// Eventhandling
// Feedback archivieren und llöschen
//

// check ob archivieren Button gedrückt
if(isset($_POST['archive']))
{
  if(isset($_POST['feedback']) && is_array($_POST['feedback']) && count($_POST['feedback']) > 0)
  {
    feedback_archive($_POST['feedback']);
  }
  else
  {
    $error_msg = true;
  }
}

if(isset($_POST['del']))
{
  if(isset($_POST['feedback']) && is_array($_POST['feedback']) && count($_POST['feedback']) > 0)
  {
    feedback_del($_POST['feedback']);
  }
  else
  {
    $error_msg = true;
  }
}

if(isset($_POST['send_ans']))
{
  if(isset($_POST['answere']))
  {
    // Email versenden :D
    $fid = $_POST['fid'];
    $res = mysqli_fetch_assoc(db_query("SELECT fid, date, ft.topic AS topic, email, msg
            FROM feedback f, feedback_topics ft 
            WHERE f.topic = ft.id AND fid = $fid"));
    // mehrere Empfänger
    $empfaenger  = $res['email']; // beachten Sie das Komma
    // Betreff
    $betreff = 'OPAC-Feedback';

    // Nachricht
    $nachricht = sprintf("
    <html>
    <head>
      <title>OPAC-Feedback</title>
    </head>
    <body>
      <p>%s</p>
      <p>======================================</p>
      <p>Ihr Feedback:</p>
      <p>%s</p>      
    </body>
    </html>
    ", $_POST['answere'], htmlspecialchars($res['msg']));
    
    //$nachricht = "Test nachrcihtumdansdbsa kdlaskld asldalsdlasdlk";
    $nachricht = wordwrap($nachricht, 70);
    
    // für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
    $headers = "MIME-Version: 1.0\n" ;
    $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
    $headers .= "X-Priority: 1 (Higuest)\n";
    $headers .= "X-MSMail-Priority: High\n";
    $headers .= "Importance: High\n";
    $headers .= "FROM: from@example.com\n";

    // verschicke die E-Mail
    //printf("EMAIL: <br>Empfänger - %s,<br>Betreff - %s,<br>%s", $empfaenger, $betreff, $nachricht);
    //$status = mail('mail@example.com', $betreff, $nachricht, $headers);
    //var_dump($status);
  }
  else
  {
    $error_msg = true;
  }
}

function feedback_archive($feedbacks)
{
  foreach($feedbacks as $fb)
  {
    $sql = "UPDATE feedback SET new=0 WHERE fid=$fb";
    //echo $sql;
    db_query($sql);
  }
}

function feedback_del($feedbacks)
{
  foreach($feedbacks as $fb)
  {
    $sql = "DELETE FROM feedback WHERE fid=$fb";
    //echo $sql;
    db_query($sql);
  }
}

function feedback_ans($fid)
{
  $res = mysqli_fetch_assoc(db_query("SELECT fid, date, ft.topic AS topic, email, msg
            FROM feedback f, feedback_topics ft 
            WHERE f.topic = ft.id AND fid = $fid"));
  
  $standard = "";
  
  $form = sprintf("
  <form name='form_ans' method='POST' action=''>
    <h2>Antwort:</h2>
    <p>Email: %s</p>
    <p>Thema: %s</p>
    <textarea rows=\"10\" cols=\"50\" readonly>%s</textarea><br />
    <textarea rows=\"10\" cols=\"50\" name='answere'>%s</textarea><br />
    <input type='hidden' name='fid' value='$fid' />
    <input type='submit' name='send_ans' value='Abschicken' />
  </form>", $res['email'], $res['topic'], $res['msg'], $standard);
  
  print($form);
}

  include("../shared/header.php");
?>

<h1>
  <img src="../images/reports.png" border="0" width="30" height="30">
  <?php echo "Feedback";?>
</h1>

<?php
//
// Antwort Formular ausgeben
//
if(isset($_POST['ans']))
{
  if(isset($_POST['feedback']) && is_array($_POST['feedback']) && count($_POST['feedback']) > 0)
  {
    feedback_ans($_POST['feedback'][0]);
  }
  else
  {
    $error_msg = true;
  }
}
?>

<form action="" method="POST">
<table>
    <?php
    if(isset($error_msg) && $error_msg != '') 
      print('<p>Es ist ein Fehler aufgetreten. Bitte &uuml;berpr&uuml;fen Sie Ihre Eingabe!</p>');
    ?>
    <tr><th>Details</th><th>Nachricht</th><th>Optionen</th></tr>
    <?php
    $sql = "SELECT fid, date, ft.topic AS topic, email, msg
            FROM feedback f, feedback_topics ft 
            WHERE f.topic = ft.id AND f.new = 1";
    $res = db_query($sql);
    while($row = mysqli_fetch_assoc($res))
    {
        printf("
            <tr>
            <td><table>
                <tr><td>%s</td></tr><tr><td>%s</td></tr><tr><td>%s</td></tr>
            </table></td>
            <td><textarea name=\"msg\" rows=\"10\" cols=\"50\" readonly>%s</textarea></td>
            <td>
            <input type='checkbox' name='feedback[]' value='%s' /></td>
            </tr>", 
            $row['date'], $row['topic'], 
            $row['email'], $row['msg'], $row['fid']
        );
    }
    ?>
    <tr>
        <td></td>
        <td></td>
        <td>
            <input type="submit" name="archive" value="Archivieren" />
            <input type="submit" name="ans" value="Beantworten" />
            <input type="submit" name="del" value="L&ouml;schen" />
        </td>
    </tr>
</table>
</form>

<?php include("../shared/footer.php"); ?>
