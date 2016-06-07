<?php

namespace Database;

/**
 * Description of MySQLi
 *
 * @author Мистраль
 */
class MySQLi extends \Database {

    private $px;
    private $link;
    private $result;
    private $errors;

    public function __construct($settings) {

        $server = $settings["server"];
        $user = $settings["user"];
        $passwd = $settings["passwd"];
        $dbname = $settings["dbname"];
        $this->px = $settings["px"];
        $query = $settings["query"];

        $link = mysqli_connect($server, $user, $passwd, $dbname);

        if ($link) {
            $this->link = $link;
            foreach ($query as $sql) {
                $this->query($sql);
            }
        } else {
            $this->errors[] = "Not connected";
        }
    }

    public function query($query, $replacers = array()) {
        global $SQL;
        $query = preg_replace("#@px:#si", $this->px, $query);
        foreach ($replacers as $k => $v) {
            $query = preg_replace("#@$k:#si", $v, $query);
        }
        //d($query);
        $SQL[] = $query;
        $this->result = mysqli_query($this->link, $query);
        $error = mysqli_error($this->link);
        if($error){
            $this->error($error);
        }
        
        return $this;
    }

    public function getRow() {
        if ($this->result) {
            return mysqli_fetch_assoc($this->result);
        } else {
            return false;
        }
    }

    public function getRows() {
        if ($this->result) {
            $data = array();
            while ($item = mysqli_fetch_assoc($this->result)) {
                $data[] = $item;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function getLastId() {
        return mysqli_insert_id($this->link);
    }

    public function isTrue() {
        return $this->result == true;
    }

    public function error($error = false) {
        if ($error) {
            $this->errors[] = $error;
        } else {
            return $this->errors;
        }
    }

}
