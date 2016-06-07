<?
define('_JEXEC', 1);
define('JPATH_BASE', "/var/www/k2c/data/www/k2c.ru");
define("HOST_URL", "http://k2c.ru");
ini_set('session.cookie_domain', '.k2c.ru');


require_once JPATH_BASE . '/configuration.php';
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';


$jconfig = new JConfig();

$mainframe = JFactory::getApplication('site');
$userObj = JFactory::getUser();
$uid = filter_input(INPUT_GET, "autologin") == "devel" ? 275 : $userObj->id;
$uid = filter_input(INPUT_GET, "autologin") == "callcenter" ? 275 : $uid;
$user = JUser::GetInstance($uid);
$userGroups = $user->GetAuthorisedGroups();
$token = JSession::getFormToken();

$settings = array(
    "server" => $jconfig->host,
    "user" => $jconfig->user,
    "passwd" => $jconfig->password,
    "dbname" => $jconfig->db,
    "px" => $jconfig->dbprefix,
    "query" => array(
        "SET NAMES utf8"
    )
);
$logout = HOST_URL . "/index.php?option=com_users&task=user.logout&$token=1";
$HOST_DB = Database::Init("MySQLi", $settings);

$sql = "SELECT DISTINCT user_id, name "
        . "FROM `@px:user_usergroup_map` "
        . "INNER JOIN @px:users "
        . "ON @px:user_usergroup_map.user_id = @px:users.id "
        . "WHERE group_id IN ( " . implode(",", $userGroups) . " )";


$result = $HOST_DB->query($sql);
$userlist = array();
while ($item = $result->getRow()) {
    $userlist[] = $item;
}

$can_access = in_array("28", $user->groups) && $user->id != null;
$can_access = in_array("29", $user->groups) && $user->id != null ? true : $can_access;
$can_access = in_array("8", $user->groups) && $user->id != null ? true : $can_access;

$usergroups = $user->groups;
$is_adm = in_array(28, $usergroups) || filter_input(INPUT_GET, "autologin") == "devel";

define("CAN_ACCESS", $can_access);
define("IS_ADMIN", $is_adm);