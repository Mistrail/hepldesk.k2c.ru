<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Мистраль
 */
abstract class Controller {

    protected $db;
    protected $errors = array();

    public function __construct() {
        global $DB;
        $this->db = $DB;
        
        $pattern = str_replace("\\", ".", strtolower(get_called_class()));
        
        $view = ROOT . "/system/templates/" . TEMPLATE . "/views/$pattern/view.php";
        $default = ROOT . "/system/modules/catalog/views/$pattern/view.php";
        $this->view = file_exists($view) ? $view : $default;
    }

    public function error($error = false) {
        if($error){
            $this->errors[] = $error;
        }else{
            return $this->errors;
        }
        
    }
    
    public function show() {
        require $this->view;
    }

    public function table($table) {
        return $this->db->query("SHOW FIELDS FROM @px:$table")->getRows();
    }

    public function fields($table, $keys = false) {
        $fields = array();
        $table = $this->db->query("SHOW FIELDS FROM @px:$table")->getRows();

        foreach ($table as $field) {
            $length = false;
            $type = $field["Type"];

            if (preg_match("#([\w]+)\(([\d]+)\)#si", $field["Type"], $matches)) {
                $type = $matches[1];
                $length = $matches[2];
            }
            
            $fields[$field["Field"]] = array(
                "type" => $type,
                "value" => $field["Default"],
                "length" => $length,
                "is_null" => $field["Null"] != "NO",
            );
        }
        
        if($keys){
            return array_keys($fields);
        }else{
            return $fields;
        }
        
    }

}
