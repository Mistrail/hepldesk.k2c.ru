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
class Counter extends \Controller {
   
    public function getData() {
        $sql = "SELECT phone, count(id) as cnt  FROM @px:tickets GROUP BY phone";
        return $this->db->query($sql)->getRows();
    }
    
}
