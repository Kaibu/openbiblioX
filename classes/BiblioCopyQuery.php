<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/global_constants.php");
require_once("../classes/Query.php");
require_once("../classes/BiblioCopy.php");

/******************************************************************************
 * BiblioCopyQuery data access component for library bibliography copies
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class BiblioCopyQuery extends Query
{
    var $_rowCount = 0;
    var $_loc;

    function BiblioCopyQuery()
    {
        $this->Query();
        $this->_loc = new Localize(OBIB_LOCALE, "classes");
    }

    function getRowCount()
    {
        return $this->_rowCount;
    }


    /****************************************************************************
     * Executes a query to select ONLY ONE COPY
     * @param string $bibid bibid of bibliography copy to select
     * @param string $copyid copyid of bibliography copy to select
     * @return bool|BiblioCopy returns copy or false, if error occurs
     * @access public
     ****************************************************************************
     */
    function doQuery($bibid, $copyid)
    {
        # setting query that will return all the data
        $sql = $this->mkSQL("select biblio_copy.*, "
            . " greatest(0,to_days(sysdate()) - to_days(biblio_copy.due_back_dt)) days_late "
            . "from biblio_copy "
            . "where biblio_copy.bibid = %N"
            . " and biblio_copy.copyid = %N",
            $bibid, $copyid);

        $res = $this->queryDb($sql);
        if (!$res) {
            return false;
        }
        $this->_rowCount = $res->num_rows;
        return $this->fetchCopy($res);
    }

    /****************************************************************************
     * Executes a query to select ONLY ONE COPY by barcode
     * @param string $barcode barcode of bibliography copy to select
     * @return bool|BiblioCopy returns copy or true if barcode doesn't exist,
     *              false on error
     * @access public
     ****************************************************************************
     */
    function queryByBarcode($barcode)
    {
        # setting query that will return all the data
        $sql = $this->mkSQL("select biblio_copy.*, "
            . "greatest(0,to_days(sysdate()) - to_days(biblio_copy.due_back_dt)) days_late "
            . "from biblio_copy where biblio_copy.barcode_nmbr = %Q",
            $barcode);

        $res = $this->queryDb($sql);
        if (!$res) {
            return false;
        }
        $this->_rowCount = $res;
        if ($this->_rowCount == 0) {
            return true;
        }
        return $this->fetchCopy($res);
    }

    function maybeGetByBarcode($barcode)
    {
        $sql = $this->mkSQL("select biblio_copy.*, "
            . "greatest(0,to_days(sysdate()) - to_days(biblio_copy.due_back_dt)) days_late "
            . "from biblio_copy where biblio_copy.barcode_nmbr = %Q",
            $barcode);
        $row = $this->select01($sql);
        if (!$row)
            return NULL;
        return $this->_mkObj($row);
    }


    /****************************************************************************
     * Executes a query to select ALL COPIES belonging to a particular bibid
     * @param string $bibid bibid of bibliography copies to select
     * @return boolean returns false, if error occurs
     * @access public
     ****************************************************************************
     */
    function execSelect($bibid)
    {
        # setting query that will return all the data
        $sql = $this->mkSQL("select biblio_copy.* "
            . ",greatest(0,to_days(sysdate()) - to_days(biblio_copy.due_back_dt)) days_late "
            . "from biblio_copy where biblio_copy.bibid = %N",
            $bibid);
        $res = $this->queryDb($sql);
        if (!$res) {
            return false;
        }
        $this->_rowCount = $res->num_rows;
        return $res;
    }

    /****************************************************************************
     * Fetches a row from the query result and populates the BiblioCopy object.
     * @return bool|BiblioCopy returns bibliography copy or false if no more
     *                    bibliography copies to fetch
     * @access public
     ****************************************************************************
     */
    function fetchCopy($res)
    {
        $array = $this->fetchRowQ($res);
        if ($array == false) {
            return false;
        }
        return $this->_mkObj($array);
    }

    function _mkObj($array)
    {
        $copy = new BiblioCopy();
        $copy->setBibid($array["bibid"]);
        $copy->setCopyid($array["copyid"]);
        $copy->setCreateDt($array["create_dt"]);
        $copy->setCopyDesc($array["copy_desc"]);
        $copy->setBarcodeNmbr($array["barcode_nmbr"]);
        $copy->setStatusCd($array["status_cd"]);
        $copy->setStatusBeginDt($array["status_begin_dt"]);
        $copy->setDueBackDt($array["due_back_dt"]);
        $copy->setDaysLate($array["days_late"]);
        $copy->setMbrid($array["mbrid"]);
        $copy->setRenewalCount($array["renewal_count"]);
        $copy->_custom = $this->getCustomFields($array['bibid'], $array['copyid']);
        return $copy;
    }

    function getCustomFields($bibid, $copyid)
    {
        $sql = $this->mkSQL('SELECT * FROM biblio_copy_fields '
            . 'WHERE bibid=%N AND copyid=%N', $bibid, $copyid);
        $rows = $this->select($sql);
        $fields = array();
        while ($r = $rows->next()) {
            $fields[$r['code']] = $r['data'];
        }
        return $fields;
    }

    function setCustomFields($bibid, $copyid, $fields)
    {
        $sql = $this->mkSQL('DELETE FROM biblio_copy_fields '
            . 'WHERE bibid=%N AND copyid=%N', $bibid, $copyid);
        $this->queryDb($sql);
        foreach ($fields as $code => $data) {
            $sql = $this->mkSQL('INSERT INTO biblio_copy_fields (bibid, copyid, code, data) '
                . 'VALUES (%N, %N, %Q, %Q)', $bibid, $copyid, $code, $data);
            $this->queryDb($sql);
        }
    }

    /****************************************************************************
     * Returns true if barcode number already exists
     * @param string $barcode Bibliography barcode number
     * @param int|string $bibid Bibliography id
     * @param int $copyid
     * @return bool returns true if barcode already exists
     * @access private
     ***************************************************************************
     */
    function _dupBarcode($barcode, $bibid = 0, $copyid = 0)
    {
        $sql = $this->mkSQL("SELECT count(*) FROM biblio_copy "
            . "WHERE barcode_nmbr = %Q "
            . " AND NOT (bibid = %N AND copyid = %N) ",
            $barcode, $bibid, $copyid);
        $res = $this->queryDb($sql);
        if (!$res) {
            return false;
        }
        $array = $this->fetchRowQ($res,OBIB_NUM);
        if ($array[0] > 0) {
            return true;
        }
        return false;
    }

    /****************************************************************************
     * Returns the next copyid number available in the biblio_copy copyid field for a given biblio
     * @return boolean returns false, if error occurs
     * @access private
     ****************************************************************************
     */
    function nextCopyid($bibid)
    {
        $sql = $this->mkSQL("SELECT max(copyid) AS lastNmbr FROM biblio_copy "
            . "WHERE biblio_copy.bibid = %Q",
            $bibid);
        $res = $this->queryDb($sql);
        if (!$res) {
            return false;
        }
        $array = $this->fetchRowQ($res);
        $nmbr = $array["lastNmbr"];
        return $nmbr + 1;
    }

    /****************************************************************************
     * Inserts a new bibliography copy into the biblio_copy table.
     * @param BiblioCopy $copy bibliography copy to insert
     * @return boolean returns false, if error occurs
     * @access public
     ****************************************************************************
     */
    function insert($copy)
    {
        # checking for duplicate barcode number
        $dupBarcode = $this->_dupBarcode($copy->getBarcodeNmbr());

        if ($dupBarcode) {
            $this->_errorOccurred = true;
            $this->_error = $this->_loc->getText("biblioCopyQueryErr2", array("barcodeNmbr" => $copy->getBarcodeNmbr()));
            return false;
        }
        $sql = $this->mkSQL("INSERT INTO biblio_copy VALUES (%N"
            . ",NULL, now(), %Q, %Q, %Q, sysdate(), ",
            $copy->getBibid(), $copy->getCopyDesc(),
            $copy->getBarcodeNmbr(), $copy->getStatusCd());
        if ($copy->getDueBackDt() == "") {
            $sql .= "null, ";
        } else {
            $sql .= $this->mkSQL("%Q, ", $copy->getDueBackDt());
        }
        if ($copy->getMbrid() == "") {
            $sql .= "null,";
        } else {
            $sql .= $this->mkSQL("%Q,", $copy->getMbrid());
        }
        $sql .= " 0)"; //Default renewal count to zero
        $ret = $this->queryDb($sql);
        if (!$ret) {
            return $ret;
        }
        $copyid = $this->getInsertID();
        $this->setCustomFields($copy->getBibid(), $copyid, $copy->_custom);
        return $ret;
    }

    /**
     * Update a copy.  Will not change the copy's status information.
     * @param BiblioCopy $copy
     * @return bool|mysqli_result
     */
    function update($copy)
    {
        # checking for duplicate barcode number
        $dupBarcode = $this->_dupBarcode($copy->getBarcodeNmbr(), $copy->getBibid(), $copy->getCopyid());
        if ($dupBarcode) {
            $this->_errorOccurred = true;
            $this->_error = $this->_loc->getText("biblioCopyQueryErr2", array("barcodeNmbr" => $copy->getBarcodeNmbr()));
            return false;
        }
        $sql = $this->mkSQL("UPDATE biblio_copy SET "
            . "copy_desc=%Q, barcode_nmbr=%Q "
            . "WHERE bibid=%N AND copyid=%N",
            $copy->getCopyDesc(), $copy->getBarcodeNmbr(),
            $copy->getBibid(), $copy->getCopyid());
        $ret = $this->queryDb($sql);
        if (!$ret) {
            return $ret;
        }
        $this->setCustomFields($copy->getBibid(), $copy->getCopyid(), $copy->_custom);
        return $ret;
    }

    /**
     * Update a copy's status information, e.g. for check in and check out.
     * @param BiblioCopy $copy
     * @return bool|mysqli_result
     */
    function updateStatus($copy)
    {
        $sql = $this->mkSQL("UPDATE biblio_copy SET "
            . "status_cd=%Q, "
            . "renewal_count=%N, ",
            $copy->getStatusCd(),
            $copy->getRenewalCount());

        if ($copy->getStatusBeginDt() != "") {
            $sql .= $this->mkSQL("status_begin_dt=%Q, ", $copy->getStatusBeginDt());
        } else {
            $sql .= "status_begin_dt=sysdate(), ";
        }
        if ($copy->getDueBackDt() != "") {
            $sql .= $this->mkSQL("due_back_dt=%Q, ",
                $copy->getDueBackDt());
        } else {
            $sql .= "due_back_dt=null, ";
        }
        if ($copy->getMbrid() != "") {
            $sql .= $this->mkSQL("mbrid=%N ", $copy->getMbrid());
        } else {
            $sql .= "mbrid=null ";
        }
        $sql .= $this->mkSQL("where bibid=%N and copyid=%N",
            $copy->getBibid(), $copy->getCopyid());
        return $this->queryDb($sql);
    }

    /****************************************************************************
     * Deletes a copy from the biblio_copy table.
     * @param string $bibid bibliography id of copy to delete
     * @param int $copyid optional copy id of copy to delete.  If none
     *               supplied then all copies under a given bibid will be deleted.
     * @return boolean returns false, if error occurs
     * @access public
     ****************************************************************************
     */
    function delete($bibid, $copyid = 0)
    {
        $sql = $this->mkSQL("DELETE FROM biblio_copy_fields WHERE bibid=%N ", $bibid);
        if ($copyid > 0) {
            $sql .= $this->mkSQL("and copyid=%N ", $copyid);
        }
        $this->queryDb($sql);
        $sql = $this->mkSQL("DELETE FROM biblio_copy WHERE bibid = %N", $bibid);
        if ($copyid > 0) {
            $sql .= $this->mkSQL(" and copyid = %N", $copyid);
        }
        return $this->queryDb($sql);
    }

    /**
     * Table is not used, function is not needed
     * @deprecated
     * @param $code
     */
    function deleteCustomField($code)
    {
        $sql = $this->mkSQL("DELETE FROM biblio_copy_fields WHERE code = %Q ", $code);
        $this->queryDb($sql);
    }

    /**
     * Retrieves collection info
     * @param int $bibid
     * @return array|null
     */
    function _getCollectionInfo($bibid)
    {
        $sql = $this->mkSQL("SELECT collection_cd FROM biblio WHERE bibid = %N",
            $bibid);
        $array = $this->select1($sql);
        $collectionCd = $array["collection_cd"];

        $sql = $this->mkSQL("SELECT * FROM collection_dm WHERE code = %N",
            $collectionCd);
        return $this->select1($sql);
    }

    /****************************************************************************
     * Retrieves days due back for a given copy's collection code
     * @param BiblioCopy $copy bibliography copy object to get days due back
     * @return integer days due back or false, if error occurs
     * @access public
     ****************************************************************************
     */
    function getDaysDueBack($copy)
    {
        $array = $this->_getCollectionInfo($copy->getBibid());
        return $array["days_due_back"];
    }

    /****************************************************************************
     * Retrieves daily late fee for a given copy's collection code
     * @param BiblioCopy $copy bibliography copy object to get days due back
     * @return float daily late fee or false, if error occurs
     * @access public
     ****************************************************************************
     */
    function getDailyLateFee($copy)
    {
        $array = $this->_getCollectionInfo($copy->getBibid());
        return $array["daily_late_fee"];
    }

    /****************************************************************************
     * Update biblio copies to set the status to checked in
     * @param boolean $massCheckin checkin all shelving cart copies
     * @param array $bibids array of bibids to checkin
     * @param array $copyids array of copyids to checkin
     * @return boolean false, if error occurs
     * @access public
     ****************************************************************************
     */
    function checkin($massCheckin, $bibids, $copyids)
    {
        $sql = $this->mkSQL("UPDATE biblio_copy SET "
            . " status_cd=%Q, status_begin_dt=sysdate(), "
            . " due_back_dt=NULL, mbrid=NULL "
            . "WHERE status_cd=%Q ",
            OBIB_STATUS_IN, OBIB_STATUS_SHELVING_CART);
        if (!$massCheckin) {
            $prefix = "and (";
            for ($i = 0; $i < count($bibids); $i++) {
                $sql .= $prefix;
                $sql .= $this->mkSQL("(bibid=%N and copyid=%N)",
                    $bibids[$i], $copyids[$i]);
                $prefix = " or ";
            }
            $sql .= ")";
        }
        return $this->queryDb($sql);
    }

    function _getCheckoutPrivs($bibid, $classification)
    {
        $sql = $this->mkSQL("select checkout_privs.* "
            . "from biblio, checkout_privs "
            . "where bibid=%N and classification=%N "
            . "and biblio.material_cd=checkout_privs.material_cd ",
            $bibid, $classification);
        $rows = $this->fetchRowQ($this->queryDb($sql));
        if (count($rows) != 1) {
            return array('checkout_limit' => 0, 'renewal_limit' => 0);
        }
        return $rows[0];
    }

    /**
     * Unfinished DOC TODO
     * @param $mbrid
     * @param $classification
     * @param BiblioCopy $copy
     * @return bool
     */
    function hasReachedRenewalLimit($mbrid, $classification, $copy)
    {
        $array = $this->_getCheckoutPrivs($copy->getBibid(), $classification);
        if ($array['renewal_limit'] == 0) {
            //0 = unlimited
            return FALSE;
        }
        if ($copy->getRenewalCount() < $array['renewal_limit']) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * determines if checkout limit for given member and material type has been reached
     * @param int $mbrid member id
     * @param int $classification member classification code
     * @param int $bibid bibliography id of bibliography material type to check for
     * @return boolean true if member has reached limit, otherwise false
     */
    function hasReachedCheckoutLimit($mbrid, $classification, $bibid)
    {
        $privs = $this->_getCheckoutPrivs($bibid, $classification);
        if ($privs['checkout_limit'] == 0) {
            //0 = unlimited
            return FALSE;
        }

        // get member's current checkout count for given material type
        $sql = $this->mkSQL("SELECT count(*) row_count FROM biblio_copy, biblio"
            . " WHERE biblio_copy.bibid = biblio.bibid"
            . " AND biblio_copy.mbrid = %N"
            . " AND biblio.material_cd = %N",
            $mbrid, $privs["material_cd"]);
        $res = $this->queryDb($sql);
        if (!$res) {
            return false;
        }
        $array = $this->fetchRowQ($res);
        if ($array["row_count"] >= $privs['checkout_limit']) {
            return TRUE;
        }
        return FALSE;
    }
}

?>
