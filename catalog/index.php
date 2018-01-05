<?php

require_once("../shared/common.php");
session_cache_limiter(null);

$tab = "cataloging";
$nav = "searchform";
$focus_form_name = "barcodesearch";
$focus_form_field = "searchText";

require_once("../shared/logincheck.php");
require_once("../shared/header.php");
require_once("../classes/Localize.php");
$loc = new Localize(OBIB_LOCALE, $tab);

$isOpac = false;

?>

<h1><img src="../images/catalog.png" border="0" width="30" height="30" align="top"> <?php echo $loc->getText("indexHdr"); ?></h1>

<?php

require_once("../classes/BiblioSearchQuery.php");
if(isset($_GET['search'])){
    if($_GET['search'] == "advanced"){

        require_once("../functions/inputFuncs.php");
        require_once("../mods/include_mods.php");

        require_once("../classes/Biblio.php");
        require_once("../classes/BiblioQuery.php");


        include("../catalog/biblio_adv_search.php");
    }else{
        include("../catalog/biblio_search.php");
    }
}else{
    include("../catalog/biblio_search.php");
}

include("../shared/footer.php");
