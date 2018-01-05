<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/common.php");
$tab = "circulation";
$restrictToMbrAuth = TRUE;
$nav = "edit";
$restrictInDemo = true;
require_once("../shared/logincheck.php");

require_once("../classes/Member.php");
require_once("../classes/MemberQuery.php");
require_once("../classes/DmQuery.php");
require_once("../functions/errorFuncs.php");
require_once("../classes/Localize.php");
# unsere requirements
require_once("../mods/include_mods.php");

$loc = new Localize(OBIB_LOCALE, $tab);

#****************************************************************************
#*  Checking for post vars.  Go back to form if none found.
#****************************************************************************

if (count($_POST) == 0) {

    header("Location: ../circ/index.php");
    exit();
}

#****************************************************************************
#*  Validate data
#****************************************************************************

$mbrid = $_POST["mbrid"];

$mbr = new Member();
$mbr->setMbrid($_POST["mbrid"]);
$mbr->setBarcodeNmbr($_POST["barcodeNmbr"]);
$_POST["barcodeNmbr"] = $mbr->getBarcodeNmbr();
$mbr->setLastChangeUserid($_SESSION["userid"]);
$mbr->setLastName($_POST["lastName"]);
$_POST["lastName"] = $mbr->getLastName();
$mbr->setFirstName($_POST["firstName"]);
$_POST["firstName"] = $mbr->getFirstName();
$mbr->setAddress($_POST["address"]);
$_POST["address"] = $mbr->getAddress();
$mbr->setHomePhone($_POST["homePhone"]);
$_POST["homePhone"] = $mbr->getHomePhone();
$mbr->setWorkPhone($_POST["workPhone"]);
$_POST["workPhone"] = $mbr->getWorkPhone();
$mbr->setEmail($_POST["email"]);
$_POST["email"] = $mbr->getEmail();
$mbr->setMembershipEnd($_POST["membershipEnd"]);
$_POST["membershipEnd"] = $mbr->getMembershipEnd();
$mbr->setClassification($_POST["classification"]);

$dmQ = new DmQuery();
$dmQ->connect();
$customFields = $dmQ->getAssoc('member_fields_dm');
$dmQ->close();
foreach ($customFields as $name => $title) {
    if (isset($_REQUEST['custom_' . $name])) {
        $mbr->setCustom($name, $_REQUEST['custom_' . $name]);
    }
}

$validData = $mbr->validateData();
if (!$validData) {
    $pageErrors["barcodeNmbr"] = $mbr->getBarcodeNmbrError();
    $pageErrors["lastName"] = $mbr->getLastNameError();
    $pageErrors["firstName"] = $mbr->getFirstNameError();
    $pageErrors["membershipEnd"] = $mbr->getMembershipEndError();
    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;
    header("Location: ../circ/mbr_edit_form.php");
    exit();
}

$mbrQ = new MemberQuery();
$mbrQ->connect();

$pageErrors["barcodeNmbr"] = $mbr->getClassification();
$dupBarcode = $mbrQ->DupBarcode($mbr->getBarcodeNmbr(), $mbr->getMbrid());


$barcodeNumber = $mbr->getBarcodeNmbr();
#********************************************************************************************************
#*Check for correct Barcodenumber if $mbr->classification 1 (Dozent)
#*otherwise generate new one
#********************************************************************************************************

if ($mbr->getClassification() == 1) {

    #******************************************************************************************
    #*prüfe aktuelle Barcodenummer auf Vorkommen der Standorte 1 und 2
    #******************************************************************************************

    $location = '/^([G]{1}|[N]{1})([^0-9][^A-Za-z])*$/';
    $suchmuster = "/^([N]{1}|[G]{1})[0-9]{3}/";
    $matrikel = "/^[0-9]{1,}[^A-Za-z]/";

    $hit = preg_match($suchmuster, $barcodeNumber, $treffer, PREG_OFFSET_CAPTURE);

    #*********************************************************************************************************************
    #* ergebnis der letzten abfrage == 0 und richtige Barcodenummer kann die klassifikation zu dozent geändert werden
    #*********************************************************************************************************************
    $sql = "SELECT * FROM member WHERE barcode_nmbr ='$barcodeNumber'";
    db_query($sql);

    if ((mysqli_affected_rows() == 0) && $hit) {

        #**************************************************************************
        #*  Update library member
        #**************************************************************************
        $mbrQ->update($mbr);
        $mbrQ->close();

        #**************************************************************************
        #*  Destroy form values and errors
        #**************************************************************************
        unset($_SESSION["postVars"]);
        unset($_SESSION["pageErrors"]);

        $msg = $loc->getText("mbrEditSuccess");
        #Maintanance#header("Location: ../circ/mbr_edit_form.php?mbrid=".U($mbr->getMbrid()));
        header("Location: ../circ/mbr_view.php?mbrid=" . U($mbr->getMbrid()) . "&reset=Y&msg=" . U($msg));
        exit();
    }
    #******************************************************************************
    #*wenn  Dozentenkennung und  DB treffer >0 dann neue Numemr
    #******************************************************************************
    else {
        if ($hit && (mysqli_affected_rows() >= 0)) {
            #Maintanace#$pageErrors["barcodeNmbr"] = "hit".mysqli_affected_rows();
            #$_SESSION["pageErrors"] = $pageErrors;

            $barcodeNumber = createNewDozentNumber($barcodeNumber);

            #OLD#header("Location: ../circ/mbr_edit_form.php?barcodeNmbr=".U($barcodeNumber));
            header("Location: ../circ/mbr_edit_form.php?mbrid=" . $mbr->getMbrid() . "&barcodeNmbr=" . $barcodeNumber);
            exit();
        }
        #******************************************************************************
        #*wenn Matrikel dann Abbruch
        #******************************************************************************
        if (preg_match($matrikel, $barcodeNumber, $treffer, PREG_OFFSET_CAPTURE)) {
            $pageErrors["barcodeNmbr"] = "Bei einem Klassifikationswechsels Student zu Dozent muss mindestens der
										Standort bei der Benutzernummer mit einem N oder G gekennzeichnet werden";
            header("Location: ../circ/mbr_edit_form.php?mbrid=" . $mbr->getMbrid());
            $_SESSION["pageErrors"] = $pageErrors;
            exit();
        }
        #******************************************************************************
        #*wenn Standortangabe durch N || G neue Nummer kreieren
        #******************************************************************************
        if (preg_match($location, $barcodeNumber, $treffer, PREG_OFFSET_CAPTURE)) {
            $genNumber = createNewDozentNumber($barcodeNumber);
            header("Location: ../circ/mbr_edit_form.php?mbrid=" . $mbr->getMbrid() . "&barcodeNmbr=" . $genNumber);
            exit();
        }
    }
}
#**************************************************************************
#*  Check for duplicate barcode number
#**************************************************************************
if ($dupBarcode) {

    $pageErrors["barcodeNmbr"] = $loc->getText("mbrDupBarcode", array("barcode" => $mbr->getBarcodeNmbr()));
    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;
    header("Location: ../circ/mbr_edit_form.php");
    exit();

}


#**************************************************************************
#*  Update library member
#**************************************************************************
$mbrQ->update($mbr);
$mbrQ->close();

#**************************************************************************
#*  Destroy form values and errors
#**************************************************************************
unset($_SESSION["postVars"]);
unset($_SESSION["pageErrors"]);

$msg = $loc->getText("mbrEditSuccess");
#Maintanance#header("Location: ../circ/mbr_edit_form.php?mbrid=".U($mbr->getMbrid()));
header("Location: ../circ/mbr_view.php?mbrid=" . U($mbr->getMbrid()) . "&reset=Y&msg=" . U($msg));

exit();
?>
