<?php
header('Content-Type: text/html; charset=utf-8');

defined('BEGIN_TIME') or define('BEGIN_TIME', microtime(true));
defined('DEBUG') or define('DEBUG', true);
defined('TRACE_LEVEL') or define('TRACE_LEVEL', 0);
defined('ENABLE_EXCEPTION_HANDLER') or define('ENABLE_EXCEPTION_HANDLER',true);
defined('ENABLE_ERROR_HANDLER') or define('ENABLE_ERROR_HANDLER',true);
defined('PATH') or define('PATH',dirname(__FILE__));
defined('PATH_LIBS') or define('PATH_LIBS',dirname(__FILE__).DIRECTORY_SEPARATOR."libs");
defined('ZII_PATH') or define('ZII_PATH',PATH.DIRECTORY_SEPARATOR.'zii');
defined('PATH_APP)') or define('PATH_APP',dirname(__FILE__).DIRECTORY_SEPARATOR."framework");

define('DS', DIRECTORY_SEPARATOR);
define('LDTR', DIRECTORY_SEPARATOR);
define('LB', "\n");

ini_set('display_errors', '-1');
ini_set('display_startup_errors', '1');
ini_set('date.timezone', 'Europe/Kiev');
ini_set('register_argc_argv', 1);

$safe_mode = array('On', 'ON', 'on', 1);
if ( !in_array(ini_get('safe_mode'), $safe_mode) and ini_get('max_execution_time') > 0 ) {
	ini_set('max_execution_time', 0);  
}

session_start();

$_detected = 'front';
//$_request_uri = $_SERVER['REQUEST_URI'];

$_request_uri = (!empty($loaderName) and isset($loaderName)) ? $loaderName : false;

//if( preg_match('/^\/adm(.*)/is', $_request_uri) or preg_match('/^\/adm.php(.*)/is', $_request_uri) ) {
//    $_detected = 'admin';
//} 

if($_request_uri == "adm.php") {
   $_detected = 'admin'; 
}
// echo " $_detected "; die('stop');
define('_request_uri', $_request_uri);
define('_detected', $_detected);


// change the following paths if necessary
$init=dirname(__FILE__).'/framework/init.php';
$config=dirname(__FILE__).'/protected/config/main.php';

//cecho "init = ".$init." config = ".$config;
require_once($init);
//echo init::getVersion();

init::createWebApplication($config)->run();