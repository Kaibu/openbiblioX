<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 *
 *
 * Opac Startseite - wird Nutzern, die nicht eingeloggt sind angezeigt.
 */

require_once("../shared/common.php");
session_cache_limiter(null);

$tab = "opac";
$nav = "home";
$helpPage = "opac";
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$lookup = "N";
if (isset($_GET["lookup"])) {
    $lookup = "Y";
    $helpPage = "opacLookup";
}

$isOpac = true;

require_once("../shared/header_opac.php");

require_once("../classes/BiblioSearchQuery.php");

if (isset($_GET['search'])) {
    if ($_GET['search'] == "advanced") {

        require_once("../functions/inputFuncs.php");
        require_once("../mods/include_mods.php");

        require_once("../classes/Biblio.php");
        require_once("../classes/BiblioQuery.php");


        include("../catalog/biblio_adv_search.php");
    } else {
        include("../catalog/biblio_search.php");
    }
} else{
    include("../catalog/biblio_search.php");
}


include("../shared/footer.php");

