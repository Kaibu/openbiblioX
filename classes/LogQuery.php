<?php

/**
 * User: vabene1111
 * Date: 06.10.2016
 * Time: 09:23
 */
class LogQuery extends Query
{
    //SELECT count(bibid) as count FROM log_views WHERE NOT ip LIKE '141.89.%' AND `timestamp` LIKE '2016-10-19%'

    /**
     * Gets GoogleCharts formatted array to display Biblio View views
     * @return mixed - GoogleCharts formatted array
     */
    function totalViewsLastMonth(){
        $res = $this->queryDb("SELECT count(bibid) as count,CONCAT(DAY(timestamp),'-',MONTH(timestamp)) AS log_day,timestamp FROM log_views WHERE timestamp > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY log_day ORDER BY timestamp");
        $ret = "";
        while ($row = $this->fetchRowQ($res)) {
            $ret .= "['".timestampToShortDate($row['timestamp'])."',".$row['count']."],";
        }
        return rtrim($ret, ",");
    }

    /**
     * Gets the most looked up objects for the last 30 days
     * @param int $location_id - location id filter
     * @return bool|mysqli_result - mysqli result if success, else false
     */
    function getMaxViews($location_id = 1){
        $location_id = $this->escape_data($location_id);
        $res = $this->queryDb("SELECT biblio.bibid,title,count(biblio.bibid) as count FROM log_views JOIN biblio ON log_views.bibid=biblio.bibid WHERE timestamp > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND location_id=".$location_id." GROUP BY biblio.bibid ORDER BY count desc LIMIT 5");
        return $res;
    }
}