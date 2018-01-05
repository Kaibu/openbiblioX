<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

# Forcibly disable register_globals
if (ini_get('register_globals')) {
    foreach ($_REQUEST as $k => $v) {
        unset(${$k});
    }
    foreach ($_ENV as $k => $v) {
        unset(${$k});
    }
    foreach ($_SERVER as $k => $v) {
        unset(${$k});
    }
}

/****************************************************************************
 * Cover css for the magic_quotes disaster.
 * Modified from ryan@wonko.com.
 ****************************************************************************
 */
ini_set('magic_quotes_runtime', 0);
if (ini_get('magic_quotes_gpc')) {
    function magicSlashes($element)
    {
        if (is_array($element))
            return array_map("magicSlashes", $element);
        else
            return stripslashes($element);
    }

    // Remove slashes from all incoming GET/POST/COOKIE data.
    $_GET = array_map("magicSlashes", $_GET);
    $_POST = array_map("magicSlashes", $_POST);
    $_COOKIE = array_map("magicSlashes", $_COOKIE);
    $_REQUEST = array_map("magicSlashes", $_REQUEST);
}

# FIXME - Until I get around to fixing all the notices...
$phpver = explode('.', PHP_VERSION);
if ($phpver[0] == 4) {
    error_reporting(E_ALL ^ E_NOTICE);
} else {
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
}

/**
 * Function to remove all potentially harmful characters from Strings
 * @param $s string data that should be escaped
 * @return string escaped/sanitized data
 */
function H($s)
{
    if (defined('OBIB_CHARSET')) {
        $charset = OBIB_CHARSET;
    } else {
        $charset = "";
    }
    $phpver = explode('.', PHP_VERSION);
    if ($phpver[0] == 4 || ($phpver[0] == 5 && $phpver[1] < 3)) {
        return htmlspecialchars($s, ENT_QUOTES);
    } elseif ($phpver[0] == 5 && $phpver[1] == 3) {
        return htmlspecialchars($s, ENT_QUOTES | ENT_IGNORE);
    } else {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
    }
}

function HURL($s)
{
    return H(urlencode($s));
}

function U($s)
{
    return urlencode($s);
}

function _mkPostVars($arr, $prefix)
{
    $pv = array();
    foreach ($arr as $k => $v) {
        if ($prefix !== NULL) {
            $k = $prefix . "[$k]";
        }
        if (is_array($v)) {
            $pv = array_merge($pv, _mkPostVars($v, $k));
        } else {
            $pv[$k] = $v;
        }
    }
    return $pv;
}

function mkPostVars()
{
    return _mkPostVars($_REQUEST, NULL);
}

# Compatibility
$phpver = explode('.', PHP_VERSION);
if (!function_exists('mysqli_real_escape_string')) {        # PHP < 4.3.0
    function mysqli_real_escape_string($s, $link)
    {
        return mysqli_escape_string($s);
    }
}
if ($phpver[0] >= 5 || ($phpver[0] == 4 && $phpver[1] >= 3)) {
    function obib_setlocale()
    {
        $a = func_get_args();
        call_user_func_array('setlocale', $a);
    }
} else {
    function obib_setlocale()
    {
        $a = func_get_args();
        setlocale($a[0], $a[1]);
    }
}
//whitlist of pages allowed for redirect
$pages = array(
    'opac'=>'../opac/index.php',
    'home'=>'../home/index.php',
    'circulation'=>'../circ/index.php',
    'cataloging'=>'../catalog/index.php',
    'admin'=>'../admin/index.php',
    'reports'=>'../reports/index.php',
    'tagcheck' => '../mods/tag_cleanup.php',
);

require_once('../database_constants.php');
require_once('../shared/global_constants.php');
require_once('../classes/Error.php');
require_once('../classes/Iter.php');
require_once('../classes/Nav.php');

if (!isset($doing_install) or !$doing_install) {
    require_once("../shared/read_settings.php");

    /* Making session user info available on all pages. */
    session_start();
    # Forcibly disable register_globals
    if (ini_get('register_globals')) {
        foreach ($_SESSION as $k => $v) {
            unset(${$k});
        }
    }
}

function timestampToShortDateTime($timestamp)
{
    if($timestamp === "" OR $timestamp == null){return "";};
    return date('d.m H:i', strtotime($timestamp));
}

function timestampToDateTime($timestamp)
{
    if($timestamp === "" OR $timestamp == null){return "";};
    return date('d.m.Y - H:i', strtotime($timestamp));
}
function timestampToDate($timestamp)
{
    if($timestamp === "" OR $timestamp == null){return "";};
    return date('d.m.Y', strtotime($timestamp));
}

function timestampToShortTime($timestamp)
{
    if($timestamp === "" OR $timestamp == null){return "";};
    return date('H:i', strtotime($timestamp));
}

function timestampToShortDate($timestamp)
{
    if($timestamp === "" OR $timestamp == null){return "";};
    return date('d.m', strtotime($timestamp));
}

function makePager($numRes,$page){
    $total = ceil($numRes/OBIB_ITEMS_PER_PAGE);
    if($total < 6 OR $page == $total){
        $max = $total;
    }else{ $max = $page + 2; }
    if($page < 4){
        $i = 1;
        if($total > 4){
            $max = 5;
        }else{$max=$total;};
    }else{ $i = $page - 2; }

    if($page > ($total - 2) && $page > 4){ $i = $page - 4; }

    if($numRes > OBIB_ITEMS_PER_PAGE){
        for(; $i <= $max;$i++){
            if($i == $page){$class = "active";}else{$class = "";}
            echo "<li class='page-item ".$class."'><a class='page-link' href='../mods/jswarning.html' onclick='setPage(".$i.");return false;'>".$i."</a></li>";
        }
    }

    return $total;
}

function statusToString($statusCd){
    switch ($statusCd){
        case "out" : return "Ausgeliehen";
        case "crt" : return "Eingangsablage";
        case "in" : return "Zurück";
        default : return $statusCd;
    }
}