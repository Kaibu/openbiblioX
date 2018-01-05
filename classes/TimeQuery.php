<?php

/**
 * Author: vabene1111
 * Date: 06.09.2016
 * Time: 12:14
 */
class TimeQuery extends Query
{
    /**
     * @param int $id user id
     * @param bool $running if entries without end timestamp should be returned, default true
     * @return bool|mysqli_result the last 25 records for given id
     */
    function getUserReport($id,$running = true){
        $id = $this->escape_data($id);
        if($running){
            $sql = "SELECT * FROM time_tracking WHERE deleted=0 AND userid=".$id." LIMIT 25";
        }else{
            $sql = "SELECT * FROM time_tracking WHERE deleted=0 AND end IS NOT NULL AND userid=".$id." LIMIT 25";
        }
        $res = $this->queryDb($sql);
        return $res;
    }

    /**
     * @return bool|mysqli_result get all users active in system
     */
    function getUsers(){
        $res = $this->queryDb("SELECT userid,last_name,first_name FROM staff");
        return $res;
    }

    /**
     * @return bool|mysqli_result get the last 25 records for logged in user
     */
    function getLastSessions(){
        return $this->getUserReport($_SESSION["userid"],false);
    }

    /**
     * Checks if given record id belongs to the currently logged in user
     * @param int $recordId ID of record to be checked
     * @return bool true if record is owned by current logged in user, false if not
     */
    function isUserRecord($recordId){
        $recordId = $this->escape_data($recordId);
        $res = $this->queryDb("SELECT userid FROM time_tracking WHERE id=".$recordId);
        if($res->num_rows != 1){
            return false;
        }

        $row = $this->fetchRowQ($res);

        if($row['userid'] == $_SESSION["userid"]){
            return true;
        }
        return false;
    }

    /**
     * @return array|bool - Array when active session else false
     */
    function getCurrentSession(){
        $uid = $this->escape_data($_SESSION["userid"]);
        $res = $this->queryDb("SELECT id,userid,start,end,comment FROM time_tracking WHERE end IS NULL AND userid=".$uid);

        if($res->num_rows > 0){
            return $this->fetchRowQ($res);
        }
        return false;
    }

    /**
     * @param int $id - entry to be queried
     * @return array|bool - selected entry as array or false
     */
    function getRecordById($id){
        $id = $this->escape_data($id);
        $res = $this->queryDb("SELECT * FROM time_tracking WHERE id=".$id);

        if($res->num_rows == 1){
            return $this->fetchRowQ($res);
        }
        return false;
    }

    /**
     * Starts a new Session
     */
    function startSession(){
        $uid = $this->escape_data($_SESSION["userid"]);
        $this->queryDb("INSERT INTO time_tracking (userId,start) VALUES (".$uid.",CURRENT_TIMESTAMP())");
    }

    /**
     * End the current active session
     * @param string $pause - pause time in minutes
     * @param string $msg - comment added to session default empty string
     */
    function endSession($pause, $msg=""){
        $uid = $this->escape_data($_SESSION["userid"]);
        $msg = $this->escape_data($msg);
        $pause = $this->escape_data($pause);
        if(!is_int((int) $pause)){
            return;
        }
        if($pause == ""){
            $pause = 0;
        }

        $this->queryDb("UPDATE time_tracking SET end=CURRENT_TIMESTAMP(),pause=".$pause.", comment='".$msg."' WHERE end IS NULL AND userid=".$uid);
    }

    /**
     * Edit Session by given Id
     * @param $id - Session ID
     * @param $start - Start Time
     * @param $end - End Time
     * @param $pause - Pause in minutes
     * @param $msg - Message
     */
    function editSession($id,$start,$end,$pause,$msg){
        $id = $this->escape_data($id);

        if(!$this->isUserRecord($id)){
            die('');
        }

        $uid = $this->escape_data($_SESSION["userid"]);
        $start = $this->escape_data($start);
        $end = $this->escape_data($end);
        $pause = $this->escape_data($pause);
        $msg = $this->escape_data($msg);

        if($pause == ""){
            $pause = 0;
        }

        $this->queryDb("UPDATE time_tracking SET start='".$start."',end='".$end."',pause=".$pause.", comment='".$msg."',manual=1 WHERE userId=".$uid." AND id=".$id);
    }


    /**
     * @param int $id - set deleted flag true for given id
     */
    function deleteSession($id){
        $id = $this->escape_data($id);
        if(!$this->isUserRecord($id)){
            die('');
        }

        $this->queryDb("UPDATE time_tracking SET deleted=1 WHERE id=".$id);
    }
}