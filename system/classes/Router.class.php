<?php

class Router {

    function get($url) {
        $content = false;
        if ($url != "") {
            list($module, $controller) = explode("/", $url);
            $filename = ROOT . "/system/modules/$module/controllers/$controller.class.php";
            $class = "\\" . ucfirst($module) . "\\" . ucfirst($controller);
            
            
            if (file_exists($filename)) {
                ob_start();
                require_once $filename;
                $obj = new $class();
                $obj->show();
                $content = ob_get_contents();
                ob_clean();
            }
        }
        return $content;
    }

}
