<?php

/**
 * Author: vabene1111
 * Date: 01.09.2016
 * Time: 12:07
 */

require_once("../shared/global_constants.php");
require_once("../classes/Query.php");

class JsonImportQuery extends Query
{
    /**
     * Inserts a new Record into the biblio import table
     * @param $ISBN - ISBN Number
     * @param $title - Media Title
     * @param $subtitle - Media Subtitle
     * @param $author - Author Name
     * @param $publisher - Publisher Name
     * @param $pub_year - Publish Year
     * @param $pub_loc - Location / Country of publishing
     * @return bool|mysqli_result
     */
    function insertRecord($ISBN, $title, $subtitle, $author, $publisher , $pub_year, $pub_loc)
    {
        $ISBN = $this->escape_data($ISBN);
        $title = $this->escape_data($title);
        $subtitle = $this->escape_data($subtitle);
        $author = $this->escape_data($author);
        $publisher = $this->escape_data($publisher);
        $pub_year = $this->escape_data($pub_year);
        $pub_loc = $this->escape_data($pub_loc);

        $sql = $this->mkSQL("INSERT INTO biblio_import (ISBN,title,subtitle,author,publisher,pub_year,pub_loc) VALUES ('" . $ISBN . "','" . $title . "','" . $subtitle . "','" . $author . "','" . $publisher . "','" . $pub_year . "','" . $pub_loc . "')");
        return $this->_query($sql, "Error inserting" . $title);
    }

    /**
     * Query's all entries of biblio_import
     * Omits all entries marked as deleted
     * @return bool|mysqli_result
     */
    function getImportList()
    {
        $sql = $this->mkSQL("SELECT * FROM biblio_import WHERE deleted=0");
        return $this->_query($sql, "Error fetching import List");
    }

    /**
     * Fetches one entry from the biblio_import table by given ID
     * @param int|$id ID of entry to be fetched
     * @return bool|mysqli_result
     */
    function getEntryByID($id)
    {
        $id = $this->escape_data($id);
        $sql = $this->mkSQL("SELECT id,ISBN,title,subtitle,author,publisher,pub_year,pub_loc FROM biblio_import WHERE id=".$id);
        return $this->_query($sql, "Error fetching import List");
    }

    /**
     * Marks given entry as imported (displayed green in table)
     * @param int|$id - Id of entry to be marked
     * @return bool|mysqli_result
     */
    function markImported($id){
        $id = $this->escape_data($id);
        $sql = $this->mkSQL("UPDATE biblio_import SET imported=1 WHERE id=".$id);
        return $this->_query($sql, "Error inserting" . $id);
    }

    /**
     * Sets all imported entries as deleted
     * Does not actually delete the entries
     * @return bool|mysqli_result
     */
    function removeImported(){
        $sql = $this->mkSQL("UPDATE biblio_import SET deleted=1 WHERE imported=1");
        return $this->_query($sql, "Error inserting" . $id);
    }
}
