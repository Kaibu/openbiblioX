<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
require_once("../shared/common.php");

$tab = "reports";
$nav = "mods_feedback_archiv";
$error_msg = null;

include("../shared/logincheck.php");
require_once("../classes/Report.php");
require_once("../classes/Localize.php");
// DB connection
require_once("include_mods.php");

$loc = new Localize(OBIB_LOCALE,$tab);


include("../shared/header.php");

//
// Löschen von Feedbacks
//
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

function feedback_del($feedbacks)
{
  foreach($feedbacks as $fb)
  {
    $sql = "DELETE FROM feedback WHERE fid=$fb";
    //echo $sql;
    db_query($sql);
  }
}
?>

<h1>
  <img src="../images/reports.png" border="0" width="30" height="30">
  <?php echo "Feedback-Archiv";?>
</h1>


<form action="" method="POST">
<table>
    <tr><th>Details</th><th>Nachricht</th></tr>
    <?php
    $sql = "SELECT fid, date, ft.topic AS topic, email, msg
            FROM feedback f, feedback_topics ft 
            WHERE f.topic = ft.id AND f.new = 0
            ORDER BY date DESC";
    $res = db_query($sql);
    while($row = mysqli_fetch_assoc($res))
    {
        printf("
            <tr>
            <td>
              <table>
                  <tr><td>%s</td></tr><tr><td>%s</td></tr><tr><td>%s</td></tr>
              </table>
            </td>
            <td>
              <textarea name=\"msg\" rows=\"10\" cols=\"50\" readonly>%s</textarea>
            </td>
            <td>
              <input type='checkbox' name='feedback[]' value='%s' />
            </td>
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
            <input type="submit" name="del" value="L&ouml;schen" />
        </td>
    </tr>
</table>
</form>

<?php include("../shared/footer.php"); ?>
