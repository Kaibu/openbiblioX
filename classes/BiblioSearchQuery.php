<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

require_once("../classes/Query.php");
require_once("../classes/Localize.php");


class BiblioSearchQuery extends Query
{
    var $mainRowCount = 0;
    var $mainQueryTime = 0;

    /**
     * @param string - $search - search form input
     * @param bool $opac - exclude non OPAC content, default false
     * @param int $page - the page to be displayed
     * @return bool|mysqli_result - MySQL fulltext search results or false on fail
     */
    function getMainResults($search, $opac = false,$page = 1){
        $search = $this->escape_data($search);
        $time_start = microtime(true);

        $bSearch = "";
        $split = explode(' ',$search);
        foreach ($split as $e){
            $bSearch .= "+".$e." ";
        }

        $sql = "
        SELECT SQL_CALC_FOUND_ROWS bibid, title, author,
        MATCH(`title`, `subtitle`, `author`, `publisher`, `pub_loc`, `summary`, `tags`) AGAINST ('".$search."') as `relevance`,
        IF (title LIKE '%".$search."%', '5' , '0') AS prio
        FROM biblio
        WHERE match(`title`, `subtitle`, `author`, `publisher`, `pub_loc`, `summary`, `tags`) AGAINST ('".$search."') ";

        if($opac){
            $sql .= " AND opac_flg = 'Y'";
        }

        $sql .= "ORDER BY(prio)+(relevance) DESC LIMIT ".OBIB_ITEMS_PER_PAGE." ";

        $sql .= "OFFSET ".(($page-1)*OBIB_ITEMS_PER_PAGE);


        $res = $this->queryDb($sql);

        $time_end = microtime(true);
        $this->mainQueryTime = round($time_end - $time_start, 4);

        if($res->num_rows > 0){
            $rows = $this->queryDb('SELECT found_rows();');
            $this->mainRowCount = (int) implode($rows->fetch_row());
        }else{
            return false;
        }
        return $res;
    }

    /**
     * @param string - $search - search form input
     * @param bool $opac - exclude non OPAC content, default false
     * @return array|bool - biblio info for entry matching barcode on fail false
     */
    function getBarcodeResult($search, $opac = false){
        $search = $this->escape_data($search);
        $sql = "SELECT biblio.bibid, title, author FROM biblio_copy JOIN biblio ON biblio_copy.bibid = biblio.bibid WHERE biblio_copy.barcode_nmbr = '".$search."'";

        if($opac){
            $sql .= " AND biblio.opac_flg = 'Y'";
        }

        $res = $this->queryDb($sql);

        if($res->num_rows != 1){
            return false;
        }
        return $this->fetchRowQ($res);
    }

    /**
     * @param string - $search - search form input
     * @param bool $opac - exclude non OPAC content, default false
     * @return array|bool - biblio info for entry matching ISBN on fail false
     */
    function getIsbnResult($search, $opac = false){
        $search = $this->escape_data($search);

        $search = trim(str_replace('-','',$search));

        $sql = "SELECT bibid, title, author FROM biblio WHERE REPLACE(isbn,'-','') LIKE '".$search."'";

        if($opac){
            $sql .= " AND opac_flg = 'Y'";
        }

        $res = $this->queryDb($sql);

        if($res->num_rows < 1){
            return false;
        }
        return $this->fetchRowQ($res);
    }

    /**
     * @param string - $search - search form input
     * @param bool $opac - exclude non OPAC content, default false
     * @return array|bool - biblio info for entry matching ISBN on fail false
     */
    function getSignatureResult($search, $opac = false){
        $search = $this->escape_data($search);

        $array = explode('.',$search);

        if(count($array) != 4){return false;}

        $sql = "SELECT bibid,title,author FROM biblio WHERE collection_cd = (SELECT code FROM nt_systematik_signatur WHERE category = ".$array[1]." AND sub_category = ".$array[2].") AND signature = ".$array[3];

        if($opac){
            $sql .= " AND opac_flg = 'Y'";
        }

        $res = $this->queryDb($sql);

        if($res->num_rows < 1){
            return false;
        }
        return $this->fetchRowQ($res);
    }

    /**
     * @param Biblio $bibObj - Biblio Object to be searched for
     * @param bool $opac - exclude non OPAC content, default false
     * @param int $page - the page to be displayed
     * @return bool|mysqli_result - Adv Search result or false
     */
    function getAdvancedSearchResult($bibObj, $opac = false, $page = 1){
        $bibObj = $bibObj->escapeAll($bibObj);
        $params = 0;
        $sql = "SELECT SQL_CALC_FOUND_ROWS biblio.bibid,title,author FROM biblio JOIN biblio_skills ON biblio.bibid = biblio_skills.bibid WHERE 0=0"; // 0=0 always true, just to make the and's easier

        if($bibObj->getTitle() != ""){ $sql .= " AND title LIKE '%".$bibObj->getTitle()."%'"; $params++;}
        if($bibObj->getSubtitle() != ""){ $sql .= " AND subtitle LIKE '%".$bibObj->getSubtitle()."%'";$params++; }
        if($bibObj->getAuthor() != ""){ $sql .= " AND author LIKE '%".$bibObj->getAuthor()."%'"; $params++;}
        if($bibObj->getPublisher() != ""){ $sql .= " AND publisher LIKE '%".$bibObj->getPublisher()."%'";$params++; }
        if($bibObj->getPubLoc() != ""){ $sql .= " AND pub_loc LIKE '%".$bibObj->getPubLoc()."%'";$params++; }
        if($bibObj->getPubYear() != ""){ $sql .= " AND pub_year LIKE '%".$bibObj->getPubYear()."%'";$params++; }

        if($bibObj->getMaterialCd() != -1){ $sql .= " AND material_cd = '".$bibObj->getMaterialCd()."'";$params++; }
        if($bibObj->getLocationId() != -1){ $sql .= " AND location_id = '".$bibObj->getLocationId()."'";$params++; }
        if($bibObj->getLanguageId() != -1){ $sql .= " AND language_id = '".$bibObj->getLanguageId()."'";$params++; }
        if($bibObj->getCollectionCd() != "ERROR"){ $sql .= " AND collection_cd = '".$bibObj->getCollectionCd()."'";$params++; }

        if($bibObj->isSkillHear()){ $sql .= " AND hearing_skill = 1";$params++; }
        if($bibObj->isSkillRead()){ $sql .= " AND read_skill = 1";$params++; }
        if($bibObj->isSkillSpeak()){ $sql .= " AND speak_skill = 1";$params++; }
        if($bibObj->isSkillWrite()){ $sql .= " AND write_skill = 1";$params++; }
        if($bibObj->isSkillGrammar()){ $sql .= " AND grammar_skill = 1";$params++; }

        if($bibObj->getLanFromLvl() != "-"){ $sql .= " AND lan_from_lvl >= '".$bibObj->getLanFromLvl()."'" ;$params++; }
        if($bibObj->getLanToLvl() != "-"){ $sql .= " AND lan_to_lvl <= '".$bibObj->getLanToLvl()."'" ;$params++; }

        if($opac){
            $sql .= " AND opac_flg = 'Y'";
        }

        $sql .= " LIMIT ".OBIB_ITEMS_PER_PAGE;

        if(isset($page)){
            $sql .= " OFFSET ".(($page-1)*OBIB_ITEMS_PER_PAGE);
        }

        if($params > 0){
            $res = $this->queryDb($sql);
            if($res->num_rows < 1){
                return false;
            }
            $rows = $this->queryDb('SELECT found_rows();');
            $this->mainRowCount = (int) implode($rows->fetch_row());
            return $res;
        }
        return true;
    }

    /**
     * @param $status - OpenBiblio Status Code
     * @param string $mbrid - Open Biblio Mbr Id
     * @return bool|mysqli_result - Mysqli result or false on fail
     */
    function getBiblioCopyByStatus($status, $mbrid = ""){
        $sql = "select biblio.* ";
        $sql .= ",biblio_copy.copyid ";
        $sql .= ",biblio_copy.barcode_nmbr ";
        $sql .= ",biblio_copy.status_cd ";
        $sql .= ",biblio_copy.status_begin_dt ";
        $sql .= ",biblio_copy.due_back_dt ";
        $sql .= ",biblio_copy.mbrid ";
        $sql .= ",biblio_copy.renewal_count ";
        $sql .= ",greatest(0,to_days(sysdate()) - to_days(biblio_copy.due_back_dt)) days_late ";
        $sql .= "from biblio, biblio_copy ";
        $sql .= "where biblio.bibid = biblio_copy.bibid ";
        if ($mbrid != "") {
            $sql .= $this->mkSQL("and biblio_copy.mbrid = %N ", $mbrid);
        }
        $sql .= $this->mkSQL(" and biblio_copy.status_cd=%Q ", $status);
        $sql .= " order by biblio_copy.status_begin_dt desc";

        $res = $this->queryDb($sql);

        $this->mainRowCount = $res->num_rows;

        if (!$res) {
            return false;
        }

        return $res;
    }

    /**
     * @param int $mbrid - Open Biblio Member id
     * @return bool|mysqli_result - Meber Biblio status
     */
    function getMemberBiblio( $mbrid){
        $mbrid = $this->escape_data($mbrid);

        return $this->getBiblioCopyByStatus(OBIB_STATUS_OUT,$mbrid);
    }
}

