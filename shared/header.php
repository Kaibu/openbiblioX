<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

if (preg_match('/[^a-zA-Z0-9_]/', $tab)) {
    Fatal::internalError("Possible security violation: bad tab name");
    exit(); # just in case
}

include("../shared/header_top.php"); ?>
<br/>

<div class="row">
    <div class="col-md-2 offset-md-1">
        <nav class="nav nav-pills nav-stacked">
            <?php require_once ("../navbars/Navobject.php"); ?>
            <?php include("../navbars/" . $tab . ".php"); ?>
            <?php   ?>
        </nav>
    </div>

    <div class="col-md-8">

