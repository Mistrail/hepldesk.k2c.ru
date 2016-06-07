<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Catalog;

/**
 * Description of Itemlist
 *
 * @author Мистраль
 */
class Stafflist extends \Controller {

    public function staffForm() {
        $keys = $this->fields("stafflist", true);
        $data = array();
        foreach ($keys as $key) {
            $data[$key] = "";
        }

        return $data;
    }

    public function addStaff($data) {
        $keys = array_keys($data);
        print $sql = "INSERT INTO @px:stafflist (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $data) . "')";
        $id = $this->db->query($sql)->getLastId();
        $this->error($this->db->error());
        return $id;
    }

    public function getTable($from = 0, $length = 20) {
        $sql = "SELECT * FROM @px:stafflist";

        if ($from) {
            $sql .= " LIMIT " . (int) $from . ", $length";
        }

        return $this->db->query($sql)->getRows();
    }

    public function deleteStaff($id) {
        $sql = "DELETE FROM @px:stafflist WHERE id = $id";
        //var_dump($sql);
        return $this->db->query($sql);
    }

    public function updateStaff($fields) {
        $id = $fields["id"];
        unset($fields["id"]);
        
        $ins = array();
        foreach ($fields as $Key => $value) {
            $ins[] = "$Key = '$value'";
        }

        $sql = "UPDATE @px:stafflist SET " . implode(", ", $ins) . " WHERE id = $id";
        //var_dump($sql);
        return $this->db->query($sql);
    }

    public function getStaff($id) {
        $sql = "SELECT * FROM @px:stafflist WHERE id = $id";
        //var_dump($sql);
        return $this->db->query($sql)->getRow();
    }

}
