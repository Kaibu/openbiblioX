<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

# Be sure we don't get leftovers.
unset($_SESSION['pageErrors']);
unset($_SESSION['postVars']);
?>


</div>
</div>

                <br><br><br>

                </font>
                <font face="Arial, Helvetica, sans-serif" size="1" color="<?php echo H(OBIB_PRIMARY_FONT_COLOR); ?>">
                    <center>
                        <?php if (OBIB_LIBRARY_URL != "") { ?>
                            <a href="<?php echo H(OBIB_LIBRARY_URL); ?>"><?php echo $headerLoc->getText("footerLibraryHome"); ?></a> |
                        <?php }
                        if (OBIB_OPAC_URL != "") { ?>
                            <a href="<?php echo H(OBIB_OPAC_URL); ?>"><?php echo $headerLoc->getText("footerOPAC"); ?></a> |
                        <?php } ?>
                        <a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=" . H(addslashes(U($helpPage))); ?>')"><?php echo $headerLoc->getText("footerHelp"); ?></a>
                        <br><br>
                        <button id="container_copyright_info_btn" onclick="toggleCopyInfo()" class="btn btn-outline-success"><i class="fa fa-copyright" aria-hidden="true"></i></button>
                        <div id="container_copyright_info" style="visibility: hidden">
                        <a href="http://obiblio.sourceforge.net/"><img src="../images/powered_by_openbiblio.gif" width="125" height="44" border="0"></a>

                        <br><br>
                        <?php echo $headerLoc->getText("footerPoweredBy"); ?> <?php echo H(OBIB_CODE_VERSION); ?>
                        <?php echo $headerLoc->getText("footerDatabaseVersion"); ?> <?php echo H(OBIB_DB_VERSION); ?><br>
                        <?php echo $headerLoc->getText("footerCopyright"); ?> &copy; 2002-2012 Dave Stevens, et al.<br>
                        <?php echo $headerLoc->getText("footerUnderThe"); ?>
                        <a href="../shared/copying.html"><?php echo $headerLoc->getText("footerGPL"); ?></a>
                        </div>
                    </center>
                </font>

<script>
    function toggleCopyInfo(){
        document.getElementById('container_copyright_info').setAttribute('style','visibility: visible');
        document.getElementById('container_copyright_info_btn').setAttribute('style','visibility: hidden');
    }
</script>

</div>
    </body>
</html>
