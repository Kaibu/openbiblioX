<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");
session_cache_limiter(null);

$tab = "circulation";
$nav = "searchform";
$helpPage = "circulation";
$focus_form_name = "barcodesearch";
$focus_form_field = "searchText";

require_once("../shared/logincheck.php");
require_once("../shared/header.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

if (isset($_REQUEST['msg'])) {
    echo '<font class="error">' . H($_REQUEST['msg']) . '</font>';
}
?>

<h1><img src="../images/circ.png" border="0" width="30" height="30" align="top"> <?php echo $loc->getText("indexHeading"); ?></h1>

<form>
    <table class="primary">
        <tr>
            <th valign="top" nowrap="yes" align="left">
                Schnelle Suche
            </th>
        </tr>
        <tr>
            <td nowrap="true" class="primary">
                Nach oder Vorname
                <input type="text" size="30" id="search_ajax" onkeyup="showResult(this.value)" autofocus autocomplete="off">
                <div id="livesearch"></div>
            </td>
        </tr>
    </table>
</form>


<form name="barcodesearch" method="POST" action="../circ/mbr_search.php">
    <table class="primary">
        <tr>
            <th valign="top" nowrap="yes" align="left">
                <?php echo $loc->getText("indexCardHdr"); ?>
            </th>
        </tr>
        <tr>
            <td nowrap="true" class="primary">
                <?php echo $loc->getText("indexCard"); ?>
                <input type="text" name="searchText" size="20" maxlength="20">
                <input type="hidden" name="searchType" value="barcodeNmbr">
                <input type="button" onclick="submitForm('barcodesearch')" value="<?php echo $loc->getText("indexSearch"); ?>" class="button">
            </td>
        </tr>
    </table>
</form>


<form name="phrasesearch" method="POST" action="../circ/mbr_search.php">
    <table class="primary">
        <tr>
            <th valign="top" nowrap="yes" align="left">
                <?php echo $loc->getText("indexNameHdr"); ?>
            </td>
        </tr>
        <tr>
            <td nowrap="true" class="primary">
                <?php echo $loc->getText("indexName"); ?>
                <input type="text" name="searchText" size="30" maxlength="80">
                <input type="hidden" name="searchType" value="lastName">
                <input type="button" onclick="submitForm('phrasesearch')" value="<?php echo $loc->getText("indexSearch"); ?>" class="button">
            </td>
        </tr>
    </table>
</form>

<script>
    function submitForm(form){
        $("form[name*='"+form+"']").submit();
    }

    $('#search_ajax').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            var ref = $('#first_result').attr('href');
            if(!(ref === "")){
                window.location.href = ref;
                return false;
            }
        }
    });

    function showResult(str) {
        if (str.length==0) {
            document.getElementById("livesearch").innerHTML="";
            document.getElementById("livesearch").style.border="0px";
            return;
        }
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        } else {  // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
                document.getElementById("livesearch").innerHTML=this.responseText;
                document.getElementById("livesearch").style.border="1px solid #A5ACB2";
            }
        }
        xmlhttp.open("GET","../circ/ajaxUserSearch.php?q="+str,true);
        xmlhttp.send();
    }
</script>

<?php include("../shared/footer.php"); ?>
