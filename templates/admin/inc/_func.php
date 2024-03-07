<?php

function daysInMonth($year, $month) {
    return cal_days_in_month(CAL_GREGORIAN, $month, $year);
}

function getmid($table) {
    global $db;
    $mid = md5(uniqid(rand()));
    if ($db->isExisit($table, 'mid', $mid)) {
        return getmid($table);
    } else {
        return $mid;
    }
}

//=============================================//
//輸入

//request GET及POST值

function request_pag($name = "page") {
    $page = request_num($name);
    if ($page == "") {
        return 1;
    }
    else if ($page < 1) {
        return 1;
    }
    else {
        return $page;
    }
}

function request_str($name) {
    $value = request($name);

    return $value;
}

function request_num($name) {
    $value = request($name);

    if (is_numeric($value)) {
        return $value;
    }
    else {
        return "0";
    }
}

function request_ary($name, $_key = 1) {
    $data = request($name);

    if (gettype($data) == "array") {
        $d = array();
        foreach ($data as $key => $value) {

            $v = stripslashes($value);

            if ($_key == 1) {
                $v = hc($v);
            }
            else if ($_key == 0) {
                $v = intval($v);
            }
            $d[$key] = $v;
        }
        return $d;
    }
    else {
        return array();
    }
}

function request_ip() {
    $headerNames = [
        1 => 'HTTP_CLIENT_IP',
        2 => 'HTTP_X_FORWARDED_FOR',
        3 => 'HTTP_X_FORWARDED',
        4 => 'HTTP_X_CLUSTER_CLIENT_IP',
        5 => 'HTTP_FORWARDED_FOR',
        6 => 'HTTP_FORWARDED',
        7 => 'REMOTE_ADDR',
        8 => 'HTTP_VIA'
    ];
    $ip_ary = [];
    foreach($headerNames as $key => $value) {
        if (isset($_SERVER[$value]) && !empty($_SERVER[$value])) {
            $ip_ary[] = $key . ':' . $_SERVER[$value];
        }
    }

    return implode(",", $ip_ary);
}

function request_cd() {
    return datetime();
}

function request_basename() {
    $path = basename($_SERVER['REQUEST_URI']);
    $last_index = strpos($path, '?');
    return $last_index > 0 ? substr($path, 0, $last_index) : $path;
}

function request_url() {

    return "http://" . dirname($_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"]) . "/";
}

function request_weburl() {

    return "http://" . $_SERVER["HTTP_HOST"] . "/";
}

function request_ref() {
    return $_SERVER["http_referer"];
}

function request_date($name, $default = "") {
    $value = request($name);

    if (strlen($value) >= 8) {
        $dat = strtotime($value);
        if ($dat) {
            return $value;
        }
        else {
            return $default;
        }
    }
    else {
        return $default;
    }
}

function request($name) {
    $value = "";
    if (isset($_GET[$name])) {
        $value = $_GET[$name];
    }
    else if (isset($_POST[$name])) {
        $value = $_POST[$name];
    }
    else {
        $value = "";
    }
    return $value;
}
function get($str, $type = 0) {
    if (!isset($_GET[$str])) return "";
    $gstr = trim($_GET[$str]);
    switch ($type) {
        case 0:
            return intval($gstr);
            break;

        case 1:
            $gstr = htmlspecialchars($gstr);
            return $gstr;
            break;
        case 4:
            $gstr = urldecode($gstr);
            return $gstr;
            break;

        case 3:
             //float
            return floatval($gstr);
            break;
    }
}
function post($str, $type = 0) {
    if (!isset($_POST[$str])) return "";
    $gstr = trim($_POST[$str]);
    switch ($type) {
        case 0:
            return intval($gstr);
            break;

        case 1:
            $gstr = htmlspecialchars($gstr);

            return trim($gstr);
            break;

        case 2:
             //admin
            return trim($gstr);
            break;

        case 3:
             //float
            return floatval($gstr);
            break;
        case 4:
            //轉化特定符號，取消反斜線
            $gstr = htmlspecialchars($gstr, ENT_QUOTES);
            return trim($gstr);
            break;
    }
}

//=============================================//
//輸出
function hc($str) {

    //輸出字串並將html字符編碼
    return htmlentities($str, ENT_QUOTES, "UTF-8");
}

function uc($str) {

    //url字符編碼
    return urlencode($str);
}

function sc($str) {
    $str = str_replace("\r", "\\r", $str);
    $str = str_replace("\n", "\\n", $str);
    $str = addslashes(str_replace("\"", "''", $str));
    return $str;
}
function br($str) {
    return preg_replace("/(\015\012)|(\015)|(\012)/", "<br/>", $str);
}

function br_ary($str) {
    $str = preg_replace("/(\015\012)|(\015)|(\012)/", "<br/>", $str);
    $ary =  ($str!="")?explode("<br/>",$str):array();
    return $ary;
}

function removebr($str) {
    return preg_replace("/<br[[:space:]]*\/?[[:space:]]*>/", "\015\012", $str);
}
function echoln($str) {
    echo $str;
    echo "<br>";
}

function bugshow($str) {
    echo $str;
    echo "<br>";
}

function bugout($str) {
    die($str);
}

function ctrimall($str) {
    if(preg_match("'/^[\x4e00-\x9fa5\s]/u'",$str)){
        $str = trim($str);
        $str = preg_replace('/\s(?=)/', '', $str);
        return $str;
    }else{
        return $str;
    }
}

function tc_left($s, $c) {
    if (mb_strlen($s) > $c) {
        return left($s, $c) . "…";
    }
    else {
        return $s;
    }
}
function GBsubstr($string, $start, $length) {
    $beginIndex = $start;
    if (strlen($string) < $start) {
        return "";
    }
    if (strlen($string) < $length) {

        return substr($string, $beginIndex);
    }

    $char = ord($string[$beginIndex + $length - 1]);
    if ($char >= 224 && $char <= 239) {
        $str = substr($string, $beginIndex, $length - 1) . "...";
        return $str;
    }

    $char = ord($string[$beginIndex + $length - 2]);
    if ($char >= 224 && $char <= 239) {
        $str = substr($string, $beginIndex, $length - 2) . "...";
        return $str;
    }

    return substr($string, $beginIndex, $length) . "...";
}

//輸出table
//傳入陣列,TABLE STYLE,一列幾欄,輸出格式化後的table
function fillTable($ary, $style, $c) {
    $iaryCount = count($ary);
    if ($iaryCount > 0) {
        $stemp = "<table style='$style'>";
        for ($i = 0; $i < $iaryCount; $i++) {
            if ($i % $c == 0) {
                $stemp.= ($i > 0) ? "</tr><tr>" : "<tr>";
            }
            $stemp.= '<td >' . $ary[$i] . '</td>';
        }
        if ($i % $c > 0) {
            for ($j = 0; $j < ($i % $c); $j++) {
                $stemp.= '<td>&nbsp;</td>';
            }
            $stemp.= '</tr>';
        }

        $stemp.= '</table>';
        return $stemp;
    }
    else {
        return "";
    }
}

//做unescape的處理
function phpUnescape($escstr) {
    preg_match_all("/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/", $escstr, $matches);
    $ar = & $matches[0];
    $c = "";
    foreach ($ar as $val) {
        if (substr($val, 0, 1) != "%") {
            $c.= $val;
        }
        elseif (substr($val, 1, 1) != "u") {
            $x = hexdec(substr($val, 1, 2));
            $c.= chr($x);
        }
        else {
            $val = intval(substr($val, 2), 16);
            if ($val < 0x7F)
             // 0000-007F
            {
                $c.= chr($val);
            }
            elseif ($val < 0x800)
             // 0080-0800
            {
                $c.= chr(0xC0 | ($val / 64));
                $c.= chr(0x80 | ($val % 64));
            }
            else

            // 0800-FFFF
            {
                $c.= chr(0xE0 | (($val / 64) / 64));
                $c.= chr(0x80 | (($val / 64) % 64));
                $c.= chr(0x80 | ($val % 64));
            }
        }
    }

    return $c;
}

//=============================================//
//格式檢查

function chkUrl($str) {
    if (substr($str, 0, 7) != 'http://') {
        $str = 'http://' . $str;
    }
    return $str;
}

//=============================================//
//字串
function right($value, $count) {
    return mb_substr($value, ($count * -1));
}

function left($string, $count) {
    return mb_substr($string, 0, $count);
}

//=============================================//
//SQL

//=============================================//
//Session

//取得session
function session($name) {
    if (isset($_SESSION[$name]) || !empty($_SESSION[$name])) {
        return $_SESSION[$name];
    }
    else {
        return "";
    }
}
//取得session
function getSession($name) {
    return session($name);
}
//設定session
function setSession($name, $value) {
    $_SESSION[$name] = $value;
}
//清除session
function unSession($name) {
    unset($_SESSION["$name"]);
}

//=============================================//
//cookie

//取得cookie
function cookie($name) {
    if (isset($_COOKIE[$name]) || !empty($_COOKIE[$name])) {
        return phpUnescape($_COOKIE[$name]);
    }
    else {
        return "";
    }
}
//取得cookie
function getCookie($name) {
    return cookie($name);
}
//清除cookie
function unCookie($name, $path = "") {
    global $web_domain, $webmanageurl_cookiepath;
    if (empty($path)) {
        $path = $webmanageurl_cookiepath;
    }
    // setcookie($name, "", time() - 1800, "{$path};SameSite=Lax", $web_domain, true, true);
    setcookie($name, '', [
        'expires' => time() - 1800,
        'path' => $path,
        'domain' => $web_domain,
        'httponly' => true,
        'secure' => true,
        'samesite' => 'Lax',
    ]);
    $_COOKIE[$name] = null;
    unset($_COOKIE[$name]);
}
function saveCookieHour($name, $val, $h, $path = "") {
    global $web_domain, $webmanageurl_cookiepath;
    if (empty($path)) {
        $path = $webmanageurl_cookiepath;
    }
    $expire = time() + $h * 60 * 60;
    unCookie($name, $path);
    // setcookie($name, urlencode($val), $expire, "{$path};SameSite=Lax", $web_domain, true, true);
    setcookie($name, urlencode($val), [
        'expires' => time() - 1800,
        'path' => $path,
        'domain' => $web_domain,
        'httponly' => true,
        'secure' => true,
        'samesite' => 'Lax',
    ]);
    $_COOKIE[$name] = urlencode($val);
}

function saveCookie($name, $val, $path = "") {
    global $web_domain, $webmanageurl_cookiepath;
    global $iCookMainExpireDay;
    if (empty($path)) {
        $path = $webmanageurl_cookiepath;
    }
    $expire = time() + $iCookMainExpireDay * 24 * 60 * 60;
    unCookie($name, $path);
    // setcookie($name, urlencode($val), $expire, "{$path};SameSite=Lax", $web_domain, true, true);
    setcookie($name, urlencode($val), [
        'expires' => time() - 1800,
        'path' => $path,
        'domain' => $web_domain,
        'httponly' => true,
        'secure' => true,
        'samesite' => 'Lax',
    ]);
    $_COOKIE[$name] = urlencode($val);
}

//=============================================//
//日期時間
function DateDiff($d1, $d2 = "now") {
    if (is_string($d1)) $d1 = strtotime($d1);
    if (is_string($d2)) $d2 = strtotime($d2);
    return ($d2 - $d1) / 86400;
}

function datetime($form = "Y/m/d H:i:s", $value = "now") {

    //Y/m/d H:i:s
    return date($form, strtotime($value));
}

function datetime_addMin($form = "Y/m/d H:i:s", $value = "now", $second = 0) {

    return datetime($form, $value . " +{$second} minutes");
}
function datetime_addDay($form = "Y/m/d H:i:s", $value = "now", $second = 0) {

    return datetime($form, $value . " +{$second} days");
}
function datetime_addMonth($form = "Y/m/d H:i:s", $value = "now", $second = 0) {

    return datetime($form, $value . " +{$second} month");
}

function dateTo_ad($in_date, $in_type = "")
 //民國年月日(1030503)轉西元年月日(20140503) $in_type為分隔符號
{
    $cyear = substr($in_date, 0, -4);
    $year = ((int)$cyear) + 1911;
    $mon = substr($in_date, -4, 2);
    $day = substr($in_date, -2);
    $date = date("Y" . $in_type . "m" . $in_type . "d", mktime(0, 0, 0, $mon, $day, date($year)));
    return $date;
}

//=============================================//
//分頁

function flip_page($page, $totalrows, $show_num = 10, $num_page = 10) {
    if ((int)$totalrows > 0) {
        $pagecount = ceil($totalrows / $show_num);
    }
    else {
        $pagecount = 1;
        $totalrows = 0;
    }

    if ((int)$page < 1) {
        $page = 1;
    }
    else if ($page > $pagecount) {
        $page = $pagecount;
    }

    $sno = (int)($num_page / 2) - 1;
    $eno = $sno * 2 + 1;
    $sec_start = $page - $sno;
    if ($sec_start < 1) {
        $sec_start = 1;
    }

    $sec_end = $sec_start + $eno;
    if ($sec_end > $pagecount) {
        $sec_end = $pagecount;
    }

    $fpage = array();

    $fpage["page"] = $page;
     //頁碼
    $fpage["pagecount"] = $pagecount;
     //總頁數
    $fpage["sec_start"] = $sec_start;
     //分頁起點
    $fpage["sec_end"] = $sec_end;
     //分頁終點
    $fpage["rs_begin"] = ($page - 1) * $show_num;
     //mysql limit 分頁起點
    $fpage["show_num"] = $show_num;
     //每頁筆數

    return $fpage;
}

//=============================================//
//檔案
function filesize_check($file, $size = 1000) {

    //檔案大小測試
    if (isset($file)) {
        if ($file["size"] <= 1024 * $size) {
            return true;
        }
    }
    return false;
}

function filename_check($filename, $ext) {
    if ($filename > '') {
        if (in_array(end(explode(".", strtolower($filename))), $ext)) {
            return true;
        }
    }
    return false;
}

function file_ext($file) {

    //傳回副檔名
    $ext = explode('.', $file["name"]);
    $size = count($ext);
    return $ext[$size - 1];
}

function file_open($file) {

    //讀取檔案
    $handle = fopen($file, "r");
    $contents = fread($handle, filesize($file));
    fclose($handle);
    return $contents;
}

//JS輔助
function script($key, $url = "") {
    if ($url != "") {
        if ($url != 'close') {
            echo "<script>if('$key'!=''){alert('$key');} window.location='$url';</script>";
        } else {
            echo "<script>if('$key'!=''){alert('$key');} window.close();</script>";
        }
    } else {
        echo "<script>if('$key'!=''){alert('$key');} window.history.go(-1);</script>";
    }
    exit;
}
function redirect($url) {
    echo "<script>window.location.href='$url'</script>";
    exit;
}

/**
 * 只保留字串首尾字元，隱藏中間用*代替（兩個字元時只顯示第一個）
 * @param string $user_name 姓名
 * @return string 格式化後的姓名
 */
function substr_cut($user_name){
    $strlen     = mb_strlen($user_name, 'utf-8');
    $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
    $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
}

//資料驗證
$str_message = "";
function isNull($str, $name, $min, $max ,$required = true) {
    global $str_message;
    if (!$required)return true;
    if (mb_strlen($str) >= $min && mb_strlen($str) <= $max) {
        return true;
    }
    else {
        $str_message = $name . '必須大於' . $min . '個字元和小於' . $max . '字元';
        return false;
    }
}

function isRequire($str, $name) {
    global $str_message;
    if (!empty($str)) {
        return true;
    }else {
        $str_message = $name.'為必填欄位';
        return false;
    }
}

function isNum($str, $name, $min, $max) {
    global $str_message;
    if (!is_numeric($str)) {
        $str_message = $name . '必須是整數型態';
        return false;
    }
    else {
        if (intval($str) >= $min && intval($str) <= $max) {
            return true;
        }
        else {
            $str_message = $name . '必須大於' . $min . '和小於' . $max ;
            return false;
        }
    }
}

function isEmail2($str) {
    global $str_message;
    $result = preg_match("/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i", $str);
    if (!$result) {
        $str_message = '請輸入正確的的Email格式';
    }
    return $result;
}
function isDate($str, $name = "") {
    global $str_message;
    if (preg_match("/^[0-9]{4}-([1-9]|0[1-9]|1[0-2])-([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/", $str)) {
        return true;
    }
    else {

        $str_message = $name . '不是合法的日期格式';
        return false;
    }
}
function isDateTime($str, $name = "") {
    global $str_message;
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) [0-2][0-9]:[0-5][0-9]$/", $str)) {
        return true;
    }
    else {

        $str_message = $name . '不是合法的日期時間格式';
        return false;
    }
}
function isAccount($str) {
    global $str_message;
    if ($str!='' && preg_match("/^\w{6,}$/", $str)) {//至少6位數以上英文或數字
        return true;
    }
    else {
        $str_message = '帳號請輸入至少6位數以上英文或數字';
        return false;
    }
}
function isPassword($str) {
    global $str_message;
    if (preg_match("/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,12}$/", $str)) {
        return true;
    } else {
        $str_message = '密碼需設定8~12字，並混合英文大小寫、特殊符號及數字。';
        return false;
    }
}
function isphone($str) {
    global $str_message;
    if ($str!='' && preg_match("/\d{2,4}-?\d{3,4}-?\d{3,4}#?(\d+)?/", $str)) {
        return true;
    }
    else {
        $str_message = '請輸入正確的電話格式';
        return false;
    }
}
function containEN_CH($str) {
    global $str_message;
    if ($str!='' && preg_match("/^[a-zA-Z\x{4e00}-\x{9fa5} ]+$/u", $str)) {
        return true;
    }
    else {
        $str_message = '需輸入中文或英文';
        return false;
    }
}
function containEN_CH_NUM($str) {
    global $str_message;
    if ($str!='' && preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+$/u", $str)) {
        return true;
    }
    else {
        $str_message = '需輸入中英文或數字';
        return false;
    }
}
function containEN_CH_NUM_3($str) {
    global $str_message;
    if ($str!='' && preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5} +-<>()\[\]\.,;:\s@\"\#$&%!_^*]+$/u", $str)) {
        return true;
    }
    else {
        $str_message = '需輸入中英文或數字+-<>()\[\]\.,;:\s@\"\#';
        return false;
    }
}

function containEN_CH_NUM_2($str) {
    global $str_message;
    if ($str!='' && preg_match("/^[a-zA-Z0-9\x{4e00}-\x{9fa5}-\/]+$/u", $str)) {
        return true;
    }
    else {
        $str_message = '需輸入中英文或數字-/';
        return false;
    }
}
function isMobile($str) {
    global $str_message;
    if ($str!='' && preg_match("/^[09]{2}[0-9]{8}$/", $str)) {
        return true;
    }
    else {
        $str_message = '請輸入正確的手機格式';
        return false;
    }
}
function compareToTodayDate($date, $compare = ">") {
    global $str_message;

    $today_date = date('Y-m-d') ;
    if($compare == "<"){
        $result = (strtotime($date)<strtotime($today_date)) ? true :false;
    }else if ($compare == "="){
        $result = (strtotime($date)==strtotime($today_date)) ? true :false;
    }else if ($compare == ">="){
        $result = (strtotime($date)>=strtotime($today_date)) ? true :false;
    }else if ($compare == "<="){
        $result = (strtotime($date)<=strtotime($today_date)) ? true :false;
    }else{
        $result = (strtotime($date)>strtotime($today_date)) ? true :false;
    }
    return $result;
}
function isPID($str) {
    global $str_message;
    if ($str!='' && preg_match("/^[A-Za-z][12]\d{8}$/", $str)) {
        return true;
    }
    else {
        $str_message = '請輸入正確的身分證格式';
        return false;
    }
}

function isTID($str){
    // return true;//先不驗證
    global $str_message;
    /* $tmp = '12121241';
    $sum=0; */
    if ($str!='' && preg_match("/\d{8}$/", $str)) {
        return true;
    }else{
        $str_message = '請輸入正確的統一編號';
        return false;
    }

    /* for ($i=0; $i< 8; $i++) {
        $s1=(int)substr($str,$i,1);
        $s2=(int)substr($tmp,$i,1);
        $s12=$s1*$s2;
        if($s12>9){
            $sum += ($s12%10);
            $sum += ($s12-($s12%10))/10;
        }else{
            $sum += $s12;
        }
    }
    if($sum%10===0){
        return true;
    }else{
        if((int)substr($str,6,1) === 7 && ($sum+1)%10===0){
            return true;
        }
    }
    $str_message = '請輸入正確的統一編號';
    return false; */
}

function post_CURL($url, $data, $headers = "", $debug = false, $CA = false, $CApem = "", $timeout = 30) {
     //網址,資料,header,返回錯誤訊息,https時驗證CA憑證,CA檔名,超時(秒)
    global $path_cacert;
    $result = "";
    $cacert = $path_cacert . $CApem;
     //CA根证书
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    if ($SSL && $CA && $CApem == "") {
        return "請指定CA檔名";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
     //允許執行的最長秒數
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout - 2);
     //連接前等待時間(0為無限)
    $headers == '' ? '' : curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ($SSL && $CA) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
         // 驗證CA憑證
        curl_setopt($ch, CURLOPT_CAINFO, $cacert);
         // CA憑證檔案位置
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }
    else if ($SSL && !$CA) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         // 信任任何憑證
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);

    if ($debug === true && curl_errno($ch)) {
        echo 'GCM error: ' . curl_error($ch);
    }
    curl_close($ch);
    return $result;
}

function get_CURL($URL) {
    $result = "";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $URL);

    //curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     //關閉SSL協議
    $result = curl_exec($ch);
    //var_dump($result);
    //var_dump(curl_error($ch));
    curl_close($ch);
    return $result;
}

function generatorPassword($num=6) {//取得亂數密碼
    $password_len = $num;
    $password = '';
    $word = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
    $len = strlen($word);

    for ($i = 0; $i < $password_len; $i++) {
        $password.= $word[rand() % $len];
    }
    return $password;
}
function do_pwHash($str){
    return hash('sha512', $str);
}

function getMimeType( $filename ) { // 檢查檔案類型
    $realpath = realpath( $filename );
    if ( $realpath
            && function_exists( 'finfo_file' )
            && function_exists( 'finfo_open' )
            && defined( 'FILEINFO_MIME_TYPE' )
    ) {
            // Use the Fileinfo PECL extension (PHP 5.3+)
            return finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $realpath );
    }
    if ( function_exists( 'mime_content_type' ) ) {
            // Deprecated in PHP 5.3
            return mime_content_type( $realpath );
    }
    return false;
}

function isYoutube($url){
    $result=preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    if (!$result){
        return false;
    }else{
        $video_id = $match[1];
    return $video_id;
    }
}

//190314 By yu add------(S)
function chkMX($email){
    global $str_message;

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //E-mail is not valid 
        $str_message = '請輸入有效的email'; 
        return false;
    }else{
        //E-mail is valid
        $_email = explode("@",$email);
        $mailDomain = $_email[1];
        if (checkdnsrr($mailDomain, "MX")) {
            // this is a valid email domain!
            return true;
        }
        else {       
            $str_message = '請輸入有效的email'; 
            return false;
        }
    }
}
//190314 By yu add------(E)

?>
