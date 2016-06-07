<?php


function repackQuery($addArray = array(), $removeArray = array()){
    $arr = $_GET;
    $arr = array_merge($arr, $addArray);
    foreach ($removeArray as $key) {
        if(isset($arr[$key]))
            unset($arr[$key]);
    }
    array_unique($arr);
    $pieces = array();
    foreach($arr as $k => $v){
        $pieces[] = "$k=$v";
    }
    return implode("&", $pieces);
}

function dateFormat($date, $format){
    $str = strtotime($date);    
    return date($format, $str);
}

function d(){
    $args = func_get_args();
    foreach ($args as $arg) {
        print "<pre class=\"dump\">" . print_r($arg, true) . "</pre>";
    }
}