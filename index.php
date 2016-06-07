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
    die("Вы не авторизованы");

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
require_once __DIR__ . "/system/templates/" . TEMPLATE . "/header.php";


$mail_tpl = __DIR__ . "/system/library/misc/newticket.php";
$MAIL = new Mail();
$MAIL->setTemplate($mail_tpl);

$ROUTER = new Router();
$route = filter_input(INPUT_GET, "route");
$route = $route ? $route : "catalog/itemlist";

print $ROUTER->get($route);

if(filter_input(INPUT_GET, "autologin") == "devel")
    d(error_get_last(), $SQL);
require_once __DIR__ . "/system/templates/$template/footer.php";

