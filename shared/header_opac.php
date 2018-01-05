<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../classes/Localize.php");
$headerLoc = new Localize(OBIB_LOCALE, "shared");

// code html tag with language attribute if specified.
echo "<html";
if (OBIB_HTML_LANG_ATTR != "") {
    echo " lang=\"" . H(OBIB_HTML_LANG_ATTR) . "\"";
}
echo ">\n";

// code character set if specified
if (OBIB_CHARSET != "") { ?>
    <META http-equiv="content-type" content="text/html; charset=<?php echo H(OBIB_CHARSET); ?>">
<?php } ?>

<style type="text/css">
    <?php include("../css/style.php");?>
</style>
<meta name="description" content="OpenBiblio Library Automation System">
<title><?php echo H(OBIB_LIBRARY_NAME); ?></title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"
        integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s= sha384-nrOSfDHtoPMzJHjVTdCopGqIqeYETSXhZDFyniQ8ZHcVy08QesyHcnOUpMpqnmWq sha512-qzrZqY/kMVCEYeu/gCm8U2800Wz++LTGK4pitW/iswpCbjwxhsmUwleL1YXaHImptCHG0vJwU7Ly7ROw3ZQoww=="
        crossorigin="anonymous"></script>
<script>!window.jQuery && document.write('<script src="../resources/js/jquery-3.1.0.min.js"><\/script>')</script>
<script src="../resources/js/tether.min.js"></script>
<script src="../resources/js/bootstrap.min.js"></script>

<link href="../resources/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../resources/css/font-awesome.min.css">

<script language="JavaScript">
    <!--
    function popSecondary(url) {
        var SecondaryWin;
        SecondaryWin = window.open(url, "secondary", "resizable=yes,scrollbars=yes,width=535,height=400");
    }
    function returnLookup(formName, fieldName, val) {
        window.opener.document.forms[formName].elements[fieldName].value = val;
        window.opener.focus();
        this.close();
    }
    -->
</script>


</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" <?php
if (isset($focus_form_name) && ($focus_form_name != "")) {
    if (preg_match('/^[a-zA-Z0-9_]+$/', $focus_form_name)
        && preg_match('/^[a-zA-Z0-9_]+$/', $focus_form_field)
    ) {
        echo 'onLoad="self.focus();document.' . $focus_form_name . "." . $focus_form_field . '.focus()"';
    }
} ?> >

<br/>
<!-- Header + Image, Info Text -->
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="row" style="text-align: center; display:table-cell;vertical-align:middle;">
            <br/>
            <div class="col-md-3">
                <?php
                if (OBIB_LIBRARY_IMAGE_URL != "") {
                    echo "<img align=\"middle\" src=\"" . H(OBIB_LIBRARY_IMAGE_URL) . "\" border=\"0\">";
                } ?>
            </div>
            <div class="col-md-9">
                <?php
                if (!OBIB_LIBRARY_USE_IMAGE_ONLY) {
                    echo " <h1>" . H(OBIB_LIBRARY_NAME) . "</h1>";
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
<br/>

<div class="row">
    <div class="col-md-2 offset-md-1">
        <nav class="nav nav-pills nav-stacked">
            <?php require_once ("../navbars/Navobject.php"); ?>
            <?php include("../navbars/opac.php"); ?>
        </nav>
    </div>

    <div class="col-md-8">


