<?

date_default_timezone_set('Europe/Prague');
session_start();

require_once __DIR__ . "/system/library/php/tools.php";

$template = "helpdesk";
define("TEMPLATE", $template);
define("URL", "");
$SQL = array();


error_reporting(E_ALL);
define("ROOT", __DIR__);
$classes = scandir(__DIR__ . "/system/classes");
foreach ($classes as $classname) {
    if (preg_match("/.class.php$/si", $classname)) {
        $filename = __DIR__ . "/system/classes/$classname";
        require_once $filename;
    }
};


require_once __DIR__ . "/system/library/php/joo2crm_user.php";

if (!CAN_ACCESS)
    die(false);

$DB = Database::Init("MySQLi", array(
            "server" => "localhost",
            "user" => "k2c",
            "passwd" => "Q52wRws6",
            "dbname" => "k2c",
            "px" => "crm_helpdesk__",
            "query" => array(
                "SET NAMES utf8"
            )
        ));
$action = filter_input(INPUT_POST, "action");
$module = filter_input(INPUT_POST, "module");
$controller = filter_input(INPUT_POST, "controller");
$request = false;

if($action && $module && $controller){
    $filename = ROOT . "/system/modules/$module/controllers/$controller.class.php";
    if(file_exists($filename)){
        require_once $filename;
        $classname = "\\".  ucfirst($module) . "\\" . ucfirst($controller);
        $obj = new $classname();
        $request = $obj->$action($_POST);
    }
}
print $request;

