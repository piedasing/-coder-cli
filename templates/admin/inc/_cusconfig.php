<?php
// load dotenv
require_once CONFIG_DIR."CustomEnv/DotEnv.php";
(new DotEnv(ROOT_DIR . ".env"))->load();

$nowHost = env('NOW_HOST');

$isProd = in_array($nowHost, ['prod', 'production']) ? true : false;

$webname = env('WEB_NAME');
$webmanagename = env('WEB_MANAGE_NAME');

$web_protocol = env('WEB_PROTOCOL');
$web_domain = env('WEB_DOMAIN');
$web_root = env('WEB_ROOT'); // 前台cookie紀錄路徑
$web_port = env('WEB_PORT');

$MYSQL_TIMEZONE = env('MYSQL_TIMEZONE');
$MYSQL_CHARACTER = env('MYSQL_CHARACTER');

$MYSQL_HS = env('MYSQL_HS');
$MYSQL_ID = env('MYSQL_ID');
$MYSQL_PW = env('MYSQL_PW');
$MYSQL_DB = env('MYSQL_DB');

$MYSQL_HS_read = $MYSQL_HS;
$MYSQL_ID_read = $MYSQL_ID;
$MYSQL_PW_read = $MYSQL_PW;
$MYSQL_DB_read = $MYSQL_DB;

// AES加解密KEY
define("AES_CRYPT_KEY", env('AES_CRYPT_KEY'));
// AES加解密IV
define("AES_CRYPT_IV", env('AES_CRYPT_IV'));
// Hashids Salt
define("HASHIDS_SALT", env('HASHIDS_SALT'));

$session_domain = $web_domain;
$webmanageurl_cookiepath = $web_root . 'Web_Manage' . "/"; //後台cookie紀錄路徑 ex.'/Web_Manage/'
if (!isset($web_protocol) || empty($web_protocol)) {
    $web_protocol = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
}
$weburl = $web_protocol."://".$web_domain . (!empty($web_port) ? ':' . $web_port : '') . $web_root; // 指定網域  最後須加【/】 ex.http://www.wmch.com.tw/

define("WEB_FAVICON_URL", "{$weburl}images/favicon.png");
define("WEB_LOGO_URL", "{$weburl}images/logo.png");
define("WEB_LOGO_M_URL", "{$weburl}images/logo-m.png");

/* Email(系統發信的寄件人) */
$sys_email = env('MAIL_SYS_FROM', '');
$sys_name = env('MAIL_SYS_NAME', '');

/*SMTP Server*/
$smtp_host = env('MAIL_HOST', '127.0.0.1'); // smtp.gmail.com:587
$smtp_port = env('MAIL_PORT', '25');
$smtp_id = env('MAIL_USERNAME', '');
$smtp_pw = env('MAIL_PASSWORD', '');
$smtp_auth = !empty($smtp_id) ? true : false;
$smtp_isSMTP = env('MAIL_DRIVER') == 'smtp' ? true : false;
$smtp_secure = env('MAIL_SECURE', 'tls');
