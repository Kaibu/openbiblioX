<?php
/**
 * User: vabene1111
 * Date: 10.11.2016
 * Time: 12:51
 *
 * Used to log how many requests hit the virtmedio index page
 */

require_once("../shared/common.php");
require_once("../classes/Logger.php");

$logger = new Logger();
$logger->logVirtMedio();