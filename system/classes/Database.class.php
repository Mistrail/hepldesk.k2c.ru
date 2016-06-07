<?php

class Database {

    public function __construct() {
        
    }

    public static function Init($driver, $settings) {
        
        $filename = __DIR__ . "/Database/drivers/" . ucfirst($driver) . ".class.php";
        $classname = "\\Database\\$driver";
        $obj = false;

        if (file_exists($filename)) {
            require_once $filename;
            $obj = new $classname($settings);
        } else {
            $obj = new \Database();
        }

        return $obj;
    }
    
    public function query($query, $replacers = array()) {
        $query = str_replace("/@px:/si", $this->px, $query);
        $query = preg_replace("/@(\s[\w\d]+):/si", "", $query);
        return $query;
    }

}
