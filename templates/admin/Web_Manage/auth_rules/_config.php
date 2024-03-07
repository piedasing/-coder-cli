<?php
/*既定的參數配置 (S)*/
$inc_path = "../../inc/";
$manage_path = "../";
$main_auth_key = 'auth';
$fun_auth_key = 'auth_rules';
include('../_config.php');

//檢查權限
$auth = coderAdmin::Auth($fun_auth_key);

//上傳檔案的路徑
$file_path = $admin_path_admin;

//對應的table column設定
$table = coderDBConf::$rules;
$colname = coderDBConf::$col_rules;
$table_auth = coderDBConf::$rules_auth;
$colname_auth = coderDBConf::$col_rules_auth;
$table_admin = coderDBConf::$admin;

//其他的頁面資訊
$page = request_pag("page");
$page_title = $auth['name'];
$page_desc = $page_title . " - 您可以在這裡檢視所有資料，或進行新增、修改、刪除等操作。";
$mtitle = '<li class="active">' . $auth['name'] . '</li>';
$mainicon = $auth['icon'];
/*既定的參數配置 (E)*/


function getAuthStr($id, $isadmin)
{
    if ($isadmin == 1) {
        return ' <span class="label label-important"><li class="icon-ok"> 超級管理員 </li></span>';
    }

    $ary_hasauth = coderAdmin::getAuthListAryByInt($id);
    $str = '';
    //print_r($ary_hasauth);

    foreach ($ary_hasauth as $item) {
        //$item['auth'] 操作權限
        if ($item['ck_auth']) {
            $str .= ' <span class="label label-primary authbtn"><li class="icon-ok-sign"> ' . $item['name'] . ' </li></span>';
        } else {
            $str .= ' <span class="label label-info authbtn"><li class="icon-ok-sign"> ' . $item['name'] . ' </li></span>';
        }
    }

    return $str;
}

?>