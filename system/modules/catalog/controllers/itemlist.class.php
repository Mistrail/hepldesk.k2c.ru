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
class Itemlist extends \Controller {

    public function ticketForm() {
        $keys = $this->fields("tickets", true);
        $data = array();
        foreach ($keys as $key) {
            $data[$key] = "";
        }
        if(filter_input(INPUT_GET, "autologin") == "callcenter"){
            $data["phone"] = "[:CALL_FROM:]";
        }
        return $data;
    }
    
    public function getNextID() {
        $sql = "SELECT max(id) as max FROM @px:tickets";
        $data = $this->db->query($sql)->getRow();
         return $data["max"] +1;
    }

    public function history($action, $ticket_id, $field = false, $old_value = false, $new_value = false) {
        global $user;
        $uid = $user->id;
        $handle = fopen(ROOT . "/history/" . date("Y-m-d") . ".csv", "a");
        $date = date("U");
        $str = "$date\tuid:$uid\t$ticket_id\t$action\t$field\t$old_value\t$new_value" . PHP_EOL;
        fwrite($handle, $str);
        fflush($handle);
        fclose($handle);
    }

    public function getTicket($id) {
        $sql = "SELECT "
                . "t.*, "
                . "st.title as source, "
                . "staff.name as staff, "
                . "staff.percent as percent "
                . " FROM @px:tickets as t, @px:soursetypes as st, @px:stafflist as staff"
                . " WHERE t.id='$id' AND"
                . " staff.id = t.staff_id AND st.id = t.source_id ORDER BY t.date DESC LIMIT 1";
        $data = $this->db->query($sql)->getRow();

        return $data;
    }
    
    public function getPercent($data) {
        //AJAX
        $sql = "SELECT * "
                . " FROM @px:stafflist"
                . " WHERE id={$data["id"]}";
        $data = $this->db->query($sql)->getRow();

        return empty($data) ? false : $data["percent"];
    }

    public function editTicket($fields) {
        $id = $fields["id"];
        unset($fields["id"]);

        $set = array();
        $old_values = $this->db->query("SELECT * FROM @px:tickets WHERE id = $id")->getRow();

        foreach ($fields as $key => $value) {

            $set[] = "$key='$value'";
            if ($old_values[$key] != $value) {
                $this->history("UPDATE", $id, $key, $old_values[$key], $value);
            }
        }

        $sql = "UPDATE @px:tickets"
                . " SET " . implode(", ", $set)
                . " WHERE id = $id";

        $data = $this->db->query($sql)->isTrue();

        return $data;
    }

    public function addTicket($data) {
        global $uid, $MAIL;

        $keys = array_keys($data);
        $sql = "INSERT INTO @px:tickets (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $data) . "')";
        $id = $this->db->query($sql)->getLastId();
        $this->error($this->db->error());
        $this->history("INSERT", $id);

        if ($id) {
            //$data["link"] = "Для перехода к заявке перейдите по ссылке: http://helpdesk.k2c.ru?token=" . md5($id) . "&id=$id";
            $data["link"] = "Для перехода к заявке перейдите по ссылке: http://helpdesk.k2c.ru/?id=$id";
            $pdr = dateFormat($data["payout_date_real"], "H:i");
            $data["id"] = $id;
            $data["payout_date_real"] = dateFormat($date["payout_date_real"], "H:i d.m.Y");
            
            $MAIL->header("$id, $pdr, {$data["address"]}");
            $MAIL->setFields($data);
            $MAIL->send();
        }

        return $id;
    }

    public function getTable($type = false, $from = 0, $length = 20, $s = false) {
        $sql = "SELECT "
                . "t.*, "
                . "st.title as source,"
                . "staff.name as staff,"
                . "ot.title as ordertype"
                . " FROM @px:tickets as t, @px:soursetypes as st, @px:stafflist as staff, @px:ordertypes as ot";
        if ($type) {
            $sql .= " WHERE t.status_id='$type' AND";
        }

        if ($s) {
            $sql .= " t.phone LIKE '%$s%' AND";
        }

        $sql .= " staff.id = t.staff_id AND st.id = t.source_id AND ot.id = t.type_id ORDER BY t.date DESC";
        if ($from) {
            $sql .= " LIMIT " . (int) $from . ", $length";
        }
        //d($sql);
        return $this->db->query($sql)->getRows();
    }

    public function getPager($type = 1, $from, $length) {
        $page = round(($from - 1) / $length) + 1;

        $sql = "SELECT count(id) as count FROM @px:tickets";
        if ($type) {
            $sql .= " WHERE status_id='$type'";
        }
        $result = $this->db->query($sql)->getRow();
        $total = $result["count"];

        $max_pages = round($total / $length);

        $pages = array();
        for ($i = 1; $i <= $max_pages; $i++) {
            $pages[] = array(
                "page" => $i,
                "from" => ($i - 1) * $length + 1,
                "current" => $page == $i
            );
        }
        return $pages;
    }

    public function deleteTicket($id) {
        $sql = "DELETE FROM @px:tickets WHERE id = $id";
        $this->history("DELETE", $id);
        //var_dump($sql);
        return $this->db->query($sql);
    }

}
