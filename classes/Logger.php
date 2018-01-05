<?php

/**
 * User: vabene1111
 * Date: 06.10.2016
 * Time: 08:53
 */
class Logger extends Query
{

    /**
     * @param $bibId
     */
    function logBibView($bibId){
        $bibId = $this->escape_data($bibId);

        $this->queryDb("INSERT INTO log_views (bibid, ip) VALUES ('".$bibId."','".$_SERVER['REMOTE_ADDR']."')");
    }

    function logVirtMedio(){

        $this->queryDb("INSERT INTO log_virtmedio (ip) VALUES ('".$_SERVER['REMOTE_ADDR']."')");
    }


}