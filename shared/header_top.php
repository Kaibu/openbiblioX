<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

 require_once("../classes/Localize.php");
  $headerLoc = new Localize(OBIB_LOCALE,"shared");

// code html tag with language attribute if specified.

echo "<html";
if (OBIB_HTML_LANG_ATTR != "") {
  echo " lang=\"".H(OBIB_HTML_LANG_ATTR)."\"";
}
echo ">\n";

// code character set if specified
if (OBIB_CHARSET != "") { ?>
<META http-equiv="content-type" content="text/html; charset=<?php echo H(OBIB_CHARSET); ?>">
<?php } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
    <style type="text/css">
        <link rel="shortcut icon" href="../mods/icons/favicon_32x32.ico" >
        <link rel="icon" href="animated_favicon1.gif" type="image/gif" >
        <?php include("../css/style.php");?>>
    </style>
 <title><?php echo H(OBIB_LIBRARY_NAME);?></title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s= sha384-nrOSfDHtoPMzJHjVTdCopGqIqeYETSXhZDFyniQ8ZHcVy08QesyHcnOUpMpqnmWq sha512-qzrZqY/kMVCEYeu/gCm8U2800Wz++LTGK4pitW/iswpCbjwxhsmUwleL1YXaHImptCHG0vJwU7Ly7ROw3ZQoww==" crossorigin="anonymous"></script>
    <script>!window.jQuery && document.write('<script src="../resources/js/jquery-3.1.0.min.js"><\/script>')</script>
    <script src="../resources/js/tether.min.js"></script>
    <script src="../resources/js/bootstrap.min.js"></script>

    <link href="../resources/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../resources/css/font-awesome.min.css">

<script language="JavaScript">
<!--
function popSecondary(url) {
    var SecondaryWin;
    SecondaryWin = window.open(url,"secondary","resizable=yes,scrollbars=yes,width=535,height=400");
    self.name="main";
}
function popSecondaryLarge(url) {
    var SecondaryWin;
    SecondaryWin = window.open(url,"secondary","toolbar=yes,resizable=yes,scrollbars=yes,width=700,height=500");
    self.name="main";
}
function backToMain(URL) {
    var mainWin;
    mainWin = window.open(URL,"main");
    mainWin.focus();
    this.close();
}
-->
</script>


    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

</head>

<body>

<!-- Nav (if elements are added they need to have $tab = id for the highlight js to work -->
<nav class="navbar navbar-dark bg-primary navbar-fixed-top" >
    <div class="nav navbar-nav">
        <a class="nav-item nav-link" id="home" href="../home/index.php"><?php echo $headerLoc->getText("headerHome"); ?></a>
        <a class="nav-item nav-link" id="circulation" href="../circ/index.php"><?php echo $headerLoc->getText("headerCirculation"); ?></a>
        <a class="nav-item nav-link" id="cataloging" href="../catalog/index.php"><?php echo $headerLoc->getText("headerCataloging"); ?></a>
        <a class="nav-item nav-link" id="reports" href="../reports/index.php"><?php echo $headerLoc->getText("headerReports"); ?></a>
        <a class="nav-item nav-link" id="admin" href="../admin/index.php"><?php echo $headerLoc->getText("headerAdmin"); ?></a>
    </div>
</nav>
<div class="container-fluid">
<br/>
<br/>
<br/>
<!-- Header + Image, Info Text -->
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="row" style="text-align: center; display:table-cell;vertical-align:middle;">
            <br/>
            <div class="col-md-3">
                <?php
                if (OBIB_LIBRARY_IMAGE_URL != "") {
                    echo "<img align=\"middle\" src=\"".H(OBIB_LIBRARY_IMAGE_URL)."\" border=\"0\">";
                } ?>
            </div>
            <div class="col-md-9">
                <?php
                if (!OBIB_LIBRARY_USE_IMAGE_ONLY) {
                    echo " <h1>".H(OBIB_LIBRARY_NAME)."</h1>";
                }
                ?>
            </div>
        </div>

    </div>

    <div class="col-md-3" style="padding-right: 30px">
        <br/>
        <div class="pull-left">
            <a target="_blank" href="url_standort1"><span style='color: #319d01'><i class="fa fa-fort-awesome" aria-hidden="true"></i> Standort 1</span></a><br/>
            <i class="fa fa-clock-o" aria-hidden="true"></i> <?php if (OBIB_LIBRARY_HOURS != "") echo H(OBIB_LIBRARY_HOURS);?><br/>
            <i class="fa fa-phone" aria-hidden="true"></i> <?php if (OBIB_LIBRARY_PHONE != "") echo H(OBIB_LIBRARY_PHONE);?>

        </div>
        <div class="pull-right text-right" style="text-align: right">
            <a target="_blank" href="url_standort2"><span style='color: #3e57b2'>Standort 2 <i class="fa fa-building" aria-hidden="true"></i></span></a><br/>
            <?php if (OBIB_GS_LIBRARY_PHONE != "") echo H(OBIB_GS_LIBRARY_PHONE);?> <i class="fa fa-phone" aria-hidden="true"></i><br/>
            <?php if (OBIB_GS_LIBRARY_HOURS != "") echo H(OBIB_GS_LIBRARY_HOURS);?> <i class="fa fa-clock-o" aria-hidden="true"></i>
        </div>
    </div>
</div>


    <script>
    $(document).ready(function(){
        activeTab("<?php echo $tab ?>");
        function activeTab(tabId){
            document.getElementById(tabId).setAttribute('class','nav-item nav-link active');
        }
    });
</script>