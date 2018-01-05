<?php

/**
 * Created by PhpStorm.
 * User: vabene1111
 * Date: 06.08.2016
 * Time: 11:50
 */
class Navobject
{
    function __construct($id, $url, $local, $group = 0)
    {
        $this->id = $id;
        $this->url = $url;
        $this->local = $local;
        $this->group = $group;
    }

    public static function printNav($navArray, $nav, $navLoc)
    {
        $curNavGroup = Navobject::searchNavGroup($navArray, $nav);

        foreach ($navArray as $x) {
            $insert = "";
            if ($nav == $x->id || ($x->url == "" && $nav == $x->id)) {
                if ($x->group != 0) {
                    $insert = "&nbsp;&nbsp;";
                }
                echo "<a href='" . $x->url . "' class='nav-link active'>" . $insert . $navLoc->getText($x->local) . "</a>";
            } else if ($nav != $x->id && $x->url != "" && ($x->group == 0 || $x->group == $curNavGroup)) {
                if ($x->group > 0) {
                    $insert = "&nbsp;&nbsp;";
                }
                echo "<a href=" . $x->url . " class='nav-link'>" . $insert . $navLoc->getText($x->local) . "</a>";
            }
        }
    }

    private static function searchNavGroup($navArray, $nav)
    {
        foreach ($navArray as $x) {
            if ($x->id == $nav) {
                return $x->group;
            }
        }
        return 0;
    }
}
