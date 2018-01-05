<?php

/**
 * User: vabene1111
 * Date: 06.10.2016
 * Time: 14:57
 */
class ReportQuery extends Query
{
    var $numRes = 0;

    /**
     * @param int $page
     * @param int $loc
     * @param bool $reverted
     * @return bool|mysqli_result all copies with status out
     */
    function mediaOutReport($page = 1,$loc = 0,$reverted = false){
        $con = "";
        if($loc != 0){
            $con = " AND location_id = ".$loc;
        }
        $order = "ASC";
        if($reverted){
            $order = "DESC";
        }
        $res = $this->queryDb("SELECT SQL_CALC_FOUND_ROWS biblio.bibid,title,author,biblio_copy.barcode_nmbr,member.mbrid,member.first_name,member.last_name,status_begin_dt,due_back_dt FROM biblio_copy JOIN biblio ON biblio.bibid=biblio_copy.bibid JOIN member ON biblio_copy.mbrid = member.mbrid WHERE status_cd='out'".$con." ORDER BY status_begin_dt ".$order." LIMIT ".OBIB_ITEMS_PER_PAGE." OFFSET ".(($page-1)*OBIB_ITEMS_PER_PAGE));
        $this->numRes = (int) implode($this->queryDb('SELECT found_rows();')->fetch_row());
        return $res;
    }

    /**
     * @param int $page
     * @param int $loc
     * @param bool $reverted
     * @return bool|mysqli_result all copies with status out
     */
    function mediaOverReport($page = 1, $loc = 0, $reverted=  false){
        $con = "";
        if($loc != 0){
            $con = " AND location_id = ".$loc;
        }
        $order = "ASC";
        if($reverted){
            $order = "DESC";
        }
        $res = $this->queryDb("SELECT SQL_CALC_FOUND_ROWS biblio.bibid,title,author,biblio_copy.barcode_nmbr,member.mbrid,member.first_name,member.last_name,status_begin_dt,due_back_dt FROM biblio_copy JOIN biblio ON biblio.bibid=biblio_copy.bibid JOIN member ON biblio_copy.mbrid = member.mbrid WHERE member.classification = 2 AND status_cd='out' AND NOW() > due_back_dt".$con." ORDER BY status_begin_dt ".$order." LIMIT ".OBIB_ITEMS_PER_PAGE." OFFSET ".(($page-1)*OBIB_ITEMS_PER_PAGE));
        $this->numRes = (int) implode($this->queryDb('SELECT found_rows();')->fetch_row());
        return $res;
    }

    /**
     * @return bool|mysqli_result all copies with status out
     */
    function statusHistoryReport($page = 1, $loc = 0){
        $con = "";
        if($loc != 0){
            $con = " WHERE biblio.location_id = ".$loc;
        }
        $res = $this->queryDb("SELECT SQL_CALC_FOUND_ROWS h.bibid,title,h.copyid,biblio_copy.barcode_nmbr,h.mbrid,h.status_cd,h.status_begin_dt,h.due_back_dt FROM biblio_status_hist As h JOIN biblio ON biblio.bibid = h.bibid JOIN biblio_copy ON biblio_copy.copyid = h.copyid AND biblio_copy.bibid = h.bibid".$con." ORDER BY status_begin_dt DESC 
                               LIMIT ".OBIB_ITEMS_PER_PAGE." OFFSET ".(($page-1)*OBIB_ITEMS_PER_PAGE));
        $this->numRes = (int) implode($this->queryDb('SELECT found_rows();')->fetch_row());
        return $res;
    }

    /**
     * @return bool|mysqli_result all copies with status out
     * hld
     */
    function statusHoldReport($page = 1, $loc = 0){
        $con = "";
        if($loc != 0){
            $con = " WHERE biblio.location_id = ".$loc;
        }
        $res = $this->queryDb("SELECT SQL_CALC_FOUND_ROWS h.bibid,title,h.copyid,biblio_copy.barcode_nmbr,h.mbrid,h.hold_begin_dt FROM biblio_hold As h JOIN biblio ON biblio.bibid = h.bibid JOIN biblio_copy ON biblio_copy.copyid = h.copyid AND biblio_copy.bibid = h.bibid".$con." ORDER BY status_begin_dt DESC 
                               LIMIT ".OBIB_ITEMS_PER_PAGE." OFFSET ".(($page-1)*OBIB_ITEMS_PER_PAGE));
        $this->numRes = (int) implode($this->queryDb('SELECT found_rows();')->fetch_row());
        return $res;
    }
}