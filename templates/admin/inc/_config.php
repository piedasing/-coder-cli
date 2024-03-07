<?php
mb_internal_encoding("UTF-8");
error_reporting(E_ALL); # report all errors
ini_set("magic_quotes_runtime", 0);

$iCache_ExpireHour = 24;
$null_date = '-0001-11-30';
//$null_date='1999-11-30';
define("CONFIG_DIR", dirname(__FILE__) . DIRECTORY_SEPARATOR);
define("ROOT_DIR", substr(CONFIG_DIR, 0, strpos(CONFIG_DIR, DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR) + 1));

session_save_path(realpath(dirname(dirname(__FILE__)) . '/upload/session/'));

ob_start();
//↓↓↓↓↓↓↓↓↓↓此區請依主機實際狀況修正↓↓↓↓↓↓↓↓↓↓↓↓
require_once(ROOT_DIR . "vendor/autoload.php");
require_once(CONFIG_DIR . "_cusconfig.php");

ini_set('display_errors', env('APP_DEBUG', false) === true);   # 0不顯示 1顯示
date_default_timezone_set(env('APP_TIMEZONE', 'Asia/Taipei'));

//↑↑↑↑↑↑↑↑↑此區請依主機實際狀況更動↑↑↑↑↑↑↑↑↑↑↑↑↑

if (!isset($_SESSION)) {
    $config_session_path = $web_root;
    if (strpos($_SERVER['SCRIPT_NAME'], $web_root . 'Web_Manage/') !== false) {
        $config_session_path = $web_root . 'Web_Manage/';
    } else {
    }

    session_save_path(realpath(dirname(dirname(__FILE__)) . '/upload/session/'));
    session_start([
        'cookie_lifetime' => 43200,
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'lax'
    ]);
}

header("Content-type: text/html; charset=utf-8");
/* 後台的標題及 Header 變數等 */
// $webmanagename = "後台管理系統-Neptunus V1.5";
$copyright = "";
$description = "";
$keywords = "";

//↓↓↓↓↓↓↓↓↓↓參數檔↓↓↓↓↓↓↓↓↓↓↓↓
require_once(CONFIG_DIR . "_configparameter.php");
//自動登出時間
$incary_loginouttime = array(1 => array('name' => '30分鐘', 'minute' => '30'), 2 => array('name' => '1小時', 'minute' => '60'), 3 => array('name' => '2小時', 'minute' => '120'));
//↑↑↑↑↑↑↑↑↑參數檔↑↑↑↑↑↑↑↑↑↑↑↑↑

require_once(CONFIG_DIR . "_func.php");
require_once(CONFIG_DIR . "_errormsg.php");
require_once(CONFIG_DIR . "_database.class.php");
require_once(CONFIG_DIR . "_func_smtp.php");

//lib的autoload
//採用spl_autoload_register載入(用__autoload會跟phpmailer的spl_autoload_register衝突)
function incautoload($classname)
{
    if (strlen($classname) > 9 && mb_substr(strtolower($classname), 0, 9) == 'phpexcel_') {
        return false;
    }
    $filename = '';
    if (strlen($classname) > 6 && (mb_substr($classname, 0, 6) == 'class_' || mb_substr($classname, 0, 8) == 'classdb_')) {
        $filename = CONFIG_DIR . "class/" . strtolower($classname) . ".php";
    } else if (strlen($classname) > 5 && mb_substr($classname, 0, 5) == 'coder') {
        $filename = CONFIG_DIR . "lib" . '/' . strtolower($classname) . ".php";
    }
    if ($filename != '') {
        if (file_exists($filename)) {
            include_once $filename;
        } else {
            echo 'notfound:' . $filename;
        }
    }
}
function incchatbotautoload($className)
{
    $className = ltrim($className, '\\');

    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = CONFIG_DIR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        // die($fileName);
    } else {
        $fileName = CONFIG_DIR . DIRECTORY_SEPARATOR;
        // die($fileName);

    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    // echo($fileName);
    if ($fileName != '') {
        if (file_exists($fileName)) {
            require $fileName;
        }
    }
}

// if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
//     //SPL autoloading was introduced in PHP 5.1.2
//     if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
//         spl_autoload_register('incautoload', true, true);
//     } else {
//         spl_autoload_register('incautoload');
//     }
// }
spl_autoload_register('incautoload', true);
spl_autoload_register('incchatbotautoload', true);

function env($key)
{
    return isset($_ENV[$key]) ? $_ENV[$key] : getenv($key);
}
