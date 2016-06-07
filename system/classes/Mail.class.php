<?php

class Mail {

    private $mailto = "sdelano-help@mail.ru";
    private $tpl;
    private $fields;
    private $header;

    public function setTemplate($filename) {

        if (file_exists($filename)) {
            $this->tpl = file_get_contents($filename);
            $this->fields = $this->getFields();
        }
    }

    public function getFields() {
        $fields = array();
        $has_fields = preg_match_all("#\@([\w\d]+)\:#si", $this->tpl, $matches);
        if ($has_fields) {
            foreach ($matches[1] as $key => $value){
                $fields[$value] = "--";
            }
        }
        return $fields;
    }

    public function setFields($fields) {
        foreach ($this->fields as $key => $value) {
            if (isset($fields[$key])) {
                $this->fields[$key] = $fields[$key];
            }
        }
    }
    
    public function header($str) {
        $this->header = $str;
    }

    public function send() {
        $body = $this->tpl;
        foreach ($this->fields as $key => $value) {
            $body = preg_replace("#@$key:#si", $value, $body);
        }
        $header = $this->header;
        file_put_contents(__DIR__ . "/Mail/mail_".date("d-m-Y_H-i-s").".txt", $header . "\n\n" . $body);
        $sent = mail($this->mailto, $header, $body);
        return $sent;
    }

}
