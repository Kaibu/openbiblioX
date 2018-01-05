<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../shared/global_constants.php");
require_once("../classes/Query.php");
require_once("../classes/Biblio.php");
require_once("../classes/Localize.php");

/******************************************************************************
 * BiblioQuery data access component for library bibliographies
 *
 * @author David Stevens <dave@stevens.name>;
 * Complete rework in 2016 by vabene1111 (Git branch feature/DBRefactor)
 ******************************************************************************
 */
class BiblioQuery extends Query
{
    var $_itemsPerPage = 1;
    var $_rowNmbr = 0;
    var $_currentRowNmbr = 0;
    var $_currentPageNmbr = 0;
    var $_rowCount = 0;
    var $_pageCount = 0;
    var $_loc;

    function BiblioQuery()
    {
        $this->Query();
        $this->_loc = new Localize(OBIB_LOCALE, "classes");
    }

    /****************************************************************************
     * Gets Biblio Object by given bibid
     * @param string $bibID bibid of bibliography to select
     * @return bool|Biblio a bibliography object or false if error occurs
     * @access public
     ****************************************************************************
     */
    function doQuery($bibID)
    {
        # reset rowNmbr
        $this->_rowNmbr = 0;
        $this->_currentRowNmbr = 1;
        $this->_rowCount = 1;
        $this->_pageCount = 1;

        /***********************************************************
         *  Reading biblio data
         ***********************************************************/
        # setting query that will return all the data in biblio
        $bibID = $this->escape_data($bibID);
        $sql = "SELECT biblio.*, staff.username,biblio_skills.* FROM biblio LEFT JOIN staff ON biblio.last_change_userid = staff.userid JOIN biblio_skills ON biblio.bibid=biblio_skills.bibid WHERE biblio.bibid =".$bibID;

        $res = $this->queryDb($sql);
        if (!$res) {
            return false;
        }

        $array = $this->fetchRowQ($res);
        $bib = new Biblio();
        $bib->setBibid($array["bibid"]);
        $bib->setCreateDt($array["create_dt"]);
        $bib->setLastChangeDt($array["last_change_dt"]);
        $bib->setLastChangeUserid($array["last_change_userid"]);
        if (isset($array["username"])) {
            $bib->setLastChangeUsername($array["username"]);
        }
        $bib->setMaterialCd($array["material_cd"]);
        $bib->setCollectionCd($array["collection_cd"]);
        $bib->setLocationId($array["location_id"]);
        $bib->setSignature($array["signature"]);

        $bib->setTitle($array["title"]);
        $bib->setAuthor($array["author"]);
        $bib->setSubtitle($array["subtitle"]);
        $bib->setIsbn($array["isbn"]);
        $bib->setPublisher($array["publisher"]);
        $bib->setPubLoc($array["pub_loc"]);
        $bib->setPubYear($array["pub_year"]);
        $bib->setSummary($array["summary"]);
        $bib->setTags($array["tags"]);
        $bib->setDuration($array["duration"]);
        $bib->setPages($array["pages"]);
        $bib->setLanguageId($array["language_id"]);
        $bib->setLanFromLvl($array["lan_from_lvl"]);
        $bib->setLanToLvl($array["lan_to_lvl"]);

        $bib->setSkillHear($array["hearing_skill"]);
        $bib->setSkillSpeak($array["speak_skill"]);
        $bib->setSkillWrite($array["write_skill"]);
        $bib->setSkillGrammar($array["grammar_skill"]);
        $bib->setSkillRead($array["read_skill"]);

        if ($array["opac_flg"] == "Y") {
            $bib->setOpacFlg(true);
        } else {
            $bib->setOpacFlg(false);
        }

        return $bib;
    }

    /**
     * @param Biblio $bibObj
     * @return string main category description
     */
    function getMainCategory($bibObj){
        $bibId = $this->escape_data($bibObj->getBibid());
        $res = $this->queryDb("SELECT main.description FROM nt_systematik_signatur sys, biblio bib, nt_systematik_main_category main WHERE bibid='".$bibId."' AND bib.collection_cd = sys.code AND sys.category = main.code");
        if($res->num_rows == 1){
            $arr = $this->fetchRowQ($res);
            return $arr['description'];
        }else{
            return "ERROR";
        }
    }


    /**
     * @param Biblio $bibObj
     * @return string sub category description
     */
    function getSubCategory($bibObj){
        $collection_id = $this->escape_data($bibObj->getCollectionCd());
        $res = $this->queryDb("SELECT description FROM collection_dm WHERE code='".$collection_id."'");
        if($res->num_rows == 1){
            $arr = $this->fetchRowQ($res);
            return $arr['description'];
        }else{
            return "";
        }
    }

    /**
     * @param Biblio $bibObj
     * @return string full systematic string (e.g. 1.1.2.42)
     */
    function getFullSystematic($bibObj){
        $bibId = $this->escape_data($bibObj->getBibid());
        $res = $this->queryDb("SELECT sys.category, sys.sub_category FROM biblio bib, nt_systematik_signatur sys WHERE bib.bibid=".$bibId." AND bib.collection_cd = sys.code");
        if($res->num_rows == 1){
            $resArr = $this->fetchRowQ($res);
            return $resArr['category'].".".$resArr['category'].".".$resArr['sub_category'].".".$bibObj->getSignature();
        }else{
            return "ERROR";
        }
    }

    /**
     * @param Biblio $bibObj
     * @return string language description
     */
    function getLanguageString($bibObj){
        $languageId = $this->escape_data($bibObj->getLanguageId());
        $res = $this->queryDb("SELECT description FROM nt_sprachen WHERE code='".$languageId."'");
        if($res->num_rows == 1){
            $arr = $this->fetchRowQ($res);
            return $arr['description'];
        }else{
            return "ERROR";
        }
    }

    /**
     * @param Biblio $bibObj
     * @return string material description
     */
    function getMaterialString($bibObj){
        $materialId = $this->escape_data($bibObj->getMaterialCd());
        $res = $this->queryDb("SELECT description FROM material_type_dm WHERE code='".$materialId."'");
        if($res->num_rows == 1){
            $arr = $this->fetchRowQ($res);
            return $arr['description'];
        }else{
            return "ERROR";
        }
    }

    /**
     * @param Biblio $bibObj
     * @return string material description
     */
    function getMaterialCategory($bibObj){
        $materialId = $this->escape_data($bibObj->getMaterialCd());
        $res = $this->queryDb("SELECT duration_type FROM material_type_dm WHERE code='".$materialId."'");
        if($res->num_rows == 1){
            $arr = $this->fetchRowQ($res);
            return $arr['duration_type'];
        }else{
            return "ERROR";
        }
    }

    /**
     * @param Biblio $bibObj
     * @return string material description
     */
    function getLocationString($bibObj){
        $locationId = $this->escape_data($bibObj->getLocationId());
        $res = $this->queryDb("SELECT description FROM locations WHERE Code='".$locationId."'");
        if($res->num_rows == 1){
            $arr = $this->fetchRowQ($res);
            return $arr['description'];
        }else{
            return "ERROR";
        }
    }

    /**
     * @param int|string $main_cat - Main category (first part)
     * @param int|string $sub_cat - sub category (second part)
     * @return int|string code to save in collection_cd to identify signature
     */
    function getSignatureId($main_cat, $sub_cat){
        $main_cat = $this->escape_data($main_cat);
        $sub_cat = $this->escape_data($sub_cat);
        $res = $this->queryDb("SELECT code FROM nt_systematik_signatur WHERE category='".$main_cat."' AND sub_category='".$sub_cat."'");
        if($res->num_rows == 1){
            $arr = $this->fetchRowQ($res);
            return $arr['code'];
        }else{
            return "ERROR";
        }
    }


    /****************************************************************************
     * Returns true if barcode number already exists
     * @param string $barcode Bibliography barcode number
     * @return boolean returns true if barcode already exists
     * @access private
     ****************************************************************************
     */
    function _dupBarcode($barcode)
    {

        $sql = "SELECT Count(*) FROM biblio_copy WHERE barcode_nmbr='".$barcode."'";
        $res = $this->queryDb($sql);
        $array = $this->fetchRowQ($res);
        if ($array[0] > 0) {
            return true;
        }
        return false;
    }

    /****************************************************************************
     * Inserts new bibliography info into the biblio and biblio_field tables.
     * @param Biblio $biblio bibliography to insert
     * @return array|int returns bibid or false, if error occurs
     * @access public
     ****************************************************************************
     */
    function insert($biblio)
    {
        if(count($biblio->validateData()) > 0){
            return false;
        }

        $sql = "INSERT INTO biblio (last_change_dt,last_change_userid,create_dt) VALUES (sysdate(),".$biblio->getLastChangeUserid().",sysdate())";
        $this->queryDb($sql);

        $biblio->setBibid($this->getInsertID());

        $sql = "INSERT INTO biblio_skills (bibid) VALUES (".$biblio->getBibid().")";
        $this->queryDb($sql);

        $this->update($biblio);

        return $biblio->getBibid();
    }

    /****************************************************************************
     * Updates a bibliography in the biblio table.
     * @param Biblio $biblio bibliography to update
     * @return boolean returns false, if error occurs
     * @access public
     ****************************************************************************
     */
    function update($biblio)
    {
        $biblio = $biblio->escapeAll($biblio);

        $sql = "UPDATE biblio";
        $sql .= " SET material_cd='".$biblio->getMaterialCd()."'";
        $sql .= ", collection_cd='".$biblio->getCollectionCd()."'";
        $sql .= ", location_id='".$biblio->getLocationId()."'";
        $sql .= ", signature='".$biblio->getSignature()."'";
        $sql .= ", title='".$biblio->getTitle()."'";
        $sql .= ", subtitle='".$biblio->getSubtitle()."'";
        $sql .= ", author='".$biblio->getAuthor()."'";
        $sql .= ", isbn='".$biblio->getIsbn()."'";
        $sql .= ", publisher='".$biblio->getPublisher()."'";
        $sql .= ", pub_loc='".$biblio->getPubLoc()."'";
        $sql .= ", pub_year='".$biblio->getPubYear()."'";
        $sql .= ", summary='".$biblio->getSummary()."'";
        $sql .= ", tags='".$biblio->getTags()."'";
        $sql .= ", duration='".$biblio->getDuration()."'";
        $sql .= ", pages='".$biblio->getPages()."'";
        $sql .= ", language_id='".$biblio->getLanguageId()."'";
        $sql .= ", lan_from_lvl='".$biblio->getLanFromLvl()."'";
        $sql .= ", lan_to_lvl='".$biblio->getLanToLvl()."'";
        $sql .= ", opac_flg='".($biblio->isOpacFlg() ? "Y" : "N")."'";
        $sql .= ", last_change_userid=".$biblio->getLastChangeUserid();
        $sql .= ", last_change_dt=sysdate() WHERE bibid=".$this->escape_data($biblio->getBibid());

        $res = $this->queryDb($sql);
        if(!$res){
            return false;
        }

        $sql2 = "UPDATE biblio_skills SET hearing_skill=".$biblio->isSkillHear(true);
        $sql2 .= ",speak_skill=".$biblio->isSkillSpeak(true);
        $sql2 .= ",write_skill=".$biblio->isSkillWrite(true);
        $sql2 .= ",grammar_skill=".$biblio->isSkillGrammar(true);
        $sql2 .= ",read_skill=".$biblio->isSkillRead(true);

        $res = $this->queryDb($sql2);
        if(!$res){
            return false;
        }

        return true;
    }

    /****************************************************************************
     * Deletes a bibliography from the biblio table.
     * @param string $bibid bibliography id of bibliography to delete
     * @return boolean returns false, if error occurs
     * @access public
     ****************************************************************************
     */
    function delete($bibid)
    {
        $sql = $this->mksql("DELETE FROM biblio_skills WHERE bibid = %N ", $bibid);
        if (!$this->queryDb($sql)) {
            return false;
        }
        $sql = $this->mkSQL("DELETE FROM biblio WHERE bibid = %N ", $bibid);
        return $this->queryDb($sql);
    }


    /**********************************************************************
     *                    GETTER & SETTTER
     **********************************************************************/

    function setItemsPerPage($value)
    {
        $this->_itemsPerPage = $value;
    }

    function getCurrentRowNmbr()
    {
        return $this->_currentRowNmbr;
    }

    function getRowCount()
    {
        return $this->_rowCount;
    }

    function getPageCount()
    {
        return $this->_pageCount;
    }

}

?>
