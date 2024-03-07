<?php
/*既定的參數配置 (S)*/
$inc_path = "../../inc/";
$manage_path = "../";
$main_auth_key = 'auth';
$fun_auth_key = 'admin';
include('../_config.php');

//檢查權限
$auth = coderAdmin::Auth($fun_auth_key);

//上傳檔案的路徑
$file_path = $admin_path_admin;
$file_path_temp = $admin_path_temp;

//對應的table column設定
$table = coderDBConf::$admin;
$table_rules = coderDBConf::$rules;
$colname_rules = coderDBConf::$col_rules;

//其他的頁面資訊
$pagename = request_basename();
$page = request_pag("page");
$page_title = $auth['name'];
$page_desc = "後台使用者帳號管理區 - 您可以在這裡檢視所有帳號，或對帳號進行新增、修改、刪除等操作。";
$mtitle = '<li class="active">' . $auth['name'] . '</li>';
$mainicon = $auth['icon'];
/*既定的參數配置 (E)*/


$rules_array = class_rules::getList(); //所有的操作權限
$r_name = get('r_name', 1);

$ary_loginouttime = array();
foreach ($incary_loginouttime as $key_loginouttime => $val_loginouttime) //自動登出時間
{
    $ary_loginouttime[] = array('name' => $val_loginouttime['name'], 'value' => $key_loginouttime);
}

function checkgroup($ary)
{
    $temp_type = array();
    if (count($ary) > 0) {
        foreach ($ary as $val) {
            $temp_type[]['key'] = $val;
        }
    }
    return $temp_type;
}

function isUsernameNotExisit($username)
{
    global $db, $table;
    if (strlen($username) > 2 && !$db->query_first('select id from ' . $table . ' where username=\'' . hc($username) . '\'')) {
        return true;
    } else {
        return false;
    }
}

//excel 不重複
function uniqueAssocArray($array, $uniqueKey)
{
    if (!is_array($array)) {
        return array();
    }
    $uniqueKeys = array();
    foreach ($array as $key => $item) {
        if (!in_array($item[$uniqueKey], $uniqueKeys)) {
            $uniqueKeys[$item[$uniqueKey]] = $item;
        }
    }
    return $uniqueKeys;
}

?>
