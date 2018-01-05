<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */


/******************************************************************************
 * Biblio represents a library bibliography record.  Contains business rules for
 * bibliography data validation.
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class Biblio
{
    var $_bibid = "";
    var $_create_dt = "";
    var $_last_change_dt = "";
    var $_last_change_userid = "";
    var $_lastChangeUsername = "";
    var $_material_cd = 0;
    var $_collection_cd = 0;
    var $_location_id = 0;
    var $_language_id = 0;
    var $_language_lvl = "";
    var $lan_from_lvl = 0;
    var $lan_to_lvl = 0;
    var $_signature = "";
    var $_publisher = "";
    var $_pub_loc = "";
    var $_isbn = "";
    var $_pub_year = "";
    var $_summary = "";
    var $_duration = "";
    var $_pages = 0;
    var $_title = "";
    var $_subtitle = "";
    var $_author = "";
    var $_tags = "";
    var $_opac_flg = true;

    var $_skill_hear = false;
    var $_skill_speak = false;
    var $_skill_write = false;
    var $_skill_grammar = false;
    var $_skill_read = false;

    function Biblio()
    {

    }

    /**
     * @return array true if data is valid, otherwise false.
     */
    function validateData()
    {
        $errors = array();

        if(!is_int((int) $this->getLanguageId()) OR $this->getLanguageId() == -1){ array_push($errors,1); }
        if(!is_int((int) $this->getMaterialCd()) OR $this->getMaterialCd() == -1){ array_push($errors,2); }
        if(!is_int((int) $this->getCollectionCd()) OR $this->getCollectionCd() == 0){ array_push($errors,3); }
        if(!is_int((int) $this->getLocationId()) OR $this->getLocationId() == -1){ array_push($errors,4); }

        //if($this->getIsbn() == ""){ array_push($errors,5); }
        if($this->getTitle() == ""){ array_push($errors,6); }
        if($this->getPublisher() == ""){ array_push($errors,7); }

        if(!preg_match('/[0-9]{1,6}/',$this->getPages()) && $this->getPages() != 0){ array_push($errors,8); }
        if(!preg_match('/[0-9][0-9]:[0-9][0-9]/',$this->getDuration()) && $this->getDuration() != ""){ array_push($errors,9); }

        return $errors;
    }

    /**
     * @param Biblio $bibObj - Biblio Objekt to be sanitized
     * @return Biblio - all fields as mysql real escape strings
     */
    function escapeAll($bibObj){
        require_once("../classes/Query.php");
        $q = new Query();

        $bibObj->setTitle($q->escape_data($bibObj->getTitle()));
        $bibObj->setSubtitle($q->escape_data($bibObj->getSubtitle()));
        $bibObj->setAuthor($q->escape_data($bibObj->getAuthor()));
        $bibObj->setPublisher($q->escape_data($bibObj->getPublisher()));
        $bibObj->setPubLoc($q->escape_data($bibObj->getPubLoc()));
        $bibObj->setPubYear($q->escape_data($bibObj->getPubYear()));
        $bibObj->setIsbn($q->escape_data($bibObj->getIsbn()));
        $bibObj->setSummary($q->escape_data($bibObj->getSummary()));
        $bibObj->setTags($q->escape_data($bibObj->getTags()));

        $bibObj->setCollectionCd($q->escape_data($bibObj->getCollectionCd()));
        $bibObj->setMaterialCd($q->escape_data($bibObj->getMaterialCd()));
        $bibObj->setLanguageId($q->escape_data($bibObj->getLanguageId()));
        $bibObj->setLanFromLvl($q->escape_data($bibObj->getLanFromLvl()));
        $bibObj->setLanToLvl($q->escape_data($bibObj->getLanToLvl()));

        $bibObj->setDuration($q->escape_data($bibObj->getDuration()));
        $bibObj->setPages($q->escape_data($bibObj->getPages()));

        return $bibObj;
    }

    /**
     * This could be pulled from nt_niveau but i see no reason to do so since lvl's seem to be static anyway
     * @param $lvlId - id of lvl
     * @return string - string of lvl id
     */
    function getLanLvlDesc($lvlId){
        switch ($lvlId){
            case 1: return "A1";
            case 2: return "A2";
            case 3: return "B1";
            case 4: return "B2";
            case 5: return "C1";
            case 6: return "C2";
        }
        return "";
    }

    /**********************************************************************
     *                    GETTER & SETTTER
     **********************************************************************/

    /**
     * @return int
     */
    public function getLanFromLvl()
    {
        return $this->lan_from_lvl;
    }

    /**
     * @param int $lan_from_lvl
     */
    public function setLanFromLvl($lan_from_lvl)
    {
        $this->lan_from_lvl = $lan_from_lvl;
    }

    /**
     * @return int
     */
    public function getLanToLvl()
    {
        return $this->lan_to_lvl;
    }

    /**
     * @param int $lan_to_lvl
     */
    public function setLanToLvl($lan_to_lvl)
    {
        $this->lan_to_lvl = $lan_to_lvl;
    }

    /**
     * @param bool $db - return db format (0|1)
     * @return boolean
     */
    public function isSkillHear($db = false)
    {
        if($db){
            if($this->_skill_hear){
                return 1;
            }else{
                return 0;
            }
        }
        return $this->_skill_hear;
    }

    /**
     * @param boolean $skill_hear
     */
    public function setSkillHear($skill_hear)
    {
        $this->_skill_hear = $skill_hear;
    }

    /**
     * @param bool $db - return db format (0|1)
     * @return boolean
     */
    public function isSkillSpeak($db = false)
    {
        if($db){
            if($this->_skill_speak){
                return 1;
            }else{
                return 0;
            }
        }
        return $this->_skill_speak;
    }

    /**
     * @param boolean $skill_speak
     */
    public function setSkillSpeak($skill_speak)
    {
        $this->_skill_speak = $skill_speak;
    }

    /**
     * @param bool $db - return db format (0|1)
     * @return boolean
     */
    public function isSkillWrite($db = false)
    {
        if($db){
            if($this->_skill_write){
                return 1;
            }else{
                return 0;
            }
        }
        return $this->_skill_write;
    }

    /**
     * @param boolean $skill_write
     */
    public function setSkillWrite($skill_write)
    {
        $this->_skill_write = $skill_write;
    }

    /**
     * @param bool $db - return db format (0|1)
     * @return boolean
     */
    public function isSkillGrammar($db = false)
    {
        if($db){
            if($this->_skill_grammar){
                return 1;
            }else{
                return 0;
            }
        }
        return $this->_skill_grammar;
    }

    /**
     * @param boolean $skill_grammar
     */
    public function setSkillGrammar($skill_grammar)
    {
        $this->_skill_grammar = $skill_grammar;
    }

    /**
     * @param bool $db - return db format (0|1)
     * @return bool
     */
    public function isSkillRead($db = false)
    {
        if($db){
            if($this->_skill_read){
                return 1;
            }else{
                return 0;
            }
        }
        return $this->_skill_read;
    }

    /**
     * @param boolean $skill_read
     */
    public function setSkillRead($skill_read)
    {
        $this->_skill_read = $skill_read;
    }

    /**
     * @return string
     */
    public function getBibid()
    {
        return $this->_bibid;
    }

    /**
     * @param string $bibid
     */
    public function setBibid($bibid)
    {
        $this->_bibid = $bibid;
    }

    /**
     * @return string
     */
    public function getCreateDt()
    {
        return $this->_create_dt;
    }

    /**
     * @param string $create_dt
     */
    public function setCreateDt($create_dt)
    {
        $this->_create_dt = $create_dt;
    }

    /**
     * @return string
     */
    public function getLastChangeDt()
    {
        return $this->_last_change_dt;
    }

    /**
     * @param string $last_change_dt
     */
    public function setLastChangeDt($last_change_dt)
    {
        $this->_last_change_dt = $last_change_dt;
    }

    /**
     * @return string
     */
    public function getLastChangeUserid()
    {
        return $this->_last_change_userid;
    }

    /**
     * @param string $last_change_userid
     */
    public function setLastChangeUserid($last_change_userid)
    {
        $this->_last_change_userid = $last_change_userid;
    }

    /**
     * @return string
     */
    public function getLastChangeUsername()
    {
        return $this->_lastChangeUsername;
    }

    /**
     * @param string $lastChangeUsername
     */
    public function setLastChangeUsername($lastChangeUsername)
    {
        $this->_lastChangeUsername = $lastChangeUsername;
    }

    /**
     * @return string
     */
    public function getMaterialCd()
    {
        return $this->_material_cd;
    }

    /**
     * @param string $material_cd
     */
    public function setMaterialCd($material_cd)
    {
        $this->_material_cd = $material_cd;
    }

    /**
     * @return string
     */
    public function getCollectionCd()
    {
        return $this->_collection_cd;
    }

    /**
     * @param string $collection_cd
     */
    public function setCollectionCd($collection_cd)
    {
        $this->_collection_cd = $collection_cd;
    }

    /**
     * @return string
     */
    public function getLocationId()
    {
        return $this->_location_id;
    }

    /**
     * @param string $location_id
     */
    public function setLocationId($location_id)
    {
        $this->_location_id = $location_id;
    }

    /**
     * @return string
     */
    public function getLanguageId()
    {
        return $this->_language_id;
    }

    /**
     * @param string $language_id
     */
    public function setLanguageId($language_id)
    {
        $this->_language_id = $language_id;
    }

    /**
     * @param int $part default 0 (everything), 1: first part, 2: second part
     * @return string
     */
    public function getLanguageLvl($part = 0)
    {
        $arr = explode("-", $this->_language_lvl);
        if($part == 1){
            return $arr[0];
        }else if($part == 2){
            return $arr[1];
        }
        return $this->_language_lvl;
    }

    /**
     * @param string $language_lvl
     */
    public function setLanguageLvl($language_lvl)
    {
        $this->_language_lvl = $language_lvl;
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->_signature;
    }

    /**
     * @param string $signature
     */
    public function setSignature($signature)
    {
        $this->_signature = $signature;
    }

    /**
     * @return string
     */
    public function getPublisher()
    {
        return $this->_publisher;
    }

    /**
     * @param string $publisher
     */
    public function setPublisher($publisher)
    {
        $this->_publisher = $publisher;
    }

    /**
     * @return string
     */
    public function getPubLoc()
    {
        return $this->_pub_loc;
    }

    /**
     * @param string $pub_loc
     */
    public function setPubLoc($pub_loc)
    {
        $this->_pub_loc = $pub_loc;
    }

    /**
     * @return string
     */
    public function getIsbn()
    {
        return $this->_isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn($isbn)
    {
        $this->_isbn = $isbn;
    }

    /**
     * @return string
     */
    public function getPubYear()
    {
        return $this->_pub_year;
    }

    /**
     * @param string $pub_year
     */
    public function setPubYear($pub_year)
    {
        $this->_pub_year = $pub_year;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->_summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->_summary = $summary;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->_duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->_duration = $duration;
    }

    /**
     * @return string
     */
    public function getPages()
    {
        return $this->_pages;
    }

    /**
     * @param string $pages
     */
    public function setPages($pages)
    {
        $this->_pages = $pages;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->_subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->_subtitle = $subtitle;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->_author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->_author = $author;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * @param string $tags
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;
    }

    /**
     * @return boolean
     */
    public function isOpacFlg()
    {
        return $this->_opac_flg;
    }

    function setOpacFlg($flag)
    {
        if ($flag == true) {
            $this->_opac_flg = true;
        } else {
            $this->_opac_flg = false;
        }
    }
}

?>
